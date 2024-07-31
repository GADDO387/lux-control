<?php
$servername = "172.20.10.4";
$username = "Gaddo";
$password = "12345";
$dbname = "sensor_data";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, temperature, humidity, datetime FROM dht11 ORDER BY datetime DESC LIMIT 10";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} 

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temperature & Humidity</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Temperature & Humidity Readings</h1>
        <nav>
            <ul>
                <li><a href="dashboard.html">Dashboard</a></li>
                <li><a href="light_control.html">Light Control</a></li>
                <li><a href="temperature_humidity.php">Temperature & Humidity</a></li>
                <li><a href="live_video.html">Live Video</a></li>
                <li><a href="pump_operation.html">Pump Operation</a></li>
            </ul>
        </nav>
        <h2>Recent Sensor Data</h2>
        <div id="sensorData">
            <table id="dataTable">
                <tr>
                    <th>ID</th>
                    <th>Temperature</th>
                    <th>Humidity</th>
                    <th>Datetime</th>
                </tr>
                <?php foreach ($data as $row): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['temperature']; ?></td>
                    <td><?php echo $row['humidity']; ?></td>
                    <td><?php echo $row['datetime']; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>
</html>
