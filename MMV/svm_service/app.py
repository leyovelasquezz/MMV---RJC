"""RJC three-class applicant screening service.

Document signals assess completeness, internal consistency, and document quality only.
"""
import hashlib
import json
import os
import sys
import tempfile
from datetime import datetime, timezone

import joblib
import numpy as np
from flask import Flask, jsonify, request
from sklearn.calibration import CalibratedClassifierCV
from sklearn.metrics import f1_score
from sklearn.model_selection import GridSearchCV, StratifiedKFold
from sklearn.pipeline import Pipeline
from sklearn.preprocessing import StandardScaler
from sklearn.svm import SVC

app = Flask(__name__)
BASE = os.path.dirname(__file__)
MODEL = os.path.join(BASE, "screening_model.pkl")
OCR_SERVICE = os.path.normpath(os.path.join(BASE, "..", "ocr_service"))
FEATURES = [
    "skill_coverage",
    "education_level_met",
    "experience_years_met",
    "required_certs_present_ratio",
    "document_verification_passed",
]
CLASSES = {"Qualified", "Potentially Qualified", "Rejected"}
EDUCATION_RANK = {
    "none": 0, "elementary": 1, "high school": 2, "senior high school": 3,
    "vocational": 4, "associate": 5, "bachelor's": 6, "master's": 7,
    "doctorate": 8, "other": -1,
}
# Extend this mapping as HR identifies equivalent terms used in actual records.
SKILL_ALIASES = {
    "security guard license": "security license", "security licence": "security license",
    "first aid training": "first aid", "incident report writing": "incident reporting",
    "access-control": "access control", "emergency response training": "emergency response",
}


def schema_id():
    return hashlib.sha256(json.dumps(FEATURES, separators=(",", ":")).encode()).hexdigest()[:16]


def normalize_terms(value):
    terms = set()
    for raw in (value or "").split(","):
        term = " ".join(raw.strip().lower().split())
        if term:
            terms.add(SKILL_ALIASES.get(term, term))
    return terms


def as_number(value):
    try:
        return float(value or 0)
    except (TypeError, ValueError):
        return 0.0


def feature_vector(pair):
    applicant_skills = normalize_terms(pair.get("applicant_skills") or pair.get("skills"))
    required_skills = normalize_terms(pair.get("required_skills"))
    applicant_certs = normalize_terms(pair.get("certifications"))
    required_certs = normalize_terms(pair.get("required_certifications"))
    years = as_number(pair.get("years_experience"))
    minimum_years = as_number(pair.get("min_years_experience"))
    education = EDUCATION_RANK.get(str(pair.get("education_level") or "").lower(), -1)
    minimum_education = EDUCATION_RANK.get(str(pair.get("min_education_level") or "none").lower(), 0)
    skill_coverage = len(applicant_skills & required_skills) / len(required_skills) if required_skills else 1.0
    cert_coverage = len(applicant_certs & required_certs) / len(required_certs) if required_certs else 1.0
    document_passed = str(pair.get("document_verification_status") or "").lower() == "complete"
    return [skill_coverage, float(education >= minimum_education), float(years >= minimum_years), cert_coverage, float(document_passed)]


def eligibility(pair, vector):
    notes = []
    if not vector[1]:
        notes.append("Minimum education requirement is not met.")
    if not vector[2]:
        notes.append("Minimum relevant experience requirement is not met.")
    if vector[3] < 1:
        notes.append("One or more required certifications or licenses are missing.")
    if not vector[4]:
        notes.append("Required document checks are not complete.")
    return not notes, notes


def load_artifact():
    if not os.path.exists(MODEL):
        return None, "No trained screening model is available."
    artifact = joblib.load(MODEL)
    if not isinstance(artifact, dict) or artifact.get("feature_schema") != FEATURES or artifact.get("schema_id") != schema_id():
        return None, "The saved screening model uses a stale feature schema and must be retrained."
    return artifact, None


@app.post("/train")
def train():
    """Train from {records: [{...screening fields, classification: label}]}.

    Training data must contain all three classes, with at least two records in
    every class so stratified validation and probability calibration are valid.
    """
    records = (request.get_json(silent=True) or {}).get("records", [])
    if not isinstance(records, list) or not records:
        return jsonify(status="error", message="Provide non-empty training records."), 422
    labels = [row.get("classification") for row in records]
    if set(labels) != CLASSES:
        return jsonify(status="error", message="Training records must contain Qualified, Potentially Qualified, and Rejected classes."), 422
    class_counts = {label: labels.count(label) for label in CLASSES}
    min_count = min(class_counts.values())
    if min_count < 2:
        return jsonify(status="error", message="Each classification needs at least two training records."), 422

    x = np.asarray([feature_vector(row) for row in records], dtype=float)
    folds = min(5, min_count)
    cv = StratifiedKFold(n_splits=folds, shuffle=True, random_state=42)
    base_pipeline = Pipeline([("scale", StandardScaler()), ("svc", SVC(decision_function_shape="ovr", class_weight="balanced"))])
    search = GridSearchCV(base_pipeline, {"svc__C": [0.1, 1, 10], "svc__gamma": ["scale", 0.1, 1], "svc__kernel": ["rbf", "linear"]}, scoring="f1_macro", cv=cv, n_jobs=1)
    search.fit(x, labels)
    best = search.best_estimator_
    calibrated = CalibratedClassifierCV(estimator=best, method="sigmoid", cv=cv)
    calibrated.fit(x, labels)
    # GridSearchCV's stratified macro-F1 is the validation metric. Calibration
    # is then fitted on all available labelled records for probability output.
    macro_f1 = float(search.best_score_)
    version = datetime.now(timezone.utc).strftime("%Y%m%dT%H%M%SZ")
    artifact = {"model": calibrated, "feature_schema": FEATURES, "schema_id": schema_id(), "model_version": version, "metrics": {"macro_f1": round(macro_f1, 4), "grid_best_macro_f1": round(float(search.best_score_), 4), "class_counts": class_counts}}
    joblib.dump(artifact, MODEL)
    return jsonify(status="ok", model_version=version, metrics=artifact["metrics"])


@app.post("/screen")
def screen():
    results = []
    artifact, model_error = load_artifact()
    for pair in (request.get_json(silent=True) or {}).get("pairs", []):
        vector = feature_vector(pair)
        eligible, notes = eligibility(pair, vector)
        result = {"eligibility_passed": eligible, "eligibility_notes": notes, "feature_snapshot": dict(zip(FEATURES, vector))}
        if not eligible:
            result.update(classification="Rejected", confidence=1.0)
        elif artifact is None:
            # Do not invent a model result when no valid trained artifact exists.
            result.update(classification="Potentially Qualified", confidence=None, eligibility_notes=[model_error])
        else:
            probabilities = artifact["model"].predict_proba([vector])[0]
            index = int(np.argmax(probabilities))
            result.update(classification=str(artifact["model"].classes_[index]), confidence=round(float(probabilities[index]), 4))
        results.append(result)
    return jsonify(status="ok", model_version=artifact.get("model_version") if artifact else None, screenings=results)


@app.post("/document-check")
def document_check():
    """Run a local OCR completeness and document-quality check on one upload."""
    uploaded = request.files.get("document")
    if uploaded is None or not uploaded.filename:
        return jsonify(status="error", message="A document upload is required."), 422
    suffix = os.path.splitext(uploaded.filename)[1].lower()
    if suffix not in {".jpg", ".jpeg", ".png", ".pdf"}:
        return jsonify(status="error", message="Only PDF, JPG, and PNG files are supported."), 422
    temporary_path = None
    try:
        with tempfile.NamedTemporaryFile(delete=False, suffix=suffix) as temporary:
            temporary_path = temporary.name
            uploaded.save(temporary_path)
        if OCR_SERVICE not in sys.path:
            sys.path.insert(0, OCR_SERVICE)
        from process_document import extract
        return jsonify(status="ok", **extract(temporary_path))
    except Exception:
        return jsonify(status="ok", ocr_text="", verification_status="Needs Review", verification_notes="The automated completeness and document-quality check could not be completed." )
    finally:
        if temporary_path and os.path.exists(temporary_path):
            os.unlink(temporary_path)


@app.get("/health")
def health():
    artifact, error = load_artifact()
    return jsonify(status="ok", trained=artifact is not None, feature_names=FEATURES, feature_schema_id=schema_id(), model_version=artifact.get("model_version") if artifact else None, model_error=error)


if __name__ == "__main__":
    app.run(host="127.0.0.1", port=5000, debug=True)
