<?php
require 'vendor/autoload.php';
use Aws\SecretsManager\SecretsManagerClient;
use Aws\Exception\AwsException;

$region = 'eu-north-1';
$secretName = 'arn:aws:secretsmanager:eu-north-1:211125702898:secret:rds!db-2a0cbf8d-3399-4dbd-ad96-433d927b272a-8YUHqV';
$rdsEndpoint = 'rds.c3as6c0gw9zt.eu-north-1.rds.amazonaws.com';
$dbname = 'mydatabase';

try {
    $client = new SecretsManagerClient([
        'version' => 'latest',
        'region'  => $region
    ]);
    $result = $client->getSecretValue(['SecretId' => $secretName]);
    $secret = json_decode($result['SecretString'], true);
    $username = $secret['username'];
    $password = $secret['password'];

    $conn = new mysqli($rdsEndpoint, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("<h3 style='color:red;'>Database connection failed: " . $conn->connect_error . "</h3>");
    }

    $sql = "SELECT * FROM employees";
    $result = $conn->query($sql);

} catch (AwsException $e) {
    die("<h3 style='color:red;'>AWS Error: " . $e->getMessage() . "</h3>");
}
?>
<!DOCTYPE html>
<html>
<head><title>Backend API</title></head>
<body>
<h3>Employee Records from RDS</h3>
<table border="1" cellpadding="6">
<tr><th>ID</th><th>Name</th><th>Age</th><th>Profession</th></tr>
<?php
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['age']}</td><td>{$row['profession']}</td></tr>";
    }
} else {
    echo "<tr><td colspan='4'>No records found</td></tr>";
}
$conn->close();
?>
</table>
</body>
</html>
