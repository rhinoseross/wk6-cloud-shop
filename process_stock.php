<?php
session_start();

// require login and admin
if (!isset($_SESSION['user']) || empty($_SESSION['user']['email'])) {
  header('Location: login.php');
  exit();
}
if (empty($_SESSION['user']['is_admin'])) {
  header('Location: index.php');
  exit();
}

// connection settings (same as register/process_register)
$serverName = "tcp:wk6-sql-server.database.windows.net,1433";

$connectionOptions = array(
  "Database" => "myDatabase",
  "Uid" => "myadmin",
  "PWD" => "1Qaz2wsx!",
  "Encrypt" => 1,
  "TrustServerCertificate" => 0
);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: admin.php');
  exit();
}

// Collect and validate inputs
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$description = isset($_POST['description']) ? trim($_POST['description']) : '';
$price = isset($_POST['price']) ? $_POST['price'] : '';
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';

if ($name === '' || $price === '' || $quantity === '') {
  header('Location: admin.php?error=' . urlencode('Please fill required fields'));
  exit();
}

if (strlen($name) > 255) {
  header('Location: admin.php?error=' . urlencode('Product name too long'));
  exit();
}

if (!is_numeric($price) || floatval($price) <= 0) {
  header('Location: admin.php?error=' . urlencode('Price must be a positive number'));
  exit();
}

if (!is_numeric($quantity) || intval($quantity) < 0) {
  header('Location: admin.php?error=' . urlencode('Quantity must be a non-negative integer'));
  exit();
}

$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) {
  $errors = sqlsrv_errors();
  $msg = 'Database connection failed';
  if ($errors) { foreach ($errors as $e) { $msg .= ': ' . $e['message']; } }
  header('Location: admin.php?error=' . urlencode($msg));
  exit();
}

// Insert into stock table — expects table `stock` with columns (name, description, price, quantity)
$sql = "INSERT INTO stock ([name], description, price, quantity) VALUES (?, ?, ?, ?);";
$params = array($name, $description, floatval($price), intval($quantity));
$stmt = sqlsrv_query($conn, $sql, $params);
if ($stmt === false) {
  $err = sqlsrv_errors();
  $msg = 'Insert failed';
  if ($err) { foreach ($err as $e) { $msg .= ': ' . $e['message']; } }
  header('Location: admin.php?error=' . urlencode($msg));
  exit();
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

header('Location: admin.php?success=1');
exit();
