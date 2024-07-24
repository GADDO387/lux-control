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
