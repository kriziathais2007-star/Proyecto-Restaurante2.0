<!--El archivo .htacces tiene este linea RewriteRule ^(.+)$ app/index.php?url=$1 [QSA,L] -->
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
                <a href="<?php echo BASE_URL; ?>/pedidos/registro"
                    class="<?php echo ($rutaActual === 'pedidos' && $accionActual === 'registro') ? 'activo' : ''; ?>">
                    <i class="fa-solid fa-edit"></i>
                    Nuevo Pedido
                </a>
                <a href="<?php echo BASE_URL; ?>/pedidos/reportes"
                    class="<?php echo ($rutaActual === 'pedidos' && $accionActual === 'reportes') ? 'activo' : ''; ?>">
                    <i class="fa-solid fa-users"></i>
                    Historial
                </a>
            </div>
        </li>
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
        <li class="<?php echo $rutaActual === 'asistencia' ? 'dropdown show' : 'dropdown'; ?>">
            <a href="#" class="dropbtn <?php echo $rutaActual === 'asistencia' ? 'activo' : ''; ?>">
                <i class="fa-solid fa-calendar-check"></i>
                <span>Asistencia</span>
                <i class="fa-solid fa-chevron-down arrow"></i>
            </a>
            <div class="dropdown-content">
                <a href="<?php echo BASE_URL; ?>/asistencia/registro"
                    class="<?php echo ($rutaActual === 'asistencia' && $accionActual === 'registro') ? 'activo' : ''; ?>">
                    <i class="fa-solid fa-edit"></i>
                    Registrar Asistencia
                </a>
                <a href="<?php echo BASE_URL; ?>/asistencia/reportes"
                    class="<?php echo ($rutaActual === 'asistencia' && ($accionActual === 'reportes' || $accionActual === '')) ? 'activo' : ''; ?>">
                    <i class="fa-solid fa-clock"></i>
                    Reporte
                </a>
            </div>
        </li>
        <!-- ================ END ASISTENCIA ================ -->

        <!-- ================ START USUARIOS ================ -->
        <li>
            <a href="<?php echo BASE_URL; ?>/usuarios"
                class="<?php echo $rutaActual === 'usuarios' ? 'activo' : ''; ?>">
                <i class="fa-solid fa-user-cog"></i>
                <span>Usuarios</span>
            </a>
        </li>
        <!-- ================ END USUARIOS ================ -->

        <li class="nav-logout">
            <a href="<?php echo BASE_URL; ?>/logout" id="btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Cerrar sesión</span>
            </a>
        </li>

    </ul>
</aside>

<script src="<?php echo BASE_URL; ?>/public/js/dropdown.js"></script>