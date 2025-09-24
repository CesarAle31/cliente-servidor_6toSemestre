<?php

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "agenda");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener los datos desde el formulario
$nombre = $_POST['inputNombre'];
$telefono_fijo = $_POST['inputTelefonoFijo'];
$telefono_cel = $_POST['inputTelefonoCel'];
$correo_electronico = $_POST['inputCorreo'];




// Validar que al menos nombre y correo no estén vacíos



if (empty($nombre) || empty($telefono_fijo) || empty($telefono_cel) || empty($correo_electronico)) {
    echo "Error: Todos los campos son obligatorios.";
    header("Refresh:2; url= registroAgenda.php");
    exit;
}

// Insertar datos en la tabla contactos
$sql = "INSERT INTO contactos (nombre, telefono_fijo, telefono_cel, correo_electronico) 
        VALUES ('$nombre', '$telefono_fijo', '$telefono_cel', '$correo_electronico')";

if ($conexion->query($sql) === TRUE) {
    echo "Contacto registrado correctamente";
    header("Refresh:2; url= registroAgenda.php");
    exit;
} else {
    echo "Error al registrar contacto: " . $conexion->error;
}

$conexion->close();


?>