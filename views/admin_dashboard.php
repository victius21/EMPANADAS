<?php ob_start(); ?>

<div class="row mb-4 justify-content-center">
  <div class="col-lg-10">
    <div class="hero p-4 p-md-5">
      <div class="row align-items-center">
        <div class="col-md-8">
          <h2 class="mb-2">Bienvenido al panel administrativo COMX</h2>
          <p class="mb-3 text-muted">
            Aquí puedes controlar las ventas, productos y el flujo de pedidos entre cocina y repartidores.
          </p>
          <div class="d-flex flex-wrap gap-2">
            <a href="index.php?action=admin-prod" class="btn btn-warning">
              🧀 Gestionar productos
            </a>
            <a href="index.php?action=cocina" class="btn btn-danger text-white">
              🔪 Ir a panel de cocina
            </a>
            <a href="index.php?action=repartidor" class="btn btn-success">
              🚚 Ir a panel de repartidor
            </a>
          </div>
        </div>
        <div class="col-md-4 text-center mt-4 mt-md-0">
          <span style="font-size:4rem;">🥟</span>
          <p class="text-muted mb-0 small">“Qué chimba de empanadas, parce”</p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="card p-3 border-0">
      <span class="badge badge-co mb-2">Clientes</span>
      <h3 class="mb-1"><?= $totalClientes ?></h3>
      <p class="text-muted mb-2">Clientes registrados en el sistema</p>
      <small class="text-muted">Cada cliente viene desde WhatsApp.</small>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-3 border-0">
      <span class="badge badge-mx mb-2">Pedidos</span>
      <h3 class="mb-1"><?= $totalPedidos ?></h3>
      <p class="text-muted mb-2">Pedidos generados en total</p>
      <small class="text-muted">Se alimenta de los pedidos del bot.</small>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-3 border-0">
      <span class="badge bg-danger mb-2">Ventas</span>
      <h3 class="mb-1">$<?= number_format($totalVentas, 2) ?></h3>
      <p class="text-muted mb-2">Monto total acumulado de ventas</p>
      <small class="text-muted">Empanadas, combos y eventos.</small>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <div class="card p-3">
      <h5 class="mb-2">Accesos rápidos</h5>
      <div class="d-grid gap-2">
        <a href="index.php?action=admin-prod" class="btn btn-outline-warning">
          🧀 Productos
        </a>
        <a href="index.php?action=cocina" class="btn btn-outline-danger">
          🔥 Cocina
        </a>
        <a href="index.php?action=repartidor" class="btn btn-outline-success">
          🚚 Repartidor
        </a>
        <a href="index.php?action=inventario" class="btn btn-outline-primary">
          📦 Inventario de Insumos
        </a>
        <a href="index.php?action=inventario-corte" class="btn btn-outline-dark">
          💰 Corte de Caja
        </a>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card p-3">
      <h5 class="mb-2">Resumen del día</h5>
      <p class="text-muted small mb-2">
        Aquí más adelante puedes agregar gráficas de ventas diarias, campañas, eventos, etc.
      </p>
      <ul class="small mb-0">
        <li>Integrado a WhatsApp (futuro n8n)</li>
        <li>Paneles operativos: cocina y repartidor</li>
        <li>Inventario y corte de caja incluidos</li>
        <li>Estilo y branding colombiano con toques mexicanos 🇨🇴🇲🇽</li>
      </ul>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
$title = "Dashboard Admin";
include __DIR__ . '/layout.php';
