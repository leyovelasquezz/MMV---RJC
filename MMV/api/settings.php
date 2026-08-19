<?php
require_once __DIR__ . '/../db_connect.php'; require_once __DIR__ . '/../auth.php'; require_hr(); header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD']==='GET') { $rows=$pdo->query('SELECT setting_key,setting_value FROM system_settings')->fetchAll(PDO::FETCH_KEY_PAIR); echo json_encode(['ok'=>true,'settings'=>$rows]); exit; }
require_hr_admin(); $data=json_decode(file_get_contents('php://input'),true) ?: $_POST; $allowed=['organization_name','default_application_status','document_check_required','screening_enabled','assessment_enabled'];
$pdo->beginTransaction(); try { foreach($allowed as $key){if(array_key_exists($key,$data)){$pdo->prepare('INSERT INTO system_settings (setting_key,setting_value,updated_by) VALUES (?,?,?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value),updated_by=VALUES(updated_by)')->execute([$key,(string)$data[$key],$_SESSION['hr_user_id']]);}} $pdo->commit(); echo json_encode(['ok'=>true]); } catch(Throwable $e){$pdo->rollBack();http_response_code(500);echo json_encode(['ok'=>false,'message'=>'Settings could not be saved.']);}
?>
