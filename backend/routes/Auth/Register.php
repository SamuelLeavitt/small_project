<?php
// Registers new user

require_once '../../connection.php';
// Now we can use $conn for queries by including connection.php

$inData = json_decode(file_get_contents('php://input'), true);

$username = $inData['username'];
$email = $inData['email'];
$password = $inData['password'];

//$conn = new mysqli("YourDigitalOceanIP", "root", "YOUR_PASSWORD", "contact_manager");
if ($conn->connect_error) {
    sendResultInfoAsJson('{"error":"' . $conn->connect_error . '"}');
    exit();
}

// Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insert new user into the database
$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $password_hash);

if ($stmt->execute()) {
    // Get the ID of the newly created user
    $userId = $stmt->insert_id;

    // Set user_id cookie upon successful registration
    setcookie("user_id", $userId, time() + (86400 * 30), "/", "", true, true); // Expires in 30 days, HTTPOnly

    sendResultInfoAsJson(json_encode([
        "success" => true,
        "message" => "Registration successful"
    ]));
} else {
    sendResultInfoAsJson(json_encode([
        "error" => "Registration failed"
    ]));
}

$stmt->close();
$conn->close();

function sendResultInfoAsJson($obj) {
    header('Content-type: application/json');
    echo $obj;
}
?>
