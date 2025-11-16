<?php
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Supermercado</title>
  <style>
    :root{
      --bg:#fff1f7;
      --panel:#ffe4ef;
      --accent:#ec4899;
      --accent-600:#db2777;
      --text:#3b3b3b;
      --muted:#6b7280;
    }
    *{box-sizing:border-box}
    body{margin:0; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; color:var(--text);
      background: radial-gradient(1200px 800px at 10% 0%, #ffeaf3, transparent),
                  radial-gradient(1200px 800px at 100% 100%, #ffeaf3, transparent), var(--bg);}
    .wrap{min-height:100dvh; display:grid; place-items:center; padding:24px}
    .card{width:100%; max-width:900px; background:linear-gradient(180deg, var(--panel), #fff);
      border:1px solid #ffd2e3; border-radius:20px; padding:32px; box-shadow:0 20px 40px rgba(236,72,153,.18)}
    .brand{display:flex; align-items:center; gap:12px;}
    .logo{width:48px; height:48px; border-radius:12px; background:linear-gradient(135deg, #fbcfe8, #f472b6);
      display:grid; place-items:center; color:white; font-size:24px; box-shadow:0 6px 18px rgba(236,72,153,.35)}
    h1{margin:0; font-size:28px; letter-spacing:.3px}
    .subtitle{margin-top:6px; color:var(--muted)}
    .grid{display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:14px; margin-top:28px}
    .btn{display:flex; align-items:center; justify-content:center; gap:10px; padding:16px 18px; border-radius:14px;
      border:1px solid #ffd2e3; background:white; color:var(--accent-600); font-weight:600; text-decoration:none;
      transition:.18s ease; box-shadow:0 6px 16px rgba(236,72,153,.12)}
    .btn:hover{transform:translateY(-1px); box-shadow:0 10px 22px rgba(236,72,153,.18)}
    .btn:active{transform:translateY(0)}
    .btn i{font-style:normal}
    .footer{margin-top:22px; font-size:14px; color:var(--muted); text-align:center}
    @media (max-width:640px){ .grid{grid-template-columns:1fr} .card{padding:24px} }
  </style>
</head>
<body>
  <div class="wrap">
    <main class="card">
      <div class="brand">
        <div class="logo">🛒</div>
        <div>
          <h1>Supermercado</h1>
          <div class="subtitle">Bienvenida, elige una sección para comenzar</div>
        </div>
      </div>

      <section class="grid">
        <a class="btn" href="agregar_productos.php"><i>🥑</i> Productos</a>
        <a class="btn" href="agregar_categoria.php"><i>🏷️</i> Categorías</a>
        <a class="btn" href="agregar_proveedores.php"><i>🧺</i> Proveedores</a>
        <a class="btn" href="add_cajeros.php"><i>👩‍💼</i> Cajeros</a>
        <a class="btn" href="add_cliente.php"><i>👩‍❤️‍👩</i> Clientes</a>
      </section>

      <div class="footer">Hecho con cariño</div>
    </main>
  </div>
</body>
</html>