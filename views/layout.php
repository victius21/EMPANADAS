<?php // views/layout.php ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($title ?? 'Empanadas COL-MX') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      min-height: 100vh;
      background: radial-gradient(circle at top, #FCD116 0, #ffffff 40%, #0033A0 100%);
      font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }
    .navbar-colombia {
      background: linear-gradient(90deg,#0033A0,#CE1126);
    }
    .navbar-brand span.co {
      color:#FCD116;
    }
    .navbar-brand span.mx {
      color:#00A550;
    }
    .card {
      border-radius: 1rem;
      box-shadow: 0 10px 25px rgba(0,0,0,.18);
    }
    .badge-co {
      background:#FCD116;
      color:#0033A0;
    }
    .badge-mx {
      background:#00A550;
      color:#ffffff;
    }
    .hero {
      background: rgba(255,255,255,0.9);
      border-radius: 1.5rem;
      box-shadow: 0 10px 35px rgba(0,0,0,.2);
    }
    footer {
      font-size: .8rem;
      color:#ffffffcc;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-colombia mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">
      🌮🥟 <span class="co">Empanadas</span> <span class="mx">COL-MX</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if(isset($_SESSION['user'])): ?>

          <?php if($_SESSION['user']['rol'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" href="index.php?action=admin">Dashboard</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="index.php?action=admin-prod">Productos</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="index.php?action=cocina">Cocina</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="index.php?action=repartidor">Repartidor</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="index.php?action=inventario">Inventario</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="index.php?action=inventario-corte">Corte de Caja</a>
            </li>

            <!-- ✅ NUEVO: INSUMOS -->
            <li class="nav-item">
              <a class="nav-link" href="index.php?action=insumos">Insumos</a>
            </li>

          <?php elseif($_SESSION['user']['rol'] === 'cocina'): ?>
            <li class="nav-item">
              <a class="nav-link" href="index.php?action=cocina">Cocina</a>
            </li>

          <?php elseif($_SESSION['user']['rol'] === 'repartidor'): ?>
            <li class="nav-item">
              <a class="nav-link" href="index.php?action=repartidor">Repartidor</a>
            </li>
          <?php endif; ?>

        <?php endif; ?>
      </ul>

      <div class="d-flex align-items-center text-white">
        <?php if(isset($_SESSION['user'])): ?>
          <span class="me-3 small">
            👤 <?= htmlspecialchars($_SESSION['user']['name']) ?>
            <span class="badge bg-light text-dark ms-1">
              <?= htmlspecialchars($_SESSION['user']['rol']) ?>
            </span>
          </span>
          <a href="index.php?action=logout" class="btn btn-sm btn-outline-light">
            Salir
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<div class="container pb-5">
  <?= $content ?? '' ?>
</div>

<footer class="text-center py-3">
  Sabor colombiano con corazón mexicano 🇨🇴🇲🇽
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
