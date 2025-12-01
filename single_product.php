<?php
// single_product.php
require_once "core.php";
require_once "controllers/product_controller.php";
require_once "controllers/vendor_controller.php";

$id = $_GET['id'] ?? 0;
$product = get_product_ctr($id);

if (!$product) {
    echo "Product not found.";
    exit();
}

// Get vendor info
$vendor = get_vendor_profile_ctr($product['vendor_id']);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?php echo htmlspecialchars($product['product_name']); ?> - AgroLinked</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .product-container { display:flex; gap:20px; flex-wrap:wrap; }
    .p-img { width:360px; height:300px; object-fit:cover; border-radius:12px; }
    .qty-box { width:70px; padding:6px; }
  </style>
</head>
<body class="page">

<?php include "nav.php"; ?>

<main class="center-wrap" style="padding:36px 20px;">
  
  <a class="btn btn-secondary" href="marketplace.php">← Back</a>

  <div class="product-container" style="margin-top:25px;">
    <div>
      <img src="<?php echo htmlspecialchars($product['main_image']); ?>" class="p-img">
    </div>

    <div style="max-width:500px;">
      <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>
      <h3 style="margin-top:8px;">GHS <?php echo number_format($product['price'],2); ?></h3>
      <p style="margin-top:12px;"><?php echo nl2br(htmlspecialchars($product['product_description'])); ?></p>

      <p class="muted">Stock: <?php echo $product['quantity']; ?></p>

      <p class="muted">Vendor: 
        <strong><?php echo htmlspecialchars($vendor['business_name'] ?? "Unknown Vendor"); ?></strong>
      </p>

      <div style="margin:18px 0;">
        <label>Quantity:</label>
        <input type="number" id="qty" class="qty-box" value="1" min="1" max="<?php echo $product['quantity']; ?>">
      </div>

      <button class="btn btn-primary" id="addToCartBtn">Add to Cart</button>

      <p id="msg" style="margin-top:12px;"></p>
    </div>
  </div>

</main>

<script>
document.getElementById("addToCartBtn").onclick = async () => {
  let qty = document.getElementById("qty").value;
  let fd = new FormData();
  fd.append("product_id", "<?php echo $product['product_id']; ?>");
  fd.append("quantity", qty);

  const res = await fetch("actions/add_to_cart_action.php", {
    method: "POST",
    body: fd
  });

  const data = await res.json();
  const msg = document.getElementById("msg");

  if (data.status === "success") {
    msg.style.color = "green";
    msg.textContent = "Added to cart!";
  } else {
    msg.style.color = "red";
    msg.textContent = data.message;
  }
};
</script>

</body>
</html>
