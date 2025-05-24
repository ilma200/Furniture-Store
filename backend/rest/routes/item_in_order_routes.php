<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials", "true");
require_once __DIR__ . '/../services/ItemInOrderService.php';
Flight::set('item_in_order_service', new ItemInOrderService());

Flight::route('POST /item', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
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
        'message' => 'Item added to order',
        'data' => $response['data']
    ]);
});

// EDIT item in order
Flight::route('PUT /item', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
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
        'message' => 'Item updated in order',
        'data' => $response['data']
    ]);
});

// DELETE item from order
Flight::route('DELETE /item', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
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
        'message' => 'Item deleted from order',
        'data' => $response['data']
    ]);
});

// GET all items by order ID
Flight::route('GET /items/@order_id', function($order_id) {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
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
