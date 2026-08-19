<?php
require_once __DIR__.'/../db_connect.php'; session_start(); header('Content-Type: application/json');
$data=json_decode(file_get_contents('php://input'),true)?:$_POST; $stmt=$pdo->prepare("SELECT * FROM hr_users WHERE username=? AND status='Active'"); $stmt->execute([$data['username']??'']); $user=$stmt->fetch(PDO::FETCH_ASSOC);
if (!$user || !password_verify($data['password']??'', $user['password_hash'])) { http_response_code(401); echo json_encode(['ok'=>false,'message'=>'Invalid credentials.']); exit; }
session_regenerate_id(true);
unset($_SESSION['applicant_id'], $_SESSION['applicant_name']);
$_SESSION['hr_user_id']=(int)$user['hr_user_id']; $_SESSION['hr_role']=$user['role']; $_SESSION['hr_name']=$user['full_name'];
echo json_encode(['ok'=>true,'name'=>$user['full_name'],'role'=>$user['role']]);
?>
