<?php
session_start();
// require admin
if (!isset($_SESSION['user']) || empty($_SESSION['user']['email']) || empty($_SESSION['user']['is_admin'])) {
  header('Location: index.php');
  exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) { header('Location: admin_dashboard.php'); exit(); }

$serverName = "tcp:wk6-sql-server.database.windows.net,1433";
$connectionOptions = array(
  "Database" => "myDatabase",
  "Uid" => "myadmin",
  "PWD" => "1Qaz2wsx!",
  "Encrypt" => 1,
  "TrustServerCertificate" => 0
);

$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) { die('DB connect failed'); }

$sql = "SELECT id, [name], description, price, quantity FROM stock WHERE id = ?";
$stmt = sqlsrv_query($conn, $sql, array($id));
if ($stmt === false) { die('Query failed'); }
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
if (!$row) { header('Location: admin_dashboard.php'); exit(); }

if (empty($_SESSION['csrf_token'])) { $_SESSION['csrf_token'] = bin2hex(random_bytes(24)); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Edit Stock</title>
  <style>body{font-family:Arial;padding:20px}.card{background:#fff;padding:20px;border-radius:6px;max-width:600px}</style>
</head>
<body>
  <div class="card">
    <h2>Edit Stock Item</h2>
    <form method="post" action="process_stock_edit.php">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
      <label>Product Name</label>
      <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
      <label>Description</label>
      <textarea name="description"><?php echo htmlspecialchars($row['description']); ?></textarea>
      <label>Price</label>
      <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($row['price']); ?>" required>
      <label>Quantity</label>
      <input type="number" name="quantity" value="<?php echo htmlspecialchars($row['quantity']); ?>" required>
      <div style="margin-top:12px"><button type="submit">Save Changes</button> <a href="admin_dashboard.php">Cancel</a></div>
    </form>
  </div>
</body>
</html>
