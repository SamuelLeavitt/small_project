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

$stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $password_hash);

if ($stmt->execute()) {
    sendResultInfoAsJson('{"success":true}');
} else {
    sendResultInfoAsJson('{"error":"Registration failed"}');
}

$stmt->close();
$conn->close();

function sendResultInfoAsJson($obj) {
    header('Content-type: application/json');
    echo $obj;
}
?>
