<?php
// actions/register.php
require_once __DIR__ . '/../controllers/AuthController.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD']!=='POST'){ echo json_encode(['status'=>'error','message'=>'Invalid']); exit; }
$name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$pass = $_POST['password'] ?? '';
$phone = trim($_POST['phone'] ?? '');
$role = (int)($_POST['role'] ?? 3);
if ($name==='' || $email==='' || $pass===''){ echo json_encode(['status'=>'error','message'=>'Missing fields']); exit; }
$res = register_user(['name'=>$name,'email'=>$email,'password'=>$pass,'phone'=>$phone,'role'=>$role]);
echo json_encode($res);
