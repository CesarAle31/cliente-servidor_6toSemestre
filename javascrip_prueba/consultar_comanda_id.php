<?php
// Este script consulta una comanda por su ID
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comandaId = $_POST['comandaId'];

    $url = "http://localhost:3000/comandas/" . urlencode($comandaId);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $respuesta = curl_exec($ch);
    $codigo_http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        echo "❌ Error en la solicitud: $error";
    } elseif ($codigo_http >= 200 && $codigo_http < 300) {
        echo "<h3>Comanda consultada:</h3>";
    } else {
        echo "<h3>❌ Error al consultar comanda (código $codigo_http)</h3>";
    }

    // Mostrar el JSON recibido bonito
    echo "<pre>" . htmlspecialchars($respuesta) . "</pre>";
}
?>
