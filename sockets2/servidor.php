<?php
$mysqli = new mysqli("localhost", "root", "", "tienda");

if ($mysqli->connect_error) {
    die("Error de la conexión a la BD: " . $mysqli->connect_error);
}

// establecer socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    die("No se pudo crear el socket: " . socket_strerror(socket_last_error()));
}

if (!socket_bind($socket, "127.0.0.1", 7080)) {
    die("No se pudo hacer bind al socket: " . socket_strerror(socket_last_error($socket)));
}

if (!socket_listen($socket)) {
    die("No se pudo poner el socket en modo escucha: " . socket_strerror(socket_last_error($socket)));
}

echo "Servidor de pedidos activo en puerto 7080...\n";

while (true) {
    $cliente = socket_accept($socket);
    if ($cliente === false) {
        echo "No se pudo aceptar la conexión: " . socket_strerror(socket_last_error($socket)) . "\n";
        continue;
    }

    $datos = socket_read($cliente, 2048);
    $pedido = json_decode($datos, true);

    if (isset($pedido['producto']) && isset($pedido['cantidad'])) {
        $stmt = $mysqli->prepare("INSERT INTO pedidos(producto, cantidad) VALUES (?, ?)");
        if ($stmt) {
            $stmt->bind_param("si", $pedido['producto'], $pedido['cantidad']);
            $stmt->execute();
            $numero_orden = $stmt->insert_id;
            $respuesta = "pedido recibido, numero de orden: " . $numero_orden;
            $stmt->close();
        } else {
            $respuesta = "error: no se pudo preparar la consulta";
        }
    } else {
        $respuesta = "error: datos invalidos";
    }
    socket_write($cliente, $respuesta, strlen($respuesta));
    socket_close($cliente);
}

socket_close($socket);
?>
