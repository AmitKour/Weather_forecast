<?php
require 'vendor/autoload.php';

use MongoDB\Client;

$client = new Client("mongodb://localhost:27017");
$collection = $client->login_system->users;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $existingUser = $collection->findOne(['email' => $email]);

    if ($existingUser) {
        echo "Email already exists";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $result = $collection->insertOne([
            'email' => $email,
            'password' => $hashedPassword
        ]);

        if ($result->getInsertedCount() > 0) {
            echo "Registration successful";
        } else {
            echo "Registration failed";
        }
    }
}
?>
