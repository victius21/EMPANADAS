<?php ob_start(); ?>

<h3 class="mb-3">Gestión de productos 🧀</h3>
<?php if (!empty($flash['exito'])): ?>
  <div class="alert alert-success py-2">
    <?= htmlspecialchars($flash['exito']) ?>
  </div>
<?php endif; ?>

<?php if (!empty($flash['error'])): ?>
  <div class="alert alert-danger py-2">
    <?= htmlspecialchars($flash['error']) ?>
  </div>
<?php endif; ?>

<p class="text-muted mb-4">
  Aquí registras las empanadas, combos y bebidas que el bot ofrecerá a los clientes por WhatsApp.
</p>

<div class="row g-3">
  <!-- Formulario nuevo producto -->
  <div class="col-md-4">
    <div class="card p-3">
      <h5 class="mb-2">Nuevo producto</h5>
      <p class="small text-muted">
        Dale nombre sabroso y precio justo, parce.
      </p>

      <!-- ✅ IMPORTANTE: apuntar a admin-prod -->
      <form method="post" action="index.php?action=admin-prod">
        <div class="mb-2">
          <label class="form-label">Nombre</label>
          <input name="nombre" class="form-control" placeholder="Empanada de carne" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Descripción</label>
          <textarea name="descripcion" class="form-control" rows="2" placeholder="Con papa criolla, sazón colombiana..."></textarea>
        </div>
        <div class="mb-2">
          <label class="form-label">Precio (MXN)</label>
          <input type="number" step="0.01" name="precio" class="form-control" placeholder="25.00" required>
        </div>
        <button class="btn btn-success w-100 mt-2">
          ➕ Guardar producto
        </button>
      </form>
    </div>
  </div>

  <!-- Lista de productos -->
  <div class="col-md-8">
    <div class="card p-3">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0">Productos registrados</h5>
        <span class="badge badge-co">
          <?= isset($productos) ? count($productos) : 0 ?> productos
        </span>
      </div>

      <?php if (empty($productos)): ?>
        <p class="text-muted small mb-0">
          Aún no hay productos. Empieza agregando la clásica empanada de carne, pollo o de lo que se te ocurra 😋
        </p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th class="text-end">Precio</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($productos as $p): ?>
                <tr>
                  <td><?= (int)$p['id'] ?></td>
                  <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
                  <td class="small"><?= nl2br(htmlspecialchars($p['descripcion'])) ?></td>
                  <td class="text-end">$<?= number_format((float)$p['precio'], 2) ?></td>

                  <td class="text-end">
                    <a class="btn btn-sm btn-warning"
                      href="index.php?action=admin-prod-edit&id=<?= (int)$p['id'] ?>">
                      Editar
                    </a>

                    <form method="POST"
                          action="index.php?action=admin-prod-delete"
                          style="display:inline;"
                          onsubmit="return confirm('¿Eliminar este producto?');">
                      <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                      <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                    </form>
                  </td>

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
$title = "Productos";
include __DIR__ . '/layout.php';
