<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials", "true");
require_once __DIR__ . '/../services/UserService.php';
Flight::set('user_service', new UserService());

/**
 * @OA\Post(
 *     path="/user",
 *     tags={"Users"},
 *     summary="Create a new user",
 *     description="Registers a new user with name, email, password, and role.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password", "role_id"},
 *             @OA\Property(property="name", type="string", example="Jane Smith"),
 *             @OA\Property(property="email", type="string", example="jane@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="secret123"),
 *             @OA\Property(property="image", type="string", example="profile.jpg"),
 *             @OA\Property(property="role_id", type="integer", example=1, description="User role (e.g. admin, customer)")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User successfully created",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User created successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Unexpected error"
 *     )
 * )
 */

Flight::route('POST /user', function() {
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
        'message' => 'User created successfully'
    ]);
});

/**
 * @OA\Put(
 *     path="/user/{id}",
 *     tags={"Users"},
 *     summary="Update a user",
 *     description="Updates the details of a user by ID.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user to update",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Jane Smith Updated"),
 *             @OA\Property(property="email", type="string", example="jane.smith@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="newpass456"),
 *             @OA\Property(property="image", type="string", example="newprofile.jpg"),
 *             @OA\Property(property="role_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User successfully updated",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User updated successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Unexpected error"
 *     )
 * )
 */

// EDIT user
Flight::route('PUT /user/@id', function($id) {
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
        'message' => 'User updated successfully'
    ]);
});

/**
 * @OA\Delete(
 *     path="/user/{id}",
 *     tags={"Users"},
 *     summary="Delete a user",
 *     description="Deletes a user by their ID.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user to delete",
 *         @OA\Schema(type="integer", example=2)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User successfully deleted",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Unexpected error"
 *     )
 * )
 */

// DELETE user
Flight::route('DELETE /user/@id', function($id) {
    $response = Flight::get('user_service')->delete_user($id);

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }
    if (!$response['success']) {
        $code = ($response['error'] === 'Invalid input') ? 400 : 500;
        Flight::halt($code, $response['error']);
    }

    Flight::json([
        'message' => 'User deleted successfully'
    ]);
});

/**
 * @OA\Get(
 *     path="/user/{id}",
 *     tags={"Users"},
 *     summary="Get user by ID",
 *     description="Retrieves user details using their unique ID.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user to retrieve",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User retrieved successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="ilma2"),
 *                 @OA\Property(property="email", type="string", example="ilma2@example.com"),
 *                 @OA\Property(property="password", type="string", example="123"),
 *                 @OA\Property(property="image", type="string", example="profile.jpg"),
 *                 @OA\Property(property="role_id", type="integer", example=1)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Unexpected error"
 *     )
 * )
 */

// GET user by id
Flight::route('GET /user/@id', function($id) {
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
