<?php
require_once __DIR__ . '/../db_connect.php';
require_once __DIR__ . '/../auth.php'; require_hr();
require_once __DIR__ . '/../ScreeningClient.php';
header('Content-Type: application/json');
$input=json_decode(file_get_contents('php://input'),true) ?: $_POST; $applicationId=(int)($input['application_id']??0);
if (!$applicationId) { http_response_code(422); echo json_encode(['ok'=>false,'message'=>'Application ID is required.']); exit; }
$stmt=$pdo->prepare('SELECT ja.application_id,a.skills,a.education_level,a.years_experience,a.certifications,j.required_skills,j.min_education_level,j.min_years_experience,j.required_certifications FROM job_applications ja JOIN applicants a ON a.applicant_id=ja.applicant_id JOIN job_postings j ON j.job_id=ja.job_id WHERE ja.application_id=?');
$stmt->execute([$applicationId]); $pair=$stmt->fetch(PDO::FETCH_ASSOC);
if (!$pair) { http_response_code(404); echo json_encode(['ok'=>false,'message'=>'Application not found.']); exit; }
$docs=$pdo->prepare("SELECT COUNT(*) total, SUM(verification_status='Complete') complete_count FROM applicant_documents WHERE application_id=?"); $docs->execute([$applicationId]); $document=$docs->fetch(PDO::FETCH_ASSOC);
$pair['applicant_skills']=$pair['skills']; $pair['document_verification_status']=((int)$document['total']>0 && (int)$document['total']===(int)$document['complete_count']) ? 'Complete' : 'Pending';
$client=new ScreeningClient(); $response=$client->screenBatch([$pair]); $result=$response['screenings'][0]??null;
if (!$result) { http_response_code(503); echo json_encode(['ok'=>false,'message'=>$response['message']??'Screening service is unavailable.']); exit; }
$save=$pdo->prepare('INSERT INTO screening_results (application_id,classification,confidence,eligibility_passed,eligibility_notes,feature_snapshot,model_version) VALUES (?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE classification=VALUES(classification),confidence=VALUES(confidence),eligibility_passed=VALUES(eligibility_passed),eligibility_notes=VALUES(eligibility_notes),feature_snapshot=VALUES(feature_snapshot),model_version=VALUES(model_version),screened_at=CURRENT_TIMESTAMP');
$save->execute([$applicationId,$result['classification'],$result['confidence'],$result['eligibility_passed']?1:0,implode("\n",$result['eligibility_notes']??[]),json_encode($result['feature_snapshot']??[]),$response['model_version']??null]);
$pdo->prepare("UPDATE job_applications SET status='Screening' WHERE application_id=? AND status IN ('Submitted','Under Review')")->execute([$applicationId]);
echo json_encode(['ok'=>true,'screening'=>$result]);
?>
