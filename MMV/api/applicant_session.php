<?php
require_once __DIR__ . '/../db_connect.php'; require_once __DIR__ . '/../auth.php'; header('Content-Type: application/json');
if (empty($_SESSION['applicant_id'])) { echo json_encode(['ok'=>true,'authenticated'=>false]); exit; }
$stmt=$pdo->prepare("SELECT applicant_id,username,first_name,surname,email FROM applicants WHERE applicant_id=? AND status='Active'"); $stmt->execute([$_SESSION['applicant_id']]); $applicant=$stmt->fetch(PDO::FETCH_ASSOC);
if (!$applicant) { unset($_SESSION['applicant_id'],$_SESSION['applicant_name']); echo json_encode(['ok'=>true,'authenticated'=>false]); exit; }
echo json_encode(['ok'=>true,'authenticated'=>true,'applicant'=>$applicant]);
?>
