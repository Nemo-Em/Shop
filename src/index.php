<?php
session_start();
include 'General.php';
include 'Homepage/Homepage.php';
include 'Product/Product.php';
include 'User/User.php';
include 'Admin/Admin.php';

if(checkRout('products/add')) {
    $product = new Product();
    $product->add();
}elseif(checkRout('products/index')) {
    $product = new Product();
    $product->index();
}elseif(checkRout('users/register')) {
    $user = new User();
    $user->register();
}
elseif(checkRout('users/login')) {
    $user = new User();
    $user->login();
}
elseif(checkRout('users/panel')) {
    $user = new User();
    $user->index();
}
elseif(checkRout('users/orders')) {
    $user = new User();
    $user->viewOrders();
}
elseif(checkRout('admins/register')) {
    $admin = new Admin();
    $admin->register();
}
elseif(checkRout('admins/login')) {
    $admin = new Admin();
    $admin->login();
}
elseif(checkRout('admins/updateStock')) {
    $admin = new Admin();
    $admin->updateStock();
}
elseif(checkRout('admins/addProduct')) {
    $admin = new Admin();
    $admin->addProduct();
}
elseif(checkRout('admins/panel')) {
    $admin = new Admin();
    $admin->index();
}
elseif(checkRout('admins/manageUsers')) {
    $admin = new Admin();
    $admin->manageUsers();
}
elseif(checkRout('admins/deleteUser')) {
    $admin = new Admin();
    $admin->deleteUser();
}
elseif(checkRout('admins/manageProducts')) {
    $admin = new Admin();
    $admin->manageProducts();
}
elseif(checkRout('admins/manageOrders')) {
    $admin = new Admin();
    $admin->manageOrders();
}
elseif(checkRout('admins/deleteProduct')) {
    $admin = new Admin();
    $admin->deleteProduct();
}
elseif(checkRout('admins/deleteOrder')) {
    $admin = new Admin();
    $admin->deleteOrder();
}
elseif(checkRout('logout')) {
    $general = new General();
    $general->logout();
}
else {
    $homePage = new Homepage();
    $homePage->index();
}

function checkRout(string $route) {
    return strpos($_SERVER['REQUEST_URI'], $route);
}
