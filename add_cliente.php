<?php
$server = "localhost";
$user = "root";
$pass = "";
$db   = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);
if ($conexion->connect_error) {
    die("Hubo un error al momento de conectar. Inténtalo de nuevo: " . $conexion->connect_error);
}
$conexion->set_charset("utf8mb4");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_cli  = filter_input(INPUT_POST, 'ide_cli', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
    $nom_cli = filter_input(INPUT_POST, 'nom_cli', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
    $tel_cli = filter_input(INPUT_POST, 'tel_cli', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
    $dir_cli = filter_input(INPUT_POST, 'dir_cli', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';

    if ($id_cli !== '' && $nom_cli !== '' && $tel_cli !== '' && $dir_cli !== '') {
        $stmt = $conexion->prepare(
            "INSERT INTO clientes (ide_cli, nom_cli, tel_cli, dir_cli) VALUES (?, ?, ?, ?)"
        );
        if ($stmt) {
            $stmt->bind_param("ssss", $id_cli, $nom_cli, $tel_cli, $dir_cli);
            if ($stmt->execute()) {
                $mensaje = "✅ El cliente ha sido agregado exitosamente.";
            } else {
                $mensaje = "❌ Lo sentimos, no se pudo agregar el cliente. Intentalo nuevamente " . $stmt->error;
            }
            $stmt->close();
        } else {
            $mensaje = "❌ Error preparando la consulta: " . $conexion->error;
        }
    } else {
        $mensaje = "⚠️ Por favor completa todos los campos.";
    }
}

$consulta = "SELECT * FROM clientes ORDER BY ide_cli ASC";
$clientes = $conexion->query($consulta);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Cliente</title>
    <link rel="stylesheet" href="add_cliente.css">
</head>
<body>
<?php include __DIR__ . '/menu.php'; ?>

<div class="contenedor-flex">
    <section class="formulario">
        <h2>Agregar Nuevo Cliente</h2>

        <?php if (!empty($mensaje)): ?>
            <p class="mensaje"><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="ide_cli" placeholder="Identificación del cliente" maxlength="11" required><br>
            <input type="text" name="nom_cli" placeholder="Nombre del cliente" maxlength="30" required><br>
            <input type="text" name="tel_cli" placeholder="Teléfono" maxlength="10" required><br>
            <input type="text" name="dir_cli" placeholder="Dirección" maxlength="30" required><br>

            <div class="btn-row">
                <input type="submit" value="Guardar Cliente">
            </div>
        </form>
        </section>

    <div class="tabla">
        <h2>Lista de Clientes</h2>

        <div class="table-wrap">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th class="actions">Acciones</th>
                </tr>

                <?php
                if ($clientes && $clientes->num_rows > 0) {
                    while ($c = $clientes->fetch_assoc()) {
                        $ide = htmlspecialchars($c['ide_cli']);
                        $nom = htmlspecialchars($c['nom_cli']);
                        $tel = htmlspecialchars($c['tel_cli']);
                        $dir = htmlspecialchars($c['dir_cli']);

                        echo '<tr>';
                        echo "  <td>{$ide}</td>";
                        echo "  <td>{$nom}</td>";
                        echo "  <td>{$tel}</td>";
                        echo "  <td>{$dir}</td>";
                        echo '  <td class="actions">';
                        echo "    <a href='editar_cliente.php?id={$ide}' class='btn-editar'>Editar</a> ";
                        echo "    <a href='eliminar_cliente.php?id={$ide}' class='btn-eliminar' onclick='return confirm(\"¿Eliminar este cliente?\")'>Eliminar</a>";
                        echo '  </td>';
                        echo '</tr>';
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay clientes registrados.</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>