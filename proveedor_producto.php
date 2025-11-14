<?php

$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);
if ($conexion->connect_error) {
    die("Hubo un error al momento de conectar. Intentalo de nuevo" . $conexion->connect_error);
}

$mensaje = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nit_prov = $_POST['nit_prov'];
    $cod_pro = $_POST['cod_pro'];

    if (!empty($nit_prov) && !empty($cod_pro)) {
        $sql = "INSERT INTO proveedores_productos (nit_prov, cod_pro) VALUES ('$nit_prov', '$cod_pro')";
        
        if ($conexion->query($sql) === TRUE) {
            $mensaje = "✅ Relación proveedor-producto agregada exitosamente.";
        } else {
            $mensaje = "❌ Error al agregar relación: " . $conexion->error;
        }
    } else {
        $mensaje = "⚠️ Por favor selecciona ambos campos.";
    }
}


$proveedores = $conexion->query("SELECT nit_prov, nom_prov FROM proveedores");
$productos = $conexion->query("SELECT cod_pro, nom_pro FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="proveedor_producto.css">
    <title>Asociar Proveedor con Producto</title>
</head>
<body>
    <div class="contenedor">
        <h2>Asociar Proveedor con Producto</h2>

        <form method="POST" action="">
            <label for="nit_prov">Proveedor:</label><br>
            <select name="nit_prov">
                <option value="">Selecciona un proveedor</option>
                <?php while($prov = $proveedores->fetch_assoc()): ?>
                    <option value="<?= $prov['nit_prov'] ?>"><?= $prov['nom_prov'] ?> (<?= $prov['nit_prov'] ?>)</option>
                <?php endwhile; ?>
            </select><br>

            <label for="cod_pro">Producto:</label><br>
            <select name="cod_pro">
                <option value="">Selecciona un producto</option>
                <?php while($prod = $productos->fetch_assoc()): ?>
                    <option value="<?= $prod['cod_pro'] ?>"><?= $prod['nom_pro'] ?> (<?= $prod['cod_pro'] ?>)</option>
                <?php endwhile; ?>
            </select><br>

            <input type="submit" value="Guardar Relación">
        </form>

        <?php if ($mensaje): ?>
            <p class="mensaje"><?= $mensaje ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
