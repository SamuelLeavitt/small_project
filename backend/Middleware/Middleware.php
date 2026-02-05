<?php
require_once __DIR__ . '/../../../vendor/autoload.php'; // For firebase/php-jwt still need to ask if this is fine to use because its 3rd party
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Secret key for JWT (store securely in production!)
$jwt_secret = 'your_secret_key_here';

// Get JWT from Authorization header
$headers = getallheaders();
$authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';
$userId = null;

if (preg_match('/Bearer\s(.*)/', $authHeader, $matches)) {
	$jwt = $matches[1];
	try {
		$decoded = JWT::decode($jwt, new Key($jwt_secret, 'HS256'));
		$userId = $decoded->user_id;
	} catch (Exception $e) {
		sendResultInfoAsJson(json_encode([
			"success" => false,
			"error" => "Invalid or expired token"
		]));
		exit();
	}
} else {
	sendResultInfoAsJson(json_encode([
		"success" => false,
		"error" => "Authorization header missing"
	]));
	exit();
}