<?php require_once 'core.php'; if (is_logged_in()) header("Location: index.php"); ?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Login - AgroLinked</title><link rel="stylesheet" href="css/style.css"><script defer src="js/login.js"></script></head>
<body class="page page-auth bg-image">
  <div class="nav"><div class="brand">AgroLinked</div><div class="nav-actions"><a href="index.php" class="btn btn-link">Home</a><a href="register.php" class="btn btn-secondary">Register</a></div></div>
  <main class="center-wrap">
    <form id="loginForm" class="card">
      <h2 class="card-title">Sign in</h2>
      <div class="input-group"><label>Email</label><input name="email" type="email" required></div>
      <div class="input-group"><label>Password</label><input name="password" type="password" required></div>
      <button class="btn btn-primary btn-full">Login</button>
      <div id="message" class="form-message"></div>
    </form>
  </main>
</body></html>
