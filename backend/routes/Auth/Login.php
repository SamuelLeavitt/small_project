<?php
require_once '../../connection.php';

// User login

// php://input = the raw request body stream
// file_get_contents(...) = reads it into a string
// json_decode(..., true) = parses JSON into an associative array (like a JS object).

$inData = json_decode(file_get_contents('php://input'), true);

$username = $inData['username'];
$password = $inData['password'];


//$conn = new mysqli("YourDigitalOceanIP", "root", "YOUR_PASSWORD", "contact_manager");
if ($conn->connect_error) {
    sendResultInfoAsJson('{"error":"' . $conn->connect_error . '"}');
    exit();
}


$stmt = $conn->prepare("SELECT id, username, email, password_hash FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password_hash'])) {
        sendResultInfoAsJson(json_encode([
            "success" => true,
            "id" => $row['id'],
            "username" => $row['username'],
            "email" => $row['email'],
            "error" => ""
        ]));
    } else {
        sendResultInfoAsJson(json_encode(["error" => "Invalid password"]));
    }
} else {
    sendResultInfoAsJson(json_encode(["error" => "User not found"]));
}

$stmt->close();
$conn->close();

function sendResultInfoAsJson($obj) {
    header('Content-type: application/json');
    echo $obj;
}
?>
