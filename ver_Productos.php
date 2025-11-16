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
    <style>
        body {
            background: #fdeaff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        h2 {
            margin-top: 30px;
            color: #c72ba5;
        }
        .contenedor {
            width: 90%;
            margin: 20px auto;
        }
        .filtro {
            margin-bottom: 20px;
        }
        select, button {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #d88ad8;
        }
        button {
            background: #e66ed6;
            color: white;
            cursor: pointer;
            border: none;
        }
        button:hover {
            background: #c457b8;
        }
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        th {
            background: #ffb3ec;
            padding: 12px;
            color: #6a006a;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ffd4fa;
        }
        tr:hover {
            background: #fff0fb;
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/menu.php'; ?>

<h2>🌸 Productos Registrados</h2>

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
