<?php
require_once 'BaseDao.php';

class ItemInOrderDao extends BaseDao {
    public function __construct() {
        parent::__construct("item_in_order");
    }

    public function create($data) {
        return $this->insert($data);
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
