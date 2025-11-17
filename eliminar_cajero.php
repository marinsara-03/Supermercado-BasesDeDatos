<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Verificar si llega el ID
if (!isset($_GET['id'])) {
    die("❌ Error: No se recibió el ID del cajero.");
}

$id = $_GET['id'];

// Primero verificar si existe el cajero
$sqlCheck = "SELECT * FROM cajeros WHERE ide_caj = '$id'";
$result = $conexion->query($sqlCheck);

if ($result->num_rows == 0) {
    die("❌ No existe un cajero con ese ID.");
}

// Intentar eliminar
$sqlDelete = "DELETE FROM cajeros WHERE ide_caj = '$id'";

if ($conexion->query($sqlDelete) === TRUE) {
    echo "
        <script>
            alert('✅ Cajero eliminado correctamente.');
            window.location.href = 'ver_cajeros.php';
        </script>
    ";
} else {
    echo "
        <script>
            alert('❌ Error al eliminar cajero: " . $conexion->error . "');
            window.location.href = 'ver_cajeros.php';
        </script>
    ";
}
?>
