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


// returns false if no rows found and true if found. This does not return a value yet.
// Allows to get the actual row data and use functions like fetch_assoc(). 
$result = $stmt->get_result();


// if there is anything result->fetch_assoc() returns a row array
// If the result is empty (no rows found), fetch_assoc() returns false.

// if more than one username exists (should not happen), only the first is checked. 
// and if we had to use multiples of them we would use while loop.
if ($row = $result->fetch_assoc()) {
// It takes the plain password from the frontend (user input).
// It takes the hashed password from the database ($row['password_hash']).
// It checks if the plain password, when hashed, matches the stored hash.
    if (password_verify($password, $row['password_hash'])) {
        sendResultInfoAsJson(json_encode([
            // $json is now: {"success":true,"username":"anyname","email":"anyemail","error":""}
            "success" => true,
            "id" => $row['id'],
            "username" => $row['username'],
            "email" => $row['email'],
            "error" => ""
        ]));
    } else {
        sendResultInfoAsJson(json_encode([
            "success" => false,
            "error" => "Invalid password"]));
    }
} else {
    sendResultInfoAsJson(json_encode([
        "success" => false,
        "error" => "User not found"]));
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
