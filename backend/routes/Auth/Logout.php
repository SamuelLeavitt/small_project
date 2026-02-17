<?php

header('Content-Type: application/json');
//clears user_id cookie by setting it to expire at time in past to log user out
setcookie("user_id", "", time() - 1, "/", "", false, true);

//send success response to frontend
echo json_encode([
    "success" => true,
]);
?>
