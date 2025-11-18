<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
$conexion->set_charset("utf8mb4");

$mensaje = "";

if (!isset($_GET['id'])) {
    die("❌ Error: No recibimos el ID del cliente.");
}

$id = $_GET['id'];

$stmt = $conexion->prepare("SELECT * FROM clientes WHERE ide_cli = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    die("❌ No existe un cliente con ese ID.");
}
$cliente = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_cli = $_POST['nom_cli'] ?? '';
    $tel_cli = $_POST['tel_cli'] ?? '';
    $dir_cli = $_POST['dir_cli'] ?? '';

    if (!empty($nom_cli) && !empty($tel_cli) && !empty($dir_cli)) {
        $upd = $conexion->prepare("UPDATE clientes SET nom_cli = ?, tel_cli = ?, dir_cli = ? WHERE ide_cli = ?");
        $upd->bind_param("ssss", $nom_cli, $tel_cli, $dir_cli, $id);
        if ($upd->execute()) {
            $mensaje = "✅ Los datos fueron actualizados correctamente.";
            $cliente['nom_cli'] = $nom_cli;
            $cliente['tel_cli'] = $tel_cli;
            $cliente['dir_cli'] = $dir_cli;
        } else {
            $mensaje = "❌ Error al actualizar: " . $upd->error;
        }
        $upd->close();
    } else {
        $mensaje = "⚠️ Todos los campos son obligatorios.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="editar_cliente.css">
    <title>Editar Cliente</title>
</head>
<body>
<div class="contenedor-flex">
    <div class="formulario">
        <h2>Editar Cliente</h2>

        <?php if (!empty($mensaje)): ?>
            <p class="mensaje"><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" value="<?= htmlspecialchars($cliente['ide_cli']) ?>" readonly><br>
            <input type="text" name="nom_cli" value="<?= htmlspecialchars($cliente['nom_cli']) ?>"><br>
            <input type="text" name="tel_cli" value="<?= htmlspecialchars($cliente['tel_cli']) ?>"><br>
            <input type="text" name="dir_cli" value="<?= htmlspecialchars($cliente['dir_cli']) ?>"><br>

            <input type="submit" value="Guardar Cambios">
            <a class="btn-volver" href="add_cliente.php">Volver</a>
        </form>
    </div>

    <div class="tabla">
        <h2>Datos Actuales</h2>
        <table>
            <tr><th>Campo</th><th>Valor</th></tr>
            <tr><td>ID</td><td><?= htmlspecialchars($cliente['ide_cli']) ?></td></tr>
            <tr><td>Nombre</td><td><?= htmlspecialchars($cliente['nom_cli']) ?></td></tr>
            <tr><td>Teléfono</td><td><?= htmlspecialchars($cliente['tel_cli']) ?></td></tr>
            <tr><td>Dirección</td><td><?= htmlspecialchars($cliente['dir_cli']) ?></td></tr>
        </table>
    </div>
</div>
</body>
</html>