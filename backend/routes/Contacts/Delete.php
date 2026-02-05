<?php
require_once '../../connection.php';
require_once '../../Middleware/Middleware.php';


$inData = json_decode(file_get_contents('php://input'), true);
$stmt = $conn->prepare("DELETE FROM contacts WHERE id=? AND user_id=?");
$contactId = $inData['contact_id'];

// userId comes from the decoded JWT in Middleware.php
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