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
<div class="container">
    <div class="row">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <legend>Manage Users</legend>
            <ul>
                <?php
                $data = General::getData();
                foreach($data as $user){
                    echo "<li>".$user['name'] ." ". $user['surname']
                        . ", " . $user['email'] ." ". $user['address']
                        . " <a href='/Shop/src/index.php/admins/deleteUser'>Delete</a></li>";
                }
                ?>
            </ul>
            <br><br><a href='/Shop/src/index.php/admins/panel'>Back to main page</a>
        </div>
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
    </div>
</div>

</body>
</html>