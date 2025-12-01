<?php
// classes/Product.php
require_once __DIR__ . '/../db/db.php';

class Product extends DB {
    public function create($data) {
        $sql = "INSERT INTO products (vendor_id, category_id, product_name, product_description, price, quantity, main_image) 
                VALUES (:vendor_id, :category_id, :name, :desc, :price, :qty, :img)";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            ':vendor_id' => $data['vendor_id'],
            ':category_id' => $data['category_id'] ?? null,
            ':name' => $data['product_name'],
            ':desc' => $data['product_description'] ?? null,
            ':price' => $data['price'],
            ':qty' => $data['quantity'] ?? 0,
            ':img' => $data['main_image'] ?? null
        ]);
    }

    public function update($product_id, $data) {
        $sql = "UPDATE products SET category_id = :category_id, product_name = :name, product_description = :desc, price = :price, quantity = :qty, main_image = :img WHERE product_id = :pid";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            ':category_id' => $data['category_id'] ?? null,
            ':name' => $data['product_name'],
            ':desc' => $data['product_description'] ?? null,
            ':price' => $data['price'],
            ':qty' => $data['quantity'] ?? 0,
            ':img' => $data['main_image'] ?? null,
            ':pid' => $product_id
        ]);
    }

    public function delete($product_id) {
        $stmt = $this->connect()->prepare("DELETE FROM products WHERE product_id = :pid");
        return $stmt->execute([':pid' => $product_id]);
    }

    public function getById($product_id) {
        $stmt = $this->connect()->prepare("
            SELECT p.*, u.full_name AS vendor_name, c.category_name 
            FROM products p 
            LEFT JOIN users u ON p.vendor_id = u.user_id 
            LEFT JOIN categories c ON p.category_id = c.category_id
            WHERE p.product_id = :pid LIMIT 1
        ");
        $stmt->execute([':pid' => $product_id]);
        return $stmt->fetch();
    }

    public function all() {
        $stmt = $this->connect()->query("SELECT p.*, u.full_name AS vendor_name, c.category_name FROM products p LEFT JOIN users u ON p.vendor_id = u.user_id LEFT JOIN categories c ON p.category_id = c.category_id ORDER BY p.date_added DESC");
        return $stmt->fetchAll();
    }

    public function byVendor($vendor_id) {
        $stmt = $this->connect()->prepare("SELECT p.*, c.category_name FROM products p LEFT JOIN categories c ON p.category_id = c.category_id WHERE p.vendor_id = :vid ORDER BY p.date_added DESC");
        $stmt->execute([':vid' => $vendor_id]);
        return $stmt->fetchAll();
    }

    public function search($q) {
        $q = "%$q%";
        $stmt = $this->connect()->prepare("SELECT p.*, c.category_name FROM products p LEFT JOIN categories c ON p.category_id = c.category_id WHERE p.product_name LIKE :q OR p.product_description LIKE :q ORDER BY p.date_added DESC");
        $stmt->execute([':q' => $q]);
        return $stmt->fetchAll();
    }
}
