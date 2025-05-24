<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials", "true");
header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require 'vendor/autoload.php';
require 'middleware/AuthMiddleware.php';

Flight::register('auth_middleware', "AuthMiddleware");
Flight::route('/*', function() {
    if(
        strpos(Flight::request()->url, '/login') === 0 ||
        strpos(Flight::request()->url, '/register') === 0
    ) {
        return TRUE;
    } else {
        try {
            $token = Flight::request()->getHeader("Authentication");
            if(Flight::auth_middleware()->verifyToken($token))
                return TRUE;
        } catch (\Exception $e) {
            Flight::halt(401, $e->getMessage());
        }
    }
 });

require 'rest/routes/auth_routes.php';
require 'rest/routes/user_routes.php';
require 'rest/routes/cart_routes.php';
require 'rest/routes/wishlist_routes.php';
require 'rest/routes/product_routes.php';
require 'rest/routes/order_routes.php';
require 'rest/routes/item_in_order_routes.php';



// // Test route to verify FlightPHP is working
//Flight::route('GET /', function () {
//    echo "FlightPHP is working!";
//});

Flight::start();