<?php
session_start();

// Azure SQL connection settings (match process_register.php)
$serverName = "tcp:wk8-database-replica.database.windows.net,1433";

$connectionOptions = array(
  "Database" => "myDatabase",
  "Uid" => "myadmin",
  "PWD" => "1Qaz2wsx!",
  "Encrypt" => 1,
  "TrustServerCertificate" => 0
);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: login.php');
  exit();
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($email) || empty($password)) {
  header('Location: login.php?error=' . urlencode('Please provide email and password'));
  exit();
}

$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) {
  $errors = sqlsrv_errors();
  $msg = 'Database connection failed';
  if ($errors) {
    foreach ($errors as $e) { $msg .= ': ' . $e['message']; }
  }
  header('Location: login.php?error=' . urlencode($msg));
  exit();
}

$sql = "SELECT id, name, email, password, is_admin FROM shopusers WHERE email = ?";
$params = array($email);
$stmt = sqlsrv_query($conn, $sql, $params);
if ($stmt === false) {
  $err = sqlsrv_errors();
  $msg = 'Query error';
  if ($err) { foreach ($err as $e) { $msg .= ': ' . $e['message']; } }
  header('Location: login.php?error=' . urlencode($msg));
  exit();
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
if (!$row) {
  header('Location: login.php?error=' . urlencode('Invalid email or password'));
  exit();
}

$hashed = isset($row['password']) ? $row['password'] : '';
if (!password_verify($password, $hashed)) {
  header('Location: login.php?error=' . urlencode('Invalid email or password'));
  exit();
}

// Successful login: store minimal session info
$_SESSION['user'] = array(
  'id' => $row['id'],
  'name' => $row['name'],
  'email' => $row['email']
);
// store admin flag (if column exists)
$_SESSION['user']['is_admin'] = isset($row['is_admin']) ? (int)$row['is_admin'] : 0;

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

header('Location: index.php');
exit();
