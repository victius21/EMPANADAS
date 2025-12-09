<?php ob_start(); ?>

<h3 class="mb-3">Corte de caja y gastos 💰</h3>
<p class="text-muted mb-4">
  Registra los gastos del negocio y realiza el corte diario con efectivo, tarjeta, impuestos y ganancia real.
</p>

<div class="row g-3">
  <!-- Registrar gasto -->
  <div class="col-md-4">
    <div class="card p-3 mb-3">
      <h5 class="mb-2">Nuevo gasto</h5>
      <form method="post">
        <input type="hidden" name="accion" value="gasto">
        <div class="mb-2">
          <label class="form-label">Fecha</label>
          <input type="date" name="fecha" class="form-control" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="mb-2">
          <label class="form-label">Concepto</label>
          <input name="concepto" class="form-control" placeholder="Gasolina, sueldo, plataforma..." required>
        </div>
        <div class="mb-2">
          <label class="form-label">Categoría</label>
          <input name="categoria" class="form-control" placeholder="gasolina, sueldos, plataformas...">
        </div>
        <div class="mb-2">
          <label class="form-label">Monto</label>
          <input type="number" step="0.01" name="monto" class="form-control" required>
        </div>
        <button class="btn btn-outline-danger w-100 mt-2">Registrar gasto</button>
      </form>
    </div>
  </div>

  <!-- Corte diario -->
  <div class="col-md-8">
    <div class="card p-3 mb-3">
      <h5 class="mb-2">Corte diario</h5>
      <form method="post" class="row g-2">
        <input type="hidden" name="accion" value="corte">
        <div class="col-md-3">
          <label class="form-label">Fecha</label>
          <input type="date" name="fecha" class="form-control" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-md-2">
          <label class="form-label">Efectivo</label>
          <input type="number" step="0.01" name="efectivo" class="form-control" value="0">
        </div>
        <div class="col-md-2">
          <label class="form-label">Tarjeta</label>
          <input type="number" step="0.01" name="tarjeta" class="form-control" value="0">
        </div>
        <div class="col-md-2">
          <label class="form-label">Impuestos</label>
          <input type="number" step="0.01" name="impuestos" class="form-control" value="0">
        </div>
        <div class="col-md-3">
          <label class="form-label">Gastos del día</label>
          <input type="number" step="0.01" name="gastos" class="form-control" value="0">
        </div>
        <div class="col-12">
          <label class="form-label">Notas</label>
          <input name="notas" class="form-control" placeholder="Resumen del día, comentarios...">
        </div>
        <div class="col-12">
          <button class="btn btn-success mt-2">Guardar corte</button>
        </div>
      </form>
    </div>

    <!-- Listas -->
    <div class="row g-3">
      <div class="col-md-6">
        <div class="card p-3">
          <h6>Gastos recientes</h6>
          <?php if (empty($gastosRecientes)): ?>
            <p class="text-muted small mb-0">Aún no hay gastos registrados.</p>
          <?php else: ?>
            <ul class="list-unstyled small mb-0">
              <?php foreach ($gastosRecientes as $g): ?>
                <li class="mb-1">
                  <strong><?= $g['fecha'] ?></strong> —
                  <?= htmlspecialchars($g['concepto']) ?>:
                  $<?= number_format($g['monto'],2) ?>
                  <span class="text-muted">
                    (<?= htmlspecialchars($g['categoria']) ?>)
                  </span>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card p-3">
          <h6>Cortes recientes</h6>
          <?php if (empty($cortesRecientes)): ?>
            <p class="text-muted small mb-0">Aún no hay cortes registrados.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-sm small">
                <thead class="table-light">
                  <tr>
                    <th>Fecha</th>
                    <th>Efec.</th>
                    <th>Tarj.</th>
                    <th>Imp.</th>
                    <th>Gastos</th>
                    <th>Neta</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($cortesRecientes as $c): ?>
                    <tr>
                      <td><?= $c['fecha'] ?></td>
                      <td>$<?= number_format($c['efectivo'],2) ?></td>
                      <td>$<?= number_format($c['tarjeta'],2) ?></td>
                      <td>$<?= number_format($c['impuestos'],2) ?></td>
                      <td>$<?= number_format($c['gastos'],2) ?></td>
                      <td><strong>$<?= number_format($c['ganancia_neta'],2) ?></strong></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
$title = "Corte de caja y gastos";
include __DIR__ . '/layout.php';
