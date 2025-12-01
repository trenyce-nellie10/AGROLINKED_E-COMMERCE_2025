<?php
// actions/edit_product_action.php
require_once __DIR__ . '/../core.php';
require_once __DIR__ . '/../controllers/product_controller.php';

header('Content-Type: application/json');

// Must be logged in vendor (owner) or admin to edit
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

// Fetch existing product to verify ownership
$existing = get_product_ctr($product_id);
if (!$existing) {
    echo json_encode(['status'=>'error','message'=>'Product not found']);
    exit;
}

// If user is vendor, ensure they own the product
if ($user_role == 2 && (int)$existing['vendor_id'] !== (int)$user_id) {
    echo json_encode(['status'=>'error','message'=>'You are not authorized to edit this product']);
    exit;
}

// Get fields
$product_name = trim($_POST['product_name'] ?? $existing['product_name']);
$category_id = (int)($_POST['category_id'] ?? $existing['category_id']);
$description = trim($_POST['product_description'] ?? $existing['product_description']);
$price = (float)($_POST['price'] ?? $existing['price']);
$quantity = (int)($_POST['quantity'] ?? $existing['quantity']);
$image_path = trim($_POST['image_path'] ?? $existing['main_image']);

// Handle optional new image upload
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
    $userFolder = $uploads . 'u' . intval($existing['vendor_id']) . '/';
    if (!is_dir($userFolder)) mkdir($userFolder, 0755, true);
    $safe = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', basename($file['name']));
    $target = $userFolder . time() . '_' . $safe;
    if (!move_uploaded_file($file['tmp_name'], $target)) {
        echo json_encode(['status'=>'error','message'=>'Failed to save image']);
        exit;
    }
    $image_path = 'uploads/u' . intval($existing['vendor_id']) . '/' . basename($target);
}

// Build data and update
$data = [
    'category_id' => $category_id ?: null,
    'product_name' => $product_name,
    'product_description' => $description,
    'price' => $price,
    'quantity' => $quantity,
    'main_image' => $image_path ?: null
];

$ok = update_product_ctr($product_id, $data);
if ($ok) echo json_encode(['status'=>'success','message'=>'Product updated']);
else echo json_encode(['status'=>'error','message'=>'Update failed']);
