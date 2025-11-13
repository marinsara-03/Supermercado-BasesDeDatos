<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "supermercado";

$conexion = new mysqli ($server, $user, $pass, $db);
if ($conexion->connect_error) {
    die("Conexion erronea" . $conexion->connect_errno);
} else {
    echo "La conexion fue exitosa";
}

?>