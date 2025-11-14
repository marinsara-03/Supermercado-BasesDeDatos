<?php

$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);
if ($conexion->connect_error) {
    die("Hubo un error al moemnto de conectar. Intentalo de nuevo" . $conexion->connect_error);
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cli = $_POST['id_cli'];
    $nom_cli = $_POST['nom_cli'];
    $tel_cli = $_POST['tel_cli'];
    $dir_cli = $_POST['dir_cli'];
    $email_cli = $_POST['email_cli'];

    if (!empty($id_cli) && !empty($nom_cli) && !empty($tel_cli) && !empty($dir_cli) && !empty($email_cli)) {
        $sql = "INSERT INTO clientes (id_cli, nom_cli, tel_cli, dir_cli, email_cli)
                VALUES ('$id_cli', '$nom_cli', '$tel_cli', '$dir_cli', '$email_cli')";
        
        if ($conexion->query($sql) === TRUE) {
            $mensaje = "✅ Cliente agregado exitosamente.";
        } else {
            $mensaje = "❌ Error al agregar cliente: " . $conexion->error;
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
    <link rel="stylesheet" href="add_cliente.css">
    <title>Agregar Cliente</title>
    </head>
    <body>
        <div class="contenedor">
            <h2>Agregar Nuevo Cliente</h2>

            <form method="POST" action="">
                <input type="text" name="id_cli" placeholder="Identificación del cliente" maxlength="11"><br>
                <input type="text" name="nom_cli" placeholder="Nombre del cliente" maxlength="30"><br>
                <input type="text" name="tel_cli" placeholder="Teléfono" maxlength="10"><br>
                <input type="text" name="dir_cli" placeholder="Dirección" maxlength="30"><br>
                <input type="email" name="email_cli" placeholder="Correo electrónico" maxlength="40"><br>

                <input type="submit" value="Guardar Cliente">
            </form>

            <?php if ($mensaje): ?>
                <p class="mensaje"><?= $mensaje ?></p>
            <?php endif; ?>
        </div>
    </body>
</html>