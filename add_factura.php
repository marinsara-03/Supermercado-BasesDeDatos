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
    <title>Agregar Factura</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="contenedor">
        <h2>🧾 Agregar Factura</h2>
        <form method="POST" action="">
            <label>Número de Factura:</label>
            <input type="number" name="nro_fac" required>

            <label>Valor Total:</label>
            <input type="number" step="0.01" name="val_tot_fac" required>

            <label>Fecha de la Factura:</label>
            <input type="datetime-local" name="fec_fac" required>

            <label>Cliente:</label>
            <select name="ide_cli" required>
                <option value="">-- Selecciona un cliente --</option>
                <?php
                foreach ($clientes as $cli) {
                    echo "<option value='{$cli['ide_cli']}'>{$cli['nom_cli']}</option>";
                }
                ?>
            </select>

            <label>Cajero:</label>
            <select name="ide_caj" required>
                <option value="">-- Selecciona un cajero --</option>
                <?php
                foreach ($cajeros as $caj) {
                    echo "<option value='{$caj['ide_caj']}'>{$caj['nom_caj']}</option>";
                }
                ?>
            </select>

            <button type="submit">🛒 Agregar Factura</button>
        </form>
    </div>
</body>
</html>