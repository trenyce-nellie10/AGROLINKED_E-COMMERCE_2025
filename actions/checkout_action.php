<?php
// actions/checkout_action.php
require_once __DIR__ . '/../core.php';
require_once __DIR__ . '/../controllers/order_controller.php';
require_once __DIR__ . '/../controllers/cart_controller.php';

header('Content-Type: application/json');

// must be logged in customer
if (!is_logged_in() || ($_SESSION['role'] ?? 0) != 3) {
    http_response_code(403);
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

$customer_id = $_SESSION['user_id'];
$cart_json = $_POST['cart'] ?? ''; // client may send cart items as JSON
$mobile = trim($_POST['mobile'] ?? '');
$delivery_fee = (float)($_POST['delivery_fee'] ?? 0);

if ($cart_json === '') {
    echo json_encode(['status'=>'error','message'=>'Cart required']);
    exit;
}

$cart_items = json_decode($cart_json, true);
if (!is_array($cart_items) || empty($cart_items)) {
    echo json_encode(['status'=>'error','message'=>'Cart empty']);
    exit;
}

// compute total and reformat items for Order class
$total = 0;
$order_items = [];
foreach ($cart_items as $it) {
    $pid = (int)($it['product_id'] ?? 0);
    $qty = max(1, (int)($it['qty'] ?? ($it['quantity'] ?? 1)));
    $price = (float)($it['price'] ?? ($it['price_each'] ?? 0));
    if ($pid <= 0 || $price <= 0) continue;
    $total += $price * $qty;
    $order_items[] = [
        'product_id' => $pid,
        'quantity' => $qty,
        'price_each' => $price
    ];
}

if (empty($order_items)) {
    echo json_encode(['status'=>'error','message'=>'No valid items']);
    exit;
}

$grand_total = $total + $delivery_fee;

// create order
$order_id = create_order_ctr($customer_id, $grand_total, $order_items);
if (!$order_id) {
    echo json_encode(['status'=>'error','message'=>'Could not create order']);
    exit;
}

// clear server-side cart (if you use cart table)
clear_cart_ctr($customer_id);

echo json_encode(['status'=>'success','message'=>'Order placed', 'order_id'=>$order_id]);
