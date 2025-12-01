<?php
// my_products.php
require_once "core.php";
require_once "controllers/product_controller.php";

if (!is_logged_in() || ($_SESSION['role'] ?? 0) != 2) {
    header("Location: login.php");
    exit();
}

$vendorId = $_SESSION['user_id'];
$products = get_products_by_vendor_ctr($vendorId);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>My Products - AgroLinked</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .product-grid { display:flex; flex-wrap:wrap; gap:16px; }
    .product-card { background:#fff;padding:14px;border-radius:10px;width:260px;box-shadow:0 4px 12px rgba(0,0,0,.06); }
    .product-img { width:100%;height:160px;object-fit:cover;border-radius:8px; }
  </style>
</head>
<body class="page">

<?php include 'nav.php'; ?>

<main class="center-wrap" style="padding:36px 20px;">
  <h1>My Products</h1>
  <p class="muted">Manage the products you’ve listed.</p>

  <a class="btn btn-primary" href="add_product.php">Add New Product</a>

  <div class="product-grid" style="margin-top:20px;">
    <?php foreach ($products as $p): ?>
      <div class="product-card">
        <img src="<?php echo htmlspecialchars($p['main_image'] ?? 'assets/images/default.png'); ?>" class="product-img">
        <h3><?php echo htmlspecialchars($p['product_name']); ?></h3>
        <p class="muted">GHS <?php echo number_format($p['price'],2); ?></p>

        <div style="display:flex; gap:8px; margin-top:10px;">
          <a class="btn btn-secondary" href="edit_product.php?id=<?php echo $p['product_id']; ?>">Edit</a>

          <form method="POST" action="actions/delete_product_action.php">
            <input type="hidden" name="product_id" value="<?php echo $p['product_id']; ?>">
            <button class="btn btn-danger" onclick="return confirm('Delete this product?')">Delete</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</main>

</body>
</html>
