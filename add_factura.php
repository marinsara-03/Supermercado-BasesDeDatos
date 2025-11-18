<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);
if ($conexion->connect_error) {
    die("Hubo un error al momento de conectar. Intentalo de nuevo " . $conexion->connect_error);
}

$clientes = [];
$sqlClientes = "SELECT ide_cli, nom_cli FROM Clientes";
$resClientes = $conexion->query($sqlClientes);
if ($resClientes->num_rows > 0) {
    while ($fila = $resClientes->fetch_assoc()) {
        $clientes[] = $fila;
    }
}

$cajeros = [];
$sqlCajeros = "SELECT ide_caj, nom_caj FROM Cajeros";
$resCajeros = $conexion->query($sqlCajeros);
if ($resCajeros->num_rows > 0) {
    while ($fila = $resCajeros->fetch_assoc()) {
        $cajeros[] = $fila;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nro_fac = $_POST['nro_fac'];
    $val_tot_fac = $_POST['val_tot_fac'];
    $fec_fac = $_POST['fec_fac'];
    $ide_cli = $_POST['ide_cli'];
    $ide_caj = $_POST['ide_caj'];

    $sql = "INSERT INTO facturas (nro_fac, val_tot_fac, fec_fac, ide_cli, ide_caj)
            VALUES ('$nro_fac', '$val_tot_fac', '$fec_fac', '$ide_cli', '$ide_caj')";

    if ($conexion->query($sql) === TRUE) {
        echo "<div class='exito'>💖 Factura agregada correctamente.</div>";
    } else {
        echo "<div class='error'>❌ Error: " . $conexion->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="add_factura.css">
    <title>Factura</title>
</head>
<body>
    <?php include __DIR__ . '/menu.php'; ?>
<div class="contenedor">

    <!-- FORMULARIO A LA IZQUIERDA -->
    <div class="formulario">
        <h2>🧾 Agregar Factura</h2>

        <?php if (!empty($mensaje)) echo $mensaje; ?>

        <form method="POST" action="">
            <input type="number" name="nro_fac" required placeholder="Número de factura">
            <input type="number" step="0.01" name="val_tot_fac" required placeholder="Valor total de la factura">

            <label>Fecha de la Factura:</label>
            <input type="datetime-local" name="fec_fac" required>

            <label>Cliente:</label>
            <select name="ide_cli" required>
                <option value="">-- Selecciona un cliente --</option>
                <?php foreach ($clientes as $cli): ?>
                    <option value="<?= $cli['ide_cli'] ?>"><?= $cli['nom_cli'] ?></option>
                <?php endforeach; ?>
            </select>

            <label>Cajero:</label>
            <select name="ide_caj" required>
                <option value="">-- Selecciona un cajero --</option>
                <?php foreach ($cajeros as $caj): ?>
                    <option value="<?= $caj['ide_caj'] ?>"><?= $caj['nom_caj'] ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">🛒 Agregar Factura</button>
        </form>
    </div>

    <!-- TABLA A LA DERECHA -->
    <div class="tabla">
        <h2>Facturas Registradas</h2>

        <table>
            <tr>
                <th>Número de Factura</th>
                <th>Cliente</th>
                <th>Cajero</th>
                <th>Valor Total</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>

            <?php
            $facturas = $conexion->query("
                SELECT f.nro_fac, f.val_tot_fac, f.fec_fac, 
                       c.nom_cli, ca.nom_caj
                FROM facturas f
                JOIN Clientes c ON f.ide_cli = c.ide_cli
                JOIN Cajeros ca ON f.ide_caj = ca.ide_caj
                ORDER BY f.nro_fac DESC
            ");

            while ($row = $facturas->fetch_assoc()):
            ?>
            <tr>
                <td><?= $row['nro_fac'] ?></td>
                <td><?= $row['nom_cli'] ?></td>
                <td><?= $row['nom_caj'] ?></td>
                <td><?= $row['val_tot_fac'] ?></td>
                <td><?= $row['fec_fac'] ?></td>
                <td>
                    <a class="btn-eliminar"
                       href="eliminar_factura.php?nro_fac=<?= $row['nro_fac'] ?>"
                       onclick="return confirm('¿Seguro que deseas eliminar esta factura?')">
                        Eliminar
                    </a>
                    <a href="imprimir_recibo.php?nro_fac=<?= $row['nro_fac'] ?>" target="_blank">
                        🖨️ Imprimir
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</div>
</body>
</html>