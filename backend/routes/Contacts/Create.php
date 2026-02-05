<?php
// Now we can use $conn for queries by including connection.php

require_once '../../connection.php';
require_once '../../Middleware/Middleware.php';

// php://input = req.body(like in node.js(express/prisma))
$inData = json_decode(file_get_contents('php://input'), true);

$name = $inData['first_name'];
$lastName = $inData['last_name'];
$email = $inData['email'];
$phone = $inData['phone_number'];


$stmt = $conn->prepare("INSERT INTO contacts (first_name, last_name, email, phone_number, user_id) VALUES (?,?,?,?,?)");
$stmt->bind_param("ssssi", $name, $lastName, $email, $phone, $userId);


if ($stmt->execute()) {
sendResultInfoAsJson(json_encode([
    "success" => true,
    "first_name" => $name,
    "last_name" => $lastName,
    "email" => $email,
    "phone_number" => $phone
]));

} else {
    sendResultInfoAsJson(json_encode([
        "error" => "Failed to create contact",
        "success" => false
        ]));
}

$stmt->close();
$conn->close();


// This tells the browser (or client) that the response will be in JSON format, not plain text or HTML. 
// It sets the HTTP Content-Type header.
function sendResultInfoAsJson($obj) {
    header('Content-type: application/json');
    //In PHP, echo is how you send data back to the client (like res.send() in Node.js/Express/Prisma).
    echo $obj;
}