<?php ob_start(); ?>
<h3>Panel Repartidor</h3>
<table class="table">
<tr><th>ID</th><th>Cliente</th><th>Items</th><th>Estado</th></tr>
<?php foreach($pedidos as $p): ?>
<tr><td><?= $p['id'] ?></td><td><?= $p['nombre'] ?></td><td><?= $p['items'] ?></td><td><?= $p['estado'] ?></td></tr>
<?php endforeach; ?>
</table>
<?php $content=ob_get_clean(); include __DIR__.'/layout.php'; ?>
