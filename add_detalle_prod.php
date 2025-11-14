<?php

$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);
if ($conexion->connect_error) {
    die("Hubo un error al moemnto de conectar. Intentalo de nuevo" . $conexion->connect_error);
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valor_uni_prod = $_POST['val_unit_prod'];
    $valor_total_prod = $_POST['val_total_prod'];
    $cantidad_prod = $_POST['cant_prod'];
    $nro_factura = $_POST['nro_fac'];
    $codigo_producto = $_POST['cod_pro'];

    if (!empty($valor_uni_prod) && !empty($valor_total_prod) && !empty($cantidad_prod) && !empty($nro_factura) && !empty($codigo_producto)) {
        $sql = "INSERT INTO clientes (val_unit_prod, val_total_prod, cant_prod, nro_fac, cad_pro)
                VALUES ('$valor_uni_prod', '$valor_total_prod', '$cantidad_prod', '$nro_factura', '$codigo_producto')";
        
        if ($conexion->query($sql) === TRUE) {
            $mensaje = "✅ Cliente agregado exitosamente.";
        } else {
            $mensaje = "❌ Error al agregar cliente: " . $conexion->error;
        }
    } else {
        $mensaje = "⚠️ Por favor completa todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="add_detalle_prod.css">
    <title>Agregar Detalle Producto</title>
</head>
<body>

    <div class="contenedor">
        <h2>Agregar Detalle de Producto</h2>

        <form method="POST" action="">
            <input type="text" name="id_det" placeholder="ID del Detalle" maxlength="10"><br>
            <input type="text" name="id_prod" placeholder="ID del Producto" maxlength="10"><br>
            <input type="number" name="cantidad" placeholder="Cantidad" min="1"><br>
            <input type="number" step="0.01" name="total" placeholder="Total"><br>

            <input type="submit" value="Guardar Detalle">
        </form>

        <?php if ($mensaje): ?>
            <p class="mensaje"><?= $mensaje ?></p>
        <?php endif; ?>
    </div>

</body>
</html>