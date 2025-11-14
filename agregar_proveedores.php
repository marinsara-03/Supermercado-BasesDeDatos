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
        $sql = "INSERT INTO proveedores (nit_prov, nom_prov, tel_prov, email_prov)
                VALUES ('$nit_prov', '$nom_prov', '$tel_prov', '$email_prov')";
        
        if ($conexion->query($sql) === TRUE) {
            $mensaje = "✅ Proveedor agregado exitosamente.";
        } else {
            $mensaje = "❌ Error al agregar proveedor: " . $conexion->error;
        }
    } else {
        $mensaje = "⚠️ Por favor, completa todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="agregar_proveedores.css">
    <title>Agregar Proveedores</title>
</head>
<body>
    <div class="contenedor">
        <h2>Agregar Nuevo Proveedor</h2>

        <form method="POST" action="">
            <input type="text" name="nit_prov" placeholder="NIT del proveedor" maxlength="11"><br>
            <input type="text" name="nom_prov" placeholder="Nombre del proveedor" maxlength="30"><br>
            <input type="text" name="tel_prov" placeholder="Teléfono" maxlength="10"><br>
            <input type="email" name="email_prov" placeholder="Correo electrónico" maxlength="40"><br>
            <input type="submit" value="Guardar Proveedor">
        </form>

        <?php if ($mensaje): ?>
            <p class="mensaje"><?= $mensaje ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
