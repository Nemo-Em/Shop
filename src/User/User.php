<?php

class User extends General
{
  /*
  public function index(){
    $this->render
  }
  */
  const VIEW_PATH = 'User/views/';

  public function register(){
      if($_SERVER['REQUEST_METHOD']=='POST'){
          if(isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['passwordVerify'])){
            if($_POST['password'] == $_POST['passwordVerify']){
              $name = $_POST['name'];
              $surname = $_POST['surname'];
              $email = $_POST['email'];
              $password = User::hashPassword($_POST['password']);
              $sqlStatement = "INSERT INTO Users(name, surname, email, password) values ('$name', '$surname', '$email', '$password')";
              $result = $this->getConnection()->query($sqlStatement);
              if ($result) {
                  $user = new User;
                  $user->setName($name)->setSurname($surname)->setEmail($email)->setPassword($password)->setId($this->getConnection()->insert_id);
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
            die("form filled out incorrectly!");
          }
      }
      elseif ($_SERVER['REQUEST_METHOD'] == 'GET'){
        $this->render(User::VIEW_PATH . 'register.html');
      }
  }

  public function login(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      if(isset($_POST['password']) && isset($_POST['email'])){
        $loadedUser = User::getByEmail($_POST['email']);
        $inputPassword = $_POST['password'];
        $userPassword = $loadedUser->getPassword();

        if(password_verify ($inputPassword,$userPassword)){
          $_SESSION['loggedUser'] = $loadedUser->getId();
          $this->render(User::VIEW_PATH . 'main.html');
        }
        else{
          die('incorrect password or email');
        }
      }
    }
    elseif ($_SERVER['REQUEST_METHOD'] == 'GET'){
      $this->render(User::VIEW_PATH . 'login.html');
    }
  }
  static public function logout(){
    unset ($_SESSION['loggedUser']);
  }
  public static function getByEmail($email){
    $user = new User;
    $sqlStatement = "SELECT * FROM Users WHERE email = '$email'";
    $result = $user->getConnection()->query($sqlStatement);
    if ($result) {
        $userData = $result->fetch_assoc();
        $user->setName($userData['name'])->setSurname($userData['surname'])->setEmail($userData['email'])
        ->setPassword($userData['password'])->setId(intVal($userData['id']));
        return $user;
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
