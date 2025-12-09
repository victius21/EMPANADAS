<?php ob_start(); ?>
<h3>Iniciar sesión</h3>
<form method="post">
<input class="form-control mb-2" name="correo" placeholder="Correo">
<input class="form-control mb-2" name="password" type="password" placeholder="Contraseña">
<button class="btn btn-primary w-100">Entrar</button>
</form>
<?php $content=ob_get_clean(); include __DIR__.'/layout.php'; ?>
