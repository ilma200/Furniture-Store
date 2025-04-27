<?php
require_once __DIR__ . '/../dao/UserDao.php';

class UserService {
    private $userDao;

    public function __construct() {
        $this->userDao = new UserDao();
    }

    public function create_user($data) {
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            return ['success' => false, 'error' => 'Invalid input'];
        }

        $result = $this->userDao->create($data);
        return ['success' => true, 'data' => $result];
    }

    public function edit_user($id, $data) {
        if (empty($id) || empty($data)) {
            return ['success' => false, 'error' => 'Invalid input'];
        }

        $result = $this->userDao->edit($id, $data);
        return ['success' => true, 'data' => $result];
    }

    public function delete_user($id) {
        if (empty($id)) {
            return ['success' => false, 'error' => 'Invalid input'];
        }

        $result = $this->userDao->deleteUser($id);
        return ['success' => true, 'data' => $result];
    }

    public function get_user_by_id($id) {
        if (empty($id)) {
            return ['success' => false, 'error' => 'Invalid input'];
        }

        $result = $this->userDao->getUserById($id);
        return ['success' => true, 'data' => $result];
    }
}
?>
