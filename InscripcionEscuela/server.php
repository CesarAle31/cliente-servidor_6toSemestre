<?php
require dirname(__DIR__) . '/vendor/autoload.php';
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class AlumnosWs implements MessageComponentInterface {
    protected $clients;
    protected $db;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->db = new mysqli('localhost', 'root', '', 'escuela');
        if ($this->db->connect_error) {
            die("Error de conexión: " . $this->db->connect_error);
        }
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        $alumnos = $this->getAlumnosFromDB();
        $conn->send(json_encode(['type' => 'alumnos', 'alumnos' => $alumnos]));
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);

        if ($data['type'] === 'addAlumno') {
            $alumno = $data['alumno'];
            $stmt = $this->db->prepare("INSERT INTO alumno (nombre, correo, usuario, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $alumno['nombre'], $alumno['correo'], $alumno['usuario'], $alumno['password']);
            $stmt->execute();
            $stmt->close();
        }

        if ($data['type'] === 'editAlumno') {
            $alumno = $data['alumno'];
            $stmt = $this->db->prepare("UPDATE alumno SET nombre=?, correo=?, usuario=?, password=? WHERE id=?");
            $stmt->bind_param("ssssi", $alumno['nombre'], $alumno['correo'], $alumno['usuario'], $alumno['password'], $alumno['id']);
            $stmt->execute();
            $stmt->close();
        }
        if ($data['type'] === 'deleteAlumno') {
            $id = $data['id'];
            $stmt = $this->db->prepare("DELETE FROM alumno WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
        $this->broadcast();
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }

    private function broadcast() {
        $alumnos = $this->getAlumnosFromDB();
        foreach ($this->clients as $client) {
            $client->send(json_encode(['type' => 'alumnos', 'alumnos' => $alumnos]));
        }
    }

    private function getAlumnosFromDB() {
        $result = $this->db->query("SELECT id, nombre, correo, usuario, password FROM alumno ORDER BY id ASC");
        $alumnos = [];
        while ($row = $result->fetch_assoc()) {
            $alumnos[] = $row;
        }
        return $alumnos;
    }
}

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new AlumnosWs()
        )
    ),
    8080
);

$server->run();