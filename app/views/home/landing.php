<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restowi – Sistema para Restaurantes</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/landing.css">
</head>
<body>


<!-- ══════════════════════════════════════════════════════════
     DASHBOARD LATERAL (menú móvil tipo slide-in)
════════════════════════════════════════════════════════════ -->
<div class="dashboard-overlay" id="dashboardOverlay"></div>

<aside class="dashboard" id="dashboard">
    <!-- Botón cerrar -->
    <button class="dashboard-close" id="dashboardClose" aria-label="Cerrar menú">
        <i class="bi bi-x-lg"></i>
    </button>

    <!-- Links de navegación -->
    <nav class="dashboard-nav">
        <a href="#" class="dashboard-link">Inicio</a>
        <a href="#" class="dashboard-link">Funcionalidades</a>
        <a href="#" class="dashboard-link">Demo</a>
        <a href="#" class="dashboard-link">Precio</a>
        <a href="#" class="dashboard-link">Contacto</a>
    </nav>
</aside>

<!-- ══ NAVBAR ══════════════════════════════════════════════════ -->
<nav class="navbar" id="navbar">
    <a class="navbar-brand" href="#">
        <div class="brand-icon"><i class="bi bi-cup-hot-fill"></i></div>
        RESTOWI
    </a>

    <!-- Links desktop -->
    <ul class="navbar-links">
        <li><a href="#">Inicio</a></li>
        <li><a href="#">Funcionalidades</a></li>
        <li><a href="#">Demo</a></li>
        <li><a href="#">Precio</a></li>
        <li><a href="#">Contacto</a></li>
        <li><a href="#" class="btn-nav">Probar Demo</a></li>
    </ul>

    <!-- Hamburguesa (móvil / tablet) -->
    <button class="menu-btn" id="menuBtn" aria-label="Abrir menú">
        <i class="bi bi-list"></i>
    </button>
</nav>

<!-- ══ HERO ════════════════════════════════════════════════════ -->
<section class="hero">
    <div class="hero-text">
        <span class="hero-badge">Sistema para restaurantes</span>
        <h1 class="hero-title">
            El sistema todo en uno<br>
            para gestionar tu <span>restaurante</span>
        </h1>
        <p class="hero-desc">
            Controla pedidos, cocina, menú, inventario y más
            desde una sola plataforma
        </p>
        <div class="hero-btns">
            <a href="#" class="btn-primary">Ver Demo en Vivo</a>
            <a href="#" class="btn-outline">Solicitar Demo</a>
        </div>
    </div>
    <div class="hero-img-wrap">
        <img src="<?php echo BASE_URL; ?>/public/image/foto_prueba.png" alt="Restaurante">
    </div>
</section>

<!-- ══ FUNCIONALIDADES – CARRUSEL ══════════════════════════════ -->
<section class="features-section">
    <div class="section-label"><span>Funcionalidades</span></div>
    <h2 class="section-title">Todo lo que tu restaurante necesita<br>en un solo sistema</h2>

    <div class="carousel-wrapper">
        <button class="carousel-btn carousel-btn--prev" id="carouselPrev" aria-label="Anterior">
            <i class="bi bi-chevron-left"></i>
        </button>

        <div class="carousel-viewport">
            <div class="carousel-track" id="carouselTrack">

                <div class="feature-card">
                    <i class="bi bi-cart3 feature-icon"></i>
                    <h3>Gestión de Pedidos</h3>
                    <p>Recibe y administra pedidos de mesa, delivery y para llevar en tiempo real.</p>
                </div>

                <div class="feature-card">
                    <i class="bi bi-display feature-icon"></i>
                    <h3>Pantalla de Cocina</h3>
                    <p>Organiza los pedidos en la cocina y mejora los tiempos de preparación.</p>
                </div>

                <div class="feature-card">
                    <i class="bi bi-book feature-icon"></i>
                    <h3>Menú Digital</h3>
                    <p>Crea y actualiza tu menú fácilmente. Agrega combos, opciones y precios.</p>
                </div>

                <div class="feature-card">
                    <i class="bi bi-bar-chart-line feature-icon"></i>
                    <h3>Reportes y Ventas</h3>
                    <p>Visualiza el rendimiento de tu negocio con reportes diarios y mensuales.</p>
                </div>

                <div class="feature-card">
                    <i class="bi bi-people feature-icon"></i>
                    <h3>Gestión de Mesas</h3>
                    <p>Controla la disponibilidad de mesas y reservas desde un solo lugar.</p>
                </div>

                <div class="feature-card">
                    <i class="bi bi-box-seam feature-icon"></i>
                    <h3>Inventario</h3>
                    <p>Lleva un control exacto de tus insumos y recibe alertas de stock bajo.</p>
                </div>

            </div>
        </div>

        <button class="carousel-btn carousel-btn--next" id="carouselNext" aria-label="Siguiente">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>

    <div class="carousel-dots" id="carouselDots"></div>
</section>

<!-- ══ DEMO EN ACCIÓN ══════════════════════════════════════════ -->
<section class="demo-section">
    <div class="section-label"><span>¡Pruébalo tú mismo!</span></div>
    <h2 class="section-title">Conoce nuestro sistema en acción</h2>
    <p class="demo-sub">
        Explora todas las funcionalidades con nuestro demo interactivo.<br>
        No necesitas registrarte.
    </p>

    <div class="demo-video-wrap">
        <img src="<?php echo BASE_URL; ?>/public/image/foto_prueba.png" alt="Demo del sistema">
        <button class="play-btn" id="playBtn" aria-label="Reproducir demo">
            <i class="bi bi-play-fill"></i>
        </button>
    </div>

    <div class="demo-btn-wrap">
        <a href="#" class="btn-outline">Ver Demo en Vivo</a>
    </div>
</section>

<!-- ══ CTA FINAL ════════════════════════════════════════════════ -->
<section class="cta-section">
    <div class="cta-inner">
        <div class="cta-text">
            <span class="cta-badge">¿Listo para llevar tu restaurante al siguiente nivel?</span>
            <h2 class="cta-title">
                Empieza hoy y transforma<br>
                la forma de gestionar tu<br>
                negocio
            </h2>
            <p class="cta-desc">
                Únete a cientos de restaurantes que ya optimizan<br>
                su operación con Restowi.
            </p>
        </div>
        <div class="cta-btns">
            <a href="#" class="btn-primary">Solicitar Demo</a>
            <a href="#" class="btn-outline">Ver Planes y Precios</a>
        </div>
    </div>
</section>

<!-- ══ FOOTER ══════════════════════════════════════════════════ -->
<footer class="footer">
    <div class="footer-inner">

        <div class="footer-about">
            <div class="footer-brand">
                <div class="brand-icon"><i class="bi bi-cup-hot-fill"></i></div>
                RESTOWI
            </div>
            <p class="footer-tagline">
                El sistema todo en uno<br>
                para restaurantes que<br>
                quieren crecer.
            </p>
        </div>

        <div class="footer-col">
            <h4>Navegación</h4>
            <ul>
                <li><a href="#">Inicio</a></li>
                <li><a href="#">Funcionalidades</a></li>
                <li><a href="#">Demo</a></li>
                <li><a href="#">Precio</a></li>
            </ul>
        </div>

        <div class="footer-col footer-cta-col">
            <p class="footer-cta-title">¡Pruébalo ahora!</p>
            <p class="footer-desc">
                Descubre por qué somos la mejor opción para tu restaurante.
            </p>
            <a href="#" class="btn-primary">Probar Demo</a>
        </div>

    </div>

    <!-- Copyright móvil -->
    <p class="footer-copy">© <?php echo date('Y'); ?> Todos los derechos reservados.</p>
</footer>

<?php include __DIR__ . '/../layouts/footer-home.php'; ?>

<div id="fadeOverlay"></div>
<script src="<?php echo BASE_URL; ?>/public/js/landing.js"></script>
</body>
</html>