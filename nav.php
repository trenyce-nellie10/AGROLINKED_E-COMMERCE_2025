<?php require_once 'core.php'; $u=current_user(); ?>
<div class="nav">
  <div class="brand">AgroLinked</div>
  <div class="nav-actions">
    <a class="btn btn-link" href="../view/all_products.php">All Products</a>
    <a class="btn btn-link" href="../view/all_products.php?filter=hub">By Hub</a>
    <?php if (is_logged_in()): ?>
      <?php if ($u['role']==1): ?>
        <a class="btn btn-link" href="../admin/dashboard.php">Admin</a>
      <?php endif; ?>
      <?php if ($u['role']==2): ?>
        <a class="btn btn-link" href="../vendor/dashboard.php">Vendor</a>
      <?php endif; ?>
      <span class="greet">Hi, <?php echo htmlspecialchars($u['name']); ?></span>
      <a class="btn btn-danger" href="../login/logout.php">Logout</a>
    <?php else: ?>
      <a class="btn btn-secondary" href="../login/register.php">Register</a>
      <a class="btn btn-primary" href="../login/login.php">Login</a>
    <?php endif; ?>
  </div>
</div>
