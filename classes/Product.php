<?php
// classes/Product.php
require_once __DIR__ . '/../db/db.php';
class Product extends DB {
    public function add($data){
        $sql = "INSERT INTO products (vendor_id,cat_id,brand_id,title,quantity,unit_price,description,image_path,grade) VALUES (:vendor,:cat,:brand,:title,:qty,:price,:desc,:img,:grade)";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([
            ':vendor'=>$data['vendor_id'], ':cat'=>$data['cat_id'], ':brand'=>$data['brand_id'] ?? null,
            ':title'=>$data['title'], ':qty'=>$data['quantity'] ?? null, ':price'=>$data['unit_price'],
            ':desc'=>$data['description'] ?? null, ':img'=>$data['image_path'] ?? null, ':grade'=>$data['grade'] ?? 'A'
        ]);
    }
    public function update($id,$data){
        $sql = "UPDATE products SET cat_id=:cat,brand_id=:brand,title=:title,quantity=:qty,unit_price=:price,description=:desc,image_path=:img,grade=:grade WHERE product_id=:id";
        $stmt = $this->connect()->prepare($sql);
        return $stmt->execute([':cat'=>$data['cat_id'],':brand'=>$data['brand_id'] ?? null,':title'=>$data['title'],':qty'=>$data['quantity'] ?? null,':price'=>$data['unit_price'],':desc'=>$data['description'] ?? null,':img'=>$data['image_path'] ?? null,':grade'=>$data['grade'] ?? 'A',':id'=>$id]);
    }
    public function all(){ return $this->connect()->query("SELECT p.*, u.full_name as vendor_name, c.cat_name, b.brand_name FROM products p JOIN users u ON p.vendor_id=u.user_id JOIN categories c ON p.cat_id=c.cat_id LEFT JOIN brands b ON p.brand_id=b.brand_id ORDER BY p.created_at DESC")->fetchAll(); }
    public function get($id){ $stmt=$this->connect()->prepare("SELECT p.*, u.full_name as vendor_name, c.cat_name, b.brand_name FROM products p JOIN users u ON p.vendor_id=u.user_id JOIN categories c ON p.cat_id=c.cat_id LEFT JOIN brands b ON p.brand_id=b.brand_id WHERE p.product_id=:id LIMIT 1"); $stmt->execute([':id'=>$id]); return $stmt->fetch(); }
    public function search($q){ $q = "%{$q}%"; $stmt = $this->connect()->prepare("SELECT p.*, c.cat_name, b.brand_name FROM products p JOIN categories c ON p.cat_id=c.cat_id LEFT JOIN brands b ON p.brand_id=b.brand_id WHERE p.title LIKE :q OR p.description LIKE :q OR p.keywords LIKE :q ORDER BY p.created_at DESC"); $stmt->execute([':q'=>$q]); return $stmt->fetchAll(); }
    public function byVendor($vendor_id){ $stmt = $this->connect()->prepare("SELECT * FROM products WHERE vendor_id=:v ORDER BY created_at DESC"); $stmt->execute([':v'=>$vendor_id]); return $stmt->fetchAll(); }
}
