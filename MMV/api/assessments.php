<?php
require_once __DIR__ . '/../db_connect.php'; require_once __DIR__ . '/../auth.php'; require_hr(); header('Content-Type: application/json');
$method=$_SERVER['REQUEST_METHOD']; $data=json_decode(file_get_contents('php://input'),true) ?: $_POST;
if ($method==='GET') { $rows=$pdo->query('SELECT assessment_id,name,assessment_type,question_count,status,created_at FROM assessments ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC); echo json_encode(['ok'=>true,'assessments'=>$rows]); exit; }
if ($method==='POST') {
  $name=trim($data['name']??''); $type=trim($data['assessment_type']??'Multiple Choice'); $questions=(int)($data['question_count']??0); $status=$data['status']??'Active';
  if ($name==='' || $questions<0 || !in_array($status,['Active','Inactive'],true)) { http_response_code(422); echo json_encode(['ok'=>false,'message'=>'Provide a name, valid question count, and status.']); exit; }
  if (!empty($data['assessment_id'])) { $stmt=$pdo->prepare('UPDATE assessments SET name=?,assessment_type=?,question_count=?,status=? WHERE assessment_id=?'); $stmt->execute([$name,$type,$questions,$status,(int)$data['assessment_id']]); echo json_encode(['ok'=>true]); exit; }
  $stmt=$pdo->prepare('INSERT INTO assessments (name,assessment_type,question_count,status,created_by) VALUES (?,?,?,?,?)'); $stmt->execute([$name,$type,$questions,$status,$_SESSION['hr_user_id']]); echo json_encode(['ok'=>true,'assessment_id'=>(int)$pdo->lastInsertId()]); exit;
}
if ($method==='DELETE') { $id=(int)($data['assessment_id']??0); if(!$id){http_response_code(422);echo json_encode(['ok'=>false,'message'=>'Assessment is required.']);exit;} $pdo->prepare('DELETE FROM assessments WHERE assessment_id=?')->execute([$id]); echo json_encode(['ok'=>true]); exit; }
http_response_code(405); echo json_encode(['ok'=>false,'message'=>'Method not allowed.']);
?>
