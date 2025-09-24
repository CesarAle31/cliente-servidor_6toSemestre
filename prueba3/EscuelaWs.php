<?php
//require dirname(__DIR__) . '/vendor/autoload.php';
//
//use Ratchet\MessageComponentInterface;
//use Ratchet\ConnectionInterface;
//
//class EscuelaWs implements MessageComponentInterface {
//    protected $clients;
//    protected $pdo;
//
//    public function __construct() {
//        $this->clients = new \SplObjectStorage;
//        $this->pdo = new PDO('mysql:host=localhost;dbname=escuela', 'root', '');
//        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    }
//
//    public function onOpen(ConnectionInterface $conn) {
//        $this->clients->attach($conn);
//        // Puedes enviar ambos datasets al conectar, si quieres
//        $cursos = $this->getCursosFromDB();
//        $alumnos = $this->getAlumnosFromDB();
//        $conn->send(json_encode(['type' => 'init', 'cursos' => $cursos, 'alumnos' => $alumnos]));
//    }
//
//    public function onMessage(ConnectionInterface $from, $msg) {
//        $data = json_decode($msg, true);
//
//        // --- CURSOS ---
//        if (isset($data['action']) && $data['action'] === 'getCursos') {
//            $cursos = $this->getCursosFromDB();
//            $from->send(json_encode(['action' => 'cursos', 'cursos' => $cursos]));
//        }
//        if (isset($data['action']) && $data['action'] === 'guardarCurso') {
//            $curso = $data['curso'];
//            if (isset($curso['id'])) {
//                $stmt = $this->pdo->prepare('UPDATE cursos SET nombre=?, capacidad=? WHERE id=?');
//                $stmt->execute([$curso['nombre'], $curso['capacidad'], $curso['id']]);
//            } else {
//                $stmt = $this->pdo->prepare('INSERT INTO cursos (nombre, capacidad) VALUES (?, ?)');
//                $stmt->execute([$curso['nombre'], $curso['capacidad']]);
//            }
//            $this->broadcast(['action' => 'refreshCursos']);
//        }
//        if (isset($data['action']) && $data['action'] === 'eliminarCurso') {
//            $stmt = $this->pdo->prepare('DELETE FROM cursos WHERE id=?');
//            $stmt->execute([$data['id']]);
//            $this->broadcast(['action' => 'refreshCursos']);
//        }
//
//        // --- ALUMNOS ---
//        if (isset($data['type']) && $data['type'] === 'addAlumno') {
//            $alumno = $data['alumno'];
//            $stmt = $this->pdo->prepare("INSERT INTO alumno (nombre, correo, usuario, password) VALUES (?, ?, ?, ?)");
//            $stmt->execute([$alumno['nombre'], $alumno['correo'], $alumno['usuario'], $alumno['password']]);
//            $this->broadcast(['action' => 'refreshAlumnos']);
//        }
//        if (isset($data['type']) && $data['type'] === 'editAlumno') {
//            $alumno = $data['alumno'];
//            $stmt = $this->pdo->prepare("UPDATE alumno SET nombre=?, correo=?, usuario=?, password=? WHERE id=?");
//            $stmt->execute([$alumno['nombre'], $alumno['correo'], $alumno['usuario'], $alumno['password'], $alumno['id']]);
//            $this->broadcast(['action' => 'refreshAlumnos']);
//        }
//        if (isset($data['type']) && $data['type'] === 'deleteAlumno') {
//            $stmt = $this->pdo->prepare("DELETE FROM alumno WHERE id=?");
//            $stmt->execute([$data['id']]);
//            $this->broadcast(['action' => 'refreshAlumnos']);
//        }
//
//
//
//        // En onMessage:
//        if (isset($data['action']) && $data['action'] === 'getAlumnos') {
//            $stmt = $this->pdo->query("SELECT id, nombre, usuario FROM alumno");
//            $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
//            $from->send(json_encode(['action' => 'alumnos', 'alumnos' => $alumnos]));
//        }
//
//        if (isset($data['action']) && $data['action'] === 'inscribirAlumnos') {
//            $id_curso = $data['id_curso'];
//            foreach ($data['alumnos'] as $id_alumno) {
//                $stmt = $this->pdo->prepare("INSERT INTO inscripcion (id_alumno, id_curso, fecha_inscripción) VALUES (?, ?, CURDATE())");
//                $stmt->execute([$id_alumno, $id_curso]);
//            }
//            // Puedes enviar confirmación si quieres:
//            $from->send(json_encode(['action' => 'inscripcionOK']));
//        }
//
//
//    }
//
//    public function onClose(ConnectionInterface $conn) {
//        $this->clients->detach($conn);
//    }
//
//    public function onError(ConnectionInterface $conn, \Exception $e) {
//        $conn->close();
//    }
//
//    private function broadcast($options = []) {
//        // Si options tiene 'action' => 'refreshCursos', manda cursos a todos
//        if (isset($options['action']) && $options['action'] === 'refreshCursos') {
//            $cursos = $this->getCursosFromDB();
//            foreach ($this->clients as $client) {
//                $client->send(json_encode(['action' => 'cursos', 'cursos' => $cursos]));
//            }
//        }
//        // Si options tiene 'action' => 'refreshAlumnos', manda alumnos a todos
//        if (isset($options['action']) && $options['action'] === 'refreshAlumnos') {
//            $alumnos = $this->getAlumnosFromDB();
//            foreach ($this->clients as $client) {
//                $client->send(json_encode(['type' => 'alumnos', 'alumnos' => $alumnos]));
//            }
//        }
//    }
//
//    private function getCursosFromDB() {
//        $stmt = $this->pdo->query('SELECT * FROM cursos');
//        return $stmt->fetchAll(PDO::FETCH_ASSOC);
//    }
//
//    private function getAlumnosFromDB() {
//        $stmt = $this->pdo->query('SELECT id, nombre, correo, usuario, password FROM alumno ORDER BY id ASC');
//        return $stmt->fetchAll(PDO::FETCH_ASSOC);
//    }
//}
//
//// Inicializa el servidor
//use Ratchet\Server\IoServer;
//use Ratchet\Http\HttpServer;
//use Ratchet\WebSocket\WsServer;
//
//$server = IoServer::factory(
//    new HttpServer(
//        new WsServer(
//            new EscuelaWs()
//        )
//    ),
//    8080
//);
//
//$server->run();
//--------------------------------------



//require dirname(__DIR__) . '/vendor/autoload.php';
//
//use Ratchet\MessageComponentInterface;
//use Ratchet\ConnectionInterface;
//
//class EscuelaWs implements MessageComponentInterface {
//    protected $clients;
//    protected $pdo;
//
//    public function __construct() {
//        $this->clients = new \SplObjectStorage;
//        $this->pdo = new PDO('mysql:host=localhost;dbname=escuela', 'root', '');
//        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    }
//
//    public function onOpen(ConnectionInterface $conn) {
//        $this->clients->attach($conn);
//        // Envía cursos y alumnos al conectar (puedes personalizar)
//        $cursos = $this->getCursosWithInscritos();
//        $alumnos = $this->getAlumnosFromDB();
//        $conn->send(json_encode(['type' => 'init', 'cursos' => $cursos, 'alumnos' => $alumnos]));
//    }
//
//    public function onMessage(ConnectionInterface $from, $msg) {
//        $data = json_decode($msg, true);
//
//        // --- CURSOS ---
//        if (($data['action'] ?? null) === 'getCursos') {
//            // Si quieres cursos con inscritos:
//            $cursos = $this->getCursosWithInscritos();
//            $from->send(json_encode(['action' => 'cursos', 'cursos' => $cursos]));
//        }
//        if (($data['action'] ?? null) === 'guardarCurso') {
//            $curso = $data['curso'];
//            if (isset($curso['id'])) {
//                $stmt = $this->pdo->prepare('UPDATE cursos SET nombre=?, capacidad=? WHERE id=?');
//                $stmt->execute([$curso['nombre'], $curso['capacidad'], $curso['id']]);
//            } else {
//                $stmt = $this->pdo->prepare('INSERT INTO cursos (nombre, capacidad) VALUES (?, ?)');
//                $stmt->execute([$curso['nombre'], $curso['capacidad']]);
//            }
//            $this->broadcast(['action' => 'refreshCursos']);
//        }
//        if (($data['action'] ?? null) === 'eliminarCurso') {
//            $stmt = $this->pdo->prepare('DELETE FROM cursos WHERE id=?');
//            $stmt->execute([$data['id']]);
//            $this->broadcast(['action' => 'refreshCursos']);
//        }
//
//        // --- ALUMNOS ---
//        if (($data['type'] ?? null) === 'addAlumno') {
//            $alumno = $data['alumno'];
//            $stmt = $this->pdo->prepare("INSERT INTO alumno (nombre, correo, usuario, password) VALUES (?, ?, ?, ?)");
//            $stmt->execute([$alumno['nombre'], $alumno['correo'], $alumno['usuario'], $alumno['password']]);
//            $this->broadcast(['action' => 'refreshAlumnos']);
//        }
//        if (($data['type'] ?? null) === 'editAlumno') {
//            $alumno = $data['alumno'];
//            $stmt = $this->pdo->prepare("UPDATE alumno SET nombre=?, correo=?, usuario=?, password=? WHERE id=?");
//            $stmt->execute([$alumno['nombre'], $alumno['correo'], $alumno['usuario'], $alumno['password'], $alumno['id']]);
//            $this->broadcast(['action' => 'refreshAlumnos']);
//        }
//        if (($data['type'] ?? null) === 'deleteAlumno') {
//            $stmt = $this->pdo->prepare("DELETE FROM alumno WHERE id=?");
//            $stmt->execute([$data['id']]);
//            $this->broadcast(['action' => 'refreshAlumnos']);
//        }
//
//        if (($data['action'] ?? null) === 'getAlumnos') {
//            $stmt = $this->pdo->query("SELECT id, nombre, usuario FROM alumno");
//            $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
//            $from->send(json_encode(['action' => 'alumnos', 'alumnos' => $alumnos]));
//        }
//
//        // --- INSCRIPCIÓN (con validaciones de capacidad y duplicidad) ---
//        if (($data['action'] ?? null) === 'inscribirAlumno') {
//            $id_curso = $data['id_curso'];
//            $id_alumno = $data['id_alumno'];
//            $cursoNombre = $data['curso'] ?? '';
//
//            // Verifica capacidad
//            $stmt = $this->pdo->prepare("
//                SELECT capacidad,
//                    (SELECT COUNT(*) FROM inscripciones WHERE id_curso = ?) AS inscritos
//                FROM cursos WHERE id = ?
//            ");
//            $stmt->execute([$id_curso, $id_curso]);
//            $info = $stmt->fetch(PDO::FETCH_ASSOC);
//
//            if (!$info) {
//                $from->send(json_encode(['action' => 'error', 'mensaje' => 'Curso no encontrado.']));
//                return;
//            }
//
//            if ($info['inscritos'] >= $info['capacidad']) {
//                $from->send(json_encode([
//                    'action' => 'cursoLleno',
//                    'curso' => $cursoNombre
//                ]));
//                return;
//            }
//
//            // Verifica si el alumno ya está inscrito
//            $stmt = $this->pdo->prepare("SELECT id FROM inscripciones WHERE id_curso = ? AND id_alumno = ?");
//            $stmt->execute([$id_curso, $id_alumno]);
//            if ($stmt->fetch()) {
//                $from->send(json_encode([
//                    'action' => 'error',
//                    'mensaje' => 'El alumno ya está inscrito en este curso.'
//                ]));
//                return;
//            }
//
//            // Inserta inscripción
//            $stmt = $this->pdo->prepare("INSERT INTO inscripciones (id_alumno, id_curso, fecha_inscripción) VALUES (?, ?, CURDATE())");
//            $stmt->execute([$id_alumno, $id_curso]);
//
//            $from->send(json_encode(['action' => 'inscripcionOK']));
//        }
//        // Opción para inscripción múltiple, manteniendo compatibilidad
//        if (($data['action'] ?? null) === 'inscribirAlumnos') {
//            $id_curso = $data['id_curso'];
//            foreach ($data['alumnos'] as $id_alumno) {
//                // Puedes usar aquí la misma lógica de validación si lo deseas
//                $stmt = $this->pdo->prepare("INSERT INTO inscripciones (id_alumno, id_curso, fecha_inscripción) VALUES (?, ?, CURDATE())");
//                $stmt->execute([$id_alumno, $id_curso]);
//            }
//            $from->send(json_encode(['action' => 'inscripcionOK']));
//        }
//    }
//
//    public function onClose(ConnectionInterface $conn) {
//        $this->clients->detach($conn);
//    }
//
//    public function onError(ConnectionInterface $conn, \Exception $e) {
//        $conn->close();
//    }
//
//    private function broadcast($options = []) {
//        if (isset($options['action']) && $options['action'] === 'refreshCursos') {
//            $cursos = $this->getCursosWithInscritos();
//            foreach ($this->clients as $client) {
//                $client->send(json_encode(['action' => 'cursos', 'cursos' => $cursos]));
//            }
//        }
//        if (isset($options['action']) && $options['action'] === 'refreshAlumnos') {
//            $alumnos = $this->getAlumnosFromDB();
//            foreach ($this->clients as $client) {
//                $client->send(json_encode(['type' => 'alumnos', 'alumnos' => $alumnos]));
//            }
//        }
//    }
//
//    private function getCursosFromDB() {
//        $stmt = $this->pdo->query('SELECT * FROM cursos');
//        return $stmt->fetchAll(PDO::FETCH_ASSOC);
//    }
//
//    // Nueva: cursos con cantidad de inscritos
//    private function getCursosWithInscritos() {
//        $stmt = $this->pdo->query("
//            SELECT c.id, c.nombre, c.capacidad,
//                (SELECT COUNT(*) FROM inscripciones i WHERE i.id_curso = c.id) AS inscritos
//            FROM cursos c
//        ");
//        return $stmt->fetchAll(PDO::FETCH_ASSOC);
//    }
//
//    private function getAlumnosFromDB() {
//        $stmt = $this->pdo->query('SELECT id, nombre, correo, usuario, password FROM alumno ORDER BY id ASC');
//        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
//            new EscuelaWs()
//        )
//    ),
//    8080
//);
//
//$server->run();


require dirname(__DIR__) . '/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class EscuelaWs implements MessageComponentInterface
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
        // Envía cursos y alumnos al conectar
        $cursos = $this->getCursosWithInscritos();
        $alumnos = $this->getAlumnosFromDB();
        $conn->send(json_encode(['type' => 'init', 'cursos' => $cursos, 'alumnos' => $alumnos]));
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);

        // --- CURSOS ---
        if (($data['action'] ?? null) === 'getCursos') {
            $cursos = $this->getCursosWithInscritos();
            $from->send(json_encode(['action' => 'cursos', 'cursos' => $cursos]));
        }
        if (($data['action'] ?? null) === 'guardarCurso') {
            $curso = $data['curso'];
            if (isset($curso['id'])) {
                $stmt = $this->pdo->prepare('UPDATE cursos SET nombre=?, capacidad=? WHERE id=?');
                $stmt->execute([$curso['nombre'], $curso['capacidad'], $curso['id']]);
            } else {
                $stmt = $this->pdo->prepare('INSERT INTO cursos (nombre, capacidad) VALUES (?, ?)');
                $stmt->execute([$curso['nombre'], $curso['capacidad']]);
            }
            $this->broadcast(['action' => 'refreshCursos']);
        }
        if (($data['action'] ?? null) === 'eliminarCurso') {
            $stmt = $this->pdo->prepare('DELETE FROM cursos WHERE id=?');
            $stmt->execute([$data['id']]);
            $this->broadcast(['action' => 'refreshCursos']);
        }

        // --- ALUMNOS ---
        if (($data['type'] ?? null) === 'addAlumno') {
            $alumno = $data['alumno'];
            $stmt = $this->pdo->prepare("INSERT INTO alumno (nombre, correo, usuario, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$alumno['nombre'], $alumno['correo'], $alumno['usuario'], $alumno['password']]);
            $this->broadcast(['action' => 'refreshAlumnos']);
        }
        if (($data['type'] ?? null) === 'editAlumno') {
            $alumno = $data['alumno'];
            $stmt = $this->pdo->prepare("UPDATE alumno SET nombre=?, correo=?, usuario=?, password=? WHERE id=?");
            $stmt->execute([$alumno['nombre'], $alumno['correo'], $alumno['usuario'], $alumno['password'], $alumno['id']]);
            $this->broadcast(['action' => 'refreshAlumnos']);
        }
        if (($data['type'] ?? null) === 'deleteAlumno') {
            $stmt = $this->pdo->prepare("DELETE FROM alumno WHERE id=?");
            $stmt->execute([$data['id']]);
            $this->broadcast(['action' => 'refreshAlumnos']);
        }

        if (($data['action'] ?? null) === 'getAlumnos') {
            $stmt = $this->pdo->query("SELECT id, nombre, usuario FROM alumno");
            $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $from->send(json_encode(['action' => 'alumnos', 'alumnos' => $alumnos]));
        }

        // --- INSCRIPCIÓN (con validaciones de capacidad y duplicidad) ---
        if (($data['action'] ?? null) === 'inscribirAlumno') {
            $id_curso = $data['id_curso'];
            $id_alumno = $data['id_alumno'];
            $cursoNombre = $data['curso'] ?? '';

            // Verifica capacidad
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
            $this->broadcast(['action' => 'refreshCursos']);
        }
        // Opción para inscripción múltiple, manteniendo compatibilidad
        if (($data['action'] ?? null) === 'inscribirAlumnos') {
            $id_curso = $data['id_curso'];
            foreach ($data['alumnos'] as $id_alumno) {
                $stmt = $this->pdo->prepare("INSERT INTO inscripciones (id_alumno, id_curso, fecha_inscripción) VALUES (?, ?, CURDATE())");
                $stmt->execute([$id_alumno, $id_curso]);
            }
            $from->send(json_encode(['action' => 'inscripcionOK']));
            $this->broadcast(['action' => 'refreshCursos']);
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

    private function broadcast($options = [])
    {
        if (isset($options['action']) && $options['action'] === 'refreshCursos') {
            $cursos = $this->getCursosWithInscritos();
            foreach ($this->clients as $client) {
                $client->send(json_encode(['action' => 'cursos', 'cursos' => $cursos]));
            }
        }
        if (isset($options['action']) && $options['action'] === 'refreshAlumnos') {
            $alumnos = $this->getAlumnosFromDB();
            foreach ($this->clients as $client) {
                $client->send(json_encode(['type' => 'alumnos', 'alumnos' => $alumnos]));
            }
        }
    }

    // Cursos con cantidad de inscritos y lista de alumnos por curso
    private function getCursosWithInscritos()
    {
        $stmt = $this->pdo->query("SELECT id, nombre, capacidad FROM cursos");
        $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($cursos as &$curso) {
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

    private function getAlumnosFromDB()
    {
        $stmt = $this->pdo->query('SELECT id, nombre, correo, usuario, password FROM alumno ORDER BY id ASC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new EscuelaWs()
        )
    ),
    8080
);

$server->run();