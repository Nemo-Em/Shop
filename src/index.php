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
elseif(checkRout('users/logout')) {
    $user = new User();
    $user->logout();
}
elseif(checkRout('admins/register')) {
    $admin = new Admin();
    $admin->register();
}
elseif(checkRout('admins/login')) {
    $admin = new Admin();
    $admin->login();
}
elseif(checkRout('admins/logout')) {
    $admin = new Admin();
    $admin->logout();
}
else {
    $homePage = new Homepage();
    $homePage->index();
}

function checkRout(string $route) {
    return strpos($_SERVER['REQUEST_URI'], $route);
}
