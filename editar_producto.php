<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$mensaje = "";

if (!isset($_GET['id'])) {
    die("❌ Error: No recibimos el ID del producto.");
}

$id = $_GET['id'];

$sql = "SELECT * FROM productos WHERE cod_pro = '$id'";
$result = $conexion->query($sql);

if ($result->num_rows == 0) {
    die("❌ No existe un producto con ese código.");
}

$producto = $result->fetch_assoc();

// Actualizar producto
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nom = $_POST['nom_pro'];
    $cant = $_POST['cant_pro'];
    $fec = $_POST['fec_vec_pro'];
    $val = $_POST['val_pro'];
    $cat = $_POST['cod_cat'];

    if (!empty($nom) && !empty($cant) && !empty($fec) && !empty($val) && !empty($cat)) {

        $update = "UPDATE productos 
                   SET nom_pro='$nom', cant_pro='$cant', fec_vec_pro='$fec',
                       val_pro='$val', cod_cat='$cat'
                   WHERE cod_pro='$id'";

        if ($conexion->query($update) === TRUE) {
            $mensaje = "✅ Producto actualizado correctamente.";

            // actualizar datos en pantalla
            $producto = [
                "nom_pro" => $nom,
                "cant_pro" => $cant,
                "fec_vec_pro" => $fec,
                "val_pro" => $val,
                "cod_cat" => $cat
            ];

        } else {
            $mensaje = "❌ Error al actualizar: " . $conexion->error;
        }

    } else {
        $mensaje = "⚠️ Todos los campos son obligatorios.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar producto</title>
    <<link rel="stylesheet" href="editar_producto.css">

</head>

<body>
    <?php include __DIR__ . '/menu.php'; ?>

<div class="contenedor-flex">

    <div class="formulario">
        <h2>Editar producto</h2>

        <form method="POST">

            <input type="text" name="nom_pro" 
                   placeholder="Nombre del producto"
                   value="<?= $producto['nom_pro'] ?>"><br>

            <input type="number" name="cant_pro" 
                   placeholder="Cantidad"
                   value="<?= $producto['cant_pro'] ?>"><br>

            <input type="date" name="fec_vec_pro" 
                   value="<?= $producto['fec_vec_pro'] ?>"><br>

            <input type="number" name="val_pro" 
                   placeholder="Valor"
                   value="<?= $producto['val_pro'] ?>"><br>

            <input type="text" name="cod_cat" 
                   placeholder="Categoría"
                   value="<?= $producto['cod_cat'] ?>"><br>

            <input type="submit" value="Guardar cambios" />
            <a class="btn-volver" href="ver_productos.php">Volver</a>
        </form>

        <p><?= $mensaje ?></p>
    </div>

    <div class="tabla">
        <h2>Datos actuales</h2>

        <table>
            <tr><th>Campo</th><th>Valor</th></tr>
            <tr><td>Código</td><td><?= $id ?></td></tr>
            <tr><td>Nombre</td><td><?= $producto['nom_pro'] ?></td></tr>
            <tr><td>Cantidad</td><td><?= $producto['cant_pro'] ?></td></tr>
            <tr><td>Fecha vencimiento</td><td><?= $producto['fec_vec_pro'] ?></td></tr>
            <tr><td>Valor</td><td><?= $producto['val_pro'] ?></td></tr>
            <tr><td>Categoría</td><td><?= $producto['cod_cat'] ?></td></tr>
        </table>
    </div>

</div>

</body>
</html>
