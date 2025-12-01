<?php
// classes/ProductImage.php
require_once __DIR__ . '/../db/db.php';

class ProductImage extends DB {
    public function add($product_id, $path) {
        $stmt = $this->connect()->prepare("INSERT INTO product_images (product_id, image_path) VALUES (:pid, :path)");
        return $stmt->execute([':pid' => $product_id, ':path' => $path]);
    }

    public function imagesForProduct($product_id) {
        $stmt = $this->connect()->prepare("SELECT * FROM product_images WHERE product_id = :pid");
        $stmt->execute([':pid' => $product_id]);
        return $stmt->fetchAll();
    }
}
