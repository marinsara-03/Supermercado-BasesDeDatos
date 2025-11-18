<?php
// Conexión a la base de datos
$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);
if ($conexion->connect_error) {
    die("Hubo un error al moemnto de conectar. Intentalo de nuevo" . $conexion->connect_error);
}

$mensaje = "";

// Procesar el formulario al enviarlo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nit_prov = $_POST['nit_prov'];
    $nom_prov = $_POST['nom_prov'];
    $tel_prov = $_POST['tel_prov'];
    $email_prov = $_POST['email_prov'];

    if (!empty($nit_prov) && !empty($nom_prov) && !empty($tel_prov) && !empty($email_prov)) {

        // Validar si el NIT ya existe
        $check = $conexion->query("SELECT * FROM proveedores WHERE nit_prov='$nit_prov'");
        if ($check->num_rows > 0) {
            $mensaje = "❌ El NIT '$nit_prov' ya existe.";
        } else {
            $sql = "INSERT INTO proveedores (nit_prov, nom_prov, tel_prov, email_prov)
                    VALUES ('$nit_prov', '$nom_prov', '$tel_prov', '$email_prov')";
            
            if ($conexion->query($sql) === TRUE) {
                $mensaje = "✅ Proveedor agregado exitosamente.";
            } else {
                $mensaje = "❌ Error al agregar proveedor: " . $conexion->error;
            }
        }

    } else {
        $mensaje = "⚠️ Por favor, completa todos los campos.";
    }
}
$proveedores = $conexion->query("SELECT * FROM proveedores ORDER BY nom_prov ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="agregar_proveedores.css">
    <title>Proveedores</title>
</head>
<body>
    <?php include __DIR__ . '/menu.php'; ?>
    <div class="contenedor">

    <!-- FORMULARIO A LA IZQUIERDA -->
        <div class="formulario">
            <h2>Agregar Nuevo Proveedor</h2>

            <?php if ($mensaje): ?>
                <p class="mensaje"><?= $mensaje ?></p>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="text" name="nit_prov" placeholder="NIT del proveedor" maxlength="11" required>
                <input type="text" name="nom_prov" placeholder="Nombre del proveedor" maxlength="30" required>
                <input type="text" name="tel_prov" placeholder="Teléfono" maxlength="10" required>
                <input type="email" name="email_prov" placeholder="Correo electrónico" maxlength="40" required>
                <button type="submit">💖 Guardar Proveedor</button>
            </form>

            <a href="proveedor_producto.php" class="btn-rosa">Asociar proveedor con producto</a>
        </div>

        <!-- TABLA A LA DERECHA -->
        <div class="tabla">
            <h2>Proveedores Registrados</h2>

            <table>
                <tr>
                    <th>NIT</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
                <?php while ($row = $proveedores->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['nit_prov'] ?></td>
                    <td><?= $row['nom_prov'] ?></td>
                    <td><?= $row['tel_prov'] ?></td>
                    <td><?= $row['email_prov'] ?></td>
                    <td>
                        <a class="btn-editar" href="editar_proveedor.php?nit_prov=<?= $row['nit_prov'] ?>">Editar</a>
                        <a class="btn-eliminar" href="eliminar_proveedor.php?nit_prov=<?= $row['nit_prov'] ?>"
                        onclick="return confirm('¿Seguro que deseas eliminar este proveedor?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

    </div>
</body>
</html>
