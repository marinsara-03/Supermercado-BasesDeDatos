<?php
$actual = basename($_SERVER['PHP_SELF']);
?>
<nav class="topmenu">
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
<style>
:root{ --bg:#fff1f7; --panel:#ffe4ef; --accent:#ec4899; --accent-600:#db2777; --text:#3b3b3b; --muted:#6b7280; --border:#ffd2e3; }
.topmenu{
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;
  display:flex; align-items:center; justify-content:space-between; gap:8px;
  padding:12px 16px;
  background:linear-gradient(180deg,#fff, #fff8fb);
  border-bottom:1px solid var(--border);
  box-shadow: 0 6px 18px rgba(236,72,153,.12);
}
.brand{ font-weight:800; color:var(--accent-600); text-decoration:none; background:linear-gradient(135deg,#fbcfe8,#f472b6); -webkit-background-clip:text; background-clip:text; color:transparent; }
.links{ list-style:none; display:flex; gap:10px; margin:0; padding:0; }
.links a{ display:block; padding:8px 12px; border-radius:10px; text-decoration:none; color:var(--accent-600); border:1px solid transparent; transition:.18s ease; }
.links a:hover{ background:#ffe4ef; border-color:var(--border); }
.links a.active{ background:var(--accent); color:#fff; border-color:var(--accent); }
@media (max-width:700px){ .topmenu{ flex-wrap:wrap } .links{ width:100%; flex-wrap:wrap; justify-content:space-between } .links a{ flex:1 1 auto; text-align:center } }
</style>