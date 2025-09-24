<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comanda_id = $_POST['comanda_id'];
    $item_id = $_POST['item_id'];
    $nombre = $_POST['nombre'];
    $cantidad = intval($_POST['cantidad']);

    $url = "http://localhost:3000/comandas/{$comanda_id}/item/{$item_id}/cantidad";
    $data = json_encode(['cantidad' => $cantidad]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $respuesta = curl_exec($ch);
    $codigo_http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);

    curl_close($ch);

    if ($error) {
        echo "❌ Error en la solicitud: $error";
    } elseif ($codigo_http >= 200 && $codigo_http < 300) {
        // Redirecciona de vuelta a la consulta de comandas activas
        header("Location: consultar_comandas_activas.php");
        exit();
    } else {
        echo "<h3>❌ Error al actualizar cantidad (código $codigo_http)</h3>";
        echo "<pre>" . htmlspecialchars($respuesta) . "</pre>";
    }
} else {
    echo "Método no permitido.";
}
?>
