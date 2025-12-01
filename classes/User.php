<?php
// classes/User.php
require_once __DIR__ . '/../db/db.php';

class User extends DB {
    public function create($full_name, $email, $password, $role = 3, $country = null, $city = null, $phone = null) {
        $pdo = $this->connect();
        $sql = "INSERT INTO users (full_name, email, password, user_role, country, city, phone) VALUES (:name, :email, :pass, :role, :country, :city, :phone)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $full_name,
            ':email' => $email,
            ':pass' => password_hash($password, PASSWORD_BCRYPT),
            ':role' => $role,
            ':country' => $country,
            ':city' => $city,
            ':phone' => $phone
        ]);
    }

    public function findByEmail($email) {
        $stmt = $this->connect()->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->connect()->prepare("SELECT * FROM users WHERE user_id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function allVendors() {
        $stmt = $this->connect()->prepare("SELECT u.*, vp.business_name, vp.approved FROM users u LEFT JOIN vendor_profiles vp ON u.user_id = vp.vendor_id WHERE u.user_role = 2 ORDER BY u.date_joined DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function allCustomers() {
        $stmt = $this->connect()->prepare("SELECT * FROM users WHERE user_role = 3 ORDER BY date_joined DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function approveVendor($vendor_id) {
        $stmt = $this->connect()->prepare("UPDATE vendor_profiles SET approved = 1 WHERE vendor_id = :vid");
        return $stmt->execute([':vid' => $vendor_id]);
    }
}
