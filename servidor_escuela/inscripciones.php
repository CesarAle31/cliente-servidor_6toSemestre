<?php
require "conexion.php";
header("Content-Type: application/json");

$sql = "SELECT c.id AS curso_id, c.nombre AS curso, a.id AS alumno_id, a.nombre AS alumno, i.fecha_inscripción, i.id AS inscripcion_id
        FROM inscripciones i
        JOIN cursos c ON i.id_curso = c.id
        JOIN alumno a ON i.id_alumno = a.id
        ORDER BY c.id, i.fecha_inscripción";

$result = $conn->query($sql);

$cursos = [];
while ($row = $result->fetch_assoc()) {
    $curso_id = $row['curso_id'];
    if (!isset($cursos[$curso_id])) {
        $cursos[$curso_id] = [
            "curso" => $row['curso'],
            "alumnos" => []
        ];
    }
    $cursos[$curso_id]["alumnos"][] = [
        "id" => $row['alumno_id'],
        "nombre" => $row['alumno'],
        "fecha_inscripción" => $row['fecha_inscripción'],
        "inscripcion_id" => $row['inscripcion_id']
    ];
}

echo json_encode(array_values($cursos));
?>
