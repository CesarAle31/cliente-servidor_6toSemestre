<?php
//require __DIR__ . '/vendor/autoload.php';
//
//use Ratchet\MessageComponentInterface;
//use Ratchet\ConnectionInterface;
//
//class Chat implements MessageComponentInterface {
//    protected $clients;
//
//    public function __construct() {
//        $this->clients = new \SplObjectStorage;
//        echo "Servidor WebSocket Ratchet iniciado...\n";
//    }
//
//    public function onOpen(ConnectionInterface $conn) {
//        $this->clients->attach($conn);
//        echo "Nueva conexión ({$conn->resourceId})\n";
//    }
//
//    public function onMessage(ConnectionInterface $from, $msg) {
//        echo "Mensaje recibido: $msg\n";
//
//        $data = json_decode($msg, true);
//
//        if (isset($data['usuario'], $data['mensaje'])) {
//            $this->saveMessage($data['usuario'], $data['mensaje']);
//
//            foreach ($this->clients as $client) {
//                $client->send(json_encode([
//                    'usuario' => $data['usuario'],
//                    'mensaje' => $data['mensaje']
//                ]));
//            }
//        }
//    }
//
//    public function onClose(ConnectionInterface $conn) {
//        $this->clients->detach($conn);
//        echo "Conexión cerrada ({$conn->resourceId})\n";
//    }
//
//    public function onError(ConnectionInterface $conn, \Exception $e) {
//        echo "Error: {$e->getMessage()}\n";
//        $conn->close();
//    }
//
//    private function saveMessage($usuario, $mensaje) {
//        try {
//            $pdo = new PDO("mysql:host=localhost;dbname=tienda", "root", "");
//            $stmt = $pdo->prepare("INSERT INTO mensajes (usuario, mensaje) VALUES (?, ?)");
////            echo "guardando".$data['usuario'];
//
//
//            echo "Guardando mensaje de $usuario\n";
//
//            $stmt->execute([$usuario, $mensaje]);
//        } catch (PDOException $e) {
//            echo "DB Error: " . $e->getMessage();
//        }
//    }
//}
//
//use Ratchet\Server\IoServer;
//use Ratchet\Http\HttpServer;
//use Ratchet\WebSocket\WsServer;
//
//$server = IoServer::factory(
//    new HttpServer(
//        new WsServer(
//            new Chat()
//        )
//    ),
//    9595
//);
//
//$server->run();
//

require __DIR__ . '/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo "Servidor WebSocket Ratchet iniciado...\n";
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "Nueva conexión ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        echo "Mensaje recibido: $msg\n";

        $data = json_decode($msg, true);

        if (isset($data['usuario'], $data['mensaje'])) {
            $this->saveMessage($data['usuario'], $data['mensaje']);

            foreach ($this->clients as $client) {
                $client->send(json_encode([
                    'usuario' => $data['usuario'],
                    'mensaje' => $data['mensaje']
                ]));
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Conexión cerrada ({$conn->resourceId})\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }

    private function saveMessage($usuario, $mensaje) {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=tienda", "root", "");
            $stmt = $pdo->prepare("INSERT INTO mensajes (usuario, mensaje) VALUES (?, ?)");

            echo "Guardando mensaje de $usuario\n";

            $stmt->execute([$usuario, $mensaje]);
        } catch (PDOException $e) {
            echo "DB Error: " . $e->getMessage();
        }
    }
}

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    9595
);

$server->run();
?>