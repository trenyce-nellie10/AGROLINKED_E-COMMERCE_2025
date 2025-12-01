<?php
// controllers/cart_controller.php
require_once __DIR__ . '/../classes/Cart.php';

function add_to_cart_ctr($customer_id, $product_id, $quantity) {
    $c = new Cart();
    return $c->add($customer_id, $product_id, $quantity);
}

function get_cart_for_customer_ctr($customer_id) {
    $c = new Cart();
    return $c->getForCustomer($customer_id);
}

function clear_cart_ctr($customer_id) {
    $c = new Cart();
    return $c->clear($customer_id);
}

function remove_cart_item_ctr($cart_id) {
    $c = new Cart();
    return $c->removeItem($cart_id);
}
