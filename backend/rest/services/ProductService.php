<?php
require_once __DIR__ . '/../dao/ProductDao.php';

class ProductService {
    private $productDao;

    public function __construct() {
        $this->productDao = new ProductDao();
    }

    public function create_product($data) {
        foreach ($data as $key => $value) {
            if (empty($value) && $value !== '0') { // allow zero as valid
                return ['success' => false, 'error' => 'Invalid input'];
            }
        }

        $result = $this->productDao->create($data);
        return ['success' => true, 'data' => $result];
    }

    public function edit_product($id, $data) {
        if (empty($id) || empty($data)) {
            return ['success' => false, 'error' => 'Invalid input'];
        }

        $result = $this->productDao->edit($id, $data);
        return ['success' => true, 'data' => $result];
    }

    public function delete_product($id) {
        if (empty($id)) {
            return ['success' => false, 'error' => 'Invalid input'];
        }

        $result = $this->productDao->deleteProduct($id);
        return ['success' => true, 'data' => $result];
    }

    public function get_product_by_id($id) {
        if (empty($id)) {
            return ['success' => false, 'error' => 'Invalid input'];
        }

        $result = $this->productDao->getProductById($id);
        return ['success' => true, 'data' => $result];
    }

    public function get_all_products() {
        $result = $this->productDao->getAllProducts();
        return ['success' => true, 'data' => $result];
    }
}
?>
