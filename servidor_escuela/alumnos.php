<?php
require "conexion.php";
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $result = $conn->query("SELECT * FROM alumno");
        $data = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($data);
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);
        $nombre = $input['nombre'];
        $correo = $input['correo'];
        $usuario = $input['usuario'];
        $password = $input['password'];

        $stmt = $conn->prepare("INSERT INTO alumno (nombre, correo, usuario, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $correo, $usuario, $password);
        $stmt->execute();

        echo json_encode(["mensaje" => "Alumno creado"]);
        break;

    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id'];
        $nombre = $input['nombre'];
        $correo = $input['correo'];
        $usuario = $input['usuario'];
        $password = $input['password'];

        $stmt = $conn->prepare("UPDATE alumno SET nombre=?, correo=?, usuario=?, password=? WHERE id=?");
        $stmt->bind_param("ssssi", $nombre, $correo, $usuario, $password, $id);
        $stmt->execute();

        echo json_encode(["mensaje" => "Alumno actualizado"]);
        break;

    case 'DELETE':
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id'];

        $stmt = $conn->prepare("DELETE FROM alumno WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        echo json_encode(["mensaje" => "Alumno eliminado"]);
        break;
}
?>
