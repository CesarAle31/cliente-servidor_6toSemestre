<?php
//escuela.php
header("Content-Type: application/json");
require 'conexion-escuela.php';


$metodoMateria=$_SERVER['REQUEST_METHOD'];

if ($metodoMateria == 'GET'){
    $sql = "SELECT * FROM materia";
    $result = $conn->query($sql);
    $materia = [];

    while ($row = $result->fetch_assoc()){
        $materia[] = $row;
    }

    echo json_encode($materia);
}






elseif($metodoMateria=='POST') {
    $data = json_decode(file_get_contents("php://input"), TRUE);

    if (isset($data['nombre']) && isset($data['profesor']) && isset($data['creditos'])) {
        $nombre = $data['nombre'];
        $profesor = $data['profesor'];
        $creditos = $data['creditos'];

        $sql = "INSERT INTO materia(nombre,profesor,creditos) VALUES ('$nombre','$profesor','$creditos')";

        if ($conn->query($sql)) {
            echo json_encode(["mensaje" => "agregado"]);
        }

        else {
            echo json_encode(["error" => "error al guardar"]);
        }

    }
    else{
        echo json_encode(["error" => "faltan datos"]);
    }
}


if ($metodoMateria == 'PUT') {

$put_vars = json_decode(file_get_contents("php://input"), true);


$id_materia = $put_vars['id_materia'];
$nombre = $put_vars['nombre'];
$profesor = $put_vars['profesor'];
$creditos = $put_vars['creditos'];



$sql = "UPDATE materia SET nombre = '$nombre', profesor = '$profesor', creditos = '$creditos' WHERE id_materia = $id_materia";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["mensaje" => "actualizado correctamente"]);
} else {
    echo json_encode(["error" => "Error al actualizar: " . $conn->error]);
}
}





if ($metodoMateria == 'DELETE') {


    $delete_vars = json_decode(file_get_contents("php://input"), true);


    $id_materia = $delete_vars['id_materia'];



    $sql = "DELETE FROM materia WHERE id_materia = $id_materia";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["mensaje" => " eliminado correctamente"]);
    } else {
        echo json_encode(["error" => "Error al eliminar: " . $conn->error]);
    }
}






?>