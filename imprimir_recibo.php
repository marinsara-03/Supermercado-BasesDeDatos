<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conn = new mysqli($server, $user, $pass, $db);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Validar si enviaron nro_fac
if (!isset($_GET['nro_fac'])) {
    die("No se especificó una factura.");
}

$nro_fac = $_GET['nro_fac'];

/* ---------------------------------------------------
   CONSULTAR FACTURA
--------------------------------------------------- */
$sql = "
SELECT f.nro_fac, f.val_tot_fac, f.fec_fac,
       c.nom_cli, ca.nom_caj
FROM facturas f
JOIN Clientes c ON f.ide_cli = c.ide_cli
JOIN Cajeros ca ON f.ide_caj = ca.ide_caj
WHERE f.nro_fac = '$nro_fac'
";

$res = $conn->query($sql);

if ($res->num_rows == 0) {
    die("<h2>Factura no encontrada</h2>");
}

$factura = $res->fetch_assoc();

/* ---------------------------------------------------
   CONSULTAR DETALLES DE LA FACTURA
--------------------------------------------------- */
$detalles = $conn->query("
SELECT cod_pro, cant_prod, val_unit_prod, val_total_prod
FROM detalle_productos
WHERE nro_fac = '$nro_fac'
");
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
            <link rel="stylesheet" href="imprimir_recibo.css">
            <title>Factura #<?= $nro_fac ?>Recibo</title>
    </head>
    <?php include __DIR__ . '/menu.php'; ?>
    <body>

<h2>🧾 Supermercado</h2>

<div class="info">
    <p><strong>Factura:</strong> <?= $factura['nro_fac'] ?></p>
    <p><strong>Cliente:</strong> <?= $factura['nom_cli'] ?></p>
    <p><strong>Cajero:</strong> <?= $factura['nom_caj'] ?></p>
    <p><strong>Fecha:</strong> <?= $factura['fec_fac'] ?></p>
</div>

<table>
    <tr>
        <th>Producto</th>
        <th>Cant</th>
        <th>Unit</th>
        <th>Total</th>
    </tr>

    <?php while ($row = $detalles->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['cod_pro'] ?></td>
        <td><?= $row['cant_prod'] ?></td>
        <td><?= number_format($row['val_unit_prod'], 0) ?></td>
        <td><?= number_format($row['val_total_prod'], 0) ?></td>
    </tr>
    <?php } ?>
</table>

<p class="total">TOTAL: $<?= number_format($factura['val_tot_fac'], 0) ?></p>

<button class="btn-imprimir" onclick="window.print()">🖨️ Imprimir</button>


</body>
</html>
