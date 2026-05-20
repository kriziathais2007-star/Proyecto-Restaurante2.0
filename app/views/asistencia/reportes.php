<!DOCTYPE html>
<html lang="Es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Asistencia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/responsive.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/botones.css">
</head>

<body>

    <?php include __DIR__ . '/../layouts/sidebar-dashboard.php'; ?>

    <!-- CONTENIDO PRINCIPAL -->
    <main>
        <nav class="breadcrumb">
            <span>Dashboard</span>
            <i class="fa-solid fa-chevron-right"></i>
            <span>Asistencia</span>
            <i class="fa-solid fa-chevron-right"></i>
            <span id="breadcrumb-page">Reportes</span>
        </nav>
        <div class="main-content">
            <div class="table-responsive">
                <?php if (empty($asistencia)) : ?>
                    <p>No hay registro</p>
                <?php else: ?>
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Hora de Entrada</th>
                                <th>Hora de Salida</th>
                                <th>Estado</th>
                                <th>Nombre</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($asistencia as $a): ?>
                                <tr>
                                    <td><?php echo $a['Id_Asistencia'] ?></td>
                                    <td><?php echo $a['fecha'] ?></td>
                                    <td><?php echo $a['hora_entrada'] ?></td>
                                    <td><?php echo $a['hora_salida'] ?></td>
                                    <td><?php echo htmlspecialchars($a['estado']) ?></td>
                                    <td><?php echo htmlspecialchars($a['nombre_usuario']) ?></td>
                                    <td>
                                        <button class="btn-editar">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <button class="btn-eliminar">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>