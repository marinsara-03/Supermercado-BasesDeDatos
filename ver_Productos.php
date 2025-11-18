<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener categorías
$categorias = $conexion->query("SELECT cod_cat, nom_cat FROM categorias");

// Filtrar productos por categoría
$productos = null;
if (isset($_GET['categoria']) && $_GET['categoria'] != "") {
    $categoria = $_GET['categoria'];
    $productos = $conexion->query("SELECT * FROM productos WHERE cod_cat = '$categoria'");
} else {
    $productos = $conexion->query("SELECT * FROM productos");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Productos</title>
    <link rel="stylesheet" href="ver_productos.css">
</head>
<body>

<?php include __DIR__ . '/menu.php'; ?>

<h2>🥑 Productos Registrados</h2>

<div class="contenedor">

    <!-- FILTRO -->
    <form method="GET" class="filtro">
        <label for="categoria">Filtrar por categoría:</label>
        <select name="categoria">
            <option value="">-- Ver todas --</option>

            <?php
            foreach ($categorias as $c) {
                $selected = (isset($_GET['categoria']) && $_GET['categoria'] == $c['cod_cat']) ? "selected" : "";
                echo "<option value='{$c['cod_cat']}' $selected>{$c['nom_cat']}</option>";
            }
            ?>
        </select>

        <button type="submit">Filtrar</button>
    </form>

    <!-- TABLA -->
    <table>
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Cantidad</th>
            <th>Fecha vencimiento</th>
            <th>Valor</th>
            <th>Categoría</th>
            <th>Detalle</th>
        </tr>

        <?php
        if ($productos->num_rows > 0) {
            while ($p = $productos->fetch_assoc()) {
                echo "
                <tr>
                    <td>{$p['cod_pro']}</td>
                    <td>{$p['nom_pro']}</td>
                    <td>{$p['cant_pro']}</td>
                    <td>{$p['fec_vec_pro']}</td>
                    <td>\${$p['val_pro']}</td>
                    <td>{$p['cod_cat']}</td>

                    <td class='acciones'>
                        <a href='add_detalle_prod.php?id={$p['cod_pro']}' class='btn-detalle'>
                            Ver detalle
                        </a>
                        <a href='editar_producto.php?id={$p['cod_pro']}' class='btn-editar'>
                            Editar
                        </a>

                        <a href='eliminar_producto.php?id={$p['cod_pro']}'
                           class='btn-eliminar'
                           onclick=\"return confirm('⚠️ ¿Estás segura de eliminar este producto? Esta acción no se puede deshacer.');\">
                           Eliminar
                        </a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No hay productos registrados.</td></tr>";
        }
        ?>
    </table>

</div>

</body>
</html>
