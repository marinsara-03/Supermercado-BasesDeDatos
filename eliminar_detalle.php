<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

// Conexión
$conn = new mysqli($server, $user, $pass, $db);
$conn->set_charset("utf8mb4");

// Validar parámetros
$nro_fac = $_GET['nro_fac'] ?? "";
$cod_pro = $_GET['cod_pro'] ?? "";

if ($nro_fac == "" || $cod_pro == "") {
    echo "❌ Parámetros inválidos.";
    exit;
}

// Ejecutar eliminación
$sql = "DELETE FROM detalle_productos 
        WHERE nro_fac = '$nro_fac' 
        AND cod_pro = '$cod_pro'";

if ($conn->query($sql)) {
    // Después de eliminar, regresar al formulario principal
    header("Location: add_detalle_prod.php?msg=detalle_eliminado");
    exit;
} else {
    echo "❌ Error al eliminar: " . $conn->error;
}
?>
