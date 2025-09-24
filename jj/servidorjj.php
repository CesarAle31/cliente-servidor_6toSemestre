<?php
//private function getCursosWithInscritos() {
//$stmt = $this->pdo->query("SELECT id, nombre, capacidad FROM cursos");
//$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//foreach ($cursos as &$curso) {
//$stmtAlumnos = $this->pdo->prepare("
//SELECT a.id, a.nombre
//FROM inscripciones i
//JOIN alumno a ON i.id_alumno = a.id
//WHERE i.id_curso = ?
//");
//$stmtAlumnos->execute([$curso['id']]);
//$curso['alumnos'] = $stmtAlumnos->fetchAll(PDO::FETCH_ASSOC);
//$curso['inscritos'] = count($curso['alumnos']);
//}
//
//return $cursos;
//}


require dirname(__DIR__) . '/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class InscripcionWs implements MessageComponentInterface
{
    protected $clients;
    protected $pdo;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->pdo = new PDO('mysql:host=localhost;dbname=escuela', 'root', '');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);

        if ($data['action'] === 'getCursos') {
            $cursos = $this->getCursosWithInscritos();
            $from->send(json_encode(['action' => 'cursos', 'cursos' => $cursos]));
        }

        if ($data['action'] === 'inscribirAlumno') {
            $id_curso = $data['id_curso'];
            $id_alumno = $data['id_alumno'];
            $cursoNombre = isset($data['curso']) ? $data['curso'] : '';

            // Traer curso y cantidad de inscritos
            $stmt = $this->pdo->prepare("
                SELECT capacidad, 
                    (SELECT COUNT(*) FROM inscripciones WHERE id_curso = ?) AS inscritos
                FROM cursos WHERE id = ?
            ");
            $stmt->execute([$id_curso, $id_curso]);
            $info = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$info) {
                $from->send(json_encode(['action' => 'error', 'mensaje' => 'Curso no encontrado.']));
                return;
            }

            if ($info['inscritos'] >= $info['capacidad']) {
                $from->send(json_encode([
                    'action' => 'cursoLleno',
                    'curso' => $cursoNombre
                ]));
                return;
            }

            // Verifica si el alumno ya está inscrito
            $stmt = $this->pdo->prepare("SELECT id FROM inscripciones WHERE id_curso = ? AND id_alumno = ?");
            $stmt->execute([$id_curso, $id_alumno]);
            if ($stmt->fetch()) {
                $from->send(json_encode([
                    'action' => 'error',
                    'mensaje' => 'El alumno ya está inscrito en este curso.'
                ]));
                return;
            }

            // Inserta inscripción
            $stmt = $this->pdo->prepare("INSERT INTO inscripciones (id_alumno, id_curso, fecha_inscripción) VALUES (?, ?, CURDATE())");
            $stmt->execute([$id_alumno, $id_curso]);

            $from->send(json_encode(['action' => 'inscripcionOK']));
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }


    private function getCursosWithInscritos()
    {
        $stmt = $this->pdo->query("SELECT id, nombre, capacidad FROM cursos");
        $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($cursos as &$curso) {
            // Contador de inscritos
            $stmtInscritos = $this->pdo->prepare("SELECT COUNT(*) as inscritos FROM inscripciones WHERE id_curso = ?");
            $stmtInscritos->execute([$curso['id']]);
            $curso['inscritos'] = $stmtInscritos->fetchColumn();

            // Lista de alumnos inscritos con nombre
            $stmtAlumnos = $this->pdo->prepare("
            SELECT a.id, a.nombre 
            FROM inscripciones i
            JOIN alumno a ON i.id_alumno = a.id
            WHERE i.id_curso = ?
        ");
            $stmtAlumnos->execute([$curso['id']]);
            $curso['alumnos'] = $stmtAlumnos->fetchAll(PDO::FETCH_ASSOC);
        }

        return $cursos;
    }


}

// Lanza el servidor WebSocket
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new InscripcionWs()
        )
    ),
    8080
);

$server->run();
