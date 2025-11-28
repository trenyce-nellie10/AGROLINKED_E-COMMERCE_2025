<?php
// controllers/AuthController.php
require_once __DIR__ . '/../classes/User.php';

function register_user($data){
    $u = new User();
    if ($u->findByEmail($data['email'])) return ['status'=>'error','message'=>'Email taken'];
    $ok = $u->add($data['name'],$data['email'],$data['password'],$data['phone'],$data['role'] ?? 3);
    return $ok ? ['status'=>'success','message'=>'Registered'] : ['status'=>'error','message'=>'Registration failed'];
}

function login_user($email,$password){
    $u = new User();
    $user = $u->findByEmail($email);
    if (!$user) return ['status'=>'error','message'=>'Account not found'];
    if (!password_verify($password,$user['password'])) return ['status'=>'error','message'=>'Invalid credentials'];
    return ['status'=>'success','user'=>$user];
}
