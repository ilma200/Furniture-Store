<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials", "true");
require_once __DIR__ . '/../services/OrderService.php';

Flight::set('order_service', new OrderService());

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
        'message' => 'Order created successfully',
        'data' => $response['data']
    ]);
});

// EDIT order
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
        'message' => 'Order updated successfully',
        'data' => $response['data']
    ]);
});

// DELETE order
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
        'message' => 'Order deleted successfully',
        'data' => $response['data']
    ]);
});

// GET all orders
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
