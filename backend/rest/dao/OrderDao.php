<?php
require_once 'BaseDao.php';

class OrderDao extends BaseDao {
    public function __construct() {
        parent::__construct("orders");
    }

    public function create($data) {
        return $this->insert($data);
    }

    public function edit($id, $data) {
        return $this->update($id, $data);
    }

    public function deleteOrder($id) {
        return $this->delete($id);
    }

    public function getAllOrders() {
        return $this->getAll();
    }
    
    public function getByUserId($user_id) {
        $stmt = $this->connection->prepare("SELECT * FROM orders WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }
    
}
?>
