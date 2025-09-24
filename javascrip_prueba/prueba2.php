<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comandaId = $_POST['comandaId'];
    $nombre = $_POST['nombre'];
    $servido = ($_POST['servido'] === 'true') ? true : false;

    $item = [
        "servido" => $servido
    ];

    $url = "http://localhost:3000/comandas/$comandaId/item/" . urlencode($nombre);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($item));

    $respuesta = curl_exec($ch);
    $codigo_http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        echo "❌ Error en la solicitud: $error";
    } elseif ($codigo_http >= 200 && $codigo_http < 300) {
        echo "<h3>Platillo servido</h3>";
    } else {
        echo "<h3>❌ Error (código $codigo_http)</h3>";
    }

    echo "<pre>" . htmlspecialchars($respuesta) . "</pre>";
}
?>