<?php
require_once '../config.php';

class ItemInOrderDao {
    private $connection;

    public function __construct() {
        $this->connection = Database::connect();
    }

    public function create($data) {
        $sql = "INSERT INTO item_in_order (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute($data);
    }

    public function edit($order_id, $product_id, $quantity) {
        $sql = "UPDATE item_in_order SET quantity = :quantity WHERE order_id = :order_id AND product_id = :product_id";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([
            'quantity' => $quantity,
            'order_id' => $order_id,
            'product_id' => $product_id
        ]);
    }

    public function deleteItem($order_id, $product_id) {
        $stmt = $this->connection->prepare("DELETE FROM item_in_order WHERE order_id = :order_id AND product_id = :product_id");
        return $stmt->execute([
            'order_id' => $order_id,
            'product_id' => $product_id
        ]);
    }

    public function getByOrderId($order_id) {
        $stmt = $this->connection->prepare("SELECT * FROM item_in_order WHERE order_id = :order_id");
        $stmt->execute(['order_id' => $order_id]);
        return $stmt->fetchAll();
    }
    
}
?>
