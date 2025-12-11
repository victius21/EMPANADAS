<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Empanadas Colombianas COL-MX</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap para que se vea bonito en todos lados -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: radial-gradient(circle at top, #FCD116 0, #ffffff 40%, #0033A0 100%);
            color: #111;
        }

        .navbar-colombia {
            background: linear-gradient(90deg, #0033A0, #FCD116, #CE1126);
        }

        .hero {
            margin-top: 4rem;
            background: rgba(255,255,255,0.9);
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .badge-colombia {
            background: #FCD116;
            color: #0033A0;
            font-weight: 600;
        }

        .flag-strip {
            height: 10px;
            background: linear-gradient(to right, #FCD116 33%, #0033A0 33%, #0033A0 66%, #CE1126 66%);
            border-radius: 999px;
        }

        .feature-card {
            background: rgba(255,255,255,0.95);
            border-radius: 1rem;
            padding: 1.2rem;
            height: 100%;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .btn-whatsapp-main {
            background: #25D366;
            border: none;
            font-weight: 600;
        }
        .btn-whatsapp-main:hover {
            background: #1ebe5c;
        }

        /* Botón flotante de WhatsApp en toda la página */
        .btn-whatsapp-floating {
            position: fixed;
            right: 18px;
            bottom: 18px;
            z-index: 9999;
            background: #25D366;
            color: #fff;
            border-radius: 999px;
            padding: 10px 16px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-whatsapp-floating span {
            font-size: 0.9rem;
        }
        .btn-whatsapp-floating:hover {
            color: #fff;
            background: #1ebe5c;
        }

        footer {
            color: #222;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-colombia shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php?action=home">
            🇨🇴 Empanadas COL-MX
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div id="mainNav" class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item me-2">
                    <a class="nav-link" href="#menu">Menú</a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link" href="#como-funciona">¿Cómo funciona?</a>
                </li>
                <li class="nav-item">
                    <!-- Enlace para el panel interno -->
                    <a class="btn btn-outline-light btn-sm" href="index.php?action=login">
                        Panel interno
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="hero p-4 p-md-5">
                <div class="row align-items-center g-4">
                    <div class="col-md-7">
                        <span class="badge badge-colombia mb-3 px-3 py-2 rounded-pill">
                            Sabor colombiano hecho en México 🇨🇴🇲🇽
                        </span>
                        <h1 class="fw-bold mb-3">
                            Empanadas colombianas <span class="text-warning">recién fritas</span> directo a tu antojo
                        </h1>
                        <p class="text-muted mb-4">
                            Pide por WhatsApp en segundos: eliges tus empanadas, mandas mensaje al bot y listo.
                            Sin registros, sin complicaciones, solo puro sabor.
                        </p>

                        <!-- 👉 BOTÓN PRINCIPAL DE WHATSAPP (CAMBIA EL NÚMERO) -->
                        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
                            <a
                                href="https://wa.me/52TU_NUMERO_BOT?text=Hola,%20quiero%20hacer%20un%20pedido%20de%20empanadas%20colombianas%20😋"
                                target="_blank"
                                class="btn btn-whatsapp-main btn-lg"
                            >
                                💬 Pedir por WhatsApp
                            </a>
                            <span class="text-muted small">
                                Atención por bot de WhatsApp, 24/7.
                            </span>
                        </div>

                        <div class="flag-strip mb-2"></div>
                        <p class="small text-muted mb-0">
                            Combos, salsas caseras y bebidas para completar tu antojo.
                        </p>
                    </div>

                    <div class="col-md-5 text-center">
                        <img src="https://images.pexels.com/photos/6605642/pexels-photo-6605642.jpeg?auto=compress&cs=tinysrgb&w=600"
                             alt="Empanadas colombianas"
                             class="img-fluid rounded-4 shadow-sm mb-3">
                        <p class="small text-muted mb-0">
                            Imagen ilustrativa de presentación. El antojo sí es real 🤤
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- sección menú rápido -->
    <section id="menu" class="mt-5">
        <h2 class="text-center mb-4 fw-bold">Lo más pedido de la casa</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <h5>Empanada de carne</h5>
                    <p class="small text-muted mb-2">La clásica colombiana, crujiente y jugosa.</p>
                    <p class="fw-bold mb-0">$15.00</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <h5>Empanada de pollo</h5>
                    <p class="small text-muted mb-2">Rellena de pollo deshebrado con sazón casero.</p>
                    <p class="fw-bold mb-0">$15.00</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <h5>Empanada de queso</h5>
                    <p class="small text-muted mb-2">Ideal para los amantes del queso derretido.</p>
                    <p class="fw-bold mb-0">$14.00</p>
                </div>
            </div>
        </div>
    </section>

    <!-- sección cómo funciona -->
    <section id="como-funciona" class="mt-5">
        <h2 class="text-center mb-4 fw-bold">¿Cómo funciona el pedido por WhatsApp?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <span class="badge bg-warning text-dark mb-2">1</span>
                    <h6>Abres el chat</h6>
                    <p class="small text-muted mb-0">
                        Das clic en el botón de WhatsApp y se abre el chat con nuestro bot.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <span class="badge bg-warning text-dark mb-2">2</span>
                    <h6>Eliges tus empanadas</h6>
                    <p class="small text-muted mb-0">
                        El bot te guía para elegir sabores, cantidad y bebidas.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <span class="badge bg-warning text-dark mb-2">3</span>
                    <h6>Confirmas y disfrutas</h6>
                    <p class="small text-muted mb-0">
                        Confirmas tu pedido y te indicamos tiempo de entrega o recolección.
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>

<footer class="text-center py-4">
    <small>Sabor colombiano con corazón mexicano 🇨🇴🇲🇽</small><br>
    <small class="text-muted">Sistema conectado a Supabase y listo para bot de WhatsApp.</small>
</footer>

<!-- Botón flotante de WhatsApp siempre visible -->
<a
    href="https://wa.me/52TU_NUMERO_BOT?text=Hola,%20quiero%20hacer%20un%20pedido%20de%20empanadas%20colombianas%20😋"
    target="_blank"
    class="btn-whatsapp-floating"
>
    💬 <span>Chatear con el bot</span>
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
