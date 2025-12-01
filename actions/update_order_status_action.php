<?php
// actions/update_order_status_action.php
require_once __DIR__ . '/../core.php';
require_once __DIR__ . '/../controllers/order_controller.php';

header('Content-Type: application/json');

// only admin or vendor (for vendor-specific order workflows) can update
if (!is_logged_in() || !in_array($_SESSION['role'], [1,2])) {
    http_response_code(403);
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

$order_id = (int)($_POST['order_id'] ?? 0);
$status = trim($_POST['status'] ?? '');

if ($order_id <= 0 || $status === '') {
    echo json_encode(['status'=>'error','message'=>'Invalid input']);
    exit;
}

$ok = update_order_status_ctr($order_id, $status);
if ($ok) echo json_encode(['status'=>'success','message'=>'Order status updated']);
else echo json_encode(['status'=>'error','message'=>'Could not update']);
