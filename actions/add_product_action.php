<?php
// actions/add_product_action.php
require_once __DIR__ . '/../core.php';
require_once __DIR__ . '/../controllers/product_controller.php';

// Only vendors (role 2) can add products
if (!is_logged_in() || ($_SESSION['role'] ?? 0) != 2) {
    http_response_code(403);
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

$vendor_id = $_SESSION['user_id'];
$product_name = trim($_POST['product_name'] ?? '');
$category_id = (int)($_POST['category_id'] ?? 0);
$description = trim($_POST['product_description'] ?? '');
$price = (float)($_POST['price'] ?? 0);
$quantity = (int)($_POST['quantity'] ?? 0);
$image_path = trim($_POST['image_path'] ?? ''); // optional (client may upload separately)

// Basic validation
if ($product_name === '' || $price <= 0) {
    echo json_encode(['status'=>'error','message'=>'Product name and positive price are required']);
    exit;
}

// If a file was uploaded as "main_image", handle it
if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['main_image'];
    $allowed = ['image/jpeg','image/png','image/webp'];
    $mime = mime_content_type($file['tmp_name']);
    if (!in_array($mime, $allowed)) {
        echo json_encode(['status'=>'error','message'=>'Invalid image type']);
        exit;
    }
    $uploads = __DIR__ . '/../uploads/';
    if (!is_dir($uploads)) mkdir($uploads, 0755, true);
    $userFolder = $uploads . 'u' . intval($vendor_id) . '/';
    if (!is_dir($userFolder)) mkdir($userFolder, 0755, true);
    $safe = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', basename($file['name']));
    $target = $userFolder . time() . '_' . $safe;
    if (!move_uploaded_file($file['tmp_name'], $target)) {
        echo json_encode(['status'=>'error','message'=>'Failed to save image']);
        exit;
    }
    // web path relative to project root
    $image_path = 'uploads/u' . intval($vendor_id) . '/' . basename($target);
}

// Prepare data and call controller
$data = [
    'vendor_id' => $vendor_id,
    'category_id' => $category_id ?: null,
    'product_name' => $product_name,
    'product_description' => $description,
    'price' => $price,
    'quantity' => $quantity,
    'main_image' => $image_path ?: null
];

$ok = add_product_ctr($data);
if ($ok) echo json_encode(['status'=>'success','message'=>'Product added']);
else echo json_encode(['status'=>'error','message'=>'Could not add product']);
