<?php
// actions/order_action.php
require_once __DIR__ . '/../classes/Order.php';
require_once __DIR__ . '/../classes/Product.php';
require_once __DIR__ . '/../core.php';
header('Content-Type: application/json');
if (!is_logged_in()){ echo json_encode(['status'=>'error','message'=>'Login required']); exit; }
$action = $_REQUEST['action'] ?? '';
$order = new Order();
switch($action){
  case 'checkout':
    // Expect cart JSON, hub_id, mobile, delivery_fee
    $cart = json_decode($_POST['cart'] ?? '[]', true);
    $hub_id = (int)($_POST['hub_id'] ?? 0);
    $mobile = trim($_POST['mobile'] ?? '');
    $delivery_fee = (float)($_POST['delivery_fee'] ?? 0);
    if (empty($cart)) { echo json_encode(['status'=>'error','message'=>'Cart empty']); exit; }
    $total = 0; foreach($cart as $it) $total += (float)$it['price'] * (int)$it['qty'];
    $grand = $total + $delivery_fee;
    $oid = $order->create($_SESSION['user_id'],$hub_id,$grand,$delivery_fee,$mobile,'momo');
    if (!$oid){ echo json_encode(['status'=>'error','message'=>'Failed to create order']); exit; }
    foreach($cart as $it){
      $order->addItem($oid,(int)$it['product_id'],(int)$it['vendor_id'],(int)$it['qty'],(float)$it['price']);
    }
    echo json_encode(['status'=>'success','order_id'=>$oid,'message'=>'Order placed']);
    break;
  default:
    echo json_encode(['status'=>'error','message'=>'Unknown']);
}
