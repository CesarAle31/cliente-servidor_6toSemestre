<?php
$ip = "127.0.0.1";       // Cambia esto si tu servidor no está en localhost
$puerto = 7080;          // Asegúrate de usar el mismo puerto que usa tu servidor

echo "🔍 Intentando conectar a $ip:$puerto...\n";

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if (!$socket) {
    die("❌ No se pudo crear el socket: " . socket_strerror(socket_last_error()) . "\n");
}

$resultado = @socket_connect($socket, $ip, $puerto);
if (!$resultado) {
    die("❌ Falló la conexión: " . socket_strerror(socket_last_error($socket)) . "\n");
}

echo "✅ Conexión exitosa. El servidor está escuchando.\n";

socket_close($socket);
?>

