<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials", "true");
require_once __DIR__ . '/../services/ProductService.php';

Flight::set('product_service', new ProductService());

// CREATE product
Flight::route('POST /product', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    $response = Flight::get('product_service')->create_product($data);

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }

    if (!$response['success']) {
        $code = ($response['error'] === 'Invalid input') ? 400 : 500;
        Flight::halt($code, $response['error']);
    }

    Flight::json([
        'message' => 'Product created successfully',
        'data' => $response['data']
    ]);
});

// EDIT product
Flight::route('PUT /product/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    $response = Flight::get('product_service')->edit_product($id, $data);

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }

    if (!$response['success']) {
        $code = ($response['error'] === 'Invalid input') ? 400 : 500;
        Flight::halt($code, $response['error']);
    }

    Flight::json([
        'message' => 'Product updated successfully',
        'data' => $response['data']
    ]);
});

// DELETE product
Flight::route('DELETE /product/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    $response = Flight::get('product_service')->delete_product($id);

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }

    if (!$response['success']) {
        $code = ($response['error'] === 'Invalid input') ? 400 : 500;
        Flight::halt($code, $response['error']);
    }

    Flight::json([
        'message' => 'Product deleted successfully',
        'data' => $response['data']
    ]);
});

// GET product by ID
Flight::route('GET /product/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $response = Flight::get('product_service')->get_product_by_id($id);

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }

    if (!$response['success']) {
        $code = ($response['error'] === 'Invalid input') ? 400 : 500;
        Flight::halt($code, $response['error']);
    }

    Flight::json([
        'message' => 'Product retrieved successfully',
        'data' => $response['data']
    ]);
});

// GET all products
Flight::route('GET /products', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::USER, Roles::ADMIN]);
    $response = Flight::get('product_service')->get_all_products();

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }

    Flight::json([
        'message' => 'All products retrieved successfully',
        'data' => $response['data']
    ]);
});
?>
