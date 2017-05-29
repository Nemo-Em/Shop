<?php

include 'General.php';
include 'Homepage/Homepage.php';
include 'Product/Product.php';
include 'User/User.php';

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
else {
    $homePage = new Homepage();
    $homePage->index();
}

function checkRout(string $route) {
    return strpos($_SERVER['REQUEST_URI'], $route);
}
