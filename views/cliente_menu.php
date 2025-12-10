<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú Cliente - Empanadas Colombianas</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 30px; max-width: 800px; margin: auto; }
        h1 { color: #e0a800; }
        .header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
        .btn-wa {
            display: inline-block;
            padding: 10px 20px;
            background: #25D366;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .btn-wa:hover { opacity:.9; }
        .btn-logout {
            text-decoration:none;
            color:#e74c3c;
        }
        ul { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1>Empanadas Colombianas</h1>
            <h3>Hola, <?php echo htmlspecialchars($clienteNombre ?? 'cliente'); ?> 👋</h3>
        </div>
        <div>
            <a class="btn-logout" href="index.php?action=cliente-logout">Cerrar sesión</a>
        </div>
    </div>

    <h2>Menú</h2>
    <!-- Sustituye esta lista por tu menú real -->
    <ul>
        <li>Empanada de carne</li>
        <li>Empanada de pollo</li>
        <li>Empanada de queso</li>
        <li>Empanada mixta</li>
    </ul>

    <a class="btn-wa" href="<?php echo htmlspecialchars($waUrl ?? '#'); ?>" target="_blank">
        📲 Enviar mensaje por WhatsApp para hacer pedido
    </a>
</body>
</html>
