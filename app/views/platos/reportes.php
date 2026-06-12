<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Carta del Menú</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/platos.css">
</head>
<body>

<?php include __DIR__ . '/../layouts/sidebar-dashboard.php'; ?>

<main>
    <header>
        <h1>Carta del Menú</h1>
    </header>

    <!--  SECCIÓN ENTRADAS  -->
    <section class="seccion-carta">
        <div class="seccion-header">
            <div class="seccion-titulo">
                <i class="fa-solid fa-bowl-food"></i>
                <h2>Entradas <span class="precio-badge">S/ 3.00</span></h2>
            </div>
            <button class="btn-registrar" id="btnAbrirEntrada">
                <i class="fa-solid fa-plus"></i> Nueva Entrada
            </button>
        </div>

        <div class="table-responsive">
            <?php if (empty($entradas)): ?>
                <p class="empty-msg">No hay entradas registradas.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Precio extra</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entradas as $entrada): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($entrada['id_entrada']); ?></td>
                                <td><?php echo htmlspecialchars($entrada['nombre']); ?></td>
                                <td class="precio">S/ <?php echo number_format($entrada['precio'], 2); ?></td>
                                <td>
                                    <span class="estado-badge <?php echo $entrada['activo'] ? 'activo' : 'inactivo'; ?>">
                                        <?php echo $entrada['activo'] ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn-editar btn-editar-entrada"
                                        data-id="<?php echo $entrada['id_entrada']; ?>"
                                        data-nombre="<?php echo htmlspecialchars($entrada['nombre']); ?>"
                                        data-precio="<?php echo $entrada['precio']; ?>"
                                        data-activo="<?php echo $entrada['activo']; ?>">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button class="btn-eliminar btn-eliminar-entrada"
                                        data-id="<?php echo $entrada['id_entrada']; ?>">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </section>
    <!--  SECCIÓN PLATOS  -->
    <section class="seccion-carta">
        <div class="seccion-header">
            <div class="seccion-titulo">
                <i class="fa-solid fa-utensils"></i>
                <h2>Platos <span class="precio-badge">S/ 8.00</span></h2>
            </div>
            <button class="btn-registrar" id="btnAbrirPlato">
                <i class="fa-solid fa-plus"></i> Nuevo Plato
            </button>
        </div>

        <div class="table-responsive">
            <?php if (empty($platos)): ?>
                <p class="empty-msg">No hay platos registrados.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($platos as $plato): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($plato['id_plato']); ?></td>
                                <td><?php echo htmlspecialchars($plato['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($plato['descripcion'] ?? '—'); ?></td>
                                <td class="precio">S/ <?php echo number_format($plato['precio'], 2); ?></td>
                                <td>
                                    <span class="estado-badge <?php echo $plato['activo'] ? 'activo' : 'inactivo'; ?>">
                                        <?php echo $plato['activo'] ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn-editar btn-editar-plato"
                                        data-id="<?php echo $plato['id_plato']; ?>"
                                        data-nombre="<?php echo htmlspecialchars($plato['nombre']); ?>"
                                        data-descripcion="<?php echo htmlspecialchars($plato['descripcion'] ?? ''); ?>"
                                        data-precio="<?php echo $plato['precio']; ?>"
                                        data-activo="<?php echo $plato['activo']; ?>">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button class="btn-eliminar btn-eliminar-plato"
                                        data-id="<?php echo $plato['id_plato']; ?>">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </section>
</main>

<!--  MODAL NUEVO PLATO  -->
<div class="modal-overlay" id="overlayNuevoPlato"></div>
<div class="modal-editar" id="modalNuevoPlato">
    <button class="modal-cerrar" id="cerrarNuevoPlato">&times;</button>
    <h2 class="modal-title"><i class="fa-solid fa-utensils"></i> Nuevo Plato</h2>
    <form class="modal-form" id="formNuevoPlato">
        <div class="modal-group">
            <label for="np-nombre">Nombre:</label>
            <input type="text" id="np-nombre" name="nombre" required placeholder="Ej. Lomo saltado">
        </div>
        <div class="modal-group">
            <label for="np-descripcion">Descripción:</label>
            <input type="text" id="np-descripcion" name="descripcion" placeholder="Descripción breve">
        </div>
        <div class="modal-group">
            <label for="np-precio">Precio (S/):</label>
            <input type="number" id="np-precio" name="precio" step="0.01" min="0" value="8.00" required>
        </div>
        <div class="modal-group">
            <label for="np-activo">Estado:</label>
            <select id="np-activo" name="activo" required>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn-guardar">Registrar Plato</button>
    </form>
</div>

<!--  MODAL EDITAR PLATO  -->
<div class="modal-overlay" id="overlayEditarPlato"></div>
<div class="modal-editar" id="modalEditarPlato">
    <button class="modal-cerrar" id="cerrarEditarPlato">&times;</button>
    <h2 class="modal-title"><i class="fa-solid fa-pen-to-square"></i> Editar Plato</h2>
    <form class="modal-form" id="formEditarPlato">
        <input type="hidden" id="ep-id" name="id_plato">
        <div class="modal-group">
            <label for="ep-nombre">Nombre:</label>
            <input type="text" id="ep-nombre" name="nombre" required>
        </div>
        <div class="modal-group">
            <label for="ep-descripcion">Descripción:</label>
            <input type="text" id="ep-descripcion" name="descripcion">
        </div>
        <div class="modal-group">
            <label for="ep-precio">Precio (S/):</label>
            <input type="number" id="ep-precio" name="precio" step="0.01" min="0" required>
        </div>
        <div class="modal-group">
            <label for="ep-activo">Estado:</label>
            <select id="ep-activo" name="activo" required>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn-guardar">Guardar Cambios</button>
    </form>
</div>

<!-- ===== MODAL NUEVA ENTRADA ===== -->
<div class="modal-overlay" id="overlayNuevaEntrada"></div>
<div class="modal-editar" id="modalNuevaEntrada">
    <button class="modal-cerrar" id="cerrarNuevaEntrada">&times;</button>
    <h2 class="modal-title"><i class="fa-solid fa-bowl-food"></i> Nueva Entrada</h2>
    <form class="modal-form" id="formNuevaEntrada">
        <div class="modal-group">
            <label for="ne-nombre">Nombre:</label>
            <input type="text" id="ne-nombre" name="nombre" required placeholder="Ej. Causa limeña">
        </div>
        <div class="modal-group">
            <label for="ne-precio">Precio extra (S/):</label>
            <input type="number" id="ne-precio" name="precio" step="0.01" min="0" value="3.00" required>
        </div>
        <div class="modal-group">
            <label for="ne-activo">Estado:</label>
            <select id="ne-activo" name="activo" required>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn-guardar">Registrar Entrada</button>
    </form>
</div>

<!--  MODAL EDITAR ENTRADA  -->
<div class="modal-overlay" id="overlayEditarEntrada"></div>
<div class="modal-editar" id="modalEditarEntrada">
    <button class="modal-cerrar" id="cerrarEditarEntrada">&times;</button>
    <h2 class="modal-title"><i class="fa-solid fa-pen-to-square"></i> Editar Entrada</h2>
    <form class="modal-form" id="formEditarEntrada">
        <input type="hidden" id="ee-id" name="id_entrada">
        <div class="modal-group">
            <label for="ee-nombre">Nombre:</label>
            <input type="text" id="ee-nombre" name="nombre" required>
        </div>
        <div class="modal-group">
            <label for="ee-precio">Precio extra (S/):</label>
            <input type="number" id="ee-precio" name="precio" step="0.01" min="0" required>
        </div>
        <div class="modal-group">
            <label for="ee-activo">Estado:</label>
            <select id="ee-activo" name="activo" required>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn-guardar">Guardar Cambios</button>
    </form>
</div>

<script>const BASE_URL = "<?php echo BASE_URL; ?>";</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
<script src="<?php echo BASE_URL; ?>/public/js/platos.js"></script>
</body>
</html>