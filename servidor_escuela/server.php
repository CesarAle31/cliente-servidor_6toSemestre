<?php
require 'vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class AlumnoCRUD implements MessageComponentInterface {
    public function onOpen(ConnectionInterface $conn) {}
    public function onClose(ConnectionInterface $conn) {}
    public function onError(ConnectionInterface $conn, \Exception $e) {}
    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);

        // Conectar a la base de datos como en tu CRUD...
        require "conexion.php";

        switch ($data['action']) {
            case 'get':
                $result = $conn->query("SELECT * FROM alumno");
                $alumnos = $result->fetch_all(MYSQLI_ASSOC);
                $from->send(json_encode(['action' => 'get', 'data' => $alumnos]));
                break;
            // Implementa 'create', 'update', 'delete' igual que en tu CRUD PHP pero usando $data['data']
        }
    }
}

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new AlumnoCRUD()
        )
    ),
    8080 // Puerto WebSocket
);

$server->run();