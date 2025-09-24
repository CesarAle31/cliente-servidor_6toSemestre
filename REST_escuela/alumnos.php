<?php
//escuela.php
header("Content-Type: application/json");
require 'conexion-escuela.php';


$metodoAlumno=$_SERVER['REQUEST_METHOD'];

    if ($metodoAlumno == 'GET'){
        $sql = "SELECT * FROM alumnos";
        $result = $conn->query($sql);
        $alumnos = [];

        while ($row = $result->fetch_assoc()){
            $alumnos[] = $row;
        }

        echo json_encode($alumnos);
    }




elseif($metodoAlumno=='POST') {
    $data = json_decode(file_get_contents("php://input"), TRUE);

    if (isset($data['nombre']) && isset($data['carrera']) && isset($data['correo'])) {
        $nombre = $data['nombre'];
        $carrera = $data['carrera'];
        $correo = $data['correo'];

        $sql = "INSERT INTO alumnos(nombre,carrera,correo) VALUES ('$nombre','$carrera','$correo')";

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




    if ($metodoAlumno == 'PUT') {

        $put_vars = json_decode(file_get_contents("php://input"), true);


        $id_alumnos = $put_vars['id_alumnos'];
        $nombre = $put_vars['nombre'];
        $carrera = $put_vars['carrera'];
        $correo = $put_vars['correo'];



        $sql = "UPDATE alumnos SET nombre = '$nombre', carrera = '$carrera', correo = '$correo' WHERE id_alumnos = $id_alumnos";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["mensaje" => "actualizado correctamente"]);
        } else {
            echo json_encode(["error" => "Error al actualizar: " . $conn->error]);
        }
    }





if ($metodoAlumno == 'DELETE') {


    $delete_vars = json_decode(file_get_contents("php://input"), true);


    $id_alumnos = $delete_vars['id_alumnos'];



    $sql = "DELETE FROM alumnos WHERE id_alumnos = $id_alumnos";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["mensaje" => " eliminado correctamente"]);
    } else {
        echo json_encode(["error" => "Error al eliminar: " . $conn->error]);
    }
}




?>
