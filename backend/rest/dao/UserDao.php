<?php
require_once 'BaseDao.php';

class UserDao extends BaseDao {
    public function __construct() {
        parent::__construct("user");
    }

    public function create($data) {
        return $this->insert($data);
    }

    public function edit($id, $data) {
        return $this->update($id, $data);
    }

    public function deleteUser($id) {
        return $this->delete($id);
    }

    public function getUserById($id) {
        return $this->getById($id);
    }
    
}
?>
