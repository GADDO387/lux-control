document.addEventListener('DOMContentLoaded', function() {
    // Function to fetch and display sensor data
    function fetchSensorData() {
        fetch('temperature_humidity.php')
            .then(response => response.json())
            .then(data => {
                const table = document.getElementById('dataTable');
                if (table) {
                    // Clear existing table rows except the header
                    const rowCount = table.rows.length;
                    for (let i = rowCount - 1; i > 0; i--) {
                        table.deleteRow(i);
                    }
                    
                    data.forEach(item => {
                        const row = table.insertRow();
                        const idCell = row.insertCell(0);
                        const tempCell = row.insertCell(1);
                        const humidityCell = row.insertCell(2);
                        const datetimeCell = row.insertCell(3);
                        
                        idCell.textContent = item.id;
                        tempCell.textContent = item.temperature;
                        humidityCell.textContent = item.humidity;
                        datetimeCell.textContent = item.datetime;
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching sensor data:', error);
            });
    }

    // Call the fetchSensorData function if the dataTable exists
    if (document.getElementById('dataTable')) {
        fetchSensorData();
        // Set up interval to fetch sensor data every 5 seconds
        setInterval(fetchSensorData, 5000);
    }

    // Light Control form submission
    const luxForm = document.getElementById('luxForm');
    if (luxForm) {
        luxForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const lux = document.getElementById('lux').value;
            
            fetch('light_control.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `lux=${lux}`
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('response').textContent = data.message;
            })
            .catch(error => {
                document.getElementById('response').textContent = 'Error: ' + error.message;
            });
        });
    }
});

