<?php
require_once __DIR__ . '/../dao/CartDao.php';

class CartService {
    private $cartDao;

    public function __construct() {
        $this->cartDao = new CartDao();
    }

    public function add_to_cart($user_id, $product_id, $quantity) {
        if (empty($user_id)) return ['success' => false, 'error' => 'Server error'];
        if (empty($product_id)) return ['success' => false, 'error' => 'Invalid input'];
        if (empty($quantity)) return ['success' => false, 'error' => 'Invalid input'];

        $result = $this->cartDao->create([
            'user_id' => $user_id,
            'product_id' => $product_id,
            'quantity' => $quantity
        ]);
        return ['success' => true, 'data' => $result];
    }

    public function edit_cart_item($user_id, $product_id, $quantity) {
        if (empty($user_id) || empty($product_id) || empty($quantity)) {
            return ['success' => false, 'error' => 'Invalid input'];
        }

        $result = $this->cartDao->edit($user_id, $product_id, $quantity);
        return ['success' => true, 'data' => $result];
    }

    public function delete_cart_item($user_id, $product_id) {
        if (empty($user_id) || empty($product_id)) {
            return ['success' => false, 'error' => 'Invalid input'];
        }

        $result = $this->cartDao->deleteItem($user_id, $product_id);
        return ['success' => true, 'data' => $result];
    }

    public function get_cart_by_user($user_id) {
        if (empty($user_id)) {
            return ['success' => false, 'error' => 'Invalid input'];
        }

        $result = $this->cartDao->getByUserId($user_id);
        return ['success' => true, 'data' => $result];
    }
}
?>
