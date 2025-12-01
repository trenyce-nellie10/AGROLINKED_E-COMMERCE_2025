<?php
// vendor_dashboard.php
require_once "core.php";
require_once "controllers/product_controller.php";
require_once "controllers/vendor_controller.php";

// Only vendors (role = 2)
if (!is_logged_in() || ($_SESSION['role'] ?? 0) != 2) {
    header("Location: login.php");
    exit();
}

$vendorId = $_SESSION['user_id'];
$myProducts = get_products_by_vendor_ctr($vendorId);
$profile = get_vendor_profile_ctr($vendorId);
$approved = $profile['approved'] ?? 0;
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Vendor Dashboard - AgroLinked</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .vendor-top { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:18px; }
    .vendor-cards { display:flex; gap:12px; flex-wrap:wrap; margin-bottom:18px; }
    .small-card { background:#fff;padding:14px;border-radius:10px;box-shadow:0 6px 18px rgba(0,0,0,.06); min-width:160px; flex:1;}
  </style>
</head>
<body class="page">
  <?php include 'nav.php'; ?>
  <main class="center-wrap" style="align-items:flex-start;padding:36px 20px;">
    <div style="width:100%; max-width:1100px;">
      <div class="vendor-top">
        <div>
          <h1>Vendor Dashboard</h1>
          <p class="muted">Welcome, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong></p>
        </div>
        <div>
          <?php if ($approved): ?>
            <span class="btn btn-secondary" style="pointer-events:none">Approved</span>
          <?php else: ?>
            <span class="btn btn-danger" style="pointer-events:none">Pending approval</span>
          <?php endif; ?>
        </div>
      </div>

      <div class="vendor-cards">
        <div class="small-card"><h3><?php echo count($myProducts); ?></h3><p>Your products</p></div>
        <div class="small-card"><h3>—</h3><p>Orders (coming soon)</p></div>
        <div class="small-card"><h3>—</h3><p>Sales (coming soon)</p></div>
      </div>

      <div style="margin-top:10px; display:flex; gap:12px;">
        <a class="btn btn-primary" href="add_product.php">Add Product</a>
        <a class="btn btn-link" href="my_products.php">Manage My Products</a>
      </div>

      <section style="margin-top:20px;">
        <h2>My latest products</h2>
        <?php if (empty($myProducts)): ?>
          <p>You have not added any products yet.</p>
        <?php else: ?>
          <div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:12px;">
            <?php foreach ($myProducts as $p): ?>
              <div class="card" style="width:260px;padding:10px;">
                <img src="<?php echo htmlspecialchars($p['main_image'] ?? 'assets/images/default.png'); ?>" style="width:100%; height:140px; object-fit:cover; border-radius:8px;">
                <h4 style="margin:8px 0 4px;"><?php echo htmlspecialchars($p['product_name']); ?></h4>
                <p class="muted" style="margin:0 0 8px;">GHS <?php echo number_format($p['price'],2); ?></p>
                <div style="display:flex;gap:8px;">
                  <a class="btn btn-link" href="edit_product.php?id=<?php echo $p['product_id']; ?>">Edit</a>
                  <form method="post" action="actions/delete_product_action.php" style="display:inline;">
                    <input type="hidden" name="product_id" value="<?php echo $p['product_id']; ?>">
                    <button class="btn btn-danger" type="submit" onclick="return confirm('Delete product?')">Delete</button>
                    <script src="js/vendor.js"></script>

                  </form>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </section>

    </div>
  </main>
</body>
</html>
