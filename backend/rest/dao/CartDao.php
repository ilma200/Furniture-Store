<?php
require_once 'BaseDao.php';

class CartDao extends BaseDao {
    public function __construct() {
        parent::__construct("cart");
    }

    public function create($data) {
        return $this->insert($data);
    }

    public function edit($user_id, $product_id, $quantity) {
        $sql = "UPDATE cart SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([
            'quantity' => $quantity,
            'user_id' => $user_id,
            'product_id' => $product_id
        ]);
    }

    public function deleteItem($user_id, $product_id) {
        $stmt = $this->connection->prepare("DELETE FROM cart WHERE user_id = :user_id AND product_id = :product_id");
        return $stmt->execute([
            'user_id' => $user_id,
            'product_id' => $product_id
        ]);
    }

    public function getByUserId($user_id) {
        $stmt = $this->connection->prepare("SELECT * FROM cart WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }
}
?>
