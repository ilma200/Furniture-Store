<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials", "true");
require_once __DIR__ . '/../services/WishlistService.php';
Flight::set('wishlist_service', new WishlistService());
// ADD to wishlist
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
        'message' => 'Item added to wishlist',
        'data' => $response['data']
    ]);
});

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
        'message' => 'Wishlist item updated',
        'data' => $response['data']
    ]);
});

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
        'message' => 'Wishlist item deleted',
        'data' => $response['data']
    ]);
});

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
