<?php
// The cookie is set in:
// - backend/routes/Auth/Login.php    (after a successful login)
// - backend/routes/Auth/Register.php (after a successful registration)
//
// Any route that needs the logged-in user should include this file and call:
//   require_once '../../Middleware/Middleware.php';
//   $userId = getUserIdFromCookie();
// WHY DOESN'T PHP HAVE JWT BUILT-IN LIKE NODE.JS? AAHHH

function getUserIdFromCookie()
{
    if (empty($_COOKIE['user_id'])) {
        // If there's no cookie, the user is not logged in.
        // sendResultInfoAsJson is defined in each route file.
        sendResultInfoAsJson(json_encode([
            'success' => false,
            'error'   => 'Unauthorized. Please log in.',
        ]));
        exit();
    }

    // Cast to int to avoid unexpected types.
    return (int) $_COOKIE['user_id'];
}