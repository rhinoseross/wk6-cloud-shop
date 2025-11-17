<?php
session_start();
// require admin
if (!isset($_SESSION['user']) || empty($_SESSION['user']['email']) || empty($_SESSION['user']['is_admin'])) {
  header('Location: index.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: admin_dashboard.php'); exit(); }

// CSRF
if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
  header('Location: admin_dashboard.php?error=' . urlencode('Invalid CSRF token'));
  exit();
}

$action = isset($_POST['action']) ? $_POST['action'] : '';
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($action === 'delete') {
  if ($id <= 0) { header('Location: admin_dashboard.php'); exit(); }
  $serverName = "tcp:wk6-sql-server.database.windows.net,1433";
  $connectionOptions = array("Database"=>"myDatabase","Uid"=>"myadmin","PWD"=>"1Qaz2wsx!","Encrypt"=>1,"TrustServerCertificate"=>0);
  $conn = sqlsrv_connect($serverName, $connectionOptions);
  if (!$conn) { header('Location: admin_dashboard.php?error=' . urlencode('DB connect failed')); exit(); }
  $sql = "DELETE FROM stock WHERE id = ?";
  $stmt = sqlsrv_query($conn, $sql, array($id));
  if ($stmt === false) { header('Location: admin_dashboard.php?error=' . urlencode('Delete failed')); exit(); }
  sqlsrv_free_stmt($stmt); sqlsrv_close($conn);
  header('Location: admin_dashboard.php'); exit();
}

if ($action === 'edit') {
  $name = isset($_POST['name']) ? trim($_POST['name']) : '';
  $description = isset($_POST['description']) ? trim($_POST['description']) : '';
  $price = isset($_POST['price']) ? $_POST['price'] : '';
  $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';
  if ($id <= 0 || $name === '' || $price === '' || $quantity === '') { header('Location: admin_dashboard.php?error=' . urlencode('Missing fields')); exit(); }
  if (strlen($name) > 255) { header('Location: admin_dashboard.php?error=' . urlencode('Name too long')); exit(); }
  if (!is_numeric($price) || floatval($price) <= 0) { header('Location: admin_dashboard.php?error=' . urlencode('Invalid price')); exit(); }
  if (!is_numeric($quantity) || intval($quantity) < 0) { header('Location: admin_dashboard.php?error=' . urlencode('Invalid quantity')); exit(); }

  $serverName = "tcp:wk6-sql-server.database.windows.net,1433";
  $connectionOptions = array("Database"=>"myDatabase","Uid"=>"myadmin","PWD"=>"1Qaz2wsx!","Encrypt"=>1,"TrustServerCertificate"=>0);
  $conn = sqlsrv_connect($serverName, $connectionOptions);
  if (!$conn) { header('Location: admin_dashboard.php?error=' . urlencode('DB connect failed')); exit(); }

  $sql = "UPDATE stock SET [name] = ?, description = ?, price = ?, quantity = ? WHERE id = ?";
  $params = array($name, $description, floatval($price), intval($quantity), $id);
  $stmt = sqlsrv_query($conn, $sql, $params);
  if ($stmt === false) { header('Location: admin_dashboard.php?error=' . urlencode('Update failed')); exit(); }
  sqlsrv_free_stmt($stmt); sqlsrv_close($conn);
  header('Location: admin_dashboard.php'); exit();
}

header('Location: admin_dashboard.php'); exit();
