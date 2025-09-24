<?php


header('Content-Type: application/json');

// Verificar que sea método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Conexión con la API REST local
$apiBaseUrl = "http://localhost:3000/api/users";

// Simulación de método PUT (actualizar)
if (isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
    $id = $_POST['id'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';

    if (!$id || !$nombre || !$correo) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos incompletos para actualización']);
        exit;
    }

    $data = json_encode(['nombre' => $nombre, 'correo' => $correo]);
    $url = "$apiBaseUrl/$id";

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => $data
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        http_response_code(500);
        echo json_encode(['error' => 'Error en la solicitud: ' . curl_error($ch)]);
    } else {
        echo $response;
    }

    curl_close($ch);
    exit;
}

// Simulación de método DELETE
if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $id = $_POST['id'] ?? '';

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Falta ID para eliminar']);
        exit;
    }

    $url = "$apiBaseUrl/$id";

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "DELETE"
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        http_response_code(500);
        echo json_encode(['error' => 'Error en la solicitud: ' . curl_error($ch)]);
    } else {
        echo $response;
    }

    curl_close($ch);
    exit;
}

// CREAR usuario
$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';

if (!$nombre || !$correo) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos para creación']);
    exit;
}

$data = json_encode(['nombre' => $nombre, 'correo' => $correo]);

$ch = curl_init($apiBaseUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => $data
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al crear usuario: ' . curl_error($ch)]);
} else {
    echo $response;
}

curl_close($ch);


?>