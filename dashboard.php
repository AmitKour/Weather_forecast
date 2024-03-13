<?php
session_start();

echo $_SESSION['user'];

if (!isset($_SESSION['user'])) {
    
    header("Location: login.html");
    exit(); 
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
<h1>Welcome to the Dashboard!</h1>
    <form action ="logout.php" method="post">
    
    <input type="submit" value="logout">
    </form>
    <form method="post">
        <label for="city">City:</label>
        <input type="text" id="city" name="city" required><br><br>
        <button type="submit" name="submit">Get Weather</button>
    </form>
   

    <?php
    // Check if form is submitted
    if(isset($_POST['submit'])) {
        // OpenWeatherMap API key
        $apiKey = 'bed78b1da26129c814417476fdc3bfed';

        // Get city name from form input
        $city = urlencode($_POST['city']);

        // API endpoint for current weather
        $endpoint = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}";

        // Initialize cURL session
        $curl = curl_init();

        // Set cURL options
        curl_setopt($curl, CURLOPT_URL, $endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL request
        $response = curl_exec($curl);

        // Check for errors
        if ($response === false) {
            echo 'Error: ' . curl_error($curl);
            // Handle errors appropriately
        } else {
            // Decode JSON response
            $data = json_decode($response, true);

            // Check if API request was successful
            if (isset($data['main'])) {
                // Extract relevant weather information
                $temperature = $data['main']['temp'];
                $description = $data['weather'][0]['description'];
                
                // Convert temperature from Kelvin to Celsius
                $temperatureCelsius = round($temperature - 273.15, 1);

                // Output weather information
                echo "<h2>Weather Information for {$data['name']}:</h2>";
                echo "Temperature: {$temperatureCelsius}°C<br>";
                echo "Description: {$description}<br>";
            } else {
                // Handle API errors or invalid response
                echo 'Error: Invalid response from API';
            }
        }

        // Close cURL session
        curl_close($curl);
    }
    ?>

</body>
</html>
