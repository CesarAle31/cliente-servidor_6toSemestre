<?php
$host = '127.0.0.1';
$port = 9595;
$server = stream_socket_server("tcp://$host:$port", $errno, $errstr);

$cursos = [
    ["id" => 1, "nombre" => "Taller de Robótica", "disponibles" => 2],
    ["id" => 2, "nombre" => "Curso de Programación", "disponibles" => 5],
];

$clients = [];

while (true) {
    $read = $clients;
    $read[] = $server;

    stream_select($read, $write, $except, null);

    if (in_array($server, $read)) {
        $clients[] = stream_socket_accept($server);
        $key = array_search($server, $read);
        unset($read[$key]);
    }

    foreach ($read as $client) {
        $data = fread($client, 1024);
        if (!$data) {
            fclose($client);
            $clients = array_diff($clients, [$client]);
            continue;
        }

        $msg = json_decode(trim($data), true);

        if ($msg['accion'] === 'listar_cursos') {
            fwrite($client, json_encode([
                    "accion" => "lista_cursos",
                    "data" => $cursos
                ]) . "\n");
        }

        if ($msg['accion'] === 'inscribirse') {
            foreach ($cursos as &$curso) {
                if ($curso['id'] == $msg['curso_id']) {
                    if ($curso['disponibles'] > 0) {
                        $curso['disponibles']--;
                        $respuesta = "Inscripción exitosa en " . $curso['nombre'];
                    } else {
                        $respuesta = "El curso " . $curso['nombre'] . " se ha llenado. No se permiten más inscripciones.";
                    }
                    break;
                }
            }

            fwrite($client, json_encode([
                    "accion" => "inscripcion",
                    "mensaje" => $respuesta
                ]) . "\n");
        }
    }
}
?>