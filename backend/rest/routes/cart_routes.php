<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials", "true");
require_once __DIR__ . '/../services/CartService.php';
Flight::set('cart_service', new CartService());


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
        'message' => 'Item added to cart',
        'data' => $response['data']
    ]);
});

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
        'message' => 'Cart item updated',
        'data' => $response['data']
    ]);
});

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
        'message' => 'Cart item deleted',
        'data' => $response['data']
    ]);
});

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
