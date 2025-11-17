<?php
session_start();
// require admin
if (!isset($_SESSION['user']) || empty($_SESSION['user']['email']) || empty($_SESSION['user']['is_admin'])) {
  header('Location: index.php');
  exit();
}

$serverName = "tcp:wk6-sql-server.database.windows.net,1433";
$connectionOptions = array(
  "Database" => "myDatabase",
  "Uid" => "myadmin",
  "PWD" => "1Qaz2wsx!",
  "Encrypt" => 1,
  "TrustServerCertificate" => 0
);

$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) { die('DB connection failed: ' . print_r(sqlsrv_errors(), true)); }

$sql = "SELECT id, [name], description, price, quantity, created_at FROM stock ORDER BY id DESC";
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) { die('Query failed: ' . print_r(sqlsrv_errors(), true)); }

if (empty($_SESSION['csrf_token'])) { $_SESSION['csrf_token'] = bin2hex(random_bytes(24)); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Dashboard - Stock</title>
  <style>body{font-family:Arial,Helvetica,sans-serif;padding:20px}table{width:100%;border-collapse:collapse}th,td{padding:8px;border:1px solid #ddd}a.btn{display:inline-block;padding:6px 10px;border-radius:4px;text-decoration:none} .btn-edit{background:#007bff;color:#fff} .btn-delete{background:#dc3545;color:#fff} .topbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}</style>
</head>
<body>
  <div class="topbar">
    <h1>Stock Items</h1>
    <div>
      <a href="admin.php">Add Stock</a> | <a href="admin_users.php">Manage Users</a> | <a href="index.php">Home</a>
    </div>
  </div>

  <table>
    <thead>
      <tr><th>ID</th><th>Name</th><th>Description</th><th>Price</th><th>Quantity</th><th>Added</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
      <tr>
        <td><?php echo htmlspecialchars($row['id']); ?></td>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo htmlspecialchars($row['description']); ?></td>
        <td><?php echo htmlspecialchars($row['price']); ?></td>
        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
        <td><?php echo isset($row['created_at']) ? htmlspecialchars($row['created_at']) : ''; ?></td>
        <td>
          <a class="btn-edit btn" href="admin_edit_stock.php?id=<?php echo urlencode($row['id']); ?>">Edit</a>
          <form style="display:inline" method="post" action="process_stock_edit.php" onsubmit="return confirm('Delete this item?');">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
            <button type="submit" class="btn-delete btn">Delete</button>
          </form>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <?php sqlsrv_free_stmt($stmt); sqlsrv_close($conn); ?>
</body>
</html>
