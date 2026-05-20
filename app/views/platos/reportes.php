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
            <span>Platos</span>
            <i class="fa-solid fa-chevron-right"></i>
            <span id="breadcrumb-page">Reportes</span>
        </nav>
        <div class="main-content">
            <div class="table-responsive">
                <?php if (empty($platos)) : ?>
                    <p>No hay registro</p>
                <?php else: ?>
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Disponibilidad</th>
                                <th>Descripcion</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($platos as $plato): ?>
                                <tr>
                                    <td><?php echo $plato['Id_Plato'] ?></td>
                                    <td><?php echo $plato['nombre'] ?></td>
                                    <td><?php echo $plato['precio'] ?></td>
                                    <td><?php echo $plato['disponibilidad'] ? 'Disponible' : 'No disponible' ?></td>
                                    <td><?php echo $plato['descripcion'] ?></td>
                                    </td>
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