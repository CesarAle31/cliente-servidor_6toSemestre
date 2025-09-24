<?php
//establecemos conexion a la BD
$conexion= new mysqli("localhost","root","","tienda");


if ($conexion->connect_error){
    die("error de conexion: ".$conexion->connect_error);
}


$usuario =$_POST['txtUsuario'];
$password =$_POST['txtPassword'];


$sql="INSERT INTO usuarios (usuario, password) VALUES('$usuario','$password')";



if ($conexion->query($sql) == TRUE){
    echo "registrado correctamente";
    header("Refresh:2; url= index-tienda.php");
    exit;
}
else{echo "usuario o password ya existentes: ".$conexion->error;
}
$conexion->close();


?>