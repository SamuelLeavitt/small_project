<?php
require_once '../../connection.php';
require_once '../../Middleware/Middleware.php';

// php://input = req.body (like in Node.js/Express)
// $inData is the decoded JSON from the frontend
$inData = json_decode(file_get_contents('php://input'), true);

// Fields coming from the request body
$contactId   = $inData['contact_id'];
$firstName   = $inData['first_name'];
$lastName    = $inData['last_name'];
$email       = $inData['email'];
$phoneNumber = $inData['phone_number'];

// Logged-in user's ID comes from the user_id cookie
// via the helper in backend/Middleware/Middleware.php
$userId = getUserIdFromCookie();

$stmt = $conn->prepare("UPDATE contacts SET first_name=?, last_name=?, email=?, phone_number=? WHERE id=? AND user_id=?");
$stmt->bind_param("ssssii", $firstName, $lastName, $email, $phoneNumber, $contactId, $userId);

if ($stmt->execute()){
    sendResultInfoAsJson(json_encode([
        "success" => true,
        "message" => "Contact updated successfully"
    ]));
} else {
    sendResultInfoAsJson(json_encode([
        "error" => "Failed to update contact",
        "success" => false
    ]));
}

$stmt->close();
$conn->close();

function sendResultInfoAsJson($obj){
    header('Content-type: application/json');
    echo $obj;
}