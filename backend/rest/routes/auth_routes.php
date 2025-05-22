<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials", "true");
header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
// Allow these headers
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// Allow these methods
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Respond to OPTIONS request with 200 OK and exit early
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../services/AuthService.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::set('auth_service', new AuthService());
Flight::route('POST /login', function() {
    $payload = Flight::request()->data->getData();

    $user = Flight::get('auth_service')->get_user_by_email($payload['email']);

        if (!$user || !password_verify($payload['password'], $user['password']))
            Flight::halt(500, "Invalid username or password");


    unset($user['password']);

    $jwt_payload = [
        'user' => $user,
        'iat' => time(),
        // If this parameter is not set, JWT will be valid for life. This is not a good approach
        'exp' => time() + (60 * 60) // valid for an hour
    ];

    $token = JWT::encode(
        $jwt_payload,
        Config::JWT_SECRET(),
        'HS256'
    );

    Flight::json(
        array_merge($user, ['token' => $token])
    );
});

Flight::route('POST /register', function() {
    $data = Flight::request()->data->getData();

    // Validate required fields
    if (!isset($data['name'], $data['email'], $data['password'], $data['repeat_password_signup'])) {
        Flight::halt(400, "Missing required fields.");
    }

    // Ensure passwords match
    if ($data['password'] !== $data['repeat_password_signup']) {
        Flight::halt(400, "Passwords do not match.");
    }

    // Hash the password
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

    // Remove repeat password before saving
    unset($data['repeat_password_signup']);

    // Set default role if not set
    if (!isset($data['role_id'])) {
        $data['role_id'] = 1;
    }

    // Save user using service
    $user = Flight::get('user_service')->create_user($data);

    // Create JWT
    $jwt_payload = [
        'user' => $user,
        'iat' => time(),
        'exp' => time() + (60 * 60) // 1 hour expiration
    ];

    $token = JWT::encode($jwt_payload, Config::JWT_SECRET(), 'HS256');

    // Send user data + token
    Flight::json(array_merge($user, ['token' => $token]));
});

;