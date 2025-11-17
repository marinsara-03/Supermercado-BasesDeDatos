<?php

$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);
if ($conexion->connect_error) {
    die("Hubo un error al conectar: " . $conexion->connect_error);
}

$mensaje = "";

// ---- GUARDAR DETALLE ----
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nro_fac = $_POST['nro_fac'];
    $cod_pro = $_POST['cod_pro'];
    $cant_prod = $_POST['cant_prod'];
    $val_unit_prod = $_POST['val_unit_prod'];
    $val_total_prod = $_POST['val_total_prod'];

    if (!empty($nro_fac) && !empty($cod_pro) && !empty($cant_prod) && !empty($val_unit_prod) && !empty($val_total_prod)) {

        $sql = "INSERT INTO detalle_productos (nro_fac, cod_pro, cant_prod, val_unit_prod, val_total_prod)
                VALUES ('$nro_fac', '$cod_pro', '$cant_prod', '$val_unit_prod', '$val_total_prod')";
        
        if ($conexion->query($sql) === TRUE) {
            $mensaje = "✅ Detalle agregado exitosamente.";
        } else {
            $mensaje = "❌ Error al agregar detalle: " . $conexion->error;
        }
    } else {
        $mensaje = "⚠️ Completa todos los campos.";
    }
}

// ---- CONSULTAR DETALLES ----
$detalles = $conexion->query("SELECT * FROM detalle_productos");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="add_detalle_prod.css">
    <title>Agregar Detalle Producto</title>
</head>
<body>

<?php include __DIR__ . '/menu.php'; ?>

<div class="contenedor_principal">

    <!-- FORMULARIO (IZQUIERDA) -->
    <div class="contenedor formulario">
        <h2>Agregar Detalle</h2>

        <form method="POST">
            <input type="text" name="nro_fac" placeholder="Número de Factura"><br>
            <input type="text" name="cod_pro" placeholder="Código del Producto"><br>
            <input type="number" name="cant_prod" placeholder="Cantidad"><br>
            <input type="number" step="0.01" name="val_unit_prod" placeholder="Valor Unitario"><br>
            <input type="number" step="0.01" name="val_total_prod" placeholder="Valor Total"><br>
            <input type="submit" value="Guardar Detalle">
        </form>

        <?php if ($mensaje): ?>
            <p class="mensaje"><?= $mensaje ?></p>
        <?php endif; ?>
    </div>

    <!-- TABLA (DERECHA) -->
    <div class="contenedor tabla">
        <h2>Detalles Registrados</h2>

        <table>
            <tr>
                <th>Nro Factura</th>
                <th>Cód Producto</th>
                <th>Cantidad</th>
                <th>Valor Unit.</th>
                <th>Valor Total</th>
            </tr>

            <?php while ($row = $detalles->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['nro_fac'] ?></td>
                <td><?= $row['cod_pro'] ?></td>
                <td><?= $row['cant_prod'] ?></td>
                <td><?= $row['val_unit_prod'] ?></td>
                <td><?= $row['val_total_prod'] ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>

</div>

</body>
</html>
