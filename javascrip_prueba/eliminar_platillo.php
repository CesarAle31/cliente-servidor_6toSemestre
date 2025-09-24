<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comanda_id = $_POST['comanda_id'];
    $item_id = $_POST['item_id'];

    $url = "http://localhost:3000/comandas/{$comanda_id}/item/{$item_id}";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $respuesta = curl_exec($ch);
    $codigo_http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);

    curl_close($ch);

    if ($error) {
        echo "❌ Error en la solicitud: $error";
    } elseif ($codigo_http >= 200 && $codigo_http < 300) {
        header("Location: consultar_comandas_activas.php");
        exit();
    } else {
        echo "<h3>❌ Error al eliminar platillo (código $codigo_http)</h3>";
        echo "<pre>" . htmlspecialchars($respuesta) . "</pre>";
    }
} else {
    echo "Método no permitido.";
}
?>