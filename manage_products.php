<?php
// manage_products.php
require_once "core.php";
require_once "controllers/product_controller.php";

if (!is_logged_in() || ($_SESSION['role'] ?? 0) != 1) {
    header("Location: login.php");
    exit();
}

$products = get_all_products_ctr();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Manage Products - AgroLinked</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .styled-table{width:100%;border-collapse:collapse}
    .styled-table th, .styled-table td{padding:10px;border-bottom:1px solid #eee;text-align:left}
    .product-thumb{width:80px;height:60px;object-fit:cover;border-radius:6px}
  </style>
</head>
<body class="page">
  <?php include 'nav.php'; ?>
  <main class="center-wrap" style="align-items:flex-start;padding:36px 20px;">
    <div style="width:100%; max-width:1200px;">
      <h1>All Products</h1>
      <p class="muted">View and moderate products listed by vendors.</p>

      <table class="styled-table" id="productsTable">
        <thead>
          <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Vendor</th>
            <th>Price (GHS)</th>
            <th>Qty</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $p): ?>
          <tr data-id="<?php echo $p['product_id']; ?>">
            <td><img class="product-thumb" src="<?php echo htmlspecialchars($p['main_image'] ?? 'assets/images/default.png'); ?>" alt=""></td>
            <td><?php echo htmlspecialchars($p['product_name']); ?></td>
            <td><?php echo htmlspecialchars($p['vendor_name'] ?? '-'); ?></td>
            <td><?php echo number_format($p['price'],2); ?></td>
            <td><?php echo (int)$p['quantity']; ?></td>
            <td><?php echo $p['date_added']; ?></td>
            <td>
              <a class="btn btn-link" href="single_product.php?id=<?php echo $p['product_id']; ?>">View</a>
              <a class="btn btn-link" href="edit_product.php?id=<?php echo $p['product_id']; ?>">Edit</a>
              <button class="btn btn-danger delete-btn">Delete</button>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>

    </div>
  </main>

<script>
document.addEventListener('click', async (e) => {
  if (!e.target.matches('.delete-btn')) return;
  if (!confirm('Delete this product?')) return;
  const btn = e.target;
  btn.disabled = true;
  const tr = btn.closest('tr');
  const pid = tr.dataset.id;
  try {
    const fd = new FormData();
    fd.append('product_id', pid);
    const res = await fetch('actions/delete_product_action.php', { method:'POST', body: fd });
    const j = await res.json();
    if (j.status === 'success') {
      tr.remove();
    } else {
      alert(j.message || 'Delete failed');
      btn.disabled = false;
    }
  } catch (err) {
    alert('Network error');
    btn.disabled = false;
  }
});
</script>
</body>
</html>
