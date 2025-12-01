<?php
// controllers/order_controller.php
require_once __DIR__ . '/../classes/Order.php';

function create_order_ctr($customer_id, $total_amount, $items = []) {
    $o = new Order();
    return $o->create($customer_id, $total_amount, $items);
}

function get_all_orders_ctr() {
    $o = new Order();
    return $o->getAll();
}

function get_order_ctr($order_id) {
    $o = new Order();
    return $o->getById($order_id);
}

function update_order_status_ctr($order_id, $status) {
    $o = new Order();
    return $o->updateStatus($order_id, $status);
}
