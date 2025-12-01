<?php
// marketplace.php
require_once "core.php";
require_once "controllers/product_controller.php";
require_once "controllers/category_controller.php";

$products = get_all_products_ctr();
$categories = get_all_categories_ctr();

$selected_cat = $_GET['category'] ?? '';
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Marketplace - AgroLinked</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .products-grid { display:flex; flex-wrap:wrap; gap:16px; margin-top:20px; }
    .product-card { background:#fff;padding:14px;border-radius:10px;width:260px;box-shadow:0 4px 12px rgba(0,0,0,.06);transition:.2s; }
    .product-card:hover { transform:translateY(-4px); }
    .product-img { width:100%;height:160px;object-fit:cover;border-radius:8px; }
    .filter-bar { background:#fff;padding:14px;border-radius:10px;margin-bottom:20px;display:flex;gap:10px; }
  </style>
</head>
<body class="page">

<?php include "nav.php"; ?>

<main class="center-wrap" style="padding:36px 20px;">
  <h1>Marketplace</h1>
  <p class="muted">Browse products from verified vendors on AgroLinked.</p>

  <div class="filter-bar">
    <form method="GET" style="display:flex; gap:10px;">
      <select name="category">
        <option value="">All Categories</option>
        <?php foreach ($categories as $c): ?>
          <option value="<?php echo $c['category_id']; ?>"
            <?php if ($selected_cat == $c['category_id']) echo "selected"; ?>>
            <?php echo htmlspecialchars($c['category_name']); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <button class="btn btn-primary">Filter</button>

      <?php if ($selected_cat): ?>
        <a href="marketplace.php" class="btn btn-secondary">Clear</a>
      <?php endif; ?>
    </form>
  </div>

  <div class="products-grid">
    <?php 
    foreach ($products as $p): 
      if ($selected_cat && $p['category_id'] != $selected_cat) continue;
    ?>
      <div class="product-card">
        <img src="<?php echo htmlspecialchars($p['main_image'] ?? 'assets/images/default.png'); ?>" class="product-img">
        <h3><?php echo htmlspecialchars($p['product_name']); ?></h3>
        <p class="muted">GHS <?php echo number_format($p['price'],2); ?></p>

        <a href="single_product.php?id=<?php echo $p['product_id']; ?>" class="btn btn-primary" style="width:100%;">View</a>
      </div>
    <?php endforeach; ?>
  </div>
</main>

</body>
</html>
