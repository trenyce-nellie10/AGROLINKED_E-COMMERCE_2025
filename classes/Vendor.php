<?php
// classes/Vendor.php
require_once __DIR__ . '/../db/db.php';

class Vendor extends DB {
    public function createProfile($vendor_id, $business_name, $business_description = null, $business_address = null) {
        $pdo = $this->connect();
        $sql = "INSERT INTO vendor_profiles (vendor_id, business_name, business_description, business_address) VALUES (:vid, :bn, :bd, :addr)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':vid' => $vendor_id,
            ':bn' => $business_name,
            ':bd' => $business_description,
            ':addr' => $business_address
        ]);
    }

    public function getProfile($vendor_id) {
        $stmt = $this->connect()->prepare("SELECT vp.*, u.email, u.full_name FROM vendor_profiles vp JOIN users u ON vp.vendor_id = u.user_id WHERE vp.vendor_id = :vid LIMIT 1");
        $stmt->execute([':vid' => $vendor_id]);
        return $stmt->fetch();
    }

    public function getAllProfiles() {
        $stmt = $this->connect()->query("SELECT vp.*, u.full_name, u.email FROM vendor_profiles vp JOIN users u ON vp.vendor_id = u.user_id ORDER BY vp.vendor_id DESC");
        return $stmt->fetchAll();
    }
}
