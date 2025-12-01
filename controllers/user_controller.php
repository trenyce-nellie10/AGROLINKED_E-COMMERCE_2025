<?php
// controllers/user_controller.php
require_once __DIR__ . '/../classes/User.php';

function register_user_ctr($data) {
    $u = new User();
    if ($u->findByEmail($data['email'])) {
        return ['status' => 'error', 'message' => 'Email already exists'];
    }
    $ok = $u->create($data['full_name'], $data['email'], $data['password'], $data['role'] ?? 3, $data['country'] ?? null, $data['city'] ?? null, $data['phone'] ?? null);
    return $ok ? ['status' => 'success', 'message' => 'Registration successful'] : ['status' => 'error', 'message' => 'Registration failed'];
}

function login_user_ctr($email, $password) {
    $u = new User();
    $user = $u->findByEmail($email);
    if (!$user) return ['status' => 'error', 'message' => 'Account not found'];
    if (!password_verify($password, $user['password'])) return ['status' => 'error', 'message' => 'Invalid credentials'];
    // return user payload
    return ['status' => 'success', 'user' => $user];
}

function get_all_vendors_ctr() {
    $u = new User();
    return $u->allVendors();
}
