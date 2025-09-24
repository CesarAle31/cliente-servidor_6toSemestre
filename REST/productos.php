<?php
//productos.php
header("Content-Type: application/json");
require "conexion.php";


$metodo=$_SERVER['REQUEST_METHOD'];

    if ($metodo == 'GET'){
        $sql = "SELECT * FROM productos";
        $result = $conn->query($sql);
        $productos = [];

        while ($row = $result->fetch_assoc()){
            $productos[] = $row;
        }

        echo json_encode($productos);
    }
    elseif($metodo=='POST') {
        $data = json_decode(file_get_contents("php://input"), TRUE);

        if (isset($data['nombre']) && isset($data['descripcion']) && isset($data['precio'])) {
            $nombre = $data['nombre'];
            $desc = $data['descripcion'];
            $precio = $data['precio'];

            $sql = "INSERT INTO productos(nombre,descripcion,precio) VALUES ('$nombre','$desc',$precio)";

            if ($conn->query($sql)) {
                echo json_encode(["mensaje" => "producto agregado"]);
            }

            else {
                echo json_encode(["error" => "error al guardar"]);
            }

        }
        else{
            echo json_encode(["error" => "faltan datos"]);
        }
    }

if ($metodo == 'PUT') {

    $put_vars = json_decode(file_get_contents("php://input"), true);


    $id = $put_vars['id'];
    $nombre = $put_vars['nombre'];
    $descripcion = $put_vars['descripcion'];
    $precio = $put_vars['precio'];



    $sql = "UPDATE productos SET nombre = '$nombre', descripcion = '$descripcion', precio = $precio WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["mensaje" => "Producto actualizado correctamente"]);
    } else {
        echo json_encode(["error" => "Error al actualizar el producto: " . $conn->error]);
    }
}



if ($metodo == 'DELETE') {


    $delete_vars = json_decode(file_get_contents("php://input"), true);


    $id = $delete_vars['id'];



    $sql = "DELETE FROM productos WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["mensaje" => "Producto eliminado correctamente"]);
    } else {
        echo json_encode(["error" => "Error al eliminar el producto: " . $conn->error]);
    }
}











    $conn->close();
?>