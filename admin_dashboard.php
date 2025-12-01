<?php
// admin_dashboard.php
require_once "core.php";
require_once "controllers/user_controller.php";
require_once "controllers/product_controller.php";
require_once "controllers/order_controller.php";

// Only admins (role = 1)
if (!is_logged_in() || ($_SESSION['role'] ?? 0) != 1) {
    header("Location: login.php");
    exit();
}

// Basic stats
$totalVendors = count(get_all_vendors_ctr());
$allProducts = get_all_products_ctr();
$totalProducts = is_array($allProducts) ? count($allProducts) : 0;
$allOrders = get_all_orders_ctr();
$totalOrders = is_array($allOrders) ? count($allOrders) : 0;
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard - AgroLinked</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .stats { display:flex; gap:18px; flex-wrap:wrap; margin-bottom:20px; }
    .stat { background:#fff;padding:18px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,.06);flex:1;min-width:160px; }
    .stat h3{margin:0;font-size:20px;}
    .stat p{margin:6px 0 0;color:#666;}
    .admin-actions { display:flex; gap:12px; margin-bottom:12px; flex-wrap:wrap; }
  </style>
</head>
<body class="page">
  <?php include 'nav.php'; ?>
  <main class="center-wrap" style="align-items:flex-start;padding:36px 20px;">
    <div style="width:100%; max-width:1200px;">
      <h1>Admin Dashboard</h1>
      <p class="muted">Welcome back, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong></p>

      <div class="stats">
        <div class="stat"><h3><?php echo $totalVendors; ?></h3><p>Registered vendors</p></div>
        <div class="stat"><h3><?php echo $totalProducts; ?></h3><p>Total products</p></div>
        <div class="stat"><h3><?php echo $totalOrders; ?></h3><p>Total orders</p></div>
      </div>

      <div class="admin-actions">
        <a class="btn btn-primary" href="manage_vendors.php">Manage Vendors</a>
        <a class="btn btn-primary" href="manage_products.php">Manage Products</a>
        <a class="btn btn-primary" href="orders_admin.php">View Orders</a>
        <a class="btn btn-secondary" href="manage_reports.php">Reports</a>
      </div>

      <section style="margin-top:18px;">
        <h2>Recent Orders</h2>
        <?php if (empty($allOrders)): ?>
          <p>No orders yet.</p>
        <?php else: ?>
          <table class="styled-table" style="width:100%;margin-top:12px;">
            <thead>
              <tr><th>Order ID</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr>
            </thead>
            <tbody>
            <?php foreach (array_slice($allOrders,0,10) as $o): ?>
              <tr>
                <td><?php echo $o['order_id']; ?></td>
                <td><?php echo htmlspecialchars($o['customer_name'] ?? ''); ?></td>
                <td>GHS <?php echo number_format($o['total_amount'],2); ?></td>
                <td><?php echo htmlspecialchars($o['order_status']); ?></td>
                <td><?php echo $o['order_date']; ?></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </section>

    </div>
  </main>
</body>
</html>
