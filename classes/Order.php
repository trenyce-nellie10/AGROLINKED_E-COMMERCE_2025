<?php
// classes/Order.php
require_once __DIR__ . '/../db/db.php';
class Order extends DB {
    public function create($buyer_id,$hub_id,$total,$delivery_fee,$mobile,$payment_method='momo'){
        $stmt = $this->connect()->prepare("INSERT INTO orders (buyer_id,hub_id,total,delivery_fee,mobile_number,payment_method) VALUES (:b,:h,:t,:d,:m,:pm)");
        $ok = $stmt->execute([':b'=>$buyer_id,':h'=>$hub_id,':t'=>$total,':d'=>$delivery_fee,':m'=>$mobile,':pm'=>$payment_method]);
        if ($ok) return $this->connect()->lastInsertId();
        return false;
    }
    public function addItem($order_id,$product_id,$vendor_id,$qty,$price){
        $stmt = $this->connect()->prepare("INSERT INTO order_items (order_id,product_id,vendor_id,qty,price) VALUES (:o,:p,:v,:q,:pr)");
        return $stmt->execute([':o'=>$order_id,':p'=>$product_id,':v'=>$vendor_id,':q'=>$qty,':pr'=>$price]);
    }
    public function getByBuyer($buyer_id){ $stmt=$this->connect()->prepare("SELECT * FROM orders WHERE buyer_id=:b ORDER BY created_at DESC"); $stmt->execute([':b'=>$buyer_id]); return $stmt->fetchAll(); }
}
