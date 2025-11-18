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
    die("❌ Error: No recibimos el ID del cajero, por favor vuelve a intentarlo.");
}

$id = $_GET['id'];


$sql = "SELECT * FROM cajeros WHERE ide_caj = '$id'";
$result = $conexion->query($sql);

if ($result->num_rows == 0) {
    die("❌ Lo sentimos, pero no hay un cajero con ese ID.");
}

$cajero = $result->fetch_assoc();

// 3. Actualizar los datos cuando envíen el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nom_caj = $_POST['nom_caj'];
    $tel_caj = $_POST['tel_caj'];
    $dir_caj = $_POST['dir_caj'];

    if (!empty($nom_caj) && !empty($tel_caj) && !empty($dir_caj)) {

        $update = "UPDATE cajeros 
            SET nom_caj='$nom_caj', tel_caj='$tel_caj', dir_caj='$dir_caj'
            WHERE ide_caj='$id'";

        if ($conexion->query($update) === TRUE) {
            $mensaje = "✅ Los datos fueron actualizados correctamente.";
            
            $cajero = [
                "nom_caj" => $nom_caj,
                "tel_caj" => $tel_caj,
                "dir_caj" => $dir_caj
            ];
        } else {
            $mensaje = "❌ Hubo un error al actualizar los datos, por favor intentalo de nuevo: " . $conexion->error;
        }
    } else {
        $mensaje = "⚠️ Todos los campos son obligatorios.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Editar cajero</title>
    <link rel="stylesheet" href="editar_cajeros.css" />
</head>
<body>
    <div class="contenedor-flex">

        <div class="formulario">
            <h2>Editar cajero</h2>

            <form method="POST">
                <input type="text" name="ide_caj" placeholder="Documento del cajero"><br>
                <input type="text" name="nom_caj" placeholder="Nombre del cajero"><br>
                <input type="text" name="tel_caj" placeholder="Teléfono"><br>
                <input type="text" name="dir_caj" placeholder="Dirección">

                <input type="submit" value="Guardar cambios" />
                <a class="btn-volver" href="lista_cajeros.php">Volver</a>
            </form>
        </div>

        <div class="tabla">
            <h2>Detalle actual</h2>
            <table>
                <tr>
                    <th>Campo</th>
                    <th>Valor</th>
                </tr>
                <tbody>
                <tr><td>ID</td><td><?= htmlspecialchars($id) ?></td></tr>
                <tr><td>Nombre</td><td><?= htmlspecialchars($cajero['nom_caj'] ?? '') ?></td></tr>
                <tr><td>Teléfono</td><td><?= htmlspecialchars($cajero['tel_caj'] ?? '') ?></td></tr>
                <tr><td>Dirección</td><td><?= htmlspecialchars($cajero['dir_caj'] ?? '') ?></td></tr>
                </tbody>
            </table>
            </div>
    </div>
</body>
</html>