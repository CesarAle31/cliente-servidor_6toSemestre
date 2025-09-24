<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comandaId = $_POST['comandaId'];
    $total = floatval($_POST['total']);
    $forma_pago = $_POST['forma_pago'];

    $data = [
        'total' => $total,
        'forma_pago' => $forma_pago
        // la hora de cierre la pone el backend automáticamente
    ];

    $url = "http://localhost:3000/comandas/$comandaId/cerrar";

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $respuesta = curl_exec($ch);
    $codigo_http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        echo "❌ Error en la solicitud: $error";
    } elseif ($codigo_http >= 200 && $codigo_http < 300) {
        echo "<h3>Comanda cerrada correctamente</h3>";
    } else {
        echo "<h3>❌ Error al cerrar comanda (código $codigo_http)</h3>";
    }

    echo "<pre>" . htmlspecialchars($respuesta) . "</pre>";
}
?>