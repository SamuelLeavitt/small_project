<?php
require_once '../../connection.php';
require_once '../../Middleware/Middleware.php';

$inData = json_decode(file_get_contents('php://input'), true);

// Logged-in user's ID is read from the user_id cookie
$userId = getUserIdFromCookie();

// search term from input
$search = isset($inData['search']) ? $inData['search'] : '';

// SQL query to handle empty search terms
if ($search === '') {
    $sql = "SELECT id, first_name, last_name, email, phone_number FROM contacts WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
} else {
    $sql = "SELECT id, first_name, last_name, email, phone_number FROM contacts WHERE user_id = ? AND (first_name LIKE ? OR last_name LIKE ?)";
    $stmt = $conn->prepare($sql);
    $likeSearch = "%$search%";
    $stmt->bind_param("iss", $userId, $likeSearch, $likeSearch);
}

// SQL query to handle separate first_name and last_name inputs
$firstName = isset($inData['first_name']) ? $inData['first_name'] : '';
$lastName = isset($inData['last_name']) ? $inData['last_name'] : '';

if ($firstName === '' && $lastName === '') {
    $sql = "SELECT id, first_name, last_name, email, phone_number FROM contacts WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
} else if ($firstName !== '' && $lastName === '') {
    $sql = "SELECT id, first_name, last_name, email, phone_number FROM contacts WHERE user_id = ? AND first_name LIKE ?";
    $stmt = $conn->prepare($sql);
    $likeFirstName = "%$firstName%";
    $stmt->bind_param("is", $userId, $likeFirstName);
} else if ($firstName === '' && $lastName !== '') {
    $sql = "SELECT id, first_name, last_name, email, phone_number FROM contacts WHERE user_id = ? AND last_name LIKE ?";
    $stmt = $conn->prepare($sql);
    $likeLastName = "%$lastName%";
    $stmt->bind_param("is", $userId, $likeLastName);
} else {
    $sql = "SELECT id, first_name, last_name, email, phone_number FROM contacts WHERE user_id = ? AND first_name LIKE ? AND last_name LIKE ?";
    $stmt = $conn->prepare($sql);
    $likeFirstName = "%$firstName%";
    $likeLastName = "%$lastName%";
    $stmt->bind_param("iss", $userId, $likeFirstName, $likeLastName);
}

if($stmt->execute()){
    $result = $stmt->get_result();
    sendResultInfoAsJson(json_encode($result->fetch_all(MYSQLI_ASSOC)));
} else {
    sendResultInfoAsJson(json_encode([
        "error" => "Failed to search contacts",
        "success" => false
    ]));
}
$stmt->close();
$conn->close();

function sendResultInfoAsJson($obj){
    header('Content-type: application/json');
    echo $obj;
}
