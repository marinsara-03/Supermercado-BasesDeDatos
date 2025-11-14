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
    $id_cajero = $_POST['id_cajero'];
    $nom_cajero = $_POST['nom_cajero'];
    $tel_cajero = $_POST['tel_cajero'];
    $dir_cajero = $_POST['dir_cajero'];

    if (!empty($id_cajero) && !empty($nom_cajero) && !empty($tel_cajero) && !empty(dir_cajero)) {
        $sql = "INSERT INTO cajeros (id_cajero, nom_cajero, tel_cajero, dir_cajero)
                VALUES ('$id_cajero', '$nom_cajero', '$tel_cajero', '$dir_cajero')";

        if ($conexion->query($sql) === TRUE) {
            $mensaje = "✅ Cajero agregado exitosamente.";
        } else {
            $mensaje = "❌ Error al agregar cajero: " . $conexion->error;
        }
    } else {
        $mensaje = "⚠️ Por favor completa todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="add_cajeros.css">
    <title>Agregar Cajero</title>
</head>
<body>

    <div class="contenedor">
        <h2>Agregar Nuevo Cajero</h2>

        <form method="POST" action="">
            <input type="text" name="id_cajero" placeholder="ID del Cajero" maxlength="10"><br>
            <input type="text" name="nom_cajero" placeholder="Nombre del Cajero" maxlength="30"><br>
            <input type="text" name="tel_cajero" placeholder="Teléfono" maxlength="10"><br>
            <input type="text" name="dir_cajero" placeholder="Direccion" maxlength="10">

            <input type="submit" value="Guardar Cajero">
        </form>

        <?php if ($mensaje): ?>
            <p class="mensaje"><?= $mensaje ?></p>
        <?php endif; ?>
    </div>

</body>
</html>
