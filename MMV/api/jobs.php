<?php
require_once __DIR__ . '/../db_connect.php'; header('Content-Type: application/json');
require_once __DIR__ . '/../auth.php';
if ($_SERVER['REQUEST_METHOD']==='GET') { if(($_GET['admin']??'')==='1'){require_hr(); $q=$pdo->query("SELECT * FROM job_postings ORDER BY created_at DESC");} else {$q=$pdo->query("SELECT * FROM job_postings WHERE status='Active' ORDER BY created_at DESC");} echo json_encode(['ok'=>true,'jobs'=>$q->fetchAll(PDO::FETCH_ASSOC)]); exit; }
require_hr();
$data=json_decode(file_get_contents('php://input'),true) ?: $_POST;
if ($_SERVER['REQUEST_METHOD']==='DELETE') { $id=(int)($data['job_id']??0); if(!$id){http_response_code(422);echo json_encode(['ok'=>false,'message'=>'Job posting is required.']);exit;} $stmt=$pdo->prepare('DELETE FROM job_postings WHERE job_id=?'); $stmt->execute([$id]); if(!$stmt->rowCount()){http_response_code(404);echo json_encode(['ok'=>false,'message'=>'Job posting not found.']);exit;} echo json_encode(['ok'=>true]); exit; }
if (empty($data['job_title']) || empty($data['job_description'])) { http_response_code(422); echo json_encode(['ok'=>false,'message'=>'Job title and description are required.']); exit; }
$experience = (float)($data['min_years_experience'] ?? 0);
if ($experience < 0) { http_response_code(422); echo json_encode(['ok'=>false,'message'=>'Minimum experience cannot be negative.']); exit; }
if (!empty($data['job_id'])) {
 $stmt=$pdo->prepare('UPDATE job_postings SET job_title=?,employment_type=?,location=?,job_description=?,required_skills=?,min_education_level=?,min_years_experience=?,required_certifications=?,required_documents=?,assessment_name=?,status=? WHERE job_id=?');
 $stmt->execute([$data['job_title'],$data['employment_type']??null,$data['location']??null,$data['job_description'],$data['required_skills']??null,$data['min_education_level']??'None',$experience,$data['required_certifications']??null,$data['required_documents']??null,$data['assessment_name']??null,$data['status']??'Active',(int)$data['job_id']]);
 if (!$stmt->rowCount()) { http_response_code(404); echo json_encode(['ok'=>false,'message'=>'Job posting was not found or has no changes.']); exit; }
 echo json_encode(['ok'=>true,'job_id'=>(int)$data['job_id']]); exit;
}
$stmt=$pdo->prepare('INSERT INTO job_postings (job_title,employment_type,location,job_description,required_skills,min_education_level,min_years_experience,required_certifications,required_documents,assessment_name,posted_by) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
$stmt->execute([$data['job_title'],$data['employment_type']??null,$data['location']??null,$data['job_description'],$data['required_skills']??null,$data['min_education_level']??'None',$experience,$data['required_certifications']??null,$data['required_documents']??null,$data['assessment_name']??null,$_SESSION['hr_user_id']]);
echo json_encode(['ok'=>true,'job_id'=>(int)$pdo->lastInsertId()]);
?>
