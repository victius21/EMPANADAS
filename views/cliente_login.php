<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Cliente - Empanadas Colombianas</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 30px; max-width: 480px; margin: auto; }
        h1 { text-align: center; color: #e0a800; }
        form { margin-top: 20px; }
        label { display: block; margin-bottom: 10px; }
        input { width: 100%; padding: 8px; margin-top: 4px; box-sizing: border-box; }
        button { padding: 10px 20px; background: #e0a800; border: none;
                 cursor: pointer; margin-top: 10px; width: 100%; }
        button:hover { opacity: .9; }
        .mensaje { margin-top: 10px; color: red; }
        a { color: #e0a800; }
    </style>
</head>
<body>
    <h1>Iniciar sesión (Cliente)</h1>

    <?php if (!empty($mensaje)): ?>
        <p class="mensaje"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <form method="POST" action="index.php?action=cliente-login">
        <label>
            Correo:
            <input type="email" name="email" required>
        </label>

        <label>
            Contraseña:
            <input type="password" name="password" required>
        </label>

        <button type="submit">Entrar</button>
    </form>

    <p>¿No tienes cuenta? <a href="index.php?action=cliente-registro">Regístrate</a></p>
    <p><a href="index.php?action=home">Volver al inicio</a></p>
</body>
</html>
