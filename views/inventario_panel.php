<?php ob_start(); ?>

<h3 class="mb-3">Inventario de insumos 🧾</h3>
<p class="text-muted mb-4">
  Control de insumos, proveedores y movimientos de entradas/salidas para la producción de empanadas.
</p>

<div class="row g-3">
  <!-- Nuevo insumo -->
  <div class="col-md-4">
    <div class="card p-3">
      <h5 class="mb-2">Nuevo insumo</h5>
      <form method="post">
        <input type="hidden" name="accion" value="nuevo_insumo">
        <div class="mb-2">
          <label class="form-label">Nombre</label>
          <input name="nombre" class="form-control" placeholder="Harina, carne, aceite..." required>
        </div>
        <div class="mb-2">
          <label class="form-label">Unidad</label>
          <input name="unidad" class="form-control" placeholder="kg, litro, pieza" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Stock mínimo</label>
          <input type="number" step="0.01" name="stock_minimo" class="form-control" value="0">
        </div>
        <div class="mb-2">
          <label class="form-label">Costo unitario</label>
          <input type="number" step="0.01" name="costo_unitario" class="form-control" value="0">
        </div>
        <button class="btn btn-success w-100 mt-2">➕ Guardar insumo</button>
      </form>
    </div>

    <div class="card p-3 mt-3">
      <h6 class="mb-2">Nuevo proveedor</h6>
      <form method="post">
        <input type="hidden" name="accion" value="nuevo_proveedor">
        <div class="mb-2">
          <input name="nombre" class="form-control" placeholder="Nombre proveedor" required>
        </div>
        <div class="mb-2">
          <input name="telefono" class="form-control" placeholder="Teléfono">
        </div>
        <div class="mb-2">
          <textarea name="notas" class="form-control" rows="2" placeholder="Notas"></textarea>
        </div>
        <button class="btn btn-outline-primary w-100">Guardar proveedor</button>
      </form>
    </div>
  </div>

  <!-- Inventario actual -->
  <div class="col-md-8">
    <div class="card p-3 mb-3">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0">Insumos en inventario</h5>
      </div>
      <?php if (empty($insumos)): ?>
        <p class="text-muted small mb-0">Aún no has registrado insumos.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>Insumo</th>
                <th>Unidad</th>
                <th class="text-end">Stock actual</th>
                <th class="text-end">Stock mínimo</th>
                <th class="text-end">Costo u.</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($insumos as $i): ?>
                <tr class="<?= $i['bajo'] ? 'table-warning' : '' ?>">
                  <td><?= htmlspecialchars($i['nombre']) ?></td>
                  <td><?= htmlspecialchars($i['unidad']) ?></td>
                  <td class="text-end"><?= $i['stock_actual'] ?></td>
                  <td class="text-end"><?= $i['stock_minimo'] ?></td>
                  <td class="text-end">$<?= number_format($i['costo_unitario'],2) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <p class="small text-muted mb-0">
          Los renglones amarillos son insumos con stock por debajo o igual al mínimo.
        </p>
      <?php endif; ?>
    </div>

    <!-- Registro rápido de movimientos -->
    <div class="card p-3">
      <h5 class="mb-2">Registrar movimiento de inventario</h5>
      <form method="post" class="row g-2">
        <input type="hidden" name="accion" value="movimiento">
        <div class="col-md-3">
          <select name="insumo_id" class="form-select" required>
            <option value="">Insumo...</option>
            <?php foreach ($insumos as $i): ?>
              <option value="<?= $i['id'] ?>"><?= htmlspecialchars($i['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-2">
          <select name="tipo" class="form-select" required>
            <option value="entrada">Entrada</option>
            <option value="salida">Salida</option>
          </select>
        </div>
        <div class="col-md-2">
          <input type="number" step="0.01" name="cantidad" class="form-control" placeholder="Cant." required>
        </div>
        <div class="col-md-2">
          <input type="number" step="0.01" name="costo_unitario" class="form-control" placeholder="Costo u.">
        </div>
        <div class="col-md-3">
          <select name="proveedor_id" class="form-select">
            <option value="">Proveedor...</option>
            <?php foreach ($proveedores as $p): ?>
              <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-12">
          <input name="nota" class="form-control mb-2" placeholder="Nota (opcional)">
        </div>
        <div class="col-12">
          <button class="btn btn-primary">Guardar movimiento</button>
        </div>
      </form>

      <hr class="my-3">

      <h6>Movimientos recientes</h6>
      <?php if (empty($movimientosRecientes)): ?>
        <p class="text-muted small mb-0">Aún no hay movimientos registrados.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-sm small">
            <thead class="table-light">
              <tr>
                <th>Fecha</th>
                <th>Insumo</th>
                <th>Tipo</th>
                <th>Cant.</th>
                <th>Proveedor</th>
                <th>Nota</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($movimientosRecientes as $m): ?>
                <tr>
                  <td><?= $m['fecha'] ?></td>
                  <td><?= htmlspecialchars($m['insumo_nombre']) ?></td>
                  <td><?= $m['tipo'] ?></td>
                  <td><?= $m['cantidad'] ?></td>
                  <td><?= htmlspecialchars($m['proveedor_nombre'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($m['nota']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
$title = "Inventario de Insumos";
include __DIR__ . '/layout.php';
