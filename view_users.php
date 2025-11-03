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
$sql = "SELECT id, username, email FROM users";
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