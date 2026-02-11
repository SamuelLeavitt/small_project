<?php
require_once '../../connection.php';
require_once '../../Middleware/Middleware.php';

$inData = json_decode(file_get_contents('php://input'), true);
$contactId = $inData['contact_id'];

// Logged-in user's ID is taken from the user_id cookie
// via the helper in backend/Middleware/Middleware.php
$userId = getUserIdFromCookie();

$stmt = $conn->prepare("DELETE FROM contacts WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $contactId, $userId);

if ($stmt->execute()) {
    sendResultInfoAsJson(json_encode([
        "success" => true,
        "message" => "Contact deleted successfully"
    ]));
} else {
    sendResultInfoAsJson(json_encode([
        "error" => "Failed to delete contact",
        "success" => false
    ]));
}

$stmt->close();
$conn->close();

function sendResultInfoAsJson($obj) {
    header('Content-type: application/json');
    echo $obj;
}