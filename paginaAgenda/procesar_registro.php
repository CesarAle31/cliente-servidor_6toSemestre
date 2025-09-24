<?php
$host = "localhost";
$usuario = "root";
$contrasena = "";
$bd = "agenda";

$conn = new mysqli($host, $usuario, $contrasena, $bd);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}



// Mostrar todos los registros
//$resultado = $conn->query("SELECT * FROM contactos");


$busqueda = "";
if (isset($_GET['buscar_nombre']) && !empty(trim($_GET['buscar_nombre']))) {
    $nombre = $conn->real_escape_string($_GET['buscar_nombre']);
    $busqueda = "WHERE nombre LIKE '%$nombre%'";
}

$sql = "SELECT * FROM contactos $busqueda";
$resultado = $conn->query($sql);


if ($resultado->num_rows > 0) {
    echo "<center><h3>Registros actuales:</h3></center>";
    echo "<center><table ></center>

            <h1><center><tr><th>ID</th><th>Nombre</th><th>Tel. Fijo</th><th>Tel. Cel</th><th>Correo</th></tr></center></h1>";
    while($fila = $resultado->fetch_assoc()) {
        echo "<tr>
                <td>".$fila["id"]."</td>
                <td>".$fila["nombre"]."</td>
                <td>".$fila["telefono_fijo"]."</td>
                <td>".$fila["telefono_cel"]."</td>
                <td>".$fila["correo_electronico"]."</td>
              </tr>";
    }
    echo "</table>";

    echo '<a href="agentaPagina.html"><button>Regresar</button></a>';

} else {
    echo "No hay registros.";
}





$conn->close();
?>
