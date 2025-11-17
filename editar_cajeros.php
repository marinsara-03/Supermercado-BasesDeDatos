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
                   WHERE ide_caj='$id";

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