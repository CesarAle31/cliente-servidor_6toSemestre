<?php
require dirname(__DIR__) . '/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class CursoWebSocket implements MessageComponentInterface {
    protected $clients;
    protected $pdo;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->pdo = new PDO('mysql:host=localhost;dbname=escuela', 'root', '');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);

        if ($data['action'] === 'getCursos') {
            $stmt = $this->pdo->query('SELECT * FROM cursos');
            $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $from->send(json_encode(['action' => 'cursos', 'cursos' => $cursos]));
        }

        if ($data['action'] === 'guardarCurso') {
            $curso = $data['curso'];
            if (isset($curso['id'])) {
                $stmt = $this->pdo->prepare('UPDATE cursos SET nombre=?, capacidad=? WHERE id=?');
                $stmt->execute([$curso['nombre'], $curso['capacidad'], $curso['id']]);
            } else {
                $stmt = $this->pdo->prepare('INSERT INTO cursos (nombre, capacidad) VALUES (?, ?)');
                $stmt->execute([$curso['nombre'], $curso['capacidad']]);
            }
            foreach ($this->clients as $client) {
                $client->send(json_encode(['action' => 'refresh']));
            }
        }

        if ($data['action'] === 'eliminarCurso') {
            $stmt = $this->pdo->prepare('DELETE FROM cursos WHERE id=?');
            $stmt->execute([$data['id']]);
            foreach ($this->clients as $client) {
                $client->send(json_encode(['action' => 'refresh']));
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new CursoWebSocket()
        )
    ),
    8080
);

$server->run();