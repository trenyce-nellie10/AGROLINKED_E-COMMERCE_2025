<?php
// actions/vendor_approve_action.php
require_once __DIR__ . '/../core.php';
require_once __DIR__ . '/../controllers/vendor_controller.php';

header('Content-Type: application/json');

// only admin can approve vendors
if (!is_logged_in() || ($_SESSION['role'] ?? 0) != 1) {
    http_response_code(403);
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

$vendor_id = (int)($_POST['vendor_id'] ?? 0);
if ($vendor_id <= 0) {
    echo json_encode(['status'=>'error','message'=>'Invalid vendor id']);
    exit;
}

$ok = approve_vendor_ctr($vendor_id);
if ($ok) echo json_encode(['status'=>'success','message'=>'Vendor approved']);
else echo json_encode(['status'=>'error','message'=>'Approval failed']);
