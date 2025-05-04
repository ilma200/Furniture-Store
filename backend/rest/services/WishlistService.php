<?php
require_once __DIR__ . '/../dao/WishlistDao.php';

class WishlistService {
    private $wishlistDao;

    public function __construct() {
        $this->wishlistDao = new WishlistDao();
    }

    public function add_to_wishlist($user_id, $product_id, $quantity) {
        if (empty($user_id)) return ['success' => false, 'error' => 'Server error'];
        if (empty($product_id)) return ['success' => false, 'error' => 'Invalid input'];
        if (empty($quantity)) return ['success' => false, 'error' => 'Invalid input'];

        $result = $this->wishlistDao->create([
            'user_id' => $user_id,
            'product_id' => $product_id,
            'quantity' => $quantity
        ]);
        return ['success' => true, 'data' => $result];
    }

    public function edit_wishlist_item($user_id, $product_id, $quantity) {
        if (empty($user_id) || empty($product_id) || empty($quantity)) {
            return ['success' => false, 'error' => 'Invalid input'];
        }

        $result = $this->wishlistDao->edit($user_id, $product_id, $quantity);
        return ['success' => true, 'data' => $result];
    }

    public function delete_wishlist_item($user_id, $product_id) {
        if (empty($user_id) || empty($product_id)) {
            return ['success' => false, 'error' => 'Invalid input'];
        }

        $result = $this->wishlistDao->deleteItem($user_id, $product_id);
        return ['success' => true, 'data' => $result];
    }

    public function get_wishlist_by_user($user_id) {
        if (empty($user_id)) {
            return ['success' => false, 'error' => 'Invalid input'];
        }

        $result = $this->wishlistDao->getByUserId($user_id);
        return ['success' => true, 'data' => $result];
    }
}
?>
