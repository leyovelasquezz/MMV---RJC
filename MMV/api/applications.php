<?php
require_once __DIR__ . '/../db_connect.php'; require_once __DIR__ . '/../auth.php'; require_applicant(); header('Content-Type: application/json');
$data=json_decode(file_get_contents('php://input'),true) ?: $_POST;
foreach (['surname','first_name','email','job_id'] as $field) if (empty($data[$field])) { http_response_code(422); echo json_encode(['ok'=>false,'message'=>"$field is required."]); exit; }
$job=$pdo->prepare("SELECT job_id FROM job_postings WHERE job_id=? AND status='Active'"); $job->execute([(int)$data['job_id']]);
if (!$job->fetchColumn()) { http_response_code(422); echo json_encode(['ok'=>false,'message'=>'The selected job posting is no longer available. Please choose an active posting.']); exit; }
$pdo->beginTransaction(); try {
 $id=(int)$_SESSION['applicant_id'];
 $update=$pdo->prepare('UPDATE applicants SET surname=?,first_name=?,middle_name=?,phone=?,address=?,education_level=?,years_experience=?,skills=?,certifications=? WHERE applicant_id=?'); $update->execute([$data['surname'],$data['first_name'],$data['middle_name']??null,$data['phone']??null,$data['address']??null,$data['education_level']??null,$data['years_experience']??0,$data['skills']??null,$data['certifications']??null,$id]);
 $apply=$pdo->prepare('INSERT INTO job_applications (applicant_id,job_id) VALUES (?,?)'); $apply->execute([$id,$data['job_id']]); $pdo->commit(); echo json_encode(['ok'=>true,'applicant_id'=>(int)$id,'application_id'=>(int)$pdo->lastInsertId()]);
} catch(Throwable $e) { $pdo->rollBack(); http_response_code(409); echo json_encode(['ok'=>false,'message'=>'Could not submit this application.']); }
?>
