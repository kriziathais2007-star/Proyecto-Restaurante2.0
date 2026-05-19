<!--El archivo .htacces tiene este linea RewriteRule ^(.+)$ app/index.php?url=$1 [QSA,L] -->
<!--Detectamos en qué pagina estamos para marcar el link activo del siderbar(inicio,producto,...)-->
<?php
$rutaActual = explode('/', trim($_GET['url'] ?? 'dashboard', '/'))[0] ?: 'dashboard';
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

        <!-- ================ START EMPLEADOS ================ -->
        <!-- 
             - `$rutaActual` contiene el primer segmento de la URL (controlador).
             - Si `$rutaActual === 'empleados'` añadimos la clase `dropdown show`
               para mantener abierto el menú y `activo` para el enlace.
                - Si no, solo añadimos `dropdown` sin `show` ni `activo`.
        -->
        <li class="<?php echo $rutaActual === 'pedidos' ? 'dropdown show' : 'dropdown'; ?>">
            <a href="#" class="dropbtn <?php echo $rutaActual === 'pedidos' ? 'activo' : ''; ?>">
                <i class="fa-solid fa-clipboard-list"></i>
                <span>Pedidos</span>
                <i class="fa-solid fa-chevron-down arrow"></i>
            </a>
            <div class="dropdown-content">
                <a href="<?php echo BASE_URL; ?>/pedidos">
                    <i class="fa-solid fa-edit"></i>
                    <span class="<?php echo $rutaActual === 'pedidos' ? 'activo' : ''; ?>">
                        Nuevo Pedido
                    </span>
                </a>
                <a href="<?php echo BASE_URL; ?>/pedidos/reportes">
                    <i class="fa-solid fa-users"></i>
                    Historial
                </a>  
                
    
            </div>
        </li>
        <!-- ================ END EMPLEADOS ================ -->

        <!-- ================ START CARGOS ================ -->
        <li class="<?php echo $rutaActual === 'platos' ? 'dropdown show' : 'dropdown'; ?>">
            <a href="#" class="dropbtn <?php echo $rutaActual === 'platos' ? 'activo' : ''; ?>">
                <i class="fa-solid fa-briefcase"></i>
                <span>Platos</span>
                <i class="fa-solid fa-chevron-down arrow"></i>
            </a>
            <div class="dropdown-content">
                <a href="<?php echo BASE_URL; ?>/platos"
                    class="<?php echo $rutaActual === 'platos' ? 'activo' : ''; ?>">
                    <i class="fa-solid fa-clipboard-list"></i>
                    Gestión de Platos
                </a>
                <a href="<?php echo BASE_URL; ?>/platos/reportes">
                    <i class="fa-solid fa-edit"></i>
                    Registrar Plato
                </a> 
            </div>
        </li>
        <!-- ================ END CARGOS ================ -->

        <!-- ================ START ASISTENCIA ================ -->
        <li class="<?php echo $rutaActual === 'asistencia' ? 'dropdown show' : 'dropdown'; ?>">
            <a href="#" class="dropbtn <?php echo $rutaActual === 'asistencia' ? 'activo' : ''; ?>">
                <i class="fa-solid fa-calendar-check"></i>
                <span>Asistencia</span>
                <i class="fa-solid fa-chevron-down arrow"></i>
            </a>
            <div class="dropdown-content">
                <a href="<?php echo BASE_URL; ?>/asistencia/registro"
                    class="<?php echo $rutaActual === 'asistencia' ? 'activo' : ''; ?>">
                    <i class="fa-solid fa-edit "></i>
                    Registrar Asistencia
                </a>
                <a href="<?php echo BASE_URL; ?>/asistencia/reportes"
                    class="<?php echo $rutaActual === 'asistencia' ? 'activo' : ''; ?>">
                    <i class="fa-solid fa-clock"></i>
                    Reporte
                </a>
            </div>
        </li>
        <!-- ================ END ASISTENCIA ================ -->


        <!-- ================ START USUARIOS ================ -->
        <li>
            <a href="<?php echo BASE_URL; ?>/usuarios" class="<?php echo $rutaActual === 'usuario' ? 'activo' : ''; ?>">
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