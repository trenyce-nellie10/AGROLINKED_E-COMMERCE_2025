<?php
// classes/Order.php
require_once __DIR__ . '/../db/db.php';

class Order extends DB {
    public function create($customer_id, $total_amount, $order_items = [], $status = 'Pending') {
        $pdo = $this->connect();
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO orders (customer_id, order_status, total_amount) VALUES (:cid, :status, :total)");
            $stmt->execute([':cid' => $customer_id, ':status' => $status, ':total' => $total_amount]);
            $order_id = $pdo->lastInsertId();

            $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_each) VALUES (:oid, :pid, :qty, :price)");
            foreach ($order_items as $it) {
                $itemStmt->execute([
                    ':oid' => $order_id,
                    ':pid' => $it['product_id'],
                    ':qty' => $it['quantity'],
                    ':price' => $it['price_each']
                ]);
            }

            $pdo->commit();
            return $order_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }

    public function getAll() {
        $stmt = $this->connect()->query("SELECT o.*, u.full_name AS customer_name FROM orders o LEFT JOIN users u ON o.customer_id = u.user_id ORDER BY o.order_date DESC");
        return $stmt->fetchAll();
    }

    public function getById($order_id) {
        $stmt = $this->connect()->prepare("SELECT o.*, u.full_name AS customer_name FROM orders o LEFT JOIN users u ON o.customer_id = u.user_id WHERE o.order_id = :oid LIMIT 1");
        $stmt->execute([':oid' => $order_id]);
        $order = $stmt->fetch();
        if ($order) {
            $items = $this->connect()->prepare("SELECT oi.*, p.product_name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.product_id WHERE oi.order_id = :oid");
            $items->execute([':oid' => $order_id]);
            $order['items'] = $items->fetchAll();
        }
        return $order;
    }

    public function updateStatus($order_id, $status) {
        $stmt = $this->connect()->prepare("UPDATE orders SET order_status = :status WHERE order_id = :oid");
        return $stmt->execute([':status' => $status, ':oid' => $order_id]);
    }
}
