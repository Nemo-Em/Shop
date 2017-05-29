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
      if($this->is_post()){
          if(true){
              $name = $this->post['name'];
              $surname = $this->post['surname'];
              $price = $this->post['email'];
              $password = $this->post['password'];

              $sqlStatement = "INSERT INTO Users(name, surname, email, password) values ('$name', '$surname', '$email', '$password')";
              $result = $this->getConnection()->query($sqlStatement);

              if ($result) {
                  $user = new User;
                  $user->setName($name)->setSurname($surname)->setEmail($email)->setPassword($password)->setId($this->getConnection()->insert_id);
                  $user->login($email, $password);
                  $this->redirect('/users/index');
              }
          }
          return false;
      }
      $this->render(User::VIEW_PATH . 'register.html');
  }

  public function login($email, $password){
    $user = User::getByEmail($email);
    if($user_verifyPassword($password)){
      $_SESSION['loggedUser'] = $this->id;
    }
    else{
      die('incorrect password or email');
    }
  }
  static public function logout(){
    unset ($_SESSION['loggedUser']);
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
  public function setHashedPassword($password){
    $this->password = password_hash($password, PASSWORD_BCRYPT);
    return $this;
  }
  public function verifyPassword($password){
    password_verify ($password , $this->password);
  }
}
