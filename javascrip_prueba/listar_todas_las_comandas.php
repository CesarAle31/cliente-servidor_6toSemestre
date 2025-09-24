<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = "http://localhost:3000/comandas";
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
            echo '<table border="1" cellpadding="5" cellspacing="0">';
            echo '<tr>
                    <th>ID</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Forma de Pago</th>
                    <th>Hora de Cierre</th>
                    <th>Acción</th>
                </tr>';
            foreach ($comandas as $comanda) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($comanda['_id']) . '</td>';
                echo '<td>' . htmlspecialchars($comanda['estado'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($comanda['total'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($comanda['forma_pago'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($comanda['hora_cierre'] ?? '') . '</td>';

                // Agrega el botón solo si el estado es "abierta"
                echo '<td>';
                if (isset($comanda['estado']) && strtolower($comanda['estado']) === 'abierta') {
                    echo '<form method="POST" action="prueba1.php" style="margin:0;">
                        <input type="hidden" name="comanda_id" value="' . htmlspecialchars($comanda['_id']) . '">
                        <button type="submit">Agregar platillo</button>
                    </form>';
                }
                echo '</td>';

                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo "<p>No hay comandas registradas.</p>";
        }
    } else {
        echo "<h3>❌ Error al consultar comandas (código $codigo_http)</h3>";
    }
}
?>