<?php

$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>
        alert('❌ No se recibió un ID válido.');
        window.location='ver_productos.php';
    </script>";
    exit;
}

$id = $_GET['id'];

$sql = "DELETE FROM productos WHERE cod_pro = '$id'";

if ($conexion->query($sql) === TRUE) {
    echo "<script>
        alert('✔ Producto eliminado correctamente');
        window.location='ver_productos.php';
    </script>";
} else {
    echo "<script>
        alert('❌ Error al eliminar el producto');
        window.location='ver_productos.php';
    </script>";
}

$conexion->close();
?>
