<?php

$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);


if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}


$mensaje = "";

// Cuando el usuario envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST['cod_cat'];
    $nombre = $_POST['nom_cat'];

    // Validar que los campos no estén vacíos
    if (!empty($codigo) && !empty($nombre)) {
        $sql = "INSERT INTO categorias (cod_cat, nom_cat) VALUES ('$codigo', '$nombre')";
        
        if ($conexion->query($sql) === TRUE) {
            $mensaje = "✅ Categoría agregada exitosamente.";
        } else {
            $mensaje = "❌ Error al agregar categoría: " . $conexion->error;
        }
    } else {
        $mensaje = "⚠️ Por favor, completa todos los campos.";
    }
}
?>