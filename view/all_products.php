<?php require_once '../core.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Products - AgroLinked</title>
  <link rel="stylesheet" href="../css/style.css">
  <script>
    async function load(){
      const res = await fetch('../actions/product_action.php?action=list');
      const prods = await res.json();
      const grid = document.getElementById('grid');
      grid.innerHTML = prods.map(p=>`
        <div class="card product-card">
          <img class="product-img" src="${p.image_path ? '../' + p.image_path : '../assets/images/default.png'}">
          <h3>${p.title}</h3>
          <p class="muted">${p.vendor_name} • ${p.cat_name}</p>
          <p class="price">₵ ${parseFloat(p.unit_price).toFixed(2)} / ${p.quantity || ''}</p>
          <a class="btn btn-primary" href="single_product.php?id=${p.product_id}">View</a>
        </div>
      `).join('');
    }
    document.addEventListener('DOMContentLoaded', load);
  </script>
</head>
<body class="page bg-image">
  <?php include '../nav.php'; ?>
  <main class="center-wrap">
    <section style="max-width:1100px;width:100%">
      <h2>Products</h2>
      <div id="grid" style="display:flex;flex-wrap:wrap;gap:12px;"></div>
    </section>
  </main>
</body>
</html>
