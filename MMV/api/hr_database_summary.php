<?php
require_once __DIR__ . '/../db_connect.php'; require_once __DIR__ . '/../auth.php'; require_hr(); header('Content-Type: application/json');
$tables=['applicants','job_postings','job_applications','applicant_documents','screening_results','assessments','hr_users']; $counts=[];
foreach($tables as $table){$counts[$table]=(int)$pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();}
echo json_encode(['ok'=>true,'counts'=>$counts]);
?>
