<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials", "true");
require_once __DIR__ . "/../../config.php";
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


Flight::route('/*', function() {
    if(
        strpos(Flight::request()->url, '/register') === 0 ||
        strpos(Flight::request()->url, '/login') === 0
        
    ) {
        return TRUE;
    } else {
        try {
            $token = Flight::request()->getHeader("Authentication");
            if(!$token){
                 Flight::halt(401, "Unauthorized access. This will be reported to administrator!");
            }
            $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
            if(isset($decoded_token->user->id)){
                Flight::set('user', $decoded_token->user->id);
            }
            Flight::set('jwt_token', $token);
            return TRUE;
        } catch (\Exception $e) {
            Flight::halt(401, $e->getMessage());
        }
    }
});

Flight::map('error', function($e){
    file_put_contents('logs.txt', $e . PHP_EOL, FILE_APPEND | LOCK_EX);

    $code = $e->getCode();
    if ($code < 100 || $code > 599) {
        $code = 500; // fallback to 500 Internal Server Error
    }

    Flight::halt($code, $e->getMessage());
});
