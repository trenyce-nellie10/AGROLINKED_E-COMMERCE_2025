<?php 
if (!function_exists('current_user')) {
  require_once 'core.php';
}
$u=current_user(); 

// Determine base path based on current directory depth
$current_dir = dirname($_SERVER['PHP_SELF']);
$base_path = '';
if (strpos($current_dir, '/view') !== false || strpos($current_dir, '/login') !== false || strpos($current_dir, '/admin') !== false) {
  $base_path = '../';
}
?>
<div class="nav">
  <div class="brand"><a href="<?php echo $base_path; ?>index.php" style="text-decoration:none;color:inherit;">AgroLinked</a></div>
  <div class="nav-actions">
    <a class="btn btn-link" href="<?php echo $base_path; ?>view/all_products.php">All Products</a>
    <a class="btn btn-link" href="<?php echo $base_path; ?>view/all_products.php?filter=hub">By Hub</a>
    <?php if (is_logged_in()): ?>
      <?php if ($u['role']==1): ?>
        <a class="btn btn-link" href="<?php echo $base_path; ?>admin/dashboard.php">Admin</a>
      <?php endif; ?>
      <?php if ($u['role']==2): ?>
        <a class="btn btn-link" href="<?php echo $base_path; ?>vendor/dashboard.php">Vendor</a>
      <?php endif; ?>
      <span class="greet">Hi, <?php echo htmlspecialchars($u['name']); ?></span>
      <a class="btn btn-danger" href="<?php echo $base_path; ?>login/logout.php">Logout</a>
    <?php else: ?>
      <a class="btn btn-secondary" href="<?php echo $base_path; ?>login/register.php">Register</a>
      <a class="btn btn-primary" href="<?php echo $base_path; ?>login/login.php">Login</a>
    <?php endif; ?>
  </div>
</div>
