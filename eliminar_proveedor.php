<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);
$conexion->set_charset("utf8mb4");

if ($conexion->connect_error) {
    die("Error al conectar: " . $conexion->connect_error);
}

$mensaje = "";
$tipo_mensaje = ""; // "exito" o "error"

if (isset($_GET['nit_prov'])) {
    $nit_prov = $_GET['nit_prov'];

    // Verificar que el proveedor exista
    $check = $conexion->query("SELECT * FROM proveedores WHERE nit_prov='$nit_prov'");
    if ($check->num_rows > 0) {

        // Verificar si tiene productos asociados
        $asociados = $conexion->query("SELECT * FROM proveedores_productos WHERE nit_prov='$nit_prov'");
        if ($asociados->num_rows > 0) {
            $mensaje = "❌ No es posible eliminar este proveedor. Tiene productos asociados.";
            $tipo_mensaje = "error";
        } else {
            // Se puede eliminar
            $sql = "DELETE FROM proveedores WHERE nit_prov='$nit_prov'";
            if ($conexion->query($sql) === TRUE) {
                $mensaje = "✅ Eliminación exitosa.";
                $tipo_mensaje = "exito";
            } else {
                $mensaje = "❌ Error al eliminar el proveedor: " . $conexion->error;
                $tipo_mensaje = "error";
            }
        }

    } else {
        $mensaje = "❌ El proveedor no existe.";
        $tipo_mensaje = "error";
    }

} else {
    $mensaje = "❌ NIT no válido.";
    $tipo_mensaje = "error";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Proveedor</title>
    <link rel="stylesheet" href="agregar_proveedores.css">
</head>
<body>
<?php include __DIR__ . '/menu.php'; ?>

<div class="contenedor" style="max-width:600px; text-align:center;">
    <h2>Eliminar Proveedor</h2>

    <?php if ($mensaje): ?>
        <p class="<?= $tipo_mensaje ?>"><?= $mensaje ?></p>
    <?php endif; ?>

    <a href="agregar_proveedores.php">
        <button style="margin-top:20px;">🔙 Volver a proveedores</button>
    </a>
</div>
</body>
</html>
