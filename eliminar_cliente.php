<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conexion = new mysqli($server, $user, $pass, $db);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
$conexion->set_charset("utf8mb4");

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if ($id === null || $id === false || $id === '') {
    die("❌ No recibimos el ID del cliente.");
}

$stmt = $conexion->prepare("DELETE FROM clientes WHERE ide_cli = ?");
if (!$stmt) {
    die("❌ Error preparando la consulta: " . $conexion->error);
}
$stmt->bind_param("s", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo "<script>
            alert('✅ Cliente eliminado correctamente');
            window.location.href='add_cliente.php';
        </script>";
    } else {
        echo "<script>
            alert('⚠️ No se encontró un cliente con ese ID');
            window.location.href='add_cliente.php';
        </script>";
    }
} else {
    echo "<script>
        alert('❌ No se pudo eliminar el cliente: " . addslashes($stmt->error) . "');
        window.location.href='add_cliente.php';
    </script>";
}

$stmt->close();
$conexion->close();