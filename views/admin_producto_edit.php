<?php ob_start(); ?>

<h3 class="mb-3">Editar producto ✏️</h3>

<div class="card p-3">
  <form method="POST" action="index.php?action=admin-prod-update">
    <input type="hidden" name="id" value="<?= (int)$producto['id'] ?>">

    <div class="mb-2">
      <label class="form-label">Nombre</label>
      <input class="form-control" name="nombre"
             value="<?= htmlspecialchars($producto['nombre']) ?>" required>
    </div>

    <div class="mb-2">
      <label class="form-label">Descripción</label>
      <textarea class="form-control" name="descripcion" rows="3"><?= htmlspecialchars($producto['descripcion'] ?? '') ?></textarea>
    </div>

    <div class="mb-2">
      <label class="form-label">Precio (MXN)</label>
      <input class="form-control" type="number" step="0.01" name="precio"
             value="<?= (float)$producto['precio'] ?>" required>
    </div>

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="disponible" id="disponible"
        <?= !empty($producto['disponible']) ? 'checked' : '' ?>>
      <label class="form-check-label" for="disponible">Disponible</label>
    </div>

    <div class="d-flex gap-2">
      <button class="btn btn-primary" type="submit">Guardar cambios</button>
      <a class="btn btn-secondary" href="index.php?action=admin-prod">Cancelar</a>
    </div>
  </form>
</div>

<?php
$content = ob_get_clean();
$title = "Editar producto";
include __DIR__ . '/layout.php';
