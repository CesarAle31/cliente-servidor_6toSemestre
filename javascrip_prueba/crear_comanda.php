<?php
// Recoge los datos del formulario
$mesa = $_POST['mesa'];
$mesero = $_POST['mesero'];
$nombre_items = $_POST['nombre_item'];
$cantidades = $_POST['cantidad'];

$items = [];
for ($i = 0; $i < count($nombre_items); $i++) {
    $items[] = [
        "nombre" => $nombre_items[$i],
        "cantidad" => intval($cantidades[$i])
    ];
}

$data = [
    "mesa" => $mesa,
    "mesero" => $mesero,
    "items" => $items
];

// Inicializa cURL
$ch = curl_init("http://localhost:3000/comandas"); // Cambia el puerto si tu backend está en otro
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Ejecuta la petición
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

// Muestra resultado
if ($http_code == 201) {
    echo "<h2>Comanda creada exitosamente</h2>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
} else {
    echo "<h2>Error al crear la comanda</h2>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
}
?>
