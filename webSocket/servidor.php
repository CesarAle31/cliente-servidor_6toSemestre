<?php

//carpeta llamada websocket y crear archivo.php
$host = '127.0.0.1';
$port = 9595;
$cliente = [];
$server = stream_socket_server("tcp://$host:$port",$errno,$errstr);
if (!$server) {
    die("Error: $errstr($errno)\n");
}
echo "webSocket server stared on $host:$port\n";
while (true) {
    $read = $cliente;
    $read[] = $server;
    stream_select($read, $write, $except, 0, 10 );
    if(in_array($server,$read)){
        $cliente = stream_socket_accept($server);
        $cliente[]=$cliente;
    }
    foreach ($cliente as $key => $cliente) {
        $data = fread($cliente,1024);
        if (!$data) continue;
        $decoded = json_decode($data,true);

        if ($decoded) {
            saveMessage($decoded['usuario'], $decoded['mensaje']);

            foreach ($cliente as $sendTo){
                fwrite($sendTo, json_decode($decoded). "\n");
            }
        }
    }
}
function saveMessage($usuario, $msg)
{
    $pdo = new PDO("mysql:host=localhost;dbname=tienda", "root", "");
    $stmt = $pdo->prepare("INSERT INTO mensajes (usuario, mensaje) VALUES (?,?)");
    $stmt->execute([$usuario, $msg]);
}
?>




//$host = '127.0.0.1';
//$port = 9595;
//$clientes = [];
//
//$server = stream_socket_server("tcp://$host:$port", $errno, $errstr);
//if (!$server) {
//    die("Error: $errstr ($errno)\n");
//}
//echo "WebSocket server started on $host:$port\n";
//
//while (true) {
//    $read = $clientes;
//    $read[] = $server;
//    stream_select($read, $write, $except, 1);
//
//    if (in_array($server, $read)) {
//        $nuevoCliente = stream_socket_accept($server);
//        if ($nuevoCliente) {
//            $clientes[] = $nuevoCliente;
//        }
//    }
//
//    foreach ($clientes as $key => $conn) {
//        $data = fread($conn, 1024);
//        if ($data === false || feof($conn)) {
//            fclose($conn);
//            unset($clientes[$key]);
//            continue;
//        }
//
//        $decoded = json_decode($data, true);
//        if ($decoded) {
//            saveMessage($decoded['usuario'], $decoded['mensaje']);
//            foreach ($clientes as $sendTo) {
//                fwrite($sendTo, json_encode($decoded) . "\n");
//            }
//        }
//    }
//}
//
//function saveMessage($usuario, $msg)
//{
//    $pdo = new PDO("mysql:host=localhost;dbname=tienda", "root", "");
//    $stmt = $pdo->prepare("INSERT INTO mensajes (usuario, mensaje) VALUES (?, ?)");
//    $stmt->execute([$usuario, $msg]);
//}
//

