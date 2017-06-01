<?php

if(!isset($_SESSION['loggedAdmin'])){
    die("<a href='/Shop/src/index.php'>Login or Register to view page</a>");
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<?php
include('NavBar.html');
?>
<div class="container">
    <div class="row">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <legend>Manage Products</legend>
            <ul>
                <?php
                $data = General::getData();
                foreach($data as $product){
                    echo "<li>".$product['name'] ." <b>price: </b>$". $product['price']
                        . ", <b>description: </b>" . $product['description'] ." <b>in stock:</b> ". $product['in_stock']
                        . " <a href='/Shop/src/index.php/admins/deleteProduct?id=".$product['id']."'>Delete</a></li>";
                }
                ?>
            </ul>
            <legend>Manage Product Stock</legend>
            <form action='/Shop/src/index.php/admins/updateStock' method='POST'>
                <b>Product</b>
                <select name='id' class="selectpicker" data-live-search="true">
                <?php
                foreach($data as $product){
                    echo '<option value="'.$product['id'].'">'.$product['name'].'</option>';
                }
                ?>
                </select>
                <b>Change stock amount by:</b>
                <input type='number' name='changeBy' min='-100' max='100'></input>
                <input type='submit' class='btn' value='change stock'></input>
            </form><br>
            <legend>Add Product</legend>
            <form action='/Shop/src/index.php/admins/addProduct' method='POST'>
                <b>Product Name:</b><br>
                <input type='text' name='name'></input><br>
                <b>Product Price</b><br>
                <input type='number' name='price' min='0'></input><br>
                <b>Product Description</b><br>
                <input type='text' name='descr'></input><br>
                <b>In stock:</b><br>
                <input type='number' name='inStock' min='0'></input><br>
                <input type='submit' class='btn' value='add product'></input><br>
            </form>
        </div>
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
    </div>
</div>

</body>
</html>