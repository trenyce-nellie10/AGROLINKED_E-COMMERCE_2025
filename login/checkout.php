<?php require_once '../core.php'; require_login(); ?>
<!doctype html><html><head><meta charset="utf-8"><title>Checkout - AgroLinked</title><link rel="stylesheet" href="../css/style.css"></head>
<body class="page bg-image"><?php include '../nav.php'; ?>
<main class="center-wrap">
  <div class="card" style="max-width:700px;">
    <h2>Checkout</h2>
    <form id="checkoutForm">
      <div class="input-group"><label>Delivery Hub</label>
        <select name="hub_id" required>
          <?php $db=(new DB())->connect(); $hubs = $db->query("SELECT * FROM hubs ORDER BY hub_name")->fetchAll(); foreach($hubs as $h) echo "<option value=\"{$h['hub_id']}\">".htmlspecialchars($h['hub_name'].' — '.$h['region'])."</option>"; ?>
        </select>
      </div>
      <div class="input-group"><label>Mobile Money Number</label><input name="mobile" required></div>
      <div class="input-group"><label>Delivery Fee (GHS)</label><input name="delivery_fee" type="number" step="0.01" value="10.00" required></div>
      <button class="btn btn-primary btn-full" type="submit">Place Order</button>
      <div id="msg" class="form-message"></div>
    </form>
  </div>
</main>
<script>
document.getElementById('checkoutForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const cart = JSON.parse(localStorage.getItem('agro_cart')||'[]');
  if (cart.length===0){ document.getElementById('msg').textContent='Cart empty'; return; }
  const fd = new FormData(e.target);
  fd.append('cart', JSON.stringify(cart));
  fd.append('action','checkout');
  const res = await fetch('../actions/order_action.php',{method:'POST',body:fd});
  const j = await res.json();
  document.getElementById('msg').textContent = j.message || 'Error';
  if (j.status === 'success'){ localStorage.removeItem('agro_cart'); setTimeout(()=>location.href='../index.php',900); }
});
</script>
</body></html>
