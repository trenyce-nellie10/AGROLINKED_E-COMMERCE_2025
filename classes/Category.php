<?php
// classes/Category.php
require_once __DIR__ . '/../db/db.php';
class Category extends DB {
    public function all(){ return $this->connect()->query("SELECT * FROM categories ORDER BY cat_name")->fetchAll(); }
    public function add($name){ $stmt=$this->connect()->prepare("INSERT INTO categories (cat_name) VALUES (:n)"); try{ return $stmt->execute([':n'=>$name]); }catch(Exception $e){ return false; } }
}
