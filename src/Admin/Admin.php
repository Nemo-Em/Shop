<?php

class Admin extends General
{
  /*
  public function index(){
    $this->render
  }
  */
  const VIEW_PATH = 'Admin/views/';

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
        $this->render(Admin::VIEW_PATH . 'register.html');
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
          $this->render(Admin::VIEW_PATH . 'index.html');
        }
        else{
          die('incorrect password or email');
        }
      }
    }
    elseif ($_SERVER['REQUEST_METHOD'] == 'GET'){
      $this->render(Admin::VIEW_PATH . 'login.html');
    }
  }
  public function logout(){
    unset ($_SESSION['loggedAdmin']);
    if (empty($_SESSION['loggedAdmin'])){
      $this->redirect (Homepage::VIEW_PATH . 'homepage.html');
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
