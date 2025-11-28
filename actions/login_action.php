<?php
// actions/login.php
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../core.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD']!=='POST'){ echo json_encode(['status'=>'error','message'=>'Invalid']); exit; }
$email = trim($_POST['email'] ?? '');
$pass = $_POST['password'] ?? '';
$res = login_user($email,$pass);
if ($res['status']==='success'){
    $u = $res['user'];
    $_SESSION['user_id'] = $u['user_id'];
    $_SESSION['full_name'] = $u['full_name'];
    $_SESSION['role'] = $u['role'];
}
echo json_encode($res);
