<?php require_once 'core.php'; if (is_logged_in()) header("Location: index.php"); ?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Register - AgroLinked</title><link rel="stylesheet" href="css/style.css"><script defer src="js/register.js"></script></head>
<body class="page page-auth bg-image">
  <div class="nav"><div class="brand">AgroLinked</div><div class="nav-actions"><a href="index.php" class="btn btn-link">Home</a><a href="login.php" class="btn btn-primary">Login</a></div></div>
  <main class="center-wrap">
    <form id="registerForm" class="card">
      <h2 class="card-title">Create account</h2>
      <div class="input-group"><label>Full name</label><input name="full_name" required></div>
      <div class="input-group"><label>Email</label><input type="email" name="email" required></div>
      <div class="input-group"><label>Password</label><input type="password" name="password" required></div>
      <div class="input-group"><label>Phone</label><input name="phone"></div>
      <div class="input-group"><label>Sign up as</label>
        <select name="role"><option value="3">Buyer</option><option value="2">Vendor</option></select>
      </div>
      <button class="btn btn-primary btn-full" type="submit">Register</button>
      <div id="message" class="form-message"></div>
    </form>
  </main>
</body></html>
