<?php
// classes/Brand.php
require_once __DIR__ . '/../db/db.php';
class Brand extends DB {
    public function all(){ return $this->connect()->query("SELECT b.*, c.cat_name FROM brands b JOIN categories c ON b.cat_id=c.cat_id ORDER BY c.cat_name, b.brand_name")->fetchAll(); }
    public function add($name,$cat_id){ $stmt=$this->connect()->prepare("INSERT INTO brands (brand_name,cat_id) VALUES (:n,:cid)"); try{ return $stmt->execute([':n'=>$name,':cid'=>$cat_id]); }catch(Exception $e){ return false; } }
}
