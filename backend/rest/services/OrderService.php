<?php
require_once __DIR__ . '/../dao/OrderDao.php';

class OrderService {
    private $orderDao;

    public function __construct() {
        $this->orderDao = new OrderDao();
    }

    public function create_order($data) {
        $requiredFields = ['name', 'address', 'city', 'country', 'phone', 'status_id'];

        foreach ($requiredFields as $field) {
            if (empty($data[$field]) && $data[$field] !== '0') { // allow 0 as valid
                return ['success' => false, 'error' => 'Invalid input'];
            }
        }
        

        $result = $this->orderDao->create($data);
        return ['success' => true, 'data' => $result];
    }

    public function edit_order($id, $data) {
        if (empty($id) || empty($data)) {
            return ['success' => false, 'error' => 'Invalid input'];
        }

        $result = $this->orderDao->edit($id, $data);
        return ['success' => true, 'data' => $result];
    }

    public function delete_order($id) {
        if (empty($id)) {
            return ['success' => false, 'error' => 'Invalid input'];
        }

        $result = $this->orderDao->deleteOrder($id);
        return ['success' => true, 'data' => $result];
    }

    public function get_all_orders() {
        $result = $this->orderDao->getAllOrders();
        return ['success' => true, 'data' => $result];
    }

    public function get_orders_by_user($user_id) {
        if (empty($user_id)) {
            return ['success' => false, 'error' => 'Invalid input'];
        }

        $result = $this->orderDao->getByUserId($user_id);
        return ['success' => true, 'data' => $result];
    }
}
?>
