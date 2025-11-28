<?php
// actions/upload_image.php
require_once __DIR__ . '/../core.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD']!=='POST'){ echo json_encode(['status'=>'error','message'=>'Invalid']); exit; }
if (!isset($_FILES['image'])){ echo json_encode(['status'=>'error','message'=>'No file']); exit; }
$u = $_SESSION['user_id'] ?? 'guest';
$uploadDir = __DIR__ . '/../uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir,0755,true);
$userFolder = $uploadDir . 'u' . intval($u) . '/';
if (!is_dir($userFolder)) mkdir($userFolder,0755,true);
$file = $_FILES['image'];
if ($file['error']!==UPLOAD_ERR_OK) { echo json_encode(['status'=>'error','message'=>'Upload error']); exit; }
$allowed = ['image/jpeg','image/png','image/webp'];
$mime = mime_content_type($file['tmp_name']);
if (!in_array($mime,$allowed)) { echo json_encode(['status'=>'error','message'=>'Invalid type']); exit; }
$basename = preg_replace('/[^a-zA-Z0-9_\-\.]/','_',basename($file['name']));
$target = $userFolder . time() . '_' . $basename;
if (!move_uploaded_file($file['tmp_name'],$target)){ echo json_encode(['status'=>'error','message'=>'Store failed']); exit; }
$rel = 'uploads/u' . intval($u) . '/' . basename($target);
echo json_encode(['status'=>'success','path'=>$rel]);
