<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
$conexion->set_charset("utf8mb4");

// -------------------------------------
// VALIDAR ID
// -------------------------------------
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($id === null || $id === false || $id === '') {
    die("<script>
            alert('❌ No recibimos el ID del detalle.');
            window.location.href='add_detalle_prod.php';
         </script>");
}

// -------------------------------------
// PREPARAR DELETE
// -------------------------------------
$stmt = $conexion->prepare("DELETE FROM detalle_productos WHERE id = ?");
if (!$stmt) {
    die("<script>
            alert('❌ Error preparando la consulta: " . addslashes($conexion->error) . "');
            window.location.href='add_detalle_prod.php';
         </script>");
}

$stmt->bind_param("i", $id);

// -------------------------------------
// EJECUTAR
// -------------------------------------
if ($stmt->execute()) {

    if ($stmt->affected_rows > 0) {
        echo "<script>
                alert('✅ Detalle eliminado correctamente');
                window.location.href='add_detalle_prod.php';
             </script>";
    } else {
        echo "<script>
                alert('⚠️ No se encontró un detalle con ese ID');
                window.location.href='add_detalle_prod.php';
             </script>";
    }

} else {
    echo "<script>
            alert('❌ No se pudo eliminar el detalle: " . addslashes($stmt->error) . "');
            window.location.href='add_detalle_prod.php';
         </script>";
}

$stmt->close();
$conexion->close();
?>
