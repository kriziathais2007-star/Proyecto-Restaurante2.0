<!DOCTYPE html>
<html lang="Es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Panel de Administración</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/responsive.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/botones.css">
</head>
<body>

<?php include __DIR__ . '/../layouts/sidebar-dashboard.php'; ?>

<main>
    <nav class="breadcrumb">
        <span>Inicio</span>
        <i class="fa-solid fa-chevron-right"></i>
        <span id="breadcrumb-page">Dashboard</span>
    </nav>

    <div class="main-content">
        <h2>Detalles de Pedidos</h2>
        <div class="table-responsive">
            <?php if (empty($detalles)): ?>
                <p>No hay detalles de pedido registrados.</p>
            <?php else: ?>
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Cantidad</th>
                            <th>Estado de Pedido</th>
                            <th>Nº de Pedido</th>
                            <th>Platos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detalles as $d): ?>
                            <tr>
                                <td><?php echo $d['Id_Detalle']; ?></td>
                                <td><?php echo htmlspecialchars($d['cantidad']); ?></td>
                                <td><?php echo htmlspecialchars($d['estado_plato']); ?></td>
                                <td><?php echo htmlspecialchars($d['Id_Pedido']); ?></td>
                                <td><?php echo htmlspecialchars($d['nombre_plato']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>const BASE_URL = "<?php echo BASE_URL; ?>";</script>
<script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>