<!--El archivo .htacces tiene este linea RewriteRule ^(.+)$ app/index.php?url=$1 [QSA,L] -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/dashboard.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/responsive.css">


<?php
$segmentos    = explode('/', trim($_GET['url'] ?? 'dashboard', '/'));
$rutaActual   = $segmentos[0] ?? 'dashboard';
$accionActual = $segmentos[1] ?? '';
$rol          = $_SESSION['usuario']['rol'] ?? '';
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
        <?php if (in_array($rol, ['admin', 'mesero', 'cocina'])): ?>
        <li class="<?php echo $rutaActual === 'pedidos' ? 'dropdown show' : 'dropdown'; ?>">
        <a href="#" class="dropbtn <?php echo $rutaActual === 'pedidos' ? 'activo' : ''; ?>">
            <i class="fa-solid fa-clipboard-list"></i>
            <span>Pedidos</span>
            <i class="fa-solid fa-chevron-down arrow"></i>
        </a>
        <div class="dropdown-content">
            <?php if (in_array($rol, ['admin', 'mesero'])): ?>
            <a href="<?php echo BASE_URL; ?>/pedidos/croquis"
                class="<?php echo ($rutaActual === 'pedidos' && $accionActual === 'croquis') ? 'activo-sub' : ''; ?>">
                <i class="fa-solid fa-table-cells"></i>
                Mesa
            </a>
            <a href="<?php echo BASE_URL; ?>/pedidos/llevar"
                class="<?php echo ($rutaActual === 'pedidos' && $accionActual === 'llevar') ? 'activo-sub' : ''; ?>">
                <i class="fa-solid fa-bag-shopping"></i>
                Llevar
            </a>
            <?php endif; ?>
            <a href="<?php echo BASE_URL; ?>/pedidos/reportes"
                class="<?php echo ($rutaActual === 'pedidos' && $accionActual === 'reportes') ? 'activo-sub' : ''; ?>">
                <i class="fa-solid fa-chart-bar"></i>
                Reportes
            </a>
        </div>
        </li>
        <?php endif; ?>
         <!-- ================ END PEDIDOS ================ -->

        <!-- ================ START PLATOS ================ -->
        <li>
            <a href="<?php echo BASE_URL; ?>/platos"
                class="<?php echo $rutaActual === 'platos' ? 'activo' : ''; ?>">
                <i class="fa-solid fa-utensils"></i>
                <span>Platos</span>
            </a>
        </li>
        <!-- ================ END PLATOS ================ -->

        <!-- ================ START ASISTENCIA ================ -->
         <li>
            <a href="<?php echo BASE_URL; ?>/asistencias"
                class="<?php echo $rutaActual === 'asistencias' ? 'activo' : ''; ?>">
                <i class="fa-solid fa-calendar-check"></i>
                <span>Asistencia</span>
            </a>
        </li>
        <!-- ================ END ASISTENCIA ================ -->

        <!-- ================ START PAGOS ================ -->
        <?php if (in_array($rol, ['admin', 'mesero'])): ?>
        <li>
            <a href="<?php echo BASE_URL; ?>/pagos"
                class="<?php echo $rutaActual === 'pagos' ? 'activo' : ''; ?>">
                <i class="fa-solid fa-money-bill"></i>
                <span>Pagos</span>
            </a>
        </li>
        <?php endif; ?>

        <!-- ================ END PAGOS ================ -->

        <!-- ================ START USUARIOS ================ -->
        <?php if (in_array($rol, ['admin'])): ?>
        <li>
            <a href="<?php echo BASE_URL; ?>/usuarios"
                class="<?php echo $rutaActual === 'usuarios' ? 'activo' : ''; ?>">
                <i class="fa-solid fa-user-cog"></i>
                <span>Usuarios</span>
            </a>
        </li>
        <?php endif; ?>
        <!-- ================ END USUARIOS ================ -->
         

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