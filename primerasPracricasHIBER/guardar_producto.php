<?php
//establecemos conexion a la BD
$conexion= new mysqli("localhost","root","","tienda");

if ($conexion->connect_error){
    die("error de conexion: ".$conexion->connect_error);
}

$nombre =$_POST['TXTnombre'];
$descripcion =$_POST['txtDescripcion'];
$precio =$_POST['TXTprecio'];

$sql="INSERT INTO productos (nombre,descripcion,precio) VALUES('$nombre','$descripcion','$precio')";

if ($conexion->query($sql) == TRUE){
    echo "producto aceptado correctamente";
    header("Refresh:2; url= index-tienda.php");
    exit;
}
else{echo "error: ".$conexion->error;
}
$conexion->close();

?>