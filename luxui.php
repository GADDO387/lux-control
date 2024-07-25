<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Light Control and Sensor Data</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Light Lux Control</h1>
        <form id="luxForm">
            <label for="lux">Enter desired lux:</label>
            <input type="number" id="lux" name="lux" required>
            <button type="submit">Submit</button>
        </form>
        <p id="response"></p>
        
        <h2>Recent Sensor Data</h2>
        <div id="sensorData">
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

                $sql = "SELECT id, temperature, humidity, datetime FROM dht11_data ORDER BY datetime DESC LIMIT 10"; // Make sure to use your actual table name here
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<table><tr><th>ID</th><th>Temperature</th><th>Humidity</th><th>Datetime</th></tr>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td>".$row["id"]."</td><td>".$row["temperature"]."</td><td>".$row["humidity"]."</td><td>".$row["datetime"]."</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "0 results";
                }

                $conn->close();
            ?>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>
</html>

<?php
$servername = "172.20.10.4";
$username = "Gaddo";
$password = "12345";
$dbname = "sensor_data";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lux = $_POST['lux'];

    // Replace '/dev/ttyUSB0' with your Arduino Nano's port
    $port = '/dev/ttyUSB0';

    if ($lux !== null && is_numeric($lux)) {
        // Send data to Arduino Nano
        $handle = fopen($port, 'w');
        if ($handle) {
            fwrite($handle, $lux . "\n");
            fclose($handle);

            // Store lux value in the database
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                echo json_encode(['message' => 'Error: Database connection failed.']);
                exit();
            }

            $sql = "INSERT INTO luxvalues (setpoints, datetime) VALUES ($lux, NOW())";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(['message' => 'Lux value sent and stored successfully!']);
            } else {
                echo json_encode(['message' => 'Error: ' . $conn->error]);
            }

            $conn->close();
        } else {
            echo json_encode(['message' => 'Error: Unable to open port.']);
        }
    } else {
        echo json_encode(['message' => 'Error: Invalid lux value.']);
    }
} else {
    echo json_encode(['message' => 'Error: Invalid request method.']);
}
?>
