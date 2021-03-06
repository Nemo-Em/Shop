<?php

class User extends General
{
  const VIEW_PATH = 'User/views/';
  
  public function index(){
     $this->render(User::VIEW_PATH . 'panel.php');
  }

  public function register(){
      if($_SERVER['REQUEST_METHOD']=='POST'){
          if(!empty($_POST['name']) && !empty($_POST['surname']) && !empty($_POST['email']) &&!empty($_POST['address']) && !empty($_POST['password']) && !empty($_POST['passwordVerify'])){
            if($_POST['password'] == $_POST['passwordVerify']){
              $name = $_POST['name'];
              $surname = $_POST['surname'];
              $email = $_POST['email'];
              $address = $_POST['address'];
              $password = User::hashPassword($_POST['password']);
              $sqlStatement = "INSERT INTO Users(name, surname, email, address, password) values ('$name', '$surname', '$email', '$address', '$password')";
              $result = $this->getConnection()->query($sqlStatement);
              if ($result) {
                  $user = new User;
                  $user->setName($name)->setSurname($surname)->setEmail($email)
                          ->setAddress($address)->setPassword($password)->setId($this->getConnection()->insert_id);
                  $this->render(User::VIEW_PATH . 'login.html');
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
        $this->render(User::VIEW_PATH . 'register.php');
      }
  }

  public function login(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      if(!empty($_POST['password']) && !empty($_POST['email'])){
        $loadedUser = User::getByEmail($_POST['email']);
        if($loadedUser == null){
          die ("no user with this email");
        }
        $inputPassword = $_POST['password'];
        $userPassword = $loadedUser->getPassword();

        if(password_verify ($inputPassword,$userPassword)){
          $_SESSION['loggedUser'] = $loadedUser->getId();
          $_SESSION['userType'] = 'USER';
          $this->render(User::VIEW_PATH . 'panel.php');
        }
        else{
          die('incorrect password or email');
        }
      }
    }
    elseif ($_SERVER['REQUEST_METHOD'] == 'GET'){
      $this->render(User::VIEW_PATH . 'login.php');
    }
  }
  public static function getByEmail($email){
    $user = new User;
    $sqlStatement = "SELECT * FROM Users WHERE email = '$email'";
    $result = $user->getConnection()->query($sqlStatement);
    if ($result->num_rows==1) {
        $userData = $result->fetch_assoc();
        $user->setName($userData['name'])->setSurname($userData['surname'])->setEmail($userData['email'])
        ->setPassword($userData['password'])->setId(intVal($userData['id']));
        return $user;
    }
    else{
      return null;
    }
  }
  public function viewOrders(){
    $id = intval($_SESSION['loggedUser']);
    $result = $this->getConnection()->query("SELECT *,p.id as productId, o.id as orderId, p.name as productName, u.id as userId"             
            . " FROM Users u"
            . " JOIN Orders o ON o.user_id = u.id"
            . " JOIN Products p ON o.product_id = p.id"
            . " WHERE u.id = $id");
    $data =[];
    $i=0;
        foreach ($result as $row) {
            $data[$i]['orderId'] = $row['orderId'];
            $data[$i]['productName'] = $row['productName'];
            $data[$i]['price'] = $row['price'];
            $data[$i]['status'] = $row['status'];
            $data[$i]['descr'] = $row['description'];
            $i++;
        }
    $this->render(User::VIEW_PATH . 'orders.php',  $data);
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
  public function getName(){
    return $this->name;
  }
  public function setName($name){
    if (is_string($name)){
      $this->name=$name;
      return $this;
    }
  }
  public function getSurname(){
    return $this->surname;
  }
  public function setSurname($surname){
    if (is_string($surname)){
      $this->surname=$surname;
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
    public function getAddress(){
    return $this->address;
  }
  public function setAddress($address){
    if (is_string($address)){
      $this->address=$address;
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
