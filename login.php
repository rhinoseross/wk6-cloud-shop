<?php
session_start();
if (empty($_SESSION['csrf_token'])) { $_SESSION['csrf_token'] = bin2hex(random_bytes(24)); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - MyShop</title>
  <style>
    body { font-family: Arial, sans-serif; background-color: #f4f4f4; display:flex;align-items:center;justify-content:center; height:100vh; margin:0 }
    .login-form { background:#fff; padding:24px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.08); width:100%; max-width:360px }
    .form-group { margin-bottom:12px }
    label { display:block; margin-bottom:6px; font-weight:600 }
    input[type="email"], input[type="password"] { width:100%; padding:10px; border:1px solid #ddd; border-radius:4px }
    .btn { background:#007bff; color:#fff; padding:10px 16px; border:none; border-radius:4px; cursor:pointer; width:100% }
    .error { background:#f8d7da; color:#721c24; padding:10px; border-radius:4px; margin-bottom:12px }
  </style>
</head>
<body>
  <div class="login-form">
    <h2>Login</h2>
    <?php if (isset($_GET['error'])): ?>
      <div class="error"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <form action="process_login.php" method="post">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div class="form-group">
        <button type="submit" class="btn">Login</button>
      </div>
    </form>

    <p style="text-align:center"><a href="register.php">Create an account</a> · <a href="index.php">Home</a></p>
  </div>
</body>
</html>
