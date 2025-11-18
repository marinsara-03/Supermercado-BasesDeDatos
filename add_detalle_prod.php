<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conn = new mysqli($server, $user, $pass, $db);

$mensaje = "";
$conn->set_charset("utf8mb4");

/* ---------------------------------------------------
   GUARDAR NUEVO DETALLE
--------------------------------------------------- */
if (isset($_POST['guardar'])) {

    $nro_fac = $_POST['nro_fac'] ?? "";
    $cod_pro = $_POST['cod_pro'] ?? "";
    $cant_prod = $_POST['cant_prod'] ?? "";
    $val_unit_prod = $_POST['val_unit_prod'] ?? "";

    $val_total_prod = $cant_prod * $val_unit_prod;

    // Validar factura existente
    $check = $conn->query("SELECT * FROM facturas WHERE nro_fac = '$nro_fac'");

    if ($check->num_rows == 0) {
        $mensaje = "<div class='error'>❌ La factura <b>$nro_fac</b> no existe.</div>";
    } else {

        $sql = "INSERT INTO detalle_productos 
                (nro_fac, cod_pro, cant_prod, val_unit_prod, val_total_prod)
                VALUES 
                ('$nro_fac','$cod_pro','$cant_prod','$val_unit_prod','$val_total_prod')";
        
        if ($conn->query($sql)) {
            $mensaje = "<div class='exito'>✔️ Detalle guardado correctamente</div>";
        } else {
            $mensaje = "<div class='error'>❌ Error al guardar: " . $conn->error . "</div>";
        }
    }
}

/* ---------------------------------------------------
   CONSULTAR DETALLES
--------------------------------------------------- */
$detalles = $conn->query("SELECT * FROM detalle_productos ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Detalle de Productos</title>
<link rel="stylesheet" href="add_detalle_prod.css">
</head>

<body>

<?php include __DIR__ . '/menu.php'; ?>

<div class="contenedor">

    <!-- FORMULARIO -->
    <div class="formulario">
        <h2>Agregar Detalle</h2>

        <?php echo $mensaje; ?>

        <form method="POST">

            <label>Número de Factura:</label>
            <input type="number" name="nro_fac" required>

            <label>Código del Producto:</label>
            <input type="text" name="cod_pro" required>

            <label>Cantidad:</label>
            <input type="number" name="cant_prod" required>

            <label>Valor Unitario:</label>
            <input type="number" step="0.01" name="val_unit_prod" required>

            <button type="submit" name="guardar">Guardar</button>
        </form>
    </div>

    <!-- TABLA -->
    <div class="tabla">
        <h2>Detalles Registrados</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Factura</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Unitario</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>

            <?php while ($row = $detalles->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['nro_fac'] ?></td>
                <td><?= $row['cod_pro'] ?></td>
                <td><?= $row['cant_prod'] ?></td>
                <td><?= $row['val_unit_prod'] ?></td>
                <td><?= $row['val_total_prod'] ?></td>

                <td>
                    <a class="btn-editar" href="editar_detalle.php?id=<?= $row['id'] ?>">
                        Editar
                    </a>

                    <a class="btn-eliminar" 
                       href="eliminar_detalle.php?id=<?= $row['id'] ?>"
                       onclick="return confirm('¿Seguro que deseas eliminar este detalle?')">
                        Eliminar
                    </a>
                </td>
            </tr>
            <?php } ?>

        </table>
    </div>

</div>

</body>
</html>