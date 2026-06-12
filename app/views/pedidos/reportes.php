<!DOCTYPE html>
<html lang="Es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Panel de Administración</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/pedidos.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/dashboard.css">
</head>

<body>

<?php include __DIR__ . '/../layouts/sidebar-dashboard.php'; ?>

<!-- CONTENIDO PRINCIPAL -->
<main>
    <div class="main-content">

    <!-- RESUMEN -->
        <div class="resumen-cards">
            <div class="card-resumen">
                <i class="fa-solid fa-receipt"></i>
                <div>
                    <span class="valor"><?php echo (int) $resumen['total_pedidos']; ?></span>
                    <span class="etiqueta">Pedidos</span>
                </div>
            </div>

            <div class="card-resumen">
                <i class="fa-solid fa-sack-dollar"></i>
                <div>
                    <span class="valor">S/ <?php echo number_format((float) $resumen['total_ventas'], 2); ?></span>
                    <span class="etiqueta">Total Vendido</span>
                </div>
            </div>

            <div class="card-resumen">
                <i class="fa-solid fa-ban"></i>
                <div>
                    <span class="valor"><?php echo (int) $resumen['total_cancelados']; ?></span>
                    <span class="etiqueta">Cancelados</span>
                </div>
            </div>
        </div>

        <!-- BOTÓN PARA ABRIR EL MODAL DE FILTROS -->
<div class="filtros-toggle-wrapper">
    <button type="button" id="btnToggleFiltros" class="btn-toggle-filtros">
        <i class="fa-solid fa-filter"></i> Filtros
    </button>
</div>


<!-- MODAL DE FILTROS -->
<div class="modal-overlay" id="modalFiltros">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fa-solid fa-sliders"></i> Filtros de búsqueda</h3>
            <button type="button" id="btnCerrarFiltros" class="btn-cerrar-modal">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form method="GET" action="<?php echo BASE_URL; ?>/pedidos/reportes" class="filtros-reporte">
            <div class="filtro-grupo">
                <label for="fecha_inicio">Desde</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio"
                       value="<?php echo htmlspecialchars($filtros['fecha_inicio']); ?>">
            </div>

            <div class="filtro-grupo">
                <label for="fecha_fin">Hasta</label>
                <input type="date" id="fecha_fin" name="fecha_fin"
                       value="<?php echo htmlspecialchars($filtros['fecha_fin']); ?>">
            </div>

            <div class="filtro-grupo">
                <label for="tipo">Tipo</label>
                <select id="tipo" name="tipo">
                    <option value="">Todos</option>
                    <option value="Mesa" <?php echo $filtros['tipo'] === 'Mesa' ? 'selected' : ''; ?>>Mesa</option>
                    <option value="Llevar" <?php echo $filtros['tipo'] === 'Llevar' ? 'selected' : ''; ?>>Llevar</option>
                </select>
            </div>

            <div class="filtro-grupo">
                <label for="estado">Estado</label>
                <select id="estado" name="estado">
                    <option value="">Todos</option>
                    <option value="Pendiente" <?php echo $filtros['estado'] === 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                    <option value="Preparando" <?php echo $filtros['estado'] === 'Preparando' ? 'selected' : ''; ?>>Preparando</option>
                    <option value="Entregado" <?php echo $filtros['estado'] === 'Entregado' ? 'selected' : ''; ?>>Entregado</option>
                    <option value="Pagado" <?php echo $filtros['estado'] === 'Pagado' ? 'selected' : ''; ?>>Pagado</option>
                    <option value="Cancelado" <?php echo $filtros['estado'] === 'Cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                </select>
            </div>

            <div class="filtro-grupo filtro-acciones">
                <button type="submit" class="btn-filtrar">
                    <i class="fa-solid fa-magnifying-glass"></i> Aplicar
                </button>
            </div>
        </form>
    </div>
</div>
        
        <!-- TABLA -->
        <div class="table-responsive">
            <table class="tabla-datos">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Mesa</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pedidos)): ?>
                        <tr>
                            <td colspan="7" class="sin-datos">No se encontraron pedidos con los filtros seleccionados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td>#<?php echo $pedido['id_pedido']; ?></td>
                                <td><?php echo htmlspecialchars($pedido['tipo']); ?></td>
                                <td><?php echo $pedido['numero_mesa'] ?? '-'; ?></td>
                                <td><?php echo htmlspecialchars($pedido['nombre_usuario'] ?? 'N/A'); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($pedido['fecha'])); ?></td>
                                <td>S/ <?php echo number_format((float) $pedido['total'], 2); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo strtolower($pedido['estado']); ?>">
                                        <?php echo htmlspecialchars($pedido['estado']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</main>
<script src="<?php echo BASE_URL; ?>/public/js/panel.js"></script>
<script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
</body>

</html>