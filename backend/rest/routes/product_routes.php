<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials", "true");
require_once __DIR__ . '/../services/ProductService.php';

Flight::set('product_service', new ProductService());

/**
 * @OA\Post(
 *     path="/product",
 *     tags={"Products"},
 *     summary="Create a new product",
 *     description="Creates a new product with material, color, and other attributes.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "material_id", "color_id", "weight", "price_each", "quantity", "description", "product_image"},
 *             @OA\Property(property="name", type="string", example="Modern Vase"),
 *             @OA\Property(property="material_id", type="integer", example=1, description="ID from the 'material' table"),
 *             @OA\Property(property="color_id", type="integer", example=1, description="ID from the 'color' table"),
 *             @OA\Property(property="weight", type="number", format="float", example=1.5),
 *             @OA\Property(property="price_each", type="number", format="float", example=29.99),
 *             @OA\Property(property="quantity", type="integer", example=10),
 *             @OA\Property(property="description", type="string", example="Elegant ceramic vase with glossy finish"),
 *             @OA\Property(property="product_image", type="string", example="vase.jpg")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product successfully created",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Product created successfully"),
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

// CREATE product
Flight::route('POST /product', function() {
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
        'message' => 'Product created successfully'
    ]);
});

/**
 * @OA\Put(
 *     path="/product/{id}",
 *     tags={"Products"},
 *     summary="Update an existing product",
 *     description="Updates the product details by product ID.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the product to update",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Updated Vase"),
 *             @OA\Property(property="material_id", type="integer", example=1),
 *             @OA\Property(property="color_id", type="integer", example=1),
 *             @OA\Property(property="weight", type="number", format="float", example=1.8),
 *             @OA\Property(property="price_each", type="number", format="float", example=34.99),
 *             @OA\Property(property="quantity", type="integer", example=15),
 *             @OA\Property(property="description", type="string", example="Updated elegant ceramic vase"),
 *             @OA\Property(property="product_image", type="string", example="updated_vase.jpg")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product successfully updated",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Product updated successfully")
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

Flight::route('PUT /product/@id', function($id) {
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
        'message' => 'Product updated successfully'
    ]);
});

/**
 * @OA\Delete(
 *     path="/product/{id}",
 *     tags={"Products"},
 *     summary="Delete a product",
 *     description="Deletes a product by its ID.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the product to delete",
 *         @OA\Schema(type="integer", example=2)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product successfully deleted",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Product deleted successfully")
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

Flight::route('DELETE /product/@id', function($id) {
    $response = Flight::get('product_service')->delete_product($id);

    if (!is_array($response) || !isset($response['success'])) {
        Flight::halt(500, "Unexpected error");
    }

    if (!$response['success']) {
        $code = ($response['error'] === 'Invalid input') ? 400 : 500;
        Flight::halt($code, $response['error']);
    }

    Flight::json([
        'message' => 'Product deleted successfully'
    ]);
});

/**
 * @OA\Get(
 *     path="/product/{id}",
 *     tags={"Products"},
 *     summary="Get a product by ID",
 *     description="Retrieves the details of a specific product using its ID.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the product to retrieve",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Product retrieved successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="Modern Vase"),
 *                 @OA\Property(property="material_id", type="integer", example=1),
 *                 @OA\Property(property="color_id", type="integer", example=1),
 *                 @OA\Property(property="weight", type="number", format="float", example=1.5),
 *                 @OA\Property(property="price_each", type="number", format="float", example=29.99),
 *                 @OA\Property(property="quantity", type="integer", example=10),
 *                 @OA\Property(property="description", type="string", example="Elegant ceramic vase with glossy finish"),
 *                 @OA\Property(property="product_image", type="string", example="vase.jpg")
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

Flight::route('GET /product/@id', function($id) {
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

/**
 * @OA\Get(
 *     path="/products",
 *     tags={"Products"},
 *     summary="Get all products",
 *     description="Retrieves a list of all available products.",
 *     @OA\Response(
 *         response=200,
 *         description="All products retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="All products retrieved successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=5),
 *                     @OA\Property(property="name", type="string", example="Modern Vase"),
 *                     @OA\Property(property="material_id", type="integer", example=2),
 *                     @OA\Property(property="color_id", type="integer", example=3),
 *                     @OA\Property(property="weight", type="number", format="float", example=1.5),
 *                     @OA\Property(property="price_each", type="number", format="float", example=29.99),
 *                     @OA\Property(property="quantity", type="integer", example=10),
 *                     @OA\Property(property="description", type="string", example="Elegant ceramic vase with glossy finish"),
 *                     @OA\Property(property="product_image", type="string", example="vase.jpg")
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

// GET all products
Flight::route('GET /products', function() {
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
