<?php
header('Content-Type: application/json');
$metodo = $_SERVER['REQUEST_METHOD'];

// Conexión a base de datos
$conn = new mysqli("localhost", "root", "", "agenda");
if ($conn->connect_error) {
    die(json_encode(["error" => "Conexión fallida: " . $conn->connect_error]));
}

// GET - Obtener todos los contactos
if ($metodo == 'GET') {
    $sql = "SELECT * FROM contactos";
    $result = $conn->query($sql);
    $contactos = [];

    while ($row = $result->fetch_assoc()) {
        $contactos[] = $row;
    }

    echo json_encode($contactos);
}

// POST - Agregar un nuevo contacto
elseif ($metodo == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['nombre']) && isset($data['telefono_fijo']) && isset($data['telefono_cel']) && isset($data['correo_electronico'])) {
        $nombre = $conn->real_escape_string($data['nombre']);
        $fijo = $conn->real_escape_string($data['telefono_fijo']);
        $cel = $conn->real_escape_string($data['telefono_cel']);
        $correo = $conn->real_escape_string($data['correo_electronico']);

        $sql = "INSERT INTO contactos (nombre, telefono_fijo, telefono_cel, correo_electronico) 
                VALUES ('$nombre', '$fijo', '$cel', '$correo')";

        if ($conn->query($sql)) {
            echo json_encode(["mensaje" => "Contacto agregado correctamente"]);
        } else {
            echo json_encode(["error" => "Error al guardar: " . $conn->error]);
        }
    } else {
        echo json_encode(["error" => "Faltan datos"]);
    }
}

// PUT - Actualizar contacto
elseif ($metodo == 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Verificar que se proporcionó un ID
    if (isset($data['id'])) {
        $id = (int)$data['id'];

        // Verificar si el contacto existe
        $verificar = "SELECT id FROM contactos WHERE id = $id";
        $resultado = $conn->query($verificar);

        if ($resultado && $resultado->num_rows > 0) {

            // Verificar que todos los campos necesarios estén presentes
            if (isset($data['nombre']) && isset($data['telefono_fijo']) &&
                isset($data['telefono_cel']) && isset($data['correo_electronico'])) {

                $nombre = $conn->real_escape_string($data['nombre']);
                $fijo = $conn->real_escape_string($data['telefono_fijo']);
                $cel = $conn->real_escape_string($data['telefono_cel']);
                $correo = $conn->real_escape_string($data['correo_electronico']);

                $sql = "UPDATE contactos SET 
                            nombre = '$nombre',
                            telefono_fijo = '$fijo',
                            telefono_cel = '$cel',
                            correo_electronico = '$correo'
                        WHERE id = $id";

                if ($conn->query($sql)) {
                    echo json_encode(["mensaje" => "Contacto actualizado correctamente"]);
                } else {
                    echo json_encode(["error" => "Error al actualizar: " . $conn->error]);
                }
            } else {
                echo json_encode(["error" => "Faltan datos para actualizar"]);
            }

        } else {
            echo json_encode(["error" => "El contacto con ID $id no existe"]);
        }

    } else {
        echo json_encode(["error" => "ID no proporcionado para actualizar"]);
    }
}


// DELETE - Eliminar contacto
//elseif ($metodo == 'DELETE') {
//    $data = json_decode(file_get_contents("php://input"), true);
//
//    if (isset($data['id'])) {
//        $id = (int)$data['id'];
//
//        $sql = "DELETE FROM contactos WHERE id = $id";
//
//        if ($conn->query($sql)) {
//            echo json_encode(["mensaje" => "Contacto eliminado correctamente"]);
//        } else {
//            echo json_encode(["error" => "Error al eliminar: " . $conn->error]);
//        }
//    } else {
//        echo json_encode(["error" => "ID no proporcionado para eliminar"]);
//    }
//}


// DELETE - Eliminar contacto
elseif ($metodo == 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id'])) {
        $id = (int)$data['id'];

        // Verificar si el contacto existe
        $verificar = "SELECT id FROM contactos WHERE id = $id";
        $resultado = $conn->query($verificar);

        if ($resultado && $resultado->num_rows > 0) {
            // El contacto existe, proceder a eliminar
            $sql = "DELETE FROM contactos WHERE id = $id";

            if ($conn->query($sql)) {
                echo json_encode(["mensaje" => "Contacto eliminado correctamente"]);
            } else {
                echo json_encode(["error" => "Error al eliminar: " . $conn->error]);
            }
        } else {
            // El contacto no existe
            echo json_encode(["error" => "El contacto con ID $id no existe"]);
        }
    } else {
        echo json_encode(["error" => "ID no proporcionado para eliminar"]);
    }
}


$conn->close();
?>
