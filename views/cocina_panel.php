<?php ob_start(); ?>

<h3 class="mb-3">Panel de Cocina 🇨🇴</h3>
<p class="text-muted mb-4">
  Aquí ves la cola de pedidos que llegan desde WhatsApp y los mueves de pendiente → en preparación → listo.
</p>

<div class="row g-3">
  <!-- Pendientes -->
  <div class="col-md-4">
    <div class="card p-3">
      <h5 class="mb-2">
        <span class="badge bg-warning text-dark me-1">Pendientes</span>
        por empezar
      </h5>
      <p class="small text-muted mb-3">Pedidos nuevos esperando que alguien los agarre.</p>

      <?php if (empty($pedidosPendientes)): ?>
        <p class="text-muted small">No hay pedidos pendientes por ahora. Tómate un tintico ☕</p>
      <?php else: ?>
        <?php foreach ($pedidosPendientes as $p): ?>
          <div class="border rounded-3 p-2 mb-2">
            <div class="d-flex justify-content-between">
              <strong>#<?= $p['id'] ?></strong>
              <span class="badge bg-secondary"><?= htmlspecialchars($p['cliente_nombre']) ?></span>
            </div>
            <div class="small text-muted">
              📱 <?= htmlspecialchars($p['numero_whatsapp']) ?>
            </div>
            <div class="small mt-1">
              🍽 <?= htmlspecialchars($p['items']) ?>
            </div>
            <div class="small mt-1 mb-2">
              💰 $<?= number_format($p['total'], 2) ?>
            </div>
            <form method="post" action="index.php?action=cocina-estado" class="d-flex gap-1">
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <button name="estado" value="en_preparacion" class="btn btn-sm btn-warning">
                🔥 Empezar
              </button>
              <button name="estado" value="cancelado" class="btn btn-sm btn-outline-danger">
                ❌ Cancelar
              </button>
            </form>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <!-- En preparación -->
  <div class="col-md-4">
    <div class="card p-3">
      <h5 class="mb-2">
        <span class="badge bg-danger me-1">En preparación</span>
        manos a la masa
      </h5>
      <p class="small text-muted mb-3">Pedidos que ya están en proceso en la cocina.</p>

      <?php if (empty($pedidosPreparacion)): ?>
        <p class="text-muted small">No hay pedidos en preparación.</p>
      <?php else: ?>
        <?php foreach ($pedidosPreparacion as $p): ?>
          <div class="border rounded-3 p-2 mb-2">
            <div class="d-flex justify-content-between">
              <strong>#<?= $p['id'] ?></strong>
              <span class="badge bg-secondary"><?= htmlspecialchars($p['cliente_nombre']) ?></span>
            </div>
            <div class="small text-muted">
              📱 <?= htmlspecialchars($p['numero_whatsapp']) ?>
            </div>
            <div class="small mt-1">
              🍽 <?= htmlspecialchars($p['items']) ?>
            </div>
            <div class="small mt-1 mb-2">
              💰 $<?= number_format($p['total'], 2) ?>
            </div>
            <form method="post" action="index.php?action=cocina-estado" class="d-flex gap-1">
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <button name="estado" value="listo" class="btn btn-sm btn-success">
                ✅ Marcar listo
              </button>
              <button name="estado" value="cancelado" class="btn btn-sm btn-outline-danger">
                ❌ Cancelar
              </button>
            </form>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <!-- Listos -->
  <div class="col-md-4">
    <div class="card p-3">
      <h5 class="mb-2">
        <span class="badge bg-success me-1">Listos</span>
        esperando repartidor
      </h5>
      <p class="small text-muted mb-3">Estos pedidos ya se pueden entregar al repartidor.</p>

      <?php if (empty($pedidosListos)): ?>
        <p class="text-muted small">Aún no hay pedidos listos. ¡Vamos que se puede! 💪</p>
      <?php else: ?>
        <?php foreach ($pedidosListos as $p): ?>
          <div class="border rounded-3 p-2 mb-2">
            <div class="d-flex justify-content-between">
              <strong>#<?= $p['id'] ?></strong>
              <span class="badge bg-secondary"><?= htmlspecialchars($p['cliente_nombre']) ?></span>
            </div>
            <div class="small text-muted">
              📱 <?= htmlspecialchars($p['numero_whatsapp']) ?>
            </div>
            <div class="small mt-1">
              🍽 <?= htmlspecialchars($p['items']) ?>
            </div>
            <div class="small mt-1 mb-2">
              💰 $<?= number_format($p['total'], 2) ?>
            </div>
            <form method="post" action="index.php?action=cocina-estado" class="d-flex gap-1">
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <button name="estado" value="en_preparacion" class="btn btn-sm btn-warning">
                🔁 Regresar a preparación
              </button>
            </form>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
$title = "Panel de Cocina";
include __DIR__ . '/layout.php';
