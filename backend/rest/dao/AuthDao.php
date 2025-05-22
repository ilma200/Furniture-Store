<?php

require_once __DIR__ . "/BaseDao.php";

class AuthDao extends BaseDao {
    public function __construct() {
        parent::__construct("users");
    }

    public  function query($query, $params) {
        $statement = $this->connection->prepare($query);
        $statement->execute($params);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public  function query_unique($query, $params) {
        $results = $this->query($query, $params);
        return reset($results);
    }

    public function get_user_by_email($email) {
        $query = "SELECT *
                  FROM user
                  WHERE email = :email";
        return $this->query_unique($query, ['email' => $email]);
    }
}