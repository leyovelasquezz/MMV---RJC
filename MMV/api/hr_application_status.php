<?php
require_once __DIR__ . '/../db_connect.php';
require_once __DIR__ . '/../auth.php';
require_hr();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$applicationId = (int)($data['application_id'] ?? 0);
$status = trim($data['status'] ?? '');
$allowed = ['Submitted','Under Review','Screening','For Interview','Accepted','Rejected'];
if (!$applicationId || !in_array($status, $allowed, true)) {
    http_response_code(422);
    echo json_encode(['ok'=>false,'message'=>'A valid application ID and recruitment status are required.']);
    exit;
}
$update = $pdo->prepare('UPDATE job_applications SET status=? WHERE application_id=?');
$update->execute([$status, $applicationId]);
if (!$update->rowCount()) {
    $exists = $pdo->prepare('SELECT 1 FROM job_applications WHERE application_id=?');
    $exists->execute([$applicationId]);
    if (!$exists->fetchColumn()) {
        http_response_code(404);
        echo json_encode(['ok'=>false,'message'=>'Application not found.']);
        exit;
    }
}
echo json_encode(['ok'=>true,'application_id'=>$applicationId,'status'=>$status]);
?>
