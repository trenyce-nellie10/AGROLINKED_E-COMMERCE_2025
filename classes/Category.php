<?php
// classes/Category.php
require_once __DIR__ . '/../db/db.php';

class Category extends DB {
    public function all() {
        $stmt = $this->connect()->query("SELECT * FROM categories ORDER BY category_name");
        return $stmt->fetchAll();
    }

    public function get($id) {
        $stmt = $this->connect()->prepare("SELECT * FROM categories WHERE category_id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}
