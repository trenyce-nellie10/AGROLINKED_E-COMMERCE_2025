<?php
// classes/User.php
require_once __DIR__ . '/../db/db.php';
class User extends DB {
    public function add($name,$email,$password,$phone=null,$role=3){
        $pdo = $this->connect();
        $sql = "INSERT INTO users (full_name,email,password,phone,role) VALUES (:n,:e,:p,:ph,:r)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':n'=>$name,':e'=>$email,':p'=>password_hash($password,PASSWORD_BCRYPT),':ph'=>$phone,':r'=>$role]);
    }
    public function findByEmail($email){
        $stmt = $this->connect()->prepare("SELECT * FROM users WHERE email=:e LIMIT 1");
        $stmt->execute([':e'=>$email]); return $stmt->fetch();
    }
    public function findById($id){
        $stmt = $this->connect()->prepare("SELECT * FROM users WHERE user_id=:id LIMIT 1");
        $stmt->execute([':id'=>$id]); return $stmt->fetch();
    }
}
