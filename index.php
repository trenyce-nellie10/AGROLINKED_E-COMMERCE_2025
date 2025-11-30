<?php require_once 'core.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>AgroLinked</title>
  <link rel="stylesheet" href="..css/style.css">
</head>
<body class="page bg-image">
  <?php include 'nav.php'; ?>
  <main class="center-wrap">
    <section class="hero card glass">
      <h1 class="hero-title">AgroLinked — Farm-to-Table Marketplace</h1>
      <p class="hero-text">Buy fresh produce directly from farmers. Filter by category, grade and hub.</p>
      <div class="hero-actions">
        <a class="btn btn-primary" href="..view/all_products.php">Browse Products</a>
        <?php if (!is_logged_in()): ?><a class="btn btn-link" href="../login/register.php">Sign Up</a><?php endif; ?>
      </div>
    </section>
  </main>
</body>
</html>
