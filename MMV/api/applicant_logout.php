<?php
session_start(); unset($_SESSION['applicant_id'],$_SESSION['applicant_name']); header('Content-Type: application/json'); echo json_encode(['ok'=>true]);
?>
