<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Asistencias</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/usuario.css">
</head>
<body>

<?php include __DIR__ . '/../layouts/sidebar-dashboard.php'; ?>

<main>
    <header>
    <h1>Control de Asistencias</h1>
    <div style="display:flex; gap:10px;">
    <button class="btn-registrar" id="btnEntrada">
        <i class="fa-solid fa-right-to-bracket"></i>
        Registrar Entrada
    </button>
    <button class="btn-registrar" id="btnSalida">
        <i class="fa-solid fa-right-from-bracket"></i>
        Registrar Salida
    </button>
    </div>
    </header>
    <div class="main-content">
        <div class="table-responsive">
            <?php if (empty($asistencias)): ?>
                <p>No hay asistencias registradas.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Fecha</th>
                            <th>Hora Entrada</th>
                            <th>Hora Salida</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($asistencias as $asistencia): ?>
                            <tr>
                                <td>
                                    <?php echo htmlspecialchars($asistencia['id_asistencia']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($asistencia['nombre'] ?? 'Sin usuario'); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($asistencia['fecha']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($asistencia['hora_entrada'] ?? '-'); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($asistencia['hora_salida'] ?? '-'); ?>
                                </td>

                                <td>
                                <button class="btn-eliminar" data-id="<?php echo $asistencia['id_asistencia']; ?>">
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

<!-- Modal Editar -->

<div class="modal-overlay" id="modalEditarOverlay"></div>

<div class="modal-editar" id="modalEditar">

    <button class="modal-cerrar" id="modalCerrar">
        &times;
    </button>

    <h2 class="modal-title">
        Editar Asistencia
    </h2>

    <form class="modal-form" id="formEditar">

        <input
            type="hidden"
            id="id_asistencia"
            name="id_asistencia"
        >

        <div class="modal-group">

            <label for="fecha">
                Fecha:
            </label>

            <input
                type="date"
                id="fecha"
                name="fecha"
                required
            >

        </div>

        <div class="modal-group">

            <label for="hora_entrada">
                Hora Entrada:
            </label>

            <input
                type="time"
                id="hora_entrada"
                name="hora_entrada"
            >

        </div>

        <div class="modal-group">

            <label for="hora_salida">
                Hora Salida:
            </label>

            <input
                type="time"
                id="hora_salida"
                name="hora_salida"
            >

        </div>

        <button type="submit" class="btn-guardar">
            Guardar Cambios
        </button>

    </form>

</div>

<script>
    const BASE_URL = "<?php echo BASE_URL; ?>";
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>

<!-- Debes crear este archivo -->
<script src="<?php echo BASE_URL; ?>/public/js/asistencias.js"></script>

</body>
</html>