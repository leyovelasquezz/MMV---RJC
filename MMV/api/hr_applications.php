<?php
require_once __DIR__ . '/../db_connect.php'; require_once __DIR__ . '/../auth.php'; require_hr(); header('Content-Type: application/json');
$filters=[]; $params=[];
foreach (['status','classification','document_status','job_id'] as $key) {
    if (isset($_GET[$key]) && $_GET[$key] !== '') { $filters[$key] = trim((string)$_GET[$key]); }
}
if (!empty($_GET['search'])) { $filters['search'] = trim((string)$_GET['search']); }
$sql="SELECT ja.application_id, CONCAT(a.first_name,' ',a.surname) applicant_name, jp.job_title, ja.applied_at, ja.status, sr.classification, sr.confidence, COALESCE(d.document_status,'Pending') document_status FROM job_applications ja JOIN applicants a ON a.applicant_id=ja.applicant_id JOIN job_postings jp ON jp.job_id=ja.job_id LEFT JOIN screening_results sr ON sr.application_id=ja.application_id LEFT JOIN (SELECT application_id, CASE WHEN COUNT(*)=0 THEN 'Pending' WHEN SUM(verification_status='Complete')=COUNT(*) THEN 'Complete' WHEN SUM(verification_status IN ('Incomplete','Unreadable'))>0 THEN 'Incomplete' ELSE 'Needs Review' END document_status FROM applicant_documents GROUP BY application_id) d ON d.application_id=ja.application_id";
if (isset($filters['status'])) { $sql.=' WHERE ja.status=?'; $params[]=$filters['status']; }
if (isset($filters['classification'])) { $sql.=empty($params)?' WHERE':' AND'; $sql.=' sr.classification=?'; $params[]=$filters['classification']; }
if (isset($filters['document_status'])) { $sql.=empty($params)?' WHERE':' AND'; $sql.=" COALESCE(d.document_status,'Pending')=?"; $params[]=$filters['document_status']; }
if (isset($filters['job_id'])) { $sql.=empty($params)?' WHERE':' AND'; $sql.=' ja.job_id=?'; $params[]=(int)$filters['job_id']; }
if (isset($filters['search'])) { $sql.=empty($params)?' WHERE':' AND'; $sql.=" CONCAT(a.first_name,' ',a.surname) LIKE ?"; $params[]='%'.$filters['search'].'%'; }
$sql.=' ORDER BY ja.applied_at DESC';
$statement=$pdo->prepare($sql); $statement->execute($params);
echo json_encode(['ok'=>true,'applications'=>$statement->fetchAll(PDO::FETCH_ASSOC)]);
?>
