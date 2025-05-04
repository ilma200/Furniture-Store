<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials", "true");
require_once __DIR__ . '/../services/OrderService.php';

Flight::set('order_service', new OrderService());
/**
 * @OA\Post(
 *     path="/order",
 *     tags={"Orders"},
 *     summary="Create a new order",
 *     description="Creates a new order with customer and delivery details.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "address", "city", "country", "phone", "status_id"},
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 example="John Doe",
 *                 description="Name of the customer"
 *             ),
 *             @OA\Property(
 *                 property="address",
 *                 type="string",
 *                 example="123 Main Street",
 *                 description="Shipping address"
 *             ),
 *             @OA\Property(
 *                 property="city",
 *                 type="string",
 *                 example="Sarajevo",
 *                 description="City for delivery"
 *             ),
 *             @OA\Property(
 *                 property="country",
 *                 type="string",
 *                 example="Bosnia and Herzegovina",
 *                 description="Country for delivery"
 *             ),
 *             @OA\Property(
 *                 property="phone",
 *                 type="string",
 *                 example="+38761111222",
 *                 description="Contact phone number"
 *             ),
 *             @OA\Property(
 *                 property="status_id",
 *                 type="integer",
 *                 example=1,
 *                 description="Status ID for the order (e.g. Pending, Shipped)"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order successfully created",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Order created successfully")
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

Flight::route('POST /order', function() {
    $data = Flight::request()->data->getData();
    $response = Flight::get('order_service')->create_order($data);

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }
    if (!$response['success']) {
        $code = ($response['error'] === 'Invalid input') ? 400 : 500;
        Flight::halt($code, $response['error']);
    }

    Flight::json([
        'message' => 'Order created successfully'
    ]);
});

/**
 * @OA\Put(
 *     path="/order/{id}",
 *     tags={"Orders"},
 *     summary="Update an existing order",
 *     description="Updates the information of an existing order by ID.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the order to update",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Jane Doe"),
 *             @OA\Property(property="address", type="string", example="456 New Street"),
 *             @OA\Property(property="city", type="string", example="Mostar"),
 *             @OA\Property(property="country", type="string", example="Bosnia and Herzegovina"),
 *             @OA\Property(property="phone", type="string", example="+38762123456"),
 *             @OA\Property(property="status_id", type="integer", example=2),
 *             @OA\Property(property="user_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order successfully updated",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Order updated successfully")
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
Flight::route('PUT /order/@id', function($id) {
    $data = Flight::request()->data->getData();
    $response = Flight::get('order_service')->edit_order($id, $data);

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }
    if (!$response['success']) {
        $code = ($response['error'] === 'Invalid input') ? 400 : 500;
        Flight::halt($code, $response['error']);
    }

    Flight::json([
        'message' => 'Order updated successfully'
    ]);
});

/**
 * @OA\Delete(
 *     path="/order/{id}",
 *     tags={"Orders"},
 *     summary="Delete an order",
 *     description="Deletes a specific order by its ID.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the order to delete",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order successfully deleted",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Order deleted successfully")
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

Flight::route('DELETE /order/@id', function($id) {
    $response = Flight::get('order_service')->delete_order($id);

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }
    if (!$response['success']) {
        $code = ($response['error'] === 'Invalid input') ? 400 : 500;
        Flight::halt($code, $response['error']);
    }

    Flight::json([
        'message' => 'Order deleted successfully'
    ]);
});

/**
 * @OA\Get(
 *     path="/orders",
 *     tags={"Orders"},
 *     summary="Get all orders",
 *     description="Retrieves a list of all orders in the system.",
 *     @OA\Response(
 *         response=200,
 *         description="List of all orders retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="All orders retrieved successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=3),
 *                     @OA\Property(property="name", type="string", example="John Doe"),
 *                     @OA\Property(property="address", type="string", example="123 Main Street"),
 *                     @OA\Property(property="city", type="string", example="Sarajevo"),
 *                     @OA\Property(property="country", type="string", example="Bosnia and Herzegovina"),
 *                     @OA\Property(property="phone", type="string", example="+38761111222"),
 *                     @OA\Property(property="status_id", type="integer", example=1),
 *                     @OA\Property(property="user_id", type="integer", example=1)

 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Unexpected error"
 *     )
 * )
 */

Flight::route('GET /orders', function() {
    $response = Flight::get('order_service')->get_all_orders();

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }

    Flight::json([
        'message' => 'All orders retrieved successfully',
        'data' => $response['data']
    ]);
});

/**
 * @OA\Get(
 *     path="/orders/user/{id}",
 *     tags={"Orders"},
 *     summary="Get all orders for a specific user",
 *     description="Retrieves all orders associated with the given user ID.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Orders retrieved successfully for user",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Orders retrieved successfully for user"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=3),
 *                     @OA\Property(property="name", type="string", example="John Doe"),
 *                     @OA\Property(property="address", type="string", example="123 Main Street"),
 *                     @OA\Property(property="city", type="string", example="Sarajevo"),
 *                     @OA\Property(property="country", type="string", example="Bosnia and Herzegovina"),
 *                     @OA\Property(property="phone", type="string", example="+38761111222"),
 *                     @OA\Property(property="status_id", type="integer", example=1),
 *                     @OA\Property(property="user_id", type="integer", example=1)
 *                 )
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

// GET orders by user
Flight::route('GET /orders/user/@id', function($id) {
    $response = Flight::get('order_service')->get_orders_by_user($id);

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }
    if (!$response['success']) {
        $code = ($response['error'] === 'Invalid input') ? 400 : 500;
        Flight::halt($code, $response['error']);
    }

    Flight::json([
        'message' => 'Orders retrieved successfully for user',
        'data' => $response['data']
    ]);
});
?>
