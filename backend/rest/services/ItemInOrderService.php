<?php
require_once __DIR__ . '/../dao/ItemInOrderDao.php';

class ItemInOrderService {
    private $itemInOrderDao;

    public function __construct() {
        $this->itemInOrderDao = new ItemInOrderDao();
    }

    public function add_item_to_order($order_id, $product_id, $quantity) {
        if (empty($order_id) || empty($product_id) || empty($quantity)) {
            return ['success' => false, 'error' => 'Invalid input'];
        }

        $result = $this->itemInOrderDao->create([
            'order_id' => $order_id,
            'product_id' => $product_id,
            'quantity' => $quantity
        ]);
        return ['success' => true, 'data' => $result];
    }

    public function edit_item_in_order($order_id, $product_id, $quantity) {
        if (empty($order_id) || empty($product_id) || empty($quantity)) {
            return ['success' => false, 'error' => 'Invalid input'];
        }

        $result = $this->itemInOrderDao->edit($order_id, $product_id, $quantity);
        return ['success' => true, 'data' => $result];
    }

    public function delete_item_in_order($order_id, $product_id) {
        if (empty($order_id) || empty($product_id)) {
            return ['success' => false, 'error' => 'Invalid input'];
        }

        $result = $this->itemInOrderDao->deleteItem($order_id, $product_id);
        return ['success' => true, 'data' => $result];
    }

    public function get_items_by_order($order_id) {
        if (empty($order_id)) {
            return ['success' => false, 'error' => 'Invalid input'];
        }

        $result = $this->itemInOrderDao->getByOrderId($order_id);
        return ['success' => true, 'data' => $result];
    }
}
?>
