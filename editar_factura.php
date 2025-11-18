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

$mensaje = "";

/* Validar parámetro */
$nro = filter_input(INPUT_GET, 'nro', FILTER_VALIDATE_INT);
if ($nro === null || $nro === false) {
    die("❌ No recibimos el número de factura.");
}

/* Cargar listas para selects */
$clientes = [];
if ($res = $conexion->query("SELECT ide_cli, nom_cli FROM clientes ORDER BY nom_cli ASC")) {
    while ($r = $res->fetch_assoc()) { $clientes[] = $r; }
    $res->free();
} else {
    $mensaje = "⚠️ No se pudieron cargar los clientes: " . $conexion->error;
}

$cajeros = [];
if ($res = $conexion->query("SELECT ide_caj, nom_caj FROM cajeros ORDER BY nom_caj ASC")) {
    while ($r = $res->fetch_assoc()) { $cajeros[] = $r; }
    $res->free();
} else {
    $mensaje = "⚠️ No se pudieron cargar los cajeros: " . $conexion->error;
}

/* Obtener factura actual */
$stmt = $conexion->prepare("SELECT nro_fac, val_tot_fac, fec_fac, ide_cli, ide_caj FROM facturas WHERE nro_fac = ?");
$stmt->bind_param("i", $nro);
$stmt->execute();
$fact = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$fact) {
    die("❌ No existe una factura con ese número.");
}

/* Procesar actualización */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $val_tot_fac = filter_input(INPUT_POST, "val_tot_fac", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $fec_raw     = $_POST["fec_fac"] ?? "";
    $ide_cli     = filter_input(INPUT_POST, "ide_cli", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";
    $ide_caj     = filter_input(INPUT_POST, "ide_caj", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";

    // Normalizar fecha
    $fec_fac = str_replace('T', ' ', $fec_raw);
    if ($fec_fac !== "" && strlen($fec_fac) === 16) {
        $fec_fac .= ":00";
    }

    if ($val_tot_fac === null || $val_tot_fac === "" || $fec_fac === "" || $ide_cli === "" || $ide_caj === "") {
        $mensaje = "⚠️ Completa todos los campos.";
    } else {
        $val_tot_num = (float)$val_tot_fac;

        $upd = $conexion->prepare("UPDATE facturas SET val_tot_fac = ?, fec_fac = ?, ide_cli = ?, ide_caj = ? WHERE nro_fac = ?");
        if (!$upd) {
            $mensaje = "❌ Error preparando la consulta: " . $conexion->error;
        } else {
            $upd->bind_param("dsssi", $val_tot_num, $fec_fac, $ide_cli, $ide_caj, $nro);
            if ($upd->execute()) {
                $mensaje = "✅ Factura actualizada correctamente.";
                // Actualizar datos locales
                $fact['val_tot_fac'] = $val_tot_num;
                $fact['fec_fac']     = $fec_fac;
                $fact['ide_cli']     = $ide_cli;
                $fact['ide_caj']     = $ide_caj;
            } else {
                $mensaje = "❌ Error al actualizar: " . $upd->error;
            }
            $upd->close();
        }
    }
}

/* Convertir DATETIME a datetime-local */
$dtLocal = "";
if (!empty($fact['fec_fac'])) {
    // fec_fac esperado como "YYYY-MM-DD HH:MM:SS"
    $dtLocal = substr($fact['fec_fac'], 0, 16);
    $dtLocal = str_replace(' ', 'T', $dtLocal);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Factura</title>
    <link rel="stylesheet" href="editar_factura.css">
</head>
<body>
    <?php include __DIR__ . '/menu.php'; ?>
    <div class="contenedor">
        <h2>🧾 Editar Factura #<?= htmlspecialchars($fact['nro_fac']) ?></h2>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="number" value="<?= htmlspecialchars($fact['nro_fac']) ?>" readonly>

            <input type="number" step="0.01" name="val_tot_fac" required placeholder="Valor total de la factura"
                   value="<?= htmlspecialchars($fact['val_tot_fac']) ?>">

            <label>Fecha de la Factura:</label>
            <input type="datetime-local" name="fec_fac" required value="<?= htmlspecialchars($dtLocal) ?>">

            <label>Cliente:</label>
            <select name="ide_cli" required>
                <option value="">-- Selecciona un cliente --</option>
                <?php foreach ($clientes as $cli): ?>
                    <option value="<?= htmlspecialchars($cli['ide_cli']) ?>"
                        <?= $cli['ide_cli'] === $fact['ide_cli'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cli['nom_cli']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Cajero:</label>
            <select name="ide_caj" required>
                <option value="">-- Selecciona un cajero --</option>
                <?php foreach ($cajeros as $caj): ?>
                    <option value="<?= htmlspecialchars($caj['ide_caj']) ?>"
                        <?= $caj['ide_caj'] === $fact['ide_caj'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($caj['nom_caj']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">💾 Guardar cambios</button>
            <a class="btn-volver" href="lista_facturas.php">Volver</a>
        </form>
    </div>
</body>
</html>