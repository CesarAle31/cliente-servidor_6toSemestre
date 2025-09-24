<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $estado = $_POST['abierta'];

    // Construir la URL con el query param de estado
    $url = "http://localhost:3000/comandas?estado=" . urlencode($estado);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $respuesta = curl_exec($ch);
    $codigo_http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        echo "❌ Error en la solicitud: $error";
    } elseif ($codigo_http >= 200 && $codigo_http < 300) {
        $comandas = json_decode($respuesta, true);

        if (is_array($comandas) && count($comandas) > 0) {
            echo "<h3>Comandas activas:</h3>";
            foreach ($comandas as $comanda) {
                // Solo mostrar si el estado es 'abierta'
                if (isset($comanda['estado']) && strtolower($comanda['estado']) !== 'abierta') {
                    continue;
                }
                echo "<h4>Comanda ID: " . htmlspecialchars($comanda['_id']) . "</h4>";
                echo "<table border='1' cellpadding='5' cellspacing='0'>";
                echo "<tr>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                      </tr>";
                if (isset($comanda['items']) && is_array($comanda['items']) && count($comanda['items']) > 0) {
                    foreach ($comanda['items'] as $item) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($item['nombre'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($item['cantidad'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($item['precio'] ?? '') . "</td>";
                        echo "<td>
                                <!-- Formulario para cambiar cantidad -->
                                <form action='cambiar_cantidad.php' method='POST' style='display:inline;'>
                                    <input type='hidden' name='comanda_id' value='" . htmlspecialchars($comanda['_id']) . "'>
                                    <input type='hidden' name='item_id' value='" . htmlspecialchars($item['_id']) . "'>
                                    <input type='hidden' name='nombre' value='" . htmlspecialchars($item['nombre']) . "'>
                                    <input type='number' name='cantidad' value='" . htmlspecialchars($item['cantidad'] ?? 1) . "' min='1' style='width:50px;'>
                                    <button type='submit'>Cambiar</button>
                                </form>
                                <!-- Formulario para eliminar platillo -->
                                <form action='eliminar_platillo.php' method='POST' style='display:inline; margin-left:5px;'>
                                    <input type='hidden' name='comanda_id' value='" . htmlspecialchars($comanda['_id']) . "'>
                                    <input type='hidden' name='item_id' value='" . htmlspecialchars($item['_id']) . "'>
                                    <input type='hidden' name='nombre' value='" . htmlspecialchars($item['nombre']) . "'>
                                    <button type='submit' onclick=\"return confirm('¿Eliminar este platillo?')\">Eliminar</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No hay platillos en esta comanda.</td></tr>";
                }
                echo "</table><br>";
            }
        } else {
            echo "<p>No hay comandas activas.</p>";
        }
    } else {
        echo "<h3>❌ Error al consultar comandas (código $codigo_http)</h3>";
    }
}
?>