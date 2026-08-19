<?php
require_once __DIR__ . '/../db_connect.php';
require_once __DIR__ . '/../auth.php';
require_hr();
header('Content-Type: application/json');

$documents = "LEFT JOIN (SELECT application_id, CASE WHEN COUNT(*)=0 THEN 'Pending' WHEN SUM(verification_status='Complete')=COUNT(*) THEN 'Complete' WHEN SUM(verification_status IN ('Incomplete','Unreadable'))>0 THEN 'Incomplete' ELSE 'Needs Review' END document_status FROM applicant_documents GROUP BY application_id) d ON d.application_id=ja.application_id";
$total = (int)$pdo->query('SELECT COUNT(*) FROM job_applications')->fetchColumn();
$activePostings = (int)$pdo->query("SELECT COUNT(*) FROM job_postings WHERE status='Active'")->fetchColumn();
$byClassification = $pdo->query("SELECT COALESCE(sr.classification,'Pending') label, COUNT(*) total FROM job_applications ja LEFT JOIN screening_results sr ON sr.application_id=ja.application_id GROUP BY COALESCE(sr.classification,'Pending')")->fetchAll(PDO::FETCH_KEY_PAIR);
$byStatus = $pdo->query('SELECT status label, COUNT(*) total FROM job_applications GROUP BY status')->fetchAll(PDO::FETCH_KEY_PAIR);
$byDocument = $pdo->query("SELECT COALESCE(d.document_status,'Pending') label, COUNT(*) total FROM job_applications ja $documents GROUP BY COALESCE(d.document_status,'Pending')")->fetchAll(PDO::FETCH_KEY_PAIR);
$byJob = $pdo->query('SELECT jp.job_title label, COUNT(ja.application_id) total FROM job_postings jp LEFT JOIN job_applications ja ON ja.job_id=jp.job_id GROUP BY jp.job_id,jp.job_title ORDER BY total DESC,jp.job_title')->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['ok'=>true,'total_applications'=>$total,'active_job_postings'=>$activePostings,'by_classification'=>$byClassification,'by_status'=>$byStatus,'by_document_status'=>$byDocument,'by_job'=>$byJob]);
?>
