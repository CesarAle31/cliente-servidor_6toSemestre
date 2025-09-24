<?php
//conexion.php
$host="localhost";
$user="root";
$pass="";
$bd="tienda";


$conn= new mysqli($host,$user,$pass,$bd);

if ($conn->connect_error){
    die("conexion fallida: ".$conn->connect_error);
}
?>