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


<!--DASHBOARD para movil -->
<div class="dashboard-overlay" id="dashboardOverlay"></div>

<aside class="dashboard" id="dashboard">
    <!-- Botón cerrar -->
    <button class="dashboard-close" id="dashboardClose" aria-label="Cerrar menú">
        <i class="bi bi-x-lg"></i>
    </button>

    <!-- navegación -->
    <nav class="dashboard-nav">
        <a href="#inicio" class="dashboard-link">Inicio</a>
        <a href="#funcionalidades" class="dashboard-link">Funcionalidades</a>
        <a href="#demo" class="dashboard-link">Demo</a>
        <a href="#precio" class="dashboard-link">Precio</a>
        <a href="#contacto" class="dashboard-link">Contacto</a>
        <div class="dropdown">
            <a href="#" class="dashboard-link">
             PROBAR DEMO ▾
            </a>
        
            <div class="dropdown-content">
                <a href="<?= BASE_URL ?>/login">Iniciar sesión</a>
                <a href="<?= BASE_URL ?>/registro">Registrarse</a>
            </div>
        </div>
    </nav>
</aside>

<!-- navbar -->
<nav class="navbar" id="navbar">
    <a class="navbar-brand" href="#">
        <div class="brand-icon"><i class="bi bi-shop" style="color: rgb(255, 255, 255);"></i></div>
        RESTOWI
    </a>

    <!-- navegación -->
    <ul class="navbar-links">
        <li><a href="#inicio">Inicio</a></li>
        <li><a href="#funcionalidades">Funcionalidades</a></li>
        <li><a href="#demo">Demo</a></li>
        <li><a href="#precio">Precio</a></li>
        <li><a href="#contacto">Contacto</a></li>
        <li class="dropdown">
            <a href="#" class="btn-nav">
            Probar Demo ▾
            </a>

             <div class="dropdown-content">
                <a href="<?= BASE_URL ?>/login">Iniciar sesión</a>
                <a href="<?= BASE_URL ?>/registro">Registrarse</a>
            </div>
        </li>
    </ul>

    <!-- menu del dashboard-->
    <button class="menu-btn" id="menuBtn" aria-label="Abrir menú">
        <i class="bi bi-list"></i>
    </button>
</nav>

<!-- inicio -->
<section class="hero" id="inicio">
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
        <img src="<?php echo BASE_URL; ?>/public/recursos/imagen-prueba.jpg" alt="Restaurante">
    </div>
</section>

<!-- funcionalidades -->
<section class="features-section" id="funcionalidades">
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

<!-- demo -->
<section class="demo-section" id="demo">
    <div class="section-label"><span>¡Pruébalo tú mismo!</span></div>
    <h2 class="section-title">Conoce nuestro sistema en acción</h2>
    <p class="demo-sub">
        Explora todas las funcionalidades con nuestro demo interactivo.<br>
        No necesitas registrarte.
    </p>

    <div class="demo-video-wrap">
        <img src="<?php echo BASE_URL; ?>/public/recursos/imagen-prueba.jpg" alt="Demo del sistema">
        <button class="play-btn" id="playBtn" aria-label="Reproducir demo">
            <i class="bi bi-play-fill"></i>
        </button>
    </div>

    <div class="demo-btn-wrap">
        <a href="#" class="btn-outline">Ver Demo en Vivo</a>
    </div>
</section>

<!--precio y planes-->
<section class="cta-section" id="precio">
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

<!-- contactos -->
<footer class="footer" id="contacto">
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
                <li><a href="#inicio">Inicio</a></li>
                <li><a href="#funcionalidades">Funcionalidades</a></li>
                <li><a href="#demo">Demo</a></li>
                <li><a href="#precio">Precio</a></li>
            </ul>
        </div>

        <div class="footer-col footer-cta-col">
            <p class="footer-cta-title">¡Pruébalo ahora!</p>
            <p class="footer-desc">
                Descubre por qué somos la mejor opción para tu restaurante.
            </p>
            <div class="dropdown">
            <a href="#" class="btn-primary">
             PROBAR DEMO ▾
            </a>

            <div class="dropdown-content">
                <a href="<?= BASE_URL ?>/login">Iniciar sesión</a>
                <a href="<?= BASE_URL ?>/registro">Registrarse</a>
            </div>
        </div>
        </div>


    </div>
</footer>
<?php include __DIR__ . '/../layouts/footer-home.php'; ?>

<div id="fadeOverlay"></div>
<script src="<?php echo BASE_URL; ?>/public/js/landing.js"></script>
</body>
</html>