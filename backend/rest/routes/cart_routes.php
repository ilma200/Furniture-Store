<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials", "true");
require_once __DIR__ . '/../services/CartService.php';
Flight::set('cart_service', new CartService());

/**
 * @OA\Post(
 *     path="/cart",
 *     tags={"Cart"},
 *     summary="Add an item to the cart",
 *     description="Adds a specific product to a user's cart with a defined quantity.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "product_id", "quantity"},
 *             @OA\Property(
 *                 property="user_id",
 *                 type="integer",
 *                 example=1,
 *                 description="The ID of the user"
 *             ),
 *             @OA\Property(
 *                 property="product_id",
 *                 type="integer",
 *                 example=2,
 *                 description="The ID of the product to add"
 *             ),
 *             @OA\Property(
 *                 property="quantity",
 *                 type="integer",
 *                 example=2,
 *                 description="The quantity of the product"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Item successfully added to cart",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Item added to cart")
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

Flight::route('POST /cart', function() {
    $data = Flight::request()->data->getData();
    $user_id = isset($data['user_id']) ? $data['user_id'] : null;

    $response = Flight::get('cart_service')->add_to_cart(
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
        'message' => 'Item added to cart'
    ]);
});

/**
 * @OA\Put(
 *     path="/cart",
 *     tags={"Cart"},
 *     summary="Update quantity of a cart item",
 *     description="Updates the quantity of a specific product in the user's cart.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "product_id", "quantity"},
 *             @OA\Property(
 *                 property="user_id",
 *                 type="integer",
 *                 example=1,
 *                 description="ID of the user whose cart is being updated"
 *             ),
 *             @OA\Property(
 *                 property="product_id",
 *                 type="integer",
 *                 example=2,
 *                 description="ID of the product to update in the cart"
 *             ),
 *             @OA\Property(
 *                 property="quantity",
 *                 type="integer",
 *                 example=3,
 *                 description="New quantity for the product"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Cart item successfully updated",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Cart item updated")
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

// EDIT cart item
Flight::route('PUT /cart', function() {
    $data = Flight::request()->data->getData();
    $user_id = isset($data['user_id']) ? $data['user_id'] : null;

    $response = Flight::get('cart_service')->edit_cart_item(
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
        'message' => 'Cart item updated'
    ]);
});

/**
 * @OA\Delete(
 *     path="/cart",
 *     tags={"Cart"},
 *     summary="Delete an item from the cart",
 *     description="Deletes a specific product from the user's cart.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "product_id"},
 *             @OA\Property(
 *                 property="user_id",
 *                 type="integer",
 *                 example=1,
 *                 description="ID of the user whose cart item is being deleted"
 *             ),
 *             @OA\Property(
 *                 property="product_id",
 *                 type="integer",
 *                 example=5,
 *                 description="ID of the product to remove from the cart"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Cart item successfully deleted",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Cart item deleted")
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

// DELETE cart item
Flight::route('DELETE /cart', function() {
    $data = Flight::request()->data->getData();

    $response = Flight::get('cart_service')->delete_cart_item(
        isset($data['user_id']) ? $data['user_id'] : null,
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
        'message' => 'Cart item deleted'
    ]);
});

/**
 * @OA\Get(
 *     path="/cart/{id}",
 *     tags={"Cart"},
 *     summary="Get cart by user ID",
 *     description="Retrieves all cart items for a specific user by user ID.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Cart successfully retrieved",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Cart retrieved"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="user_id", type="integer", example=1),
 *                     @OA\Property(property="product_id", type="integer", example=1),
 *                     @OA\Property(property="quantity", type="integer", example=2)
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

// GET all cart items for a user
Flight::route('GET /cart/@id', function($id) {
    $response = Flight::get('cart_service')->get_cart_by_user($id);

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }
    if (!$response['success']) {
        $code = ($response['error'] === 'Invalid input') ? 400 : 500;
        Flight::halt($code, $response['error']);
    }
    Flight::json([
        'message' => 'Cart retrieved',
        'data' => $response['data']
    ]);
});
?>
