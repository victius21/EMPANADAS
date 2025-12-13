<?php ob_start(); ?>

<h3 class="mb-3">Insumos 🧂</h3>

<?php if (!empty($flash['exito'])): ?>
  <div class="alert alert-success py-2"><?= htmlspecialchars($flash['exito']) ?></div>
<?php endif; ?>

<?php if (!empty($flash['error'])): ?>
  <div class="alert alert-danger py-2"><?= htmlspecialchars($flash['error']) ?></div>
<?php endif; ?>

<div class="row g-3">
  <!-- Nuevo insumo -->
  <div class="col-md-4">
    <div class="card p-3">
      <h5 class="mb-3">Nuevo insumo</h5>

      <form method="POST" action="index.php?action=insumo-store">
        <div class="mb-2">
          <label class="form-label">Nombre</label>
          <input class="form-control" name="nombre" placeholder="Harina, carne, aceite..." required>
        </div>

        <div class="mb-2">
          <label class="form-label">Unidad</label>
          <input class="form-control" name="unidad" placeholder="kg, litro, pieza" required>
        </div>

        <div class="mb-2">
          <label class="form-label">Stock mínimo</label>
          <input class="form-control" name="stock_minimo" type="number" step="1" value="0">
        </div>

        <div class="mb-3">
          <label class="form-label">Costo unitario</label>
          <input class="form-control" name="costo_unitario" type="number" step="0.01" value="0">
        </div>

        <button class="btn btn-success w-100" type="submit">➕ Guardar insumo</button>
      </form>
    </div>

    <!-- Nuevo proveedor -->
    <div class="card p-3 mt-3">
      <h5 class="mb-3">Nuevo proveedor</h5>

      <form method="POST" action="index.php?action=proveedor-store">
        <div class="mb-2">
          <label class="form-label">Nombre proveedor</label>
          <input class="form-control" name="nombre" required>
        </div>

        <div class="mb-2">
          <label class="form-label">Teléfono</label>
          <input class="form-control" name="telefono">
        </div>

        <div class="mb-3">
          <label class="form-label">Notas</label>
          <textarea class="form-control" name="notas" rows="3"></textarea>
        </div>

        <button class="btn btn-primary w-100" type="submit">Guardar proveedor</button>
      </form>
    </div>
  </div>

  <!-- Lista insumos + movimientos -->
  <div class="col-md-8">
    <div class="card p-3">
      <h5 class="mb-2">Insumos en inventario</h5>

      <?php if (empty($insumos)): ?>
        <p class="text-muted mb-0">Aún no has registrado insumos.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-sm align-middle mb-0">
            <thead>
              <tr>
                <th>Insumo</th>
                <th>Unidad</th>
                <th class="text-end">Stock</th>
                <th class="text-end">Mínimo</th>
                <th class="text-end">Costo u.</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($insumos as $i): ?>
                <tr>
                  <td><?= htmlspecialchars($i['nombre']) ?></td>
                  <td><?= htmlspecialchars($i['unidad']) ?></td>
                  <td class="text-end"><?= htmlspecialchars($i['stock_actual']) ?></td>
                  <td class="text-end"><?= htmlspecialchars($i['stock_minimo']) ?></td>
                  <td class="text-end">$<?= htmlspecialchars($i['costo_unitario']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

    <div class="card p-3 mt-3">
      <h5 class="mb-3">Registrar movimiento de inventario</h5>

      <form method="POST" action="index.php?action=movimiento-store">
        <div class="row g-2 align-items-end">
          <div class="col-md-3">
            <label class="form-label">Insumo</label>
            <select class="form-select" name="insumo_id" required>
              <option value="">Insumo...</option>
              <?php foreach ($insumos as $i): ?>
                <option value="<?= (int)$i['id'] ?>"><?= htmlspecialchars($i['nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label">Tipo</label>
            <select class="form-select" name="tipo" required>
              <option value="entrada">Entrada</option>
              <option value="salida">Salida</option>
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label">Cant.</label>
            <input class="form-control" name="cantidad" type="number" step="0.001" required>
          </div>

          <div class="col-md-2">
            <label class="form-label">Costo u.</label>
            <input class="form-control" name="costo_unitario" type="number" step="0.01">
          </div>

          <div class="col-md-3">
            <label class="form-label">Proveedor</label>
            <select class="form-select" name="proveedor_id">
              <option value="">Proveedor...</option>
              <?php foreach ($proveedores as $p): ?>
                <option value="<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-12 mt-2">
            <label class="form-label">Nota (opcional)</label>
            <input class="form-control" name="nota" placeholder="Nota...">
          </div>

          <div class="col-12 mt-3">
            <button class="btn btn-primary" type="submit">Guardar movimiento</button>
          </div>
        </div>
      </form>

      <hr>

      <h6 class="mb-2">Movimientos recientes</h6>
      <?php if (empty($movimientos)): ?>
        <p class="text-muted mb-0">Aún no hay movimientos registrados.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-sm align-middle mb-0">
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Insumo</th>
                <th>Tipo</th>
                <th class="text-end">Cantidad</th>
                <th>Proveedor</th>
                <th>Nota</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($movimientos as $m): ?>
                <tr>
                  <td><?= htmlspecialchars($m['creado_en']) ?></td>
                  <td><?= htmlspecialchars($m['insumo']) ?></td>
                  <td><?= htmlspecialchars($m['tipo']) ?></td>
                  <td class="text-end"><?= htmlspecialchars($m['cantidad']) ?></td>
                  <td><?= htmlspecialchars($m['proveedor'] ?? '') ?></td>
                  <td><?= htmlspecialchars($m['nota'] ?? '') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/layout.php'; ?>
