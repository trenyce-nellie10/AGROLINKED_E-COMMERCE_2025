<?php
// actions/product_action.php
require_once __DIR__ . '/../classes/Product.php';
require_once __DIR__ . '/../core.php';
header('Content-Type: application/json');
$prod = new Product();
$action = $_REQUEST['action'] ?? '';
switch($action){
  case 'add':
    if (!is_logged_in() || ($_SESSION['role'] ?? 0) != 2){ echo json_encode(['status'=>'error','message'=>'Unauthorized']); exit; }
    $data = [
      'vendor_id'=>$_SESSION['user_id'],
      'cat_id'=> (int)($_POST['cat_id'] ?? 0),
      'brand_id'=> (int)($_POST['brand_id'] ?? 0),
      'title'=> trim($_POST['title'] ?? ''),
      'quantity'=> trim($_POST['quantity'] ?? ''),
      'unit_price'=> (float)($_POST['unit_price'] ?? 0),
      'description'=> trim($_POST['description'] ?? ''),
      'image_path'=> trim($_POST['image_path'] ?? null),
      'grade'=> $_POST['grade'] ?? 'A'
    ];
    echo json_encode($prod->add($data) ? ['status'=>'success','message'=>'Product added'] : ['status'=>'error','message'=>'Add failed']);
    break;
  case 'update':
    if (!is_logged_in() || ($_SESSION['role'] ?? 0) != 2){ echo json_encode(['status'=>'error','message'=>'Unauthorized']); exit; }
    $pid = (int)($_POST['product_id'] ?? 0);
    $data = [
      'cat_id'=> (int)($_POST['cat_id'] ?? 0),
      'brand_id'=> (int)($_POST['brand_id'] ?? 0),
      'title'=> trim($_POST['title'] ?? ''),
      'quantity'=> trim($_POST['quantity'] ?? ''),
      'unit_price'=> (float)($_POST['unit_price'] ?? 0),
      'description'=> trim($_POST['description'] ?? ''),
      'image_path'=> trim($_POST['image_path'] ?? null),
      'grade'=> $_POST['grade'] ?? 'A'
    ];
    echo json_encode($prod->update($pid,$data) ? ['status'=>'success','message'=>'Updated'] : ['status'=>'error','message'=>'Update failed']);
    break;
  case 'list':
    echo json_encode($prod->all());
    break;
  case 'get':
    $id = (int)($_GET['id'] ?? 0); echo json_encode($prod->get($id)); break;
  case 'search':
    $q = trim($_GET['q'] ?? ''); echo json_encode($prod->search($q)); break;
  case 'vendor_list':
    if (!is_logged_in()) { echo json_encode([]); exit; }
    echo json_encode($prod->byVendor($_SESSION['user_id']));
    break;
  default:
    echo json_encode(['status'=>'error','message'=>'Unknown action']);
}
