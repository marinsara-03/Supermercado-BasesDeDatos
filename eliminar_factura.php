<?php
$server = "localhost";
$user = "root";
$pass = "";
$db   = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
$conexion->set_charset("utf8mb4");

$nro = filter_input(INPUT_GET, 'nro', FILTER_VALIDATE_INT);
if ($nro === null || $nro === false) {
    die("❌ No recibimos el número de factura.");
}

$stmt = $conexion->prepare("DELETE FROM facturas WHERE nro_fac = ?");
if (!$stmt) {
    die("❌ Error preparando la consulta: " . $conexion->error);
}
$stmt->bind_param("i", $nro);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo "<script>
            alert('✅ Factura eliminada correctamente');
            window.location.href='lista_facturas.php';
        </script>";
    } else {
        echo "<script>
            alert('⚠️ No se encontró una factura con ese número');
            window.location.href='lista_facturas.php';
        </script>";
    }
} else {
    echo "<script>
        alert('❌ No se pudo eliminar la factura: " . addslashes($stmt->error) . "');
        window.location.href='lista_facturas.php';
    </script>";
}

$stmt->close();
$conexion->close();