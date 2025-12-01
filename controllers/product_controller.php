<?php
// controllers/product_controller.php
require_once __DIR__ . '/../classes/Product.php';

function add_product_ctr($data) {
    $p = new Product();
    return $p->create($data);
}

function update_product_ctr($product_id, $data) {
    $p = new Product();
    return $p->update($product_id, $data);
}

function delete_product_ctr($product_id) {
    $p = new Product();
    return $p->delete($product_id);
}

function get_product_ctr($product_id) {
    $p = new Product();
    return $p->getById($product_id);
}

function get_all_products_ctr() {
    $p = new Product();
    return $p->all();
}

function get_products_by_vendor_ctr($vendor_id) {
    $p = new Product();
    return $p->byVendor($vendor_id);
}

function search_products_ctr($q) {
    $p = new Product();
    return $p->search($q);
}
