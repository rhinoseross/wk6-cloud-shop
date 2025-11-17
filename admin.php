<?php
session_start();

// Require login
if (!isset($_SESSION['user']) || empty($_SESSION['user']['email'])) {
  header('Location: login.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin - Add Stock</title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; background:#f6f7fb; margin:0; padding:20px }
    .container { max-width:900px; margin:0 auto }
    .header { display:flex; justify-content:space-between; align-items:center; background:#2c3e50; color:#fff; padding:12px 20px; border-radius:6px }
    .card { background:#fff; padding:20px; margin-top:20px; border-radius:6px; box-shadow:0 2px 8px rgba(0,0,0,0.06) }
    label { display:block; margin:8px 0 4px }
    input[type=text], input[type=number], textarea { width:100%; padding:8px; border:1px solid #ddd; border-radius:4px }
    .btn { background:#007bff; color:#fff; padding:10px 14px; border:none; border-radius:4px; cursor:pointer }
    .success { background:#d4edda; color:#155724; padding:10px; border-radius:4px; margin-bottom:12px }
    .error { background:#f8d7da; color:#721c24; padding:10px; border-radius:4px; margin-bottom:12px }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div>
        <strong>Admin</strong> — Add Stock
      </div>
      <div style="color:#fff">Signed in as <?php echo htmlspecialchars($_SESSION['user']['name'] ?? $_SESSION['user']['email']); ?></div>
    </div>

    <div class="card">
      <h2>Add New Stock Item</h2>

      <?php if (isset($_GET['success'])): ?>
        <div class="success">Stock item added successfully.</div>
      <?php endif; ?>

      <?php if (isset($_GET['error'])): ?>
        <div class="error"><?php echo htmlspecialchars($_GET['error']); ?></div>
      <?php endif; ?>

      <form action="process_stock.php" method="post">
        <label for="name">Product Name</label>
        <input type="text" id="name" name="name" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4"></textarea>

        <label for="price">Price (decimal)</label>
        <input type="number" step="0.01" id="price" name="price" required>

        <label for="quantity">Quantity</label>
        <input type="number" id="quantity" name="quantity" required>

        <div style="margin-top:12px">
          <button type="submit" class="btn">Add Stock</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
