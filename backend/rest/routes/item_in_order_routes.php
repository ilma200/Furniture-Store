<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials", "true");
require_once __DIR__ . '/../services/ItemInOrderService.php';
Flight::set('item_in_order_service', new ItemInOrderService());

/**
 * @OA\Post(
 *     path="/item",
 *     tags={"Item In Order"},
 *     summary="Add an item to an order",
 *     description="Adds a product to an existing order with the specified quantity.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"order_id", "product_id", "quantity"},
 *             @OA\Property(
 *                 property="order_id",
 *                 type="integer",
 *                 example=1,
 *                 description="ID of the order"
 *             ),
 *             @OA\Property(
 *                 property="product_id",
 *                 type="integer",
 *                 example=3,
 *                 description="ID of the product being added"
 *             ),
 *             @OA\Property(
 *                 property="quantity",
 *                 type="integer",
 *                 example=2,
 *                 description="Quantity of the product"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Item successfully added to order",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Item added to order")
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

Flight::route('POST /item', function() {
    $data = Flight::request()->data->getData();

    $response = Flight::get('item_in_order_service')->add_item_to_order(
        isset($data['order_id']) ? $data['order_id'] : null,
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
        'message' => 'Item added to order'
    ]);
});

/**
 * @OA\Put(
 *     path="/item",
 *     tags={"Item In Order"},
 *     summary="Update an item in an order",
 *     description="Updates the quantity of a specific product in a given order.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"order_id", "product_id", "quantity"},
 *             @OA\Property(
 *                 property="order_id",
 *                 type="integer",
 *                 example=1,
 *                 description="ID of the order"
 *             ),
 *             @OA\Property(
 *                 property="product_id",
 *                 type="integer",
 *                 example=2,
 *                 description="ID of the product in the order"
 *             ),
 *             @OA\Property(
 *                 property="quantity",
 *                 type="integer",
 *                 example=4,
 *                 description="Updated quantity of the product"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Item successfully updated in order",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Item updated in order")
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

// EDIT item in order
Flight::route('PUT /item', function() {
    $data = Flight::request()->data->getData();

    $response = Flight::get('item_in_order_service')->edit_item_in_order(
        isset($data['order_id']) ? $data['order_id'] : null,
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
        'message' => 'Item updated in order'
    ]);
});

/**
 * @OA\Delete(
 *     path="/item",
 *     tags={"Item In Order"},
 *     summary="Delete an item from an order",
 *     description="Removes a specific product from a given order.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"order_id", "product_id"},
 *             @OA\Property(
 *                 property="order_id",
 *                 type="integer",
 *                 example=1,
 *                 description="ID of the order"
 *             ),
 *             @OA\Property(
 *                 property="product_id",
 *                 type="integer",
 *                 example=2,
 *                 description="ID of the product to be removed from the order"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Item successfully deleted from order",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Item deleted from order")
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

// DELETE item from order
Flight::route('DELETE /item', function() {
    $data = Flight::request()->data->getData();

    $response = Flight::get('item_in_order_service')->delete_item_in_order(
        isset($data['order_id']) ? $data['order_id'] : null,
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
        'message' => 'Item deleted from order'
    ]);
});

/**
 * @OA\Get(
 *     path="/items/{order_id}",
 *     tags={"Item In Order"},
 *     summary="Get items by order ID",
 *     description="Retrieves all items associated with a specific order.",
 *     @OA\Parameter(
 *         name="order_id",
 *         in="path",
 *         required=true,
 *         description="ID of the order",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Items successfully retrieved",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Items retrieved from order"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="order_id", type="string", example="1"),
 *                     @OA\Property(property="product_id", type="string", example="1"),
 *                     @OA\Property(property="quantity", type="string", example="2"),
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

// GET all items by order ID
Flight::route('GET /items/@order_id', function($order_id) {
    $response = Flight::get('item_in_order_service')->get_items_by_order($order_id);

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }
    if (!$response['success']) {
        $code = ($response['error'] === 'Invalid input') ? 400 : 500;
        Flight::halt($code, $response['error']);
    }

    Flight::json([
        'message' => 'Items retrieved from order',
        'data' => $response['data']
    ]);
});
?>
