<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Weather Fetcher</title>
</head>
<body>
    <h1>Weekly Weather Fetcher</h1>

    <form action="weather.php" method="get">
        <label for="latitude">Latitude:</label>
        <input type="text" id="latitude" name="latitude" placeholder="Enter Latitude" required>

        <label for="longitude">Longitude:</label>
        <input type="text" id="longitude" name="longitude" placeholder="Enter Longitude" required>

        <button type="submit">Fetch Weekly Weather</button>
    </form>

    <?php

// OpenWeatherMap API key
$apiKey = 'bed78b1da26129c814417476fdc3bfed';

// Get latitude and longitude from the form
$latitude = isset($_GET['latitude']) ? floatval($_GET['latitude']) : null;
$longitude = isset($_GET['longitude']) ? floatval($_GET['longitude']) : null;

// Validate latitude and longitude values
if ($latitude === null || $longitude === null) {
    echo 'Error: Please enter both latitude and longitude.';
    exit;
}

// API endpoint URL with latitude and longitude parameters
$apiUrl = "https://api.openweathermap.org/data/2.5/forecast/daily??lat=$latitude&lon=$longitude&appid=$apiKey";

// Initialize cURL session
$ch = curl_init($apiUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute cURL session and get the response
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
} else {
    // Decode the JSON response
    $data = json_decode($response, true);

    // Check if the API request was successful
    if ($data['cod'] == '200') {
        // Display the weekly weather information in Bootstrap cards
        $today = time();
        $oneWeekLater = strtotime('+7 days', $today);

        echo '<div class="card-group">';

        foreach ($data['list'] as $forecast) {
            $timestamp = $forecast['dt'];

            // Check if the forecast date is within the next 7 days
            if ($timestamp <= $oneWeekLater) {
                $date = date('Y-m-d', $timestamp);
                $temperature = $forecast['main']['temp'];
                $description = $forecast['weather'][0]['description'];

                // Customize the card style based on the temperature
                $cardClass = ($temperature > 20) ? 'bg-warning' : 'bg-info';

                echo '
                <div class="card '.$cardClass.' m-2">
                    <div class="card-body">
                        <h5 class="card-title">Date: '.$date.'</h5>
                        <p class="card-text">Temperature: '.$temperature.' °C</p>
                        <p class="card-text">Description: '.$description.'</p>
                    </div>
                </div>';
            }
        }

        echo '</div>';
    } else {
        // Display an error message
        echo 'Error: ' . $data['message'];
    }
}

// Close cURL session
curl_close($ch);

?>


</body>
</html>
