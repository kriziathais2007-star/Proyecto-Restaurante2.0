<!DOCTYPE html>
<html lang="Es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Panel de Administración</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/responsive.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/detalle-pedido.css">
</head>
<body>

<?php include __DIR__ . '/../layouts/sidebar-dashboard.php'; ?>

<main>
  <nav class="breadcrumb">
    <span>Dashboard</span>
    <i class="fa-solid fa-chevron-right"></i>
    <span>Inicio</span>
  </nav>

  <div class="main-content">

    <!-- Saludo -->
    <div class="dash-header">
      <div>
        <h1 class="dash-title">
          Bienvenida, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?> 
        </h1>
        <p class="dash-sub"><?php echo date('l, d \d\e F \d\e Y'); ?></p>
      </div>
      <a href="<?php echo BASE_URL; ?>/../pedidos/registro" class="btn-nuevo-pedido">
        <i class="fa-solid fa-plus"></i> Nuevo Pedido
      </a>
    </div>

    <!-- Tarjetas resumen -->
    <div class="stats-grid">

      <div class="stat-card stat-pink">
        <div class="stat-icon"><i class="fa-solid fa-receipt"></i></div>
        <div>
          <p class="stat-label">Pedidos hoy</p>
          <p class="stat-value"><?php echo $pedidos_hoy; ?></p>
        </div>
      </div>

      <div class="stat-card stat-rose">
        <div class="stat-icon"><i class="fa-solid fa-dollar-sign"></i></div>
        <div>
          <p class="stat-label">Ingresos hoy</p>
          <p class="stat-value">$<?php echo number_format($ingresos_hoy, 2); ?></p>
        </div>
      </div>

      <div class="stat-card stat-mauve">
        <div class="stat-icon"><i class="fa-solid fa-clock"></i></div>
        <div>
          <p class="stat-label">Pendientes</p>
          <p class="stat-value"><?php echo $pedidos_pendientes; ?></p>
        </div>
      </div>

    </div>

    <!-- Pedidos recientes -->
    <div class="section-card">
      <div class="section-header">
        <h2 class="section-title">
          <i class="fa-solid fa-fire"></i> Pedidos recientes
        </h2>
        <a href="<?php echo BASE_URL; ?>/../pedidos/reportes" class="link-ver-todos">
          Ver todos <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>

      <?php if (empty($pedidos_recientes)): ?>
        <p class="empty-msg">No hay pedidos aún hoy.</p>
      <?php else: ?>
        <div class="pedidos-list">
          <?php foreach ($pedidos_recientes as $p): ?>
            <div class="pedido-row">

              <div class="pedido-mesa">
                <span class="mesa-badge">Mesa <?php echo $p['mesa']; ?></span>
              </div>

              <div class="pedido-info">
                <p class="pedido-platos">
                  <?php echo $p['total_platos']; ?> plato(s)
                </p>
                <p class="pedido-hora">
                  <?php echo date('H:i', strtotime($p['hora_pedido'])); ?>
                </p>
              </div>

              <div class="pedido-total">
                $<?php echo number_format($p['total'], 2); ?>
              </div>

              <div class="pedido-estado">
                <?php
                  $estado = $p['estado_pedido'];
                  $clase  = match($estado) {
                    'Entregado' => 'badge-entregado',
                    'Pendiente' => 'badge-pendiente',
                    'En proceso'=> 'badge-proceso',
                    default     => 'badge-default',
                  };
                ?>
                <span class="estado-badge <?php echo $clase; ?>">
                  <?php echo htmlspecialchars($estado); ?>
                </span>
              </div>

            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

  </div>
</main>

<script>const BASE_URL = "<?php echo BASE_URL; ?>";</script>
<script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>