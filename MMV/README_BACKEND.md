# RJC Corporate Center screening backend

The PHP application uses MySQL for applicants, job postings, applications,
document-check records, and screening results. The Python service performs
three-class screening: `Qualified`, `Potentially Qualified`, or `Rejected`.

## Set up

1. Run `migrations/20260818_mmv_full_schema.sql` in MySQL for a new `mmv_db`
   database, then run `migrations/20260818_create_initial_hr_admin.sql` to
   create the initial HR account.
2. From `svm_service`, install the declared packages with:

   ```powershell
   py -m pip install -r requirements.txt
   ```

3. Start the screening service:

   ```powershell
   py app.py
   ```

The service listens only on `127.0.0.1:5000`. The PHP application calls it
through `ScreeningClient.php`.

## Screening behaviour

Before the SVM is consulted, minimum education, relevant experience, required
certifications/licenses, and a complete document check are evaluated as fixed
requirements. If one fails, the application is classified as `Rejected`.

Document checks cover completeness, internal consistency, and document quality.

## Training the model

The service intentionally starts without a trained model. HR must supply
labelled historical records containing all three classifications. Send them to
`POST /train` as `{ "records": [...] }`; each record includes the applicant and
posting fields used by `/screen` plus a `classification`. The service requires at
least two records per classification.

Training uses stratified cross-validation, a macro-F1 hyperparameter search,
and calibrated probability output. It stores a feature-schema identifier with
the model and refuses a stale model after the feature schema changes.

Until a valid model has been trained, applicants who pass every fixed
requirement are returned as `Potentially Qualified`; this does not fabricate a
model prediction.
