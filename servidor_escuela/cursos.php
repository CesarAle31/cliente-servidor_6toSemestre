<?php
require "conexion.php";
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Leer todos los cursos o uno específico si se pasa id
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $stmt = $conn->prepare("SELECT * FROM cursos WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_assoc());
        } else {
            $result = $conn->query("SELECT * FROM cursos");
            $data = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($data);
        }
        break;

    case 'POST':
        // Crear nuevo curso
        $input = json_decode(file_get_contents("php://input"), true);
        $nombre = $input['nombre'] ?? null;
        $capacidad = $input['capacidad'] ?? null;

        if ($nombre && $capacidad !== null) {
            $stmt = $conn->prepare("INSERT INTO cursos (nombre, capacidad) VALUES (?, ?)");
            $stmt->bind_param("si", $nombre, $capacidad);
            $stmt->execute();
            echo json_encode(["mensaje" => "Curso creado con éxito"]);
        } else {
            echo json_encode(["error" => "Faltan datos"]);
        }
        break;

    case 'PUT':
        // Actualizar curso existente
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id'] ?? null;
        $nombre = $input['nombre'] ?? null;
        $capacidad = $input['capacidad'] ?? null;

        if ($id && $nombre && $capacidad !== null) {
            $stmt = $conn->prepare("UPDATE cursos SET nombre = ?, capacidad = ? WHERE id = ?");
            $stmt->bind_param("sii", $nombre, $capacidad, $id);
            $stmt->execute();
            echo json_encode(["mensaje" => "Curso actualizado"]);
        } else {
            echo json_encode(["error" => "Faltan datos o ID"]);
        }
        break;

    case 'DELETE':
        // Eliminar un curso
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id'] ?? null;

        if ($id) {
            $stmt = $conn->prepare("DELETE FROM cursos WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            echo json_encode(["mensaje" => "Curso eliminado"]);
        } else {
            echo json_encode(["error" => "Falta el ID"]);
        }
        break;

    default:
        echo json_encode(["error" => "Método no soportado"]);
        break;
}
?>


