<?php
/*
Original view_users.php content (commented out by request).

<?php
// Database connection
$serverName = "tcp:wk6-sql-server.database.windows.net,1433";

$connectionOptions = array(
    "Database" => "myDatabase",
    "Uid" => "myadmin",
    "PWD" => "1Qaz2wsx!",
    "Encrypt" => 1,
    "TrustServerCertificate" => 0
);

// Create connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Check connection
if (!$conn) {
    die("Connection failed: " . print_r(sqlsrv_errors(), true));
}

// Fetch users from the database
$sql = "SELECT * FROM shopusers";
$result = sqlsrv_query($conn, $sql);

if ($result === false) {
    die("Error in query: " . print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users - MyShop</title>
    <style>
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
            text-align: center;
            margin-bottom: 30px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .users-table th, .users-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .users-table th {
            background-color: #f5f5f5;
        }
        .users-table tr:hover {
            background-color: #f9f9f9;
        }
        .back-button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>MyShop - Registered Users</h1>
        </div>
    </div>
    
    <div class="container">
        <a href="index.php" class="back-button">Back to Home</a>
        
        <table class="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $hasRows = false;
                while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)): 
                    $hasRows = true;
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                    </tr>
                <?php endwhile; ?>
                
                <?php if (!$hasRows): ?>
                    <tr>
                        <td colspan="3">No users found in the database.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
sqlsrv_close($conn);
?>

*/

// Minimal test page served while view_users.php is disabled
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>View Users - Test</title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; padding: 40px; color: #333; }
    .card { max-width: 800px; margin: 0 auto; border: 1px solid #e0e0e0; padding: 24px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
    a { color: #007bff; text-decoration: none; }
  </style>
</head>
<body>
  <div class="card">
    <h1>View Users — Test Page</h1>
    <p>The original <code>view_users.php</code> file has been temporarily disabled and preserved inside this file as a comment.</p>
    <p>This is a basic test page to confirm the route is reachable. If you want the original functionality re-enabled, I can restore it or move it to a backup file.</p>
    <p><a href="index.php">Back to Home</a></p>
  </div>
</body>
</html>
