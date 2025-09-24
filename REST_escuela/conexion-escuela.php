<?php
//conexion.php
$host="localhost";
$user="root";
$pass="";
$bd="escuela";


$conn= new mysqli($host,$user,$pass,$bd);

if ($conn->connect_error){
    die("conexion fallida: ".$conn->connect_error);
}

?>
