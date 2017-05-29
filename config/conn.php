<?php
include_once ('../src/General.php');
$configDB = array(
    'servername' => "localhost",
    'username' => "root",
    'password' => "newyork-13",
    'baseName' => "Shop",
);

$conn = new mysqli($configDB['servername'], $configDB['username'], $configDB['password'], $configDB['baseName']);
if ($conn->connect_error) {
    die("Polaczenie nieudane. Blad: " . $conn->connect_error."<br>");
}

General::SetConnection($conn);
/*
Quiz::SetConnection($conn2);
Question::SetConnection($conn2);
Answer::SetConnection($conn2);
*/
?>
