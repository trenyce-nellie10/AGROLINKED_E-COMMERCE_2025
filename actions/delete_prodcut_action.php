<?php
// actions/delete_product_action.php
require_once __DIR__ . '/../core.php';
require_once __DIR__ . '/../controllers/product_controller.php';

header('Content-Type: application/json');

// must be logged in
if (!is_logged_in()) {
    http_response_code(403);
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

$user_role = $_SESSION['role'] ?? 0;
$user_id = $_SESSION['user_id'] ?? 0;
$product_id = (int)($_POST['product_id'] ?? 0);

if ($product_id <= 0) {
    echo json_encode(['status'=>'error','message'=>'Invalid product id']);
    exit;
}

$existing = get_product_ctr($product_id);
if (!$existing) {
    echo json_encode(['status'=>'error','message'=>'Product not found']);
    exit;
}

// vendor can delete only their own, admin can delete any
if ($user_role == 2 && (int)$existing['vendor_id'] !== (int)$user_id) {
    echo json_encode(['status'=>'error','message'=>'Not authorized']);
    exit;
}

$ok = delete_product_ctr($product_id);
if ($ok) echo json_encode(['status'=>'success','message'=>'Product deleted']);
else echo json_encode(['status'=>'error','message'=>'Delete failed']);
