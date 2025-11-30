<?php require_once '../core.php'; $id=(int)($_GET['id']??0); if(!$id) header('Location: all_products.php'); require_once 'classes/Product.php'; $p=(new Product())->get($id); if (!$p) { echo "Not found"; exit; }
?>
<!doctype html><html><head><meta charset="utf-8"><title><?php echo htmlspecialchars($p['title']); ?></title><link rel="stylesheet" href="../css/style.css"></head>
<body class="page bg-image"><?php include 'nav.php'; ?>
<main class="center-wrap">
  <div class="card" style="max-width:900px;">
    <div style="display:flex;gap:20px;">
      <div style="flex:1;"><img src="<?php echo htmlspecialchars($p['image_path'] ?? 'assets/images/default.png'); ?>" style="width:100%;height:420px;object-fit:cover;border-radius:12px;"></div>
      <div style="flex:1;">
        <h1><?php echo htmlspecialchars($p['title']); ?></h1>
        <p><strong>Vendor:</strong> <?php echo htmlspecialchars($p['vendor_name']); ?></p>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($p['cat_name']); ?></p>
        <p><strong>Price:</strong> ₵ <?php echo number_format($p['unit_price'],2); ?> / <?php echo htmlspecialchars($p['quantity']); ?></p>
        <p><?php echo nl2br(htmlspecialchars($p['description'])); ?></p>
        <div>
          <label>Qty</label><input id="qty" type="number" value="1" min="1" style="width:80px;padding:8px;border-radius:8px;">
          <button class="btn btn-primary" id="addToCart">Add to Cart</button>
        </div>
        <div id="msg" class="form-message"></div>
      </div>
    </div>
  </div>
</main>
<script>
document.getElementById('addToCart').addEventListener('click',()=>{
  const qty = parseInt(document.getElementById('qty').value);
  const item = { product_id: <?php echo $p['product_id']; ?>, vendor_id: <?php echo $p['vendor_id']; ?>, title: "<?php echo addslashes($p['title']); ?>", price: <?php echo (float)$p['unit_price']; ?>, qty: qty };
  const cart = JSON.parse(localStorage.getItem('agro_cart')||'[]');
  cart.push(item);
  localStorage.setItem('agro_cart', JSON.stringify(cart));
  document.getElementById('msg').textContent = 'Added to cart';
});
</script>
</body></html>
