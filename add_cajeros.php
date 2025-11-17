<?php

$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);
if ($conexion->connect_error) {
    die("Hubo un error al momento de conectar. Inténtalo de nuevo: " . $conexion->connect_error);
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nom_cajero = $_POST['nom_cajero'];
    $tel_cajero = $_POST['tel_cajero'];
    $dir_cajero = $_POST['dir_cajero'];

    // VALIDACIÓN CORRECTA
    if (!empty($nom_cajero) && !empty($tel_cajero) && !empty($dir_cajero)) {

        // INSERT CORRECTO SEGÚN TU BASE DE DATOS
        $sql = "INSERT INTO cajeros (nom_caj, dir_caj, tel_caj)
                VALUES ('$nom_cajero', '$dir_cajero', '$tel_cajero')";

        if ($conexion->query($sql) === TRUE) {
            $mensaje = "✅ Cajero agregado exitosamente.";
        } else {
            $mensaje = "❌ Error al agregar cajero: " . $conexion->error;
        }

    } else {
        $mensaje = "⚠️ Por favor completa todos los campos.";
    }
}
$cajeros = $conexion->query("SELECT * FROM cajeros ORDER BY ide_caj ASC");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="add_cajeros.css">
    <title>Cajero</title>
</head>
<body>
    <?php include __DIR__ . '/menu.php'; ?>
 
<div class="contenedor-flex">

    
    <div class="formulario">
        <h2>Agregar Nuevo Cajero</h2>

        <form method="POST">
            <input type="text" name="nom_cajero" placeholder="Nombre del Cajero"><br>
            <input type="text" name="tel_cajero" placeholder="Teléfono"><br>
            <input type="text" name="dir_cajero" placeholder="Dirección">

            <input type="submit" value="Guardar Cajero">
        </form>

        <?php if ($mensaje): ?>
            <p class="mensaje"><?= $mensaje ?></p>
        <?php endif; ?>
    </div>

    <div class="tabla">
        <h2>Lista de Cajeros</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>

            <?php
            if ($cajeros->num_rows > 0) {
                while ($c = $cajeros->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>{$c['ide_caj']}</td>
                        <td>{$c['nom_caj']}</td>
                        <td>{$c['dir_caj']}</td>
                        <td>{$c['tel_caj']}</td>

                        <td>
                            <a href='editar_cajeros.php?id={$c['ide_caj']}' class='btn-editar'>Editar</a>
                            <a href='eliminar_cajero.php?id={$c['ide_caj']}' class='btn-eliminar' onclick='return confirm(\"¿Eliminar este cajero?\")'>Eliminar</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay cajeros registrados.</td></tr>";
            }
            ?>
        </table>
    </div>

</div>

</body>
</html>
