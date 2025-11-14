<?php

$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);


if ($conexion->connect_error) {
    die("Hubo un error al momento de conectar. Intentalo de nuevo " . $conexion->connect_error);
}


$mensaje = "";

// Cuando el usuario envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST['cod_cat'];
    $nombre = $_POST['nom_cat'];

    // Validar que los campos no estén vacíos
    if (!empty($codigo) && !empty($nombre)) {
        $sql = "INSERT INTO categorias (cod_cat, nom_cat) VALUES ('$codigo', '$nombre')";
        
        if ($conexion->query($sql) === TRUE) {
            $mensaje = "✅ Categoría agregada exitosamente.";
        } else {
            $mensaje = "❌ Error al agregar categoría: " . $conexion->error;
        }
    } else {
        $mensaje = "⚠️ Por favor, completa todos los campos.";
    }
}

$categorias = $conexion->query("SELECT cod_cat, nom_cat FROM categorias");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="agregar_categoria.css">
    <title>Agregar Categorías</title>
</head>
<body>
    <div class="contenedor">
        <h2>Agregar Nueva Categoría</h2>

        <form method="POST" action="">
            <input type="text" name="cod_cat" placeholder="Código de categoría" maxlength="5"><br>
            <input type="text" name="nom_cat" placeholder="Nombre de la categoría" maxlength="20"><br>
            <input type="submit" value="Guardar Categoría">
        </form>

        <?php if ($mensaje): ?>
            <p class="mensaje"><?= $mensaje ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
