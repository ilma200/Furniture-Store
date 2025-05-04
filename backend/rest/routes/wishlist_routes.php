<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials", "true");
require_once __DIR__ . '/../services/WishlistService.php';
Flight::set('wishlist_service', new WishlistService());
// ADD to wishlist

/**
 * @OA\Post(
 *     path="/wishlist",
 *     tags={"Wishlist"},
 *     summary="Add an item to the wishlist",
 *     description="Adds a product to the user's wishlist with optional quantity.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "product_id"},
 *             @OA\Property(property="user_id", type="integer", example=1, description="ID of the user"),
 *             @OA\Property(property="product_id", type="integer", example=5, description="ID of the product"),
 *             @OA\Property(property="quantity", type="integer", example=1, description="Optional quantity to save")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Item successfully added to wishlist",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Item added to wishlist")
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

Flight::route('POST /wishlist', function() {
    $data = Flight::request()->data->getData();
    $user_id = isset($data['user_id']) ? $data['user_id'] : null;

    $response = Flight::get('wishlist_service')->add_to_wishlist(
        $user_id,
        isset($data['product_id']) ? $data['product_id'] : null,
        isset($data['quantity']) ? $data['quantity'] : null
    );

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }
    if (!$response['success']) {
        $code = ($response['error'] === 'Invalid input') ? 400 : 500;
        Flight::halt($code, $response['error']);
    }
    Flight::json([
        'message' => 'Item added to wishlist'
    ]);
});

/**
 * @OA\Put(
 *     path="/wishlist",
 *     tags={"Wishlist"},
 *     summary="Update an item in the wishlist",
 *     description="Updates the quantity of a specific product in the user's wishlist.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "product_id", "quantity"},
 *             @OA\Property(property="user_id", type="integer", example=1, description="ID of the user"),
 *             @OA\Property(property="product_id", type="integer", example=4, description="ID of the product"),
 *             @OA\Property(property="quantity", type="integer", example=44, description="New quantity for the wishlist item")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Wishlist item successfully updated",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Wishlist item updated")
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

// EDIT wishlist item
Flight::route('PUT /wishlist', function() {
    $data = Flight::request()->data->getData();

    $response = Flight::get('wishlist_service')->edit_wishlist_item(
        isset($data['user_id']) ? $data['product_id'] : null,
        isset($data['product_id']) ? $data['product_id'] : null,
        isset($data['quantity']) ? $data['quantity'] : null
    );

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }
    if (!$response['success']) {
        $code = ($response['error'] === 'Invalid input') ? 400 : 500;
        Flight::halt($code, $response['error']);
    }
    Flight::json([
        'message' => 'Wishlist item updated'
    ]);
});

/**
 * @OA\Delete(
 *     path="/wishlist",
 *     tags={"Wishlist"},
 *     summary="Delete an item from the wishlist",
 *     description="Removes a specific product from the user's wishlist.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "product_id"},
 *             @OA\Property(property="user_id", type="integer", example=1, description="ID of the user"),
 *             @OA\Property(property="product_id", type="integer", example=4, description="ID of the product to remove")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Wishlist item successfully deleted",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Wishlist item deleted")
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

// DELETE wishlist item
Flight::route('DELETE /wishlist', function() {
    $data = Flight::request()->data->getData();
    $user_id = isset($data['user_id']) ? $data['user_id'] : null;


    $response = Flight::get('wishlist_service')->delete_wishlist_item(
        $user_id,
        isset($data['product_id']) ? $data['product_id'] : null
    );

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }
    if (!$response['success']) {
        $code = ($response['error'] === 'Invalid input') ? 400 : 500;
        Flight::halt($code, $response['error']);
    }
    Flight::json([
        'message' => 'Wishlist item deleted'
    ]);
});

/**
 * @OA\Get(
 *     path="/wishlist/{id}",
 *     tags={"Wishlist"},
 *     summary="Get wishlist by user ID",
 *     description="Retrieves all products saved in the wishlist for a specific user.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Wishlist retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Wishlist retrieved"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="user_id", type="string", example="1"),
 *                     @OA\Property(property="product_id", type="string", example="1"),
 *                     @OA\Property(property="quantity", type="string", example="2")
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

// GET wishlist by user
Flight::route('GET /wishlist/@id', function($id) {
    $response = Flight::get('wishlist_service')->get_wishlist_by_user($id);

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }
    if (!$response['success']) {
        $code = ($response['error'] === 'Invalid input') ? 400 : 500;
        Flight::halt($code, $response['error']);
    }
    Flight::json([
        'message' => 'Wishlist retrieved',
        'data' => $response['data']
    ]);
});
?>
