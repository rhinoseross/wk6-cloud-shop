<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MyShop - Welcome</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .header {
      position: relative;
      background-color: #2c3e50;
      color: white;
      padding: 20px 0;
      text-align: center;
      margin-bottom: 30px;
    }
    .header h1 {
      margin: 0;
      font-size: 2.5em;
    }
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }
    .welcome-text {
      text-align: center;
      margin-bottom: 30px;
      font-size: 1.2em;
      color: #555;
    }
    .button-container {
      margin-top: 20px;
    }
    .btn {
      display: inline-block;
      color: white;
      padding: 12px 30px;
      text-decoration: none;
      border-radius: 4px;
      font-size: 18px;
      margin: 0 10px;
    }
    .btn-primary {
      background-color: #007bff;
    }
    .btn-primary:hover {
      background-color: #0056b3;
    }
    .btn-secondary {
      background-color: #6c757d;
    }
    .btn-secondary:hover {
      background-color: #545b62;
    }
    .user-info {
      position: absolute;
      right: 20px;
      top: 18px;
      color: #fff;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <div class="header">
    <div class="container">
      <h1>MyShop</h1>
      <p>Your one-stop destination for amazing products</p>
      <?php if (isset($_SESSION['user']) && !empty($_SESSION['user']['name'])): ?>
        <div class="user-info">Hello, <?php echo htmlspecialchars($_SESSION['user']['name']); ?><?php echo (!empty($_SESSION['user']['is_admin']) ? ' (Admin)' : ''); ?></div>
      <?php endif; ?>
    </div>
  </div>

  <div class="container">
    <div class="welcome-text">
      <h2>Welcome to MyShop</h2>
      <p>Join our community today to access exclusive deals and features!</p>

      <div class="button-container">
        <a href="register.php" class="btn btn-primary">Create Your Account</a>
        <a href="randomusers.php" class="btn btn-primary">Create Users</a>
        <?php if (isset($_SESSION['user'])): ?>
          <?php if (!empty($_SESSION['user']['is_admin'])): ?>
            <a href="admin.php" class="btn btn-primary">Admin</a>
          <?php endif; ?>
          <a href="logout.php" class="btn btn-secondary">Logout</a>
        <?php else: ?>
          <a href="login.php" class="btn btn-primary">Login</a>
        <?php endif; ?>
        <a href="./view_users.php" class="btn btn-secondary">View Users</a>
      </div>
    </div>
  </div>
</body>
</html>