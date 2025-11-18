<?php

$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);
$conexion->set_charset("utf8mb4");


if ($conexion->connect_error) {
    die("Hubo un error al momento de conectar. Intentalo de nuevo" . $conexion->connect_error);
}


$mensaje = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo_producto  = $_POST['cod_pro'];
    $codigo_categoria = $_POST['cod_cat'];
    $fecha_ven_pro    = $_POST['fec_vec_pro']; 
    $cantidad_prod    = $_POST['cant_pro'];
    $nombre_prod      = $_POST['nom_pro'];
    
    $valor_producto   = $_POST['val_pro'];

    
    if (!empty($codigo_producto) && !empty($codigo_categoria) && !empty($fecha_ven_pro) && !empty($cantidad_prod) && !empty($nombre_prod) && !empty($valor_producto)) {

        // Verificar si el código de producto ya existe
        $check = $conexion->query("SELECT * FROM productos WHERE cod_pro='$codigo_producto'");
        if ($check->num_rows > 0) {
            $mensaje = "❌ El código de producto '$codigo_producto' ya existe.";
        } else {
            $sql = "INSERT INTO productos (cod_pro, cod_cat, fec_vec_pro, cant_pro, nom_pro, val_pro) 
                    VALUES ('$codigo_producto', '$codigo_categoria', '$fecha_ven_pro', '$cantidad_prod', '$nombre_prod', '$valor_producto')";

            if ($conexion->query($sql) === TRUE) {
                $mensaje = "✔️ El producto fue agregado exitosamente.";
            } else {
                $mensaje = "❌ Error al agregar el producto: " . $conexion->error;
            }
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
    <link rel="stylesheet" href="agregar_productos.css">
    <title>Agregar Productos</title>
    
</head>
<body>
    <?php include __DIR__ . '/menu.php'; ?>
<div class="contenedor">
        <h2>🛒 Agregar Producto</h2>
        <form method="POST" action="">
            <label>Código del producto:</label>
            <input type="text" name="cod_pro" required>

            <label>Nombre del producto:</label>
            <input type="text" name="nom_pro" required>

            <label>Cantidad:</label>
            <input type="number" name="cant_pro" required>

            <label>Fecha de vencimiento:</label>
            <input type="date" name="fec_vec_pro" required>

            <label>Valor del producto:</label>
            <input type="number" step="0.01" name="val_pro" required>

            <label>Categoría:</label>
            <select name="cod_cat" required>
                <option value="">-- Selecciona una categoría --</option>
                <?php
                foreach ($categorias as $cat) {
                    echo "<option value='{$cat['cod_cat']}'>{$cat['nom_cat']}</option>";
                }
                ?>
            </select>
            <button type="submit">💖 Agregar Producto</button>
            <button type="button" onclick="window.location.href='ver_productos.php'" style="margin-top: 15px;">
                📋 Ver Productos
            </button>
             <a href="proveedor_producto.php" class="btn-rosa">Asociar producto con proveedor</a>
        </form>
    </div>
</body>
</html>