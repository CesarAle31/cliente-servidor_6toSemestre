<?php
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if (!$socket) die("Error al crear socket");

$connected = socket_connect($socket, $_POST['ip'], (int)$_POST['puerto']);
if (!$connected) die("No se pudo conectar al servidor");

$pedido = [
    "producto" => $_POST['producto'],
    "cantidad" => (int) $_POST['cantidad']
];
$msg = json_encode($pedido);
socket_write($socket, $msg, strlen($msg));

$respuesta = socket_read($socket, 2048);
socket_close($socket);
echo $respuesta;
?>
