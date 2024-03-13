<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background-color: #ffffff;
        }
        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            overflow: hidden;
        }
        .card-body {
            padding: 20px;
        }
        .card-title {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .card-text {
            font-size: 18px;
            color: #333;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <form method="post">
        <label for="city">City:</label>
        <input type="text" id="city" name="city" required><br><br>
        <button type="submit" name="submit" class="btn btn-primary">Get Weather</button>
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
            echo '<div class="alert alert-danger" role="alert">';
            echo 'Error: ' . curl_error($curl);
            echo '</div>';
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

                // Display weather information in a Bootstrap card
                echo "
                <div class='card mt-3'>
                    <div class='card-body'>
                        <h2 class='card-title'>Weather Information for {$data['name']}:</h2>
                        <p class='card-text'>Temperature: {$temperatureCelsius}°C</p>
                        <p class='card-text'>Description: {$description}</p>
                    </div>
                </div>";
            } else {
                // Handle API errors or invalid response
                echo '<div class="alert alert-danger" role="alert">';
                echo 'Error: Invalid response from API';
                echo '</div>';
            }
        }

        // Close cURL session
        curl_close($curl);
    }
    ?>
</div>

</body>
</html>
