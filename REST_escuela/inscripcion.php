<?php
//escuela.php
header("Content-Type: application/json");
require 'conexion-escuela.php';


$metodoInscripcion=$_SERVER['REQUEST_METHOD'];

if ($metodoInscripcion == 'GET'){
    $sql = "SELECT * FROM inscripcion";
    $result = $conn->query($sql);
    $inscripcion = [];

    while ($row = $result->fetch_assoc()){
        $inscripcion[] = $row;
    }

    echo json_encode($inscripcion);
}



//elseif($metodoInscripcion=='POST') {
//    $data = json_decode(file_get_contents("php://input"), TRUE);
//
//    if (isset($data['id_alumnos']) && isset($data['id_materia']) && isset($data['fecha_incripcion'])) {
//        $id_alumnos = $data['id_alumnos'];
//        $id_materia = $data['id_materia'];
//        $fecha_incripcion = $data['fecha_incripcion'];
//
//        $sql = "INSERT INTO inscripcion(id_alumnos,id_materia,fecha_incripcion) VALUES ('$id_alumnos','$id_materia','$fecha_incripcion')";
//
//        if ($conn->query($sql)) {
//            echo json_encode(["mensaje" => "agregado"]);
//        }
//
//        else {
//            echo json_encode(["error" => "error al guardar"]);
//        }
//
//    }
//    else{
//        echo json_encode(["error" => "faltan datos"]);
//    }
//}
if ($metodoInscripcion == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id_alumno']) && isset($data['id_materia']) && isset($data['fecha_incripcion'])) {
        $id_alumno = $data['id_alumno'];
        $id_materia = $data['id_materia'];
        $fecha_incripcion = $data['fecha_incripcion'];

      
        $stmt = $conn->prepare("INSERT INTO inscripcion (id_alumno, id_materia, fecha_incripcion) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $id_alumno, $id_materia, $fecha_incripcion);

        if ($stmt->execute()) {
            echo json_encode(["mensaje" => "agregado"]);
        } else {
            echo json_encode(["error" => "error al guardar"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "faltan datos"]);
    }
}






?>
