<?php

namespace mpc_system\models;

use PDO;

class ProductModel {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getProductById($id) {
        $stmt = $this->db->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllProducts() {
        $stmt = $this->db->query('SELECT * FROM products');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addProduct($data) {
        $stmt = $this->db->prepare('INSERT INTO products (name, price, description) VALUES (:name, :price, :description)');
        return $stmt->execute($data);
    }

    public function updateProduct($id, $data) {
        $data['id'] = $id;
        $stmt = $this->db->prepare('UPDATE products SET name = :name, price = :price, description = :description WHERE id = :id');
        return $stmt->execute($data);
    }

    public function deleteProduct($id) {
        $stmt = $this->db->prepare('DELETE FROM products WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
?>