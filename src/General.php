<?php
include ('../config/conn.php');

class General {
    static private $conn;
    static private $data;
    public $post;
    public $files;

    public static function SetConnection($newConnection){
       General::$conn = $newConnection;
    }
    public function __construct()
    {
        $this->post = $_POST;
        $this->files = $_FILES;
    }

    public function getData(){
        return General::$data;
    }

    public function render(string $pathToFile, $data = [])
    {
        if(!empty($data)){
            General::$data = $data;
        }

        require ($pathToFile);
    }

    public function getConnection()
    {
        $conn = General::$conn;

        if ($conn->connect_error) {
            die("Polaczenie nieudane. Blad: " . $conn->connect_error."<br>");
        }

        return $conn;
    }


    public function is_post()
    {
        if(!empty($this->post)){
            return true;
        }

        return false;
    }

    public function redirect(string $destiny) {
        header('location:' . $destiny);
    }
    
    public function logout(){
        unset ($_SESSION['loggedUser']);
        unset ($_SESSION['loggedAdmin']);
        if (empty($_SESSION['loggedUser']) && empty($_SESSION['loggedAdmin'])){
            $this->render(Homepage::VIEW_PATH . 'home.php');
        }
    }
}
