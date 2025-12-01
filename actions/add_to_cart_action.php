<?php
// actions/add_to_cart_action.php
require_once __DIR__ . '/../core.php';
require_once __DIR__ . '/../controllers/cart_controller.php';

header('Content-Type: application/json');

// must be logged in customer
if (!is_logged_in() || ($_SESSION['role'] ?? 0) != 3) {
    http_response_code(403);
    echo json_encode(['status'=>'error','message'=>'Only customers can add to cart']);
    exit;
}

$customer_id = $_SESSION['user_id'];
$product_id = (int)($_POST['product_id'] ?? 0);
$quantity = max(1, (int)($_POST['quantity'] ?? 1));

if ($product_id <= 0) {
    echo json_encode(['status'=>'error','message'=>'Invalid product']);
    exit;
}

$ok = add_to_cart_ctr($customer_id, $product_id, $quantity);
if ($ok) echo json_encode(['status'=>'success','message'=>'Added to cart']);
else echo json_encode(['status'=>'error','message'=>'Could not add to cart']);
