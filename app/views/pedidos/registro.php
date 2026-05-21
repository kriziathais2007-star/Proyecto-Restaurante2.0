<!DOCTYPE html>
<html lang="Es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Registrar Pedido</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/responsive.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/registro-asi.css">
</head>
<body>

    <?php include __DIR__ . '/../layouts/sidebar-dashboard.php'; ?>

    <main>
        <nav class="breadcrumb">
            <span>Dashboard</span>
            <i class="fa-solid fa-chevron-right"></i>
            <span>Pedidos</span>
            <i class="fa-solid fa-chevron-right"></i>
            <span id="breadcrumb-page">Registrar</span>
        </nav>

        <div class="main-content">
            <div class="form-container">

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <h2>Registrar Pedido</h2>

                <form action="<?php echo BASE_URL; ?>/pedidos/guardar" method="POST">

                    <div class="mb-3">
                        <label for="Id_Usuario">Empleado</label>
                        <select name="Id_Usuario" id="Id_Usuario" class="form-control" required>
                            <option value="">-- Selecciona un empleado --</option>
                            <?php foreach ($usuarios as $u): ?>
                                <option value="<?php echo $u['Id_Usuario']; ?>">
                                    <?php echo htmlspecialchars($u['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="mesa">Mesa</label>
                        <input type="number" name="mesa" id="mesa" class="form-control" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label for="fecha">Fecha</label>
                        <input type="date" name="fecha" id="fecha" class="form-control"
                               value="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="hora_pedido">Hora</label>
                        <input type="time" name="hora_pedido" id="hora_pedido" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="estado_pedido">Estado</label>
                        <select name="estado_pedido" id="estado_pedido" class="form-control" required>
                            <option value="">-- Selecciona estado --</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="En proceso">En proceso</option>
                            <option value="Entregado">Entregado</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-guardar">
                        <i class="fa-solid fa-save"></i> Guardar
                    </button>

                    <a href="<?php echo BASE_URL; ?>/pedidos/reportes" class="btn-cancelar">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>

                </form>
            </div>
        </div>
    </main>

    <script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>