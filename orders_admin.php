<?php
// orders_admin.php
require_once "core.php";
require_once "controllers/order_controller.php";

if (!is_logged_in() || ($_SESSION['role'] ?? 0) != 1) {
    header("Location: login.php");
    exit();
}

$orders = get_all_orders_ctr();
$statuses = ['Pending','Processing','Shipped','Delivered','Cancelled'];
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Orders - Admin - AgroLinked</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .styled-table{width:100%;border-collapse:collapse}
    .styled-table th, .styled-table td{padding:10px;border-bottom:1px solid #eee;text-align:left}
    select.status-select{padding:6px;border-radius:8px}
  </style>
</head>
<body class="page">
  <?php include 'nav.php'; ?>
  <main class="center-wrap" style="align-items:flex-start;padding:36px 20px;">
    <div style="width:100%; max-width:1200px;">
      <h1>Orders</h1>
      <p class="muted">All customer orders on the platform.</p>

      <table class="styled-table" id="ordersTable">
        <thead>
          <tr>
            <th>Order</th><th>Customer</th><th>Total (GHS)</th><th>Status</th><th>Date</th><th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $o): ?>
          <tr data-id="<?php echo $o['order_id']; ?>">
            <td>#<?php echo $o['order_id']; ?></td>
            <td><?php echo htmlspecialchars($o['customer_name'] ?? '-'); ?></td>
            <td><?php echo number_format($o['total_amount'],2); ?></td>
            <td>
              <select class="status-select">
                <?php foreach ($statuses as $s): $sel = ($s === $o['order_status']) ? 'selected' : ''; ?>
                  <option value="<?php echo $s; ?>" <?php echo $sel; ?>><?php echo $s; ?></option>
                <?php endforeach; ?>
              </select>
            </td>
            <td><?php echo $o['order_date']; ?></td>
            <td><a class="btn btn-link" href="order_view.php?id=<?php echo $o['order_id']; ?>">View</a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>

    </div>
  </main>

<script>
document.querySelectorAll('#ordersTable .status-select').forEach(select => {
  select.addEventListener('change', async function () {
    const tr = this.closest('tr');
    const orderId = tr.dataset.id;
    const status = this.value;
    this.disabled = true;
    try {
      const fd = new FormData();
      fd.append('order_id', orderId);
      fd.append('status', status);
      const res = await fetch('actions/update_order_status_action.php', { method:'POST', body: fd });
      const j = await res.json();
      if (j.status !== 'success') alert(j.message || 'Could not update status');
    } catch (err) {
      alert('Network error');
    } finally {
      this.disabled = false;
    }
  });
});
</script>
</body>
</html>
