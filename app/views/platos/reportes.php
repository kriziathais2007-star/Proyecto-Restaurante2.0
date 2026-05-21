<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Platos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>../public/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>../public/css/menu.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>../public/css/responsive.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>../public/css/botones.css">
</head>

<body>

    <?php include __DIR__ . '/../layouts/sidebar-dashboard.php'; ?>

    <main class="main">

        <div class="menu-header">
            <h2 class="menu-title">
                <i class="fa-solid fa-book-open"></i>
                Carta del Menú
                <span class="badge-total" id="badge-total"><?php echo count($platos); ?></span>
            </h2>
            <button class="btn-agregar-plato"
                    data-bs-toggle="modal" data-bs-target="#modalAgregarPlato">
                <i class="fa-solid fa-plus"></i> Agregar plato
            </button>
        </div>

        <div class="main">

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
                    <?php if ($_GET['success'] === 'editado'): ?>
                        Plato actualizado correctamente.
                    <?php elseif ($_GET['success'] === 'eliminado'): ?>
                        Plato eliminado correctamente.
                    <?php else: ?>
                        Plato agregado correctamente.
                    <?php endif; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show mx-3" role="alert">
                    <?php if ($_GET['error'] === 'datos_invalidos'): ?>
                        Los datos ingresados no son válidos.
                    <?php else: ?>
                        Ocurrió un error al procesar la solicitud.
                    <?php endif; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (empty($platos)): ?>
                <p class="p-3">No hay registro</p>
            <?php else: ?>
                <div class="row g-3 p-3">
                    <?php foreach ($platos as $plato): ?>
                    <div class="col-25 col-sm-24 col-md-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php echo htmlspecialchars($plato['nombre']); ?>
                                </h5>
                                <p class="card-text">
                                    <strong>Precio:</strong>
                                    $<?php echo number_format($plato['precio'], 2); ?>
                                </p>
                                <p class="card-text">
                                    <strong>Disponibilidad:</strong>
                                    <?php if ($plato['disponibilidad']): ?>
                                        <span class="badge bg-success">Disponible</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No disponible</span>
                                    <?php endif; ?>
                                </p>
                                <p class="card-text">
                                    <strong>Descripción:</strong>
                                    <?php echo htmlspecialchars($plato['descripcion']); ?>
                                </p>
                            </div>
                            <div class="card-footer d-flex gap-2">
                                <button class="btn-editar"
                                    title="Editar"
                                    data-id="<?php echo $plato['Id_Plato']; ?>"
                                    data-nombre="<?php echo htmlspecialchars($plato['nombre']); ?>"
                                    data-precio="<?php echo $plato['precio']; ?>"
                                    data-disponibilidad="<?php echo $plato['disponibilidad']; ?>"
                                    data-descripcion="<?php echo htmlspecialchars($plato['descripcion']); ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarPlato">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button class="btn-eliminar"
                                    title="Eliminar"
                                    data-id="<?php echo $plato['Id_Plato']; ?>"
                                    data-nombre="<?php echo htmlspecialchars($plato['nombre']); ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEliminarPlato">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </main>

    <!-- MODAL AGREGAR -->
    <div class="modal fade" id="modalAgregarPlato" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-plato">
                <div class="modal-plato-header">
                    <h5><i class="fa-solid fa-plus me-2"></i>Nuevo plato</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo BASE_URL; ?>/platos/crear" method="POST">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="field-label">Nombre del plato</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej: Lomo Saltado" required>
                        </div>
                        <div class="mb-3">
                            <label class="field-label">Precio (S/.)</label>
                            <input type="number" name="precio" class="form-control" placeholder="Ej: 25.00" min="0" step="0.50" required>
                        </div>
                        <div class="mb-3">
                            <label class="field-label">Disponibilidad</label>
                            <select name="disponibilidad" class="form-select">
                                <option value="1">Disponible</option>
                                <option value="0">No disponible</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="field-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3" placeholder="Describe el plato..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-guardar-plato">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDITAR -->
    <div class="modal fade" id="modalEditarPlato" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-plato">
                <div class="modal-plato-header">
                    <h5><i class="fa-solid fa-pen me-2"></i>Editar plato</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo BASE_URL; ?>/platos/editar" method="POST">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="field-label">Nombre del plato</label>
                            <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="field-label">Precio (S/.)</label>
                            <input type="number" name="precio" id="edit_precio" class="form-control" step="0.50" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="field-label">Disponibilidad</label>
                            <select name="disponibilidad" id="edit_disponibilidad" class="form-select">
                                <option value="1">Disponible</option>
                                <option value="0">No disponible</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="field-label">Descripción</label>
                            <textarea name="descripcion" id="edit_descripcion" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-guardar-plato">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL ELIMINAR -->
    <div class="modal fade" id="modalEliminarPlato" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-plato">
                <div class="modal-plato-header">
                    <h5><i class="fa-solid fa-trash me-2"></i>Eliminar plato</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo BASE_URL; ?>/platos/eliminar" method="POST">
                    <input type="hidden" name="id" id="eliminar_id">
                    <div class="modal-body p-4 text-center">
                        <p style="font-size: 15px; color: #333;">
                            ¿Estás seguro que deseas eliminar el plato
                            <strong id="eliminar_nombre">?
                        </p>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="confirm">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="<?php echo BASE_URL; ?>../public/js/dashboard.js"></script>
    <script src="<?php echo BASE_URL; ?>../public/js/menu-botones.js"></script>    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>