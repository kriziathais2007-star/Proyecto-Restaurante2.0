<!--El archivo .htacces tiene este linea RewriteRule ^(.+)$ app/index.php?url=$1 [QSA,L] -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/dashboard.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/responsive.css">


<?php
$segmentos    = explode('/', trim($_GET['url'] ?? 'dashboard', '/'));
$rutaActual   = $segmentos[0] ?? 'dashboard';
$accionActual = $segmentos[1] ?? '';
?>

<!-- TOPBAR (solo visible en móvil) -->
<div class="topbar">
    <div class="title-business">
        <span><?php echo htmlspecialchars($usuario['nombre'] ?? 'Usuario'); ?></span>
    </div>
    <div class="btn-menu">
        <button class="hamburger" aria-label="Abrir menú">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
</div>

<!-- OVERLAY -->
<div class="overlay"></div>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo"><?php echo htmlspecialchars($usuario['nombre'] ?? 'Usuario'); ?></div>
    <ul>

        <!-- ================ START DASHBOARD ================ -->
        <li>
            <a href="<?php echo BASE_URL; ?>/dashboard"
                class="<?php echo $rutaActual === 'dashboard' ? 'activo' : ''; ?>">
                <i class="fa-solid fa-house"></i>
                <span>Inicio</span>
            </a>
        </li>
        <!-- ================ END DASHBOARD ================ -->

        <!-- ================ START PEDIDOS ================ -->
        <li class="<?php echo $rutaActual === 'pedidos' ? 'dropdown show' : 'dropdown'; ?>">
            <a href="#" class="dropbtn <?php echo $rutaActual === 'pedidos' ? 'activo' : ''; ?>">
                <i class="fa-solid fa-clipboard-list"></i>
                <span>Pedidos</span>
                <i class="fa-solid fa-chevron-down arrow"></i>
            </a>
            <div class="dropdown-content">
                <a href="<?php echo BASE_URL; ?>??"
                    class="<?php echo ($rutaActual === '??' && $accionActual === 'registro') ? 'activo' : ''; ?>">
                    <i class="fa-solid fa-table"></i>
                    Mesa
                </a>
                <a href="<?php echo BASE_URL; ?>??"
                    class="<?php echo ($rutaActual === '??' && $accionActual === 'reportes') ? 'activo' : ''; ?>">
                    <i class="fa-solid fa-clock"></i>
                    Llevar
                </a>
                <a href="<?php echo BASE_URL; ?>??"
                    class="<?php echo ($rutaActual === '??' && $accionActual === 'registro') ? 'activo' : ''; ?>">
                    <i class="fa-solid fa-chart-bar"></i>
                    Resportes
                </a>
            </div>
        </li>
        <!-- ================ END PEDIDOS ================ -->

        <!-- ================ START PLATOS ================ -->
        <li>
            <a href="<?php echo BASE_URL; ?>??"
                class="<?php echo $rutaActual === '??' ? 'activo' : ''; ?>">
                <i class="fa-solid fa-utensils"></i>
                <span>Platos</span>
            </a>
        </li>
        <!-- ================ END PLATOS ================ -->

        <!-- ================ START ASISTENCIA ================ -->
         <li>
            <a href="<?php echo BASE_URL; ?>??"
                class="<?php echo $rutaActual === '??' ? 'activo' : ''; ?>">
                <i class="fa-solid fa-calendar-check"></i>
                <span>Asistencia</span>
            </a>
        </li>
        <!-- ================ END ASISTENCIA ================ -->

        <!-- ================ START USUARIOS ================ -->
        <li>
            <a href="<?php echo BASE_URL; ?>??"
                class="<?php echo $rutaActual === '??' ? 'activo' : ''; ?>">
                <i class="fa-solid fa-user-cog"></i>
                <span>Usuarios</span>
            </a>
        </li>
        <!-- ================ END USUARIOS ================ -->
         <li>
            <a href="<?php echo BASE_URL; ?>??"
                class="<?php echo $rutaActual === '??' ? 'activo' : ''; ?>">
                <i class="fa-solid fa-money-bill"></i>
                <span>Pagos</span>
            </a>
        </li>

        <li class="nav-logout">
            <a href="<?php echo BASE_URL; ?>/logout" id="btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Cerrar sesión</span>
            </a>
        </li>

    </ul>
</aside>
<script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
<script src="<?php echo BASE_URL; ?>/public/js/dropdown.js"></script>