<?php
require_once __DIR__ . '/../db_connect.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['document']) || empty($_POST['application_id']) || empty($_POST['document_type'])) {
    http_response_code(422); echo json_encode(['ok'=>false,'message'=>'Application, document type, and file are required.']); exit;
}
$file=$_FILES['document'];
if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 10*1024*1024) { http_response_code(422); echo json_encode(['ok'=>false,'message'=>'Upload failed or exceeds 10MB.']); exit; }
$allowed=['application/pdf','image/jpeg','image/png']; $mime=(new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
if (!in_array($mime,$allowed,true)) { http_response_code(422); echo json_encode(['ok'=>false,'message'=>'Only PDF, JPG, and PNG files are allowed.']); exit; }
$lookup=$pdo->prepare('SELECT applicant_id FROM job_applications WHERE application_id=?'); $lookup->execute([(int)$_POST['application_id']]); $applicantId=$lookup->fetchColumn();
if (!$applicantId) { http_response_code(404); echo json_encode(['ok'=>false,'message'=>'Application not found.']); exit; }
$dir=__DIR__.'/../uploads/documents/'; if (!is_dir($dir)) mkdir($dir,0755,true);
$ext=strtolower(pathinfo($file['name'],PATHINFO_EXTENSION)); $stored=bin2hex(random_bytes(16)).'.'.$ext;
if (!move_uploaded_file($file['tmp_name'],$dir.$stored)) { http_response_code(500); echo json_encode(['ok'=>false,'message'=>'Could not store the uploaded file.']); exit; }
$stmt=$pdo->prepare("INSERT INTO applicant_documents (applicant_id,application_id,document_type,original_name,stored_name,mime_type,file_size_bytes,verification_status,verification_notes) VALUES (?,?,?,?,?,?,?,'Pending',?)");
$stmt->execute([$applicantId,(int)$_POST['application_id'],trim($_POST['document_type']),$file['name'],$stored,$mime,$file['size'],'Awaiting document completeness, consistency, and quality checks.']);
$documentId=(int)$pdo->lastInsertId();
$result=['verification_status'=>'Pending'];
if (function_exists('curl_init')) {
    $check=curl_init('http://127.0.0.1:5000/document-check');
    curl_setopt_array($check,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>['document'=>new CURLFile($dir.$stored,$mime,$file['name'])],CURLOPT_TIMEOUT=>20]);
    $raw=curl_exec($check); curl_close($check); $checked=$raw ? json_decode($raw,true) : null;
    $allowedStatuses=['Complete','Needs Review','Incomplete','Unreadable'];
    if (is_array($checked) && ($checked['status']??'')==='ok' && in_array($checked['verification_status']??'', $allowedStatuses,true)) {
        $result=['verification_status'=>$checked['verification_status'],'verification_notes'=>$checked['verification_notes']??null];
        $update=$pdo->prepare('UPDATE applicant_documents SET ocr_text=?,verification_status=?,verification_notes=?,checked_at=NOW() WHERE document_id=?');
        $update->execute([$checked['ocr_text']??null,$result['verification_status'],$result['verification_notes'],$documentId]);
    }
}
echo json_encode(['ok'=>true,'document_id'=>$documentId]+$result);
?>
