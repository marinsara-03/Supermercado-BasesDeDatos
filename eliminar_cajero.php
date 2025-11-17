<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}


if (!isset($_GET['id'])) {
    die("❌ Error: No recibimos el ID del cajero, por favor vuelve a intentarlo");
}

$id = $_GET['id'];


$sqlCheck = "SELECT * FROM cajeros WHERE ide_caj = '$id'";
$result = $conexion->query($sqlCheck);

if ($result->num_rows == 0) {
    die("❌ Lo sentimos, pero no hay un cajero con ese ID");
}


$sqlDelete = "DELETE FROM cajeros WHERE ide_caj = '$id'";

if ($conexion->query($sqlDelete) === TRUE) {
    echo "
        <script>
            alert('✅ El cajero ha sido eliminado correctamente.');
            window.location.href = 'ver_cajeros.php';
        </script>
    ";
} else {
    echo "
        <script>
            alert('❌ Hubo un error al eliminar el cajero, por favor intentalo de nuevo " . $conexion->error . "');
            window.location.href = 'ver_cajeros.php';
        </script>
    ";
}
?>
