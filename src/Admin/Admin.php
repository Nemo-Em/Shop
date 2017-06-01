<?php

class Admin extends General
{

  const VIEW_PATH = 'Admin/views/';
  private $email;
  private $password;
  
  public function index(){
     $this->render(Admin::VIEW_PATH . 'panel.php');
  }

  public function register(){
      if($_SERVER['REQUEST_METHOD']=='POST'){
          if(!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['passwordVerify'])){
            if($_POST['password'] == $_POST['passwordVerify']){
              $email = $_POST['email'];
              $password = Admin::hashPassword($_POST['password']);
              $sqlStatement = "INSERT INTO Admins(email, password) values ('$email', '$password')";
              $result = $this->getConnection()->query($sqlStatement);
              if ($result) {
                  $admin = new Admin;
                  $admin->setEmail($email)->setPassword($password)->setId($this->getConnection()->insert_id);
                  $this->render(Admin::VIEW_PATH . 'login.html');
              }
              else{
                die("registration failed!");
              }
            }
            else{
              die("passwords don't match!");
            }
          }
          else{
            die("form incomplete!");
          }
      }
      elseif ($_SERVER['REQUEST_METHOD'] == 'GET'){
        $this->render(Admin::VIEW_PATH . 'register.php');
      }
  }

  public function login(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      if(!empty($_POST['password']) && !empty($_POST['email'])){
        $loadedAdmin = Admin::getByEmail($_POST['email']);
        if($loadedAdmin == null){
          die ("no admin with this email");
        }
        $inputPassword = $_POST['password'];
        $adminPassword = $loadedAdmin->getPassword();

        if(password_verify ($inputPassword,$adminPassword)){
          $_SESSION['loggedAdmin'] = $loadedAdmin->getId();
          $this->render(Admin::VIEW_PATH . 'panel.php');
        }
        else{
          die('incorrect password or email');
        }
      }
    }
    elseif ($_SERVER['REQUEST_METHOD'] == 'GET'){
      $this->render(Admin::VIEW_PATH . 'login.php');
    }
  }
  public static function getByEmail($email){
    $admin = new Admin;
    $sqlStatement = "SELECT * FROM Admins WHERE email = '$email'";
    $result = $admin->getConnection()->query($sqlStatement);
    if ($result->num_rows==1) {
        $adminData = $result->fetch_assoc();
        $admin->setEmail($adminData['email'])
        ->setPassword($adminData['password'])->setId(intVal($adminData['id']));
        return $admin;
    }
    else{
      return null;
    }
  }
  
  public function manageUsers(){
      $result = $this->getConnection()->query('SELECT * FROM Users');
      $data =[];
      $i=0;
        foreach ($result as $row) {
            $data[$i]['id'] = $row['id'];
            $data[$i]['name'] = $row['name'];
            $data[$i]['surname'] = $row['surname'];
            $data[$i]['email'] = $row['email'];
            $data[$i]['address'] = $row['address'];
            $i++;
        }
        $this->render(Admin::VIEW_PATH . 'manageUsers.php',  $data);
  }
  public function deleteUser(){
      if($_SERVER['REQUEST_METHOD']=='GET'){
        if (!empty($_GET['id'])){
            $id=intval($_GET['id']);
           $result = $this->getConnection()->query("DELETE FROM Users WHERE id = $id");
           if($result == true){
               $this->redirect ('admins/manageProducts');
           }
        }    
      }
  }
   public function manageProducts(){
      $result = $this->getConnection()->query('SELECT * FROM Products');
      $data =[];
      $i=0;
        foreach ($result as $row) {
            $data[$i]['id'] = $row['id'];
            $data[$i]['name'] = $row['name'];
            $data[$i]['price'] = $row['price'];
            $data[$i]['description'] = $row['description'];
            $data[$i]['in_stock'] = $row['in_stock'];
            $i++;
        }
        $this->render(Admin::VIEW_PATH . 'manageProducts.php',  $data);
  }
  public function deleteProduct(){
      if($_SERVER['REQUEST_METHOD']=='GET'){
        if (!empty($_GET['id'])){
            $id=intval($_GET['id']);
           $result = $this->getConnection()->query("DELETE FROM Products WHERE id = $id");
           if($result == true){
               $this->redirect ('admins/manageProducts');
           }
        }    
      }
  }
  public function updateStock(){
      if($_SERVER['REQUEST_METHOD']=='POST'){
        if (!empty($_POST['id']) && !empty($_POST['changeBy'])){
           $id=intval($_POST['id']);
           $changeBy=intval($_POST['changeBy']);
           $result = $this->getConnection()->query("SELECT * FROM Products WHERE id = $id");
           $stock = 0;
           foreach($result as $row){
              $stock = intval($row['in_stock']); 
           }
           $newStock = $stock + intval($changeBy);
           if($newStock >=0){
               $result = $this->getConnection()->query("UPDATE Products SET in_stock = $newStock WHERE id = $id");
               if ($result == true){
                    $this->redirect ('admins/manageProducts');
                }
           }
           else{
               $this->redirect ('admins/manageProducts');
           }
        }    
      }
  }
  public function addProduct(){
      if($_SERVER['REQUEST_METHOD']=='POST'){
        if (!empty($_POST['name']) && !empty($_POST['price']) && !empty($_POST['descr']) && !empty($_POST['inStock'])){
           $name=$_POST['name'];
           $price=intval($_POST['price']);
           $descr=$_POST['descr'];
           $inStock=intval($_POST['inStock']);
           $result = $this->getConnection()->query("INSERT INTO Products (name, price, description, in_stock)"
                   . " VALUES ('$name', $price, '$descr', $inStock)");

            if ($result == true){
                $this->redirect ('admins/manageProducts');
            }
            else{
               var_dump($this->getConnection()->error);
               //$this->redirect ('admins/manageProducts');
           }
        }    
      }
  }
  public function manageOrders(){
    $result = $this->getConnection()->query("SELECT *,p.id as productId, o.id as orderId,"
            . " p.name as productName, u.id as userId, u.name as userName FROM Orders o"
            . " JOIN Users u ON o.user_id = u.id"
            . " JOIN Products p ON o.product_id = p.id GROUP BY o.id");
    $data =[];
    $i=0;
        foreach ($result as $row) {
            $data[$i]['userName'] = $row['userName'];
            $data[$i]['surname'] = $row['surname'];
            $data[$i]['address'] = $row['address'];
            $data[$i]['orderId'] = $row['orderId'];
            $data[$i]['productName'] = $row['productName'];
            $data[$i]['price'] = $row['price'];
            $data[$i]['status'] = $row['status'];
            $data[$i]['descr'] = $row['description'];
            $i++;
        }
    $this->render(Admin::VIEW_PATH . 'manageOrders.php',  $data);
  }
   public function deleteOrder(){
      if($_SERVER['REQUEST_METHOD']=='GET'){
        if (!empty($_GET['id'])){
            $id=intval($_GET['id']);
           $result = $this->getConnection()->query("DELETE FROM Orders WHERE id = $id");
           if($result == true){
               $this->redirect ('admins/manageOrders');
           }
        }    
      }
  }
  public function getID(){
    return $this->id;
  }
  public function setID($id){
    if (is_integer($id) && $id>=0){
      $this->id=$id;
      return $this;
    }
  }
  public function getEmail(){
    return $this->email;
  }
  public function setEmail($email){
    if (is_string($email)){
      $this->email=$email;
      return $this;
    }
  }
  public function getPassword(){
    return $this->password;
  }
  public function setPassword($password){
    if (is_string($password)){
      $this->password=$password;
      return $this;
    }
  }
  public static function hashPassword($password){
    return password_hash($password, PASSWORD_BCRYPT);
  }
}
