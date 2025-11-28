<?php
// core.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db/db.php';

function is_logged_in(){ return isset($_SESSION['user_id']); }
function current_user(){ return ['id'=>$_SESSION['user_id'] ?? null, 'name'=>$_SESSION['full_name'] ?? null, 'role'=>$_SESSION['role'] ?? 0]; }
function require_login(){ if (!is_logged_in()) { header("Location: login.php"); exit; } }
function require_role($r){ if (!is_logged_in() || ($_SESSION['role'] ?? 0) != $r){ header("Location: login.php"); exit; } }
