<?php
require_once '../../connection.php';
require_once '../../Middleware/Middleware.php';

// php://input = req.body(like in node.js(express/prisma))
//indata should be decoded json from frontend
$inData = json_decode(file_get_contents('php://input'), true);

$stmt = $conn->prepare("UPDATE contacts SET first_name=?, last_name=?, email=?, phone_number=? WHERE id=? AND user_id=?");
$contactId = $inData['contact_id'];
$firstName = $inData['first_name'];
$lastName = $inData['last_name'];
$email = $inData['email'];
$phoneNumber = $inData['phone_number'];

// userId comes from the decoded JWT in Middleware.php
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