<?php
if(!isset($_SESSION['loggedUser'])){
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
    <title>Shop</title>
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
            <legend>All Orders</legend>
            <ul>
                <?php
                $data = General::getData();
                foreach($data as $order){
                    echo "<span><b>Order Id: ".$order['orderId'] ."</b></span>"
                        . "<p> name: " . $order['productName'] . "</p>"
                        . "<p> price: $" . $order['price'] . "</p>"
                        . "<p> description: " . $order['descr'] . "</p>"
                        . "<p> status :". $order['status'] . "</p>"
                        . "<hr>";
                }
                ?>
            </ul>
            <br><br><a href='/Shop/src/index.php/users/panel'>Back to panel</a>
        </div>
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
    </div>
</div>

</body>
</html>