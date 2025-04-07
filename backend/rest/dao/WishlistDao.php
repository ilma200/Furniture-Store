<?php
require_once '../config.php';

class WishlistDao {
    private $connection;

    public function __construct() {
        $this->connection = Database::connect();
    }

    public function create($data) {
        $sql = "INSERT INTO wishlist (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute($data);
    }

    public function edit($user_id, $product_id, $quantity) {
        $sql = "UPDATE wishlist SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([
            'quantity' => $quantity,
            'user_id' => $user_id,
            'product_id' => $product_id
        ]);
    }

    public function deleteItem($user_id, $product_id) {
        $stmt = $this->connection->prepare("DELETE FROM wishlist WHERE user_id = :user_id AND product_id = :product_id");
        return $stmt->execute([
            'user_id' => $user_id,
            'product_id' => $product_id
        ]);
    }

    public function getByUserId($user_id) {
        $stmt = $this->connection->prepare("SELECT * FROM wishlist WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }
    
}
?>
