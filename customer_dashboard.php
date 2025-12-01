<?php
// customer_dashboard.php
require_once "core.php";
require_once "controllers/order_controller.php";

// Only customers (role = 3)
if (!is_logged_in() || ($_SESSION['role'] ?? 0) != 3) {
    header("Location: login.php");
    exit();
}

$customerId = $_SESSION['user_id'];
$orders = get_all_orders_ctr(); // admin view; for per-customer you can call order class per customer
// we'll filter here for the customer
$myOrders = array_filter($orders, function($o) use ($customerId) {
    return (int)$o['customer_id'] === (int)$customerId;
});
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>My Account - AgroLinked</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .account-top { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; }
    .order-list { margin-top:12px; }
  </style>
</head>
<body class="page">
  <?php include 'nav.php'; ?>
  <main class="center-wrap" style="align-items:flex-start;padding:36px 20px;">
    <div style="width:100%; max-width:1000px;">
      <div class="account-top">
        <div>
          <h1>My Account</h1>
          <p class="muted">Hello, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong></p>
        </div>
        <div>
          <a class="btn btn-primary" href="cart.php">View Cart</a>
        </div>
      </div>

      <section>
        <h2>My Orders</h2>
        <?php if (empty($myOrders)): ?>
          <p>You have no orders yet.</p>
        <?php else: ?>
          <table class="styled-table" style="width:100%;margin-top:12px;">
            <thead><tr><th>Order</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
            <?php foreach ($myOrders as $o): ?>
              <tr>
                <td>#<?php echo $o['order_id']; ?></td>
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
