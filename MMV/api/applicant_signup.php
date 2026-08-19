<?php
require_once __DIR__ . '/../db_connect.php'; session_start(); header('Content-Type: application/json');
$data=json_decode(file_get_contents('php://input'),true) ?: $_POST;
$username=trim($data['username']??''); $email=trim($data['email']??''); $password=(string)($data['password']??'');
if (!preg_match('/^[A-Za-z0-9_.-]{3,50}$/',$username) || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($password)<8 || empty(trim($data['first_name']??'')) || empty(trim($data['surname']??''))) { http_response_code(422); echo json_encode(['ok'=>false,'message'=>'Provide a valid username, email, name, and password of at least 8 characters.']); exit; }
try { $stmt=$pdo->prepare('INSERT INTO applicants (username,password_hash,surname,first_name,middle_name,email) VALUES (?,?,?,?,?,?)'); $stmt->execute([$username,password_hash($password,PASSWORD_DEFAULT),trim($data['surname']),trim($data['first_name']),trim($data['middle_name']??'')?:null,$email]); $id=(int)$pdo->lastInsertId(); session_regenerate_id(true); unset($_SESSION['hr_user_id'], $_SESSION['hr_role'], $_SESSION['hr_name']); $_SESSION['applicant_id']=$id; $_SESSION['applicant_name']=trim($data['first_name']); echo json_encode(['ok'=>true,'applicant_id'=>$id]); }
catch(PDOException $e) { http_response_code(409); echo json_encode(['ok'=>false,'message'=>'That username or email is already registered.']); }
?>
