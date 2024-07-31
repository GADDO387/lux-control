<?php
header('Content-Type: application/json');

$servername = "172.20.10.4";
$username = "Gaddo";
$password = "12345";
$dbname = "sensor_data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

$sql = "SELECT id, temperature, humidity, datetime FROM DHT11 ORDER BY datetime DESC LIMIT 10";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
$conn->close();
?>
