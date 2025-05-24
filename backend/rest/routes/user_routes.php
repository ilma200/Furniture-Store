<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials", "true");
require_once __DIR__ . '/../services/UserService.php';
Flight::set('user_service', new UserService());


Flight::route('POST /user', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    $response = Flight::get('user_service')->create_user($data);

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }
    if (!$response['success']) {
        $code = ($response['error'] === 'Invalid input') ? 400 : 500;
        Flight::halt($code, $response['error']);
    }

    Flight::json([
        'message' => 'User created successfully',
        'data' => $response['data']
    ]);
});

// EDIT user
Flight::route('PUT /user/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    $response = Flight::get('user_service')->edit_user($id, $data);

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }
    if (!$response['success']) {
        $code = ($response['error'] === 'Invalid input') ? 400 : 500;
        Flight::halt($code, $response['error']);
    }

    Flight::json([
        'message' => 'User updated successfully',
        'data' => $response['data']
    ]);
});

// DELETE user
Flight::route('DELETE /user/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $response = Flight::get('user_service')->delete_user($id);

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }
    if (!$response['success']) {
        $code = ($response['error'] === 'Invalid input') ? 400 : 500;
        Flight::halt($code, $response['error']);
    }

    Flight::json([
        'message' => 'User deleted successfully',
        'data' => $response['data']
    ]);
});

// GET user by id
Flight::route('GET /user/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $response = Flight::get('user_service')->get_user_by_id($id);

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }
    if (!$response['success']) {
        $code = ($response['error'] === 'Invalid input') ? 400 : 500;
        Flight::halt($code, $response['error']);
    }
//Handle if data false
    Flight::json([
        'message' => 'User retrieved successfully',
        'data' => $response['data']
    ]);
});
?>
