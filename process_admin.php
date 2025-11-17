<?php
session_start();
// require admin
if (!isset($_SESSION['user']) || empty($_SESSION['user']['email']) || empty($_SESSION['user']['is_admin'])) {
  header('Location: index.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: admin_users.php');
  exit();
}

// CSRF check
if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
  header('Location: admin_users.php?error=' . urlencode('Invalid CSRF token'));
  exit();
}

$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';
if ($user_id <= 0 || ($action !== 'grant' && $action !== 'revoke')) {
  header('Location: admin_users.php?error=' . urlencode('Invalid request'));
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
if (!$conn) { header('Location: admin_users.php?error=' . urlencode('DB connect failed')); exit(); }

$is_admin_val = ($action === 'grant') ? 1 : 0;
$sql = "UPDATE shopusers SET is_admin = ? WHERE id = ?";
$params = array($is_admin_val, $user_id);
$stmt = sqlsrv_query($conn, $sql, $params);
if ($stmt === false) {
  header('Location: admin_users.php?error=' . urlencode('Update failed'));
  exit();
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
header('Location: admin_users.php');
exit();
