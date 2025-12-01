<?php
// cart.php
require_once "core.php";
require_once "controllers/cart_controller.php";
require_once "controllers/product_controller.php";

if (!is_logged_in() || ($_SESSION['role'] ?? 0) != 3) {
    header("Location: login.php");
    exit();
}

$customerId = $_SESSION['user_id'];
$items = get_cart_items_ctr($customerId);

$total = 0;
foreach ($items as $i) {
    $total += $i['price'] * $i['quantity'];
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>My Cart - AgroLinked</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .cart-row { display:flex; align-items:center; gap:14px; margin-bottom:14px;background:#fff;padding:12px;border-radius:10px; }
    .cart-img { width:80px;height:70px;object-fit:cover;border-radius:8px; }
    .checkout-box { background:#fff;padding:20px;border-radius:10px;margin-top:20px; }
  </style>
</head>
<body class="page">

<?php include "nav.php"; ?>

<main class="center-wrap" style="padding:36px 20px;">
  <h1>Your Cart</h1>

  <div style="max-width:900px;">
    <?php if (empty($items)): ?>
      <p>Your cart is empty.</p>

    <?php else: ?>
      <?php foreach ($items as $item): ?>
        <div class="cart-row">
          <img src="<?php echo htmlspecialchars($item['main_image']); ?>" class="cart-img">

          <div>
            <h3><?php echo htmlspecialchars($item['product_name']); ?></h3>
            <p class="muted">GHS <?php echo number_format($item['price'],2); ?></p>
          </div>

          <div style="margin-left:auto;">
            Qty: <?php echo $item['quantity']; ?>
          </div>
        </div>
      <?php endforeach; ?>

      <div class="checkout-box">
        <h3>Total: GHS <?php echo number_format($total,2); ?></h3>

        <form id="checkoutForm">
          <label>Mobile Money Number</label>
          <input type="text" name="mobile" required>

          <label>Delivery Fee (GHS)</label>
          <input type="number" name="delivery_fee" value="15" required>

          <input type="hidden" name="cart" value="<?php echo htmlspecialchars(json_encode($items)); ?>">

          <button class="btn btn-primary" style="margin-top:12px;">Checkout</button>
        </form>

        <p id="msg" style="margin-top:12px;"></p>
      </div>
    <?php endif; ?>
  </div>
</main>

<script>
document.getElementById("checkoutForm")?.addEventListener("submit", async e => {
  e.preventDefault();

  let fd = new FormData(e.target);

  const res = await fetch("actions/checkout_action.php", {
    method: "POST",
    body: fd
  });

  const data = await res.json();
  const msg = document.getElementById("msg");

  if (data.status === "success") {
    msg.style.color = "green";
    msg.textContent = "Order placed successfully!";
  } else {
    msg.style.color = "red";
    msg.textContent = data.message;
  }
});
</script>

</body>
</html>
