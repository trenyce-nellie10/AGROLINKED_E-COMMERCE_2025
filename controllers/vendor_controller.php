<?php
// controllers/vendor_controller.php
require_once __DIR__ . '/../classes/Vendor.php';
require_once __DIR__ . '/../classes/User.php';

function create_vendor_profile_ctr($vendor_id, $business_name, $description = null, $address = null) {
    $v = new Vendor();
    return $v->createProfile($vendor_id, $business_name, $description, $address);
}

function get_vendor_profile_ctr($vendor_id) {
    $v = new Vendor();
    return $v->getProfile($vendor_id);
}

function get_all_vendor_profiles_ctr() {
    $v = new Vendor();
    return $v->getAllProfiles();
}

function approve_vendor_ctr($vendor_id) {
    $u = new User();
    return $u->approveVendor($vendor_id);
}
