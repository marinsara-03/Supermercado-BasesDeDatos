<?php
$actual = basename($_SERVER['PHP_SELF']);
?>
<nav class="topmenu">
  <link rel="stylesheet" href="menu.css">

  <a class="brand" href="index.php">🛒 Supermercado</a>
  <ul class="links">
    <li><a href="index.php" class="<?php echo $actual==='index.php'?'active':''; ?>">Inicio</a></li>
    <li><a href="agregar_productos.php" class="<?php echo $actual==='agregar_productos.php'?'active':''; ?>">Productos</a></li>
    <li><a href="agregar_categoria.php" class="<?php echo $actual==='agregar_categoria.php'?'active':''; ?>">Categorías</a></li>
    <li><a href="agregar_proveedores.php" class="<?php echo $actual==='agregar_proveedores.php'?'active':''; ?>">Proveedores</a></li>
    <li><a href="add_cajeros.php" class="<?php echo $actual==='add_cajeros.php'?'active':''; ?>">Cajeros</a></li>
    <li><a href="add_cliente.php" class="<?php echo $actual==='add_cliente.php'?'active':''; ?>">Clientes</a></li>
    <li><a href="ver_productos.php" class="<?php echo $actual==='ver_productos.php'?'active':''; ?>">Inventario</a></li>
  </ul>
</nav>
