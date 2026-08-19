<?php
require_once __DIR__ . '/../db_connect.php'; session_start(); header('Content-Type: application/json');
$data=json_decode(file_get_contents('php://input'),true) ?: $_POST; $identity=trim($data['identity']??'');
$stmt=$pdo->prepare("SELECT applicant_id,first_name,password_hash FROM applicants WHERE (username=? OR email=?) AND status='Active'"); $stmt->execute([$identity,$identity]); $applicant=$stmt->fetch(PDO::FETCH_ASSOC);
if (!$applicant || empty($applicant['password_hash']) || !password_verify((string)($data['password']??''),$applicant['password_hash'])) { http_response_code(401); echo json_encode(['ok'=>false,'message'=>'Invalid username/email or password.']); exit; }
session_regenerate_id(true); unset($_SESSION['hr_user_id'], $_SESSION['hr_role'], $_SESSION['hr_name']); $_SESSION['applicant_id']=(int)$applicant['applicant_id']; $_SESSION['applicant_name']=$applicant['first_name']; echo json_encode(['ok'=>true,'applicant_id'=>(int)$applicant['applicant_id'],'name'=>$applicant['first_name']]);
?>
