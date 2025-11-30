<?php require_once '../core.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Cart - AgroLinked</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body class="page bg-image">
  <?php include '../nav.php'; ?>
  <main class="center-wrap">
    <div class="card" style="max-width:900px;">
      <h2>Your Cart</h2>
      <div id="cartList"></div>
      <div id="total" style="margin-top:12px;font-weight:700"></div>
      <a class="btn btn-primary" href="../login/checkout.php">Checkout</a>
      <a class="btn btn-link" href="all_products.php" style="margin-left:10px;">Continue Shopping</a>
    </div>
  </main>
  <script>
    function render(){
      const cart=JSON.parse(localStorage.getItem('agro_cart')||'[]');
      const el=document.getElementById('cartList');
      if(cart.length===0){ 
        el.innerHTML='<p>Cart empty</p>'; 
        document.getElementById('total').textContent=''; 
        return; 
      }
      el.innerHTML = cart.map((c,i)=>`
        <div style="display:flex;justify-content:space-between;padding:8px;border-bottom:1px solid #eee;">
          <div>${c.title} x ${c.qty}</div>
          <div>₵ ${(c.price*c.qty).toFixed(2)}</div>
        </div>
      `).join('');
      const total = cart.reduce((s,c)=>s + c.qty*c.price,0);
      document.getElementById('total').textContent = `Total: ₵ ${total.toFixed(2)}`;
    }
    render();
  </script>
</body>
</html>
