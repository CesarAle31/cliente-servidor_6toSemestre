<?php
$mysqli = new mysqli("localhost", "root", "", "tienda");
if ($mysqli->connect_error) {
    die(json_encode(["error" => "Conexión fallida: " . $mysqli->connect_error]));
}

$resultado = $mysqli->query("SELECT id, producto, cantidad, fecha FROM pedidos ORDER BY id DESC");
$pedidos = [];

while ($fila = $resultado->fetch_assoc()) {
    $pedidos[] = $fila;
}

header('Content-Type: application/json');
echo json_encode($pedidos);
?>
