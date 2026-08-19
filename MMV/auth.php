<?php
session_start();
function require_hr(): void {
    if (empty($_SESSION['hr_user_id']) || empty($_SESSION['hr_role'])) {
        http_response_code(401); header('Content-Type: application/json'); echo json_encode(['ok'=>false,'message'=>'HR authentication is required.']); exit;
    }
}
function require_hr_page(string $loginPage = 'hr_login.php'): void {
    if (empty($_SESSION['hr_user_id']) || empty($_SESSION['hr_role'])) {
        header('Location: ' . $loginPage, true, 302);
        exit;
    }
}
function require_hr_admin(): void {
    require_hr();
    if (($_SESSION['hr_role'] ?? '') !== 'HR Admin') {
        http_response_code(403); header('Content-Type: application/json'); echo json_encode(['ok'=>false,'message'=>'HR Admin access is required.']); exit;
    }
}
function require_applicant(): void {
    if (empty($_SESSION['applicant_id'])) {
        http_response_code(401); header('Content-Type: application/json'); echo json_encode(['ok'=>false,'message'=>'Applicant authentication is required.']); exit;
    }
}
?>
