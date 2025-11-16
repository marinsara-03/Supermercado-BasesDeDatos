<?php

$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);

if ($conexion->connect_error) {
    die("Hubo un error al conectar: " . $conexion->connect_error);
}

$mensaje = "";
$tipo_mensaje = "";

/* ============= ELIMINAR ============= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["eliminar_cod"])) {
    $elim = trim($_POST["eliminar_cod"]);
    if ($elim !== "") {
        $stmt = $conexion->prepare("DELETE FROM categorias WHERE cod_cat = ?");
        $stmt->bind_param("s", $elim);
        if ($stmt->execute()) {
            $mensaje = "Categoría eliminada correctamente.";
            $tipo_mensaje = "exito";
        } else {
            $mensaje = "No se pudo eliminar: " . $conexion->error;
            $tipo_mensaje = "error";
        }
        $stmt->close();
    }
}

/* ============= INSERTAR ============= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["cod_cat"], $_POST["nom_cat"]) && !isset($_POST["eliminar_cod"])) {
    
    $cod_cat = strtoupper(trim($_POST["cod_cat"]));
    $nom_cat = trim($_POST["nom_cat"]);

    if ($cod_cat === "" || $nom_cat === "") {
        $mensaje = "Por favor completa todos los campos.";
        $tipo_mensaje = "error";
    } else {
        $stmt = $conexion->prepare("INSERT INTO categorias (cod_cat, nom_cat) VALUES (?, ?)");
        $stmt->bind_param("ss", $cod_cat, $nom_cat);

        if ($stmt->execute()) {
            $mensaje = "Categoría agregada correctamente.";
            $tipo_mensaje = "exito";
        } else {
            $mensaje = ($conexion->errno === 1062)
                ? "El código ingresado ya existe."
                : "Error al guardar: " . $conexion->error;

            $tipo_mensaje = "error";
        }
        $stmt->close();
    }
}

/* ============= CONSULTAR CATEGORÍAS ============= */
$categorias = [];
$res = $conexion->query("SELECT cod_cat, nom_cat FROM categorias ORDER BY cod_cat ASC");
while ($row = $res->fetch_assoc()) {
    $categorias[] = $row;
}
$res->close();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categorías</title>
    <link rel="stylesheet" href="agregar_categoria.css">
</head>

<body>
<?php include __DIR__ . '/menu.php'; ?>
<div class="layout">

    <!-- PANEL IZQUIERDO -->
    <div class="panel">
        <h2>Nueva Categoría</h2>

        <?php if (!empty($mensaje)): ?>
        <div class="mensaje <?php echo htmlspecialchars($tipo_mensaje); ?>">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="cod_cat" maxlength="5" placeholder="Código (máx. 5)">
            <input type="text" name="nom_cat" maxlength="20" placeholder="Nombre (máx. 20)">
            <button type="submit" class="btn-guardar">Guardar</button>
        </form>
    </div>

    <!-- PANEL DERECHO -->
    <div class="panel">
        <h2>Categorías Registradas</h2>

        <?php if (!empty($categorias)): ?>
        <table class="tabla">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $c): ?>
                <tr>
                    <td><?php echo htmlspecialchars($c["cod_cat"]); ?></td>
                    <td><?php echo htmlspecialchars($c["nom_cat"]); ?></td>
                    <td>
                       <a href="ver_productos.php?categoria=<?php echo $c['cod_cat']; ?>" class="btn-ver">Ver</a>
                        <form method="POST" onsubmit="return confirm('¿Eliminar categoría?')">
                            <input type="hidden" name="eliminar_cod" value="<?php echo $c["cod_cat"]; ?>">
                            <button class="btn-eliminar">Eliminar</button>
                        </form>
                    </td>
                </tr>
                
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php else: ?>
        <div class="mensaje">No hay categorías registradas.</div>
        <?php endif; ?>

    </div>

</div>

</body>
</html>
