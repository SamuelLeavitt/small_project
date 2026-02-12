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


// Verify user credentials (password_hash column as defined in schema.sql)
$stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password_hash'])) {
        // Set user_id cookie upon successful login.
        // For local http development, secure=false so the cookie is set.
        // In production on https, change the second-to-last argument to true.
        setcookie("user_id", $row['id'], time() + (86400 * 30), "/", "", false, true);
        sendResultInfoAsJson(json_encode([
            "success" => true,
            "message" => "Login successful"
        ]));
    } else {
        sendResultInfoAsJson(json_encode([
            "error" => "Invalid password"
        ]));
    }
} else {
    sendResultInfoAsJson(json_encode([
        "error" => "User not found"
    ]));
}

$stmt->close();
$conn->close();

// This tells the browser (or client) that the response will be in JSON format, not plain text or HTML. 
// It sets the HTTP Content-Type header.
function sendResultInfoAsJson($obj) {
    header('Content-type: application/json');
    echo $obj;
}
?>
