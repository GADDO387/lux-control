document.getElementById('luxForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const lux = document.getElementById('lux').value;

    fetch('index.php', {
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
