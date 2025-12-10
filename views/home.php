<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Empanadas Colombianas</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 40px; }
        h1 { color: #e0a800; }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            text-decoration: none;
            border-radius: 5px;
            border: 1px solid #e0a800;
        }
        .btn-primary { background: #e0a800; color: #000; }
        .btn-outline { background: #fff; color: #e0a800; }
        p { max-width: 500px; margin: 10px auto; }
    </style>
</head>
<body>
    <h1>Empanadas Colombianas</h1>
    <p>Bienvenido/a. Inicia sesión o regístrate para ver el menú y hacer tu pedido.</p>

    <a class="btn btn-primary" href="index.php?action=cliente-login">Soy cliente (Iniciar sesión)</a>
    <a class="btn btn-outline" href="index.php?action=cliente-registro">Soy cliente nuevo (Registrarme)</a>

    <hr>

    <p>
        ¿Eres administrador, cocina o repartidor?<br>
        <a href="index.php?action=login">Entrar al panel interno</a>
    </p>
</body>
</html>
