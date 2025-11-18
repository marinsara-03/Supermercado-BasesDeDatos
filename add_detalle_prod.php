<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conn = new mysqli($server, $user, $pass, $db);

$mensaje = "";

// -----------------------------------
// ELIMINAR REGISTRO
// -----------------------------------
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM detalle_productos WHERE id = $id");
    $mensaje = "<div class='exito'>✔️ Registro eliminado correctamente.</div>";
}

// -----------------------------------
// ACTIVAR MODO EDICIÓN
// -----------------------------------
$modo_editar = false;
$editar_dato = null;

if (isset($_GET['editar'])) {
    $modo_editar = true;
    $id = $_GET['editar'];
    $resultado = $conn->query("SELECT * FROM detalle_productos WHERE id = $id");
    $editar_dato = $resultado->fetch_assoc();
}

// -----------------------------------
// GUARDAR CAMBIOS EDITADOS
// -----------------------------------
if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $nro_fac = $_POST['nro_fac'];
    $cod_pro = $_POST['cod_pro'];
    $cant_prod = $_POST['cant_prod'];
    $val_unit_prod = $_POST['val_unit_prod'];
    $val_total_prod = $cant_prod * $val_unit_prod;

    // Verificar que exista la factura
    $check = $conn->query("SELECT * FROM facturas WHERE nro_fac = '$nro_fac'");

    if ($check->num_rows == 0) {
        $mensaje = "<div class='error'>❌ El número de factura <b>$nro_fac</b> no existe. No se puede actualizar.</div>";
    } else {
        $sql = "UPDATE detalle_productos SET 
                nro_fac='$nro_fac',
                cod_pro='$cod_pro',
                cant_prod='$cant_prod',
                val_unit_prod='$val_unit_prod',
                val_total_prod='$val_total_prod'
                WHERE id = '$id'";

        if ($conn->query($sql)) {
            $mensaje = "<div class='exito'>✔️ Detalle actualizado correctamente</div>";
        } else {
            $mensaje = "<div class='error'>❌ Error al actualizar: " . $conn->error . "</div>";
        }
    }
}

// -----------------------------------
// GUARDAR NUEVO REGISTRO
// -----------------------------------
if (isset($_POST['guardar'])) {
    $nro_fac = $_POST['nro_fac'];
    $cod_pro = $_POST['cod_pro'];
    $cant_prod = $_POST['cant_prod'];
    $val_unit_prod = $_POST['val_unit_prod'];
    $val_total_prod = $cant_prod * $val_unit_prod;

    $check = $conn->query("SELECT * FROM facturas WHERE nro_fac = '$nro_fac'");

    if ($check->num_rows == 0) {
        $mensaje = "<div class='error'>❌ La factura <b>$nro_fac</b> no existe.</div>";
    } else {
        $sql = "INSERT INTO detalle_productos (nro_fac, cod_pro, cant_prod, val_unit_prod, val_total_prod)
                VALUES ('$nro_fac','$cod_pro','$cant_prod','$val_unit_prod','$val_total_prod')";
        
        if ($conn->query($sql)) {
            $mensaje = "<div class='exito'>✔️ Detalle guardado correctamente</div>";
        } else {
            $mensaje = "<div class='error'>❌ Error al guardar: " . $conn->error . "</div>";
        }
    }
}

// -----------------------------------
$detalles = $conn->query("SELECT * FROM detalle_productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Detalles de Producto</title>
<link rel="stylesheet" href="add_detalle_prod.css">
</head>

<body>
<?php include __DIR__ . '/menu.php'; ?> <?php include __DIR__ . '/menu.php'; ?>

<div class="contenedor">

    <!-- FORMULARIO -->
    <div class="formulario">
        <h2><?php echo $modo_editar ? "Editar Detalle" : "Agregar Detalle"; ?></h2>

        <?php echo $mensaje; ?>

        <form method="POST">

            <?php if ($modo_editar) { ?>
                <input type="hidden" name="id" value="<?php echo $editar_dato['id']; ?>">
            <?php } ?>

            <label>Número de Factura:</label>
            <input type="number" name="nro_fac" required
                   value="<?php echo $modo_editar ? $editar_dato['nro_fac'] : ''; ?>">

            <label>Código del Producto:</label>
            <input type="text" name="cod_pro" required
                   value="<?php echo $modo_editar ? $editar_dato['cod_pro'] : ''; ?>">

            <label>Cantidad:</label>
            <input type="number" name="cant_prod" required
                   value="<?php echo $modo_editar ? $editar_dato['cant_prod'] : ''; ?>">

            <label>Valor Unitario:</label>
            <input type="number" step="0.01" name="val_unit_prod" required
                   value="<?php echo $modo_editar ? $editar_dato['val_unit_prod'] : ''; ?>">

            <?php if ($modo_editar) { ?>
                <button type="submit" name="actualizar">Actualizar</button>
                <a href="add_detalle_prod.php" class="cancelar">Cancelar</a>
            <?php } else { ?>
                <button type="submit" name="guardar">Guardar</button>
            <?php } ?>
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
                    <a class="btn-editar" href="add_detalle_prod.php?editar=<?= $row['id'] ?>">Editar</a>
                    <a class="btn-eliminar" href="add_detalle_prod.php?eliminar=<?= $row['id'] ?>"
                       onclick="return confirm('¿Seguro que deseas eliminar este registro?')">Eliminar</a>
                </td>
            </tr>
            <?php } ?>

        </table>
    </div>

</div>

</body>
</html>