<?php
require_once '../../connection.php';
require_once '../../Middleware/Middleware.php';

$inData = json_decode(file_get_contents('php://input'), true);

// Logged-in user's ID is read from the user_id cookie
// using the helper in backend/Middleware/Middleware.php
$userId = getUserIdFromCookie();

$stmt = $conn->prepare("SELECT id, first_name, last_name, email, phone_number, creation_date FROM contacts WHERE user_id = ?");
$stmt->bind_param("i", $userId);

if($stmt->execute()){
    //get_result returns a result set from a prepared statement not really the data itself. 
    // Also, allows us to fetch data or fetch_assoc()
    $result = $stmt->get_result();
    // Fetch all contacts as an associative array and send as JSON response
    sendResultInfoAsJson(json_encode($result->fetch_all(MYSQLI_ASSOC)));
}

else {
    sendResultInfoAsJson(json_encode([
        "error" => "Failed to retrieve contacts",
        "success" => false
    ]));
}
$stmt->close();
$conn->close();

function sendResultInfoAsJson($obj){
    header('Content-type: application/json');
    echo $obj;
}