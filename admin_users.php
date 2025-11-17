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
if (!$conn) {
  die('DB connection failed: ' . print_r(sqlsrv_errors(), true));
}

$sql = "SELECT id, name, email, is_admin FROM shopusers ORDER BY id";
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) { die('Query failed: ' . print_r(sqlsrv_errors(), true)); }

if (empty($_SESSION['csrf_token'])) { $_SESSION['csrf_token'] = bin2hex(random_bytes(24)); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin - Manage Users</title>
  <style>
    body{font-family:Arial,Helvetica,sans-serif;padding:20px}
    table{border-collapse:collapse;width:100%}
    th,td{padding:8px;border:1px solid #ddd;text-align:left}
    .btn{padding:6px 10px;border-radius:4px;border:none;cursor:pointer}
    .btn-admin{background:#28a745;color:#fff}
    .btn-remove{background:#dc3545;color:#fff}
  </style>
</head>
<body>
  <h1>Manage Users</h1>
  <p>Signed in as <?php echo htmlspecialchars($_SESSION['user']['name']); ?> — <a href="admin.php">Back</a></p>
  <table>
    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Admin</th><th>Action</th></tr></thead>
    <tbody>
    <?php while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
      <tr>
        <td><?php echo htmlspecialchars($row['id']); ?></td>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        <td><?php echo !empty($row['is_admin']) ? 'Yes' : 'No'; ?></td>
        <td>
          <form action="process_admin.php" method="post" style="display:inline">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($row['id']); ?>">
            <?php if (empty($row['is_admin'])): ?>
              <input type="hidden" name="action" value="grant">
              <button class="btn btn-admin" type="submit">Make Admin</button>
            <?php else: ?>
              <input type="hidden" name="action" value="revoke">
              <button class="btn btn-remove" type="submit">Revoke Admin</button>
            <?php endif; ?>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
