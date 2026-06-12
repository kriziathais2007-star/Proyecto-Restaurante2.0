<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Pagos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/platos.css">
</head>
<body>

<?php include __DIR__ . '/../layouts/sidebar-dashboard.php'; ?>

<main>
    <header>
        <h1>Pagos</h1>
        <button class="btn-registrar" id="btnAbrirPago">
            <i class="fa-solid fa-plus"></i> Registrar Pago
        </button>
    </header>

    <!-- ===================== RESUMEN DEL DÍA ===================== -->
    <div class="resumen-cards">
        <div class="resumen-card">
            <span class="resumen-label">Pagos hoy</span>
            <span class="resumen-valor"><?php echo $resumen['total_pagos'] ?? 0; ?></span>
        </div>
        <div class="resumen-card">
            <span class="resumen-label">Efectivo</span>
            <span class="resumen-valor"><?php echo $resumen['pagos_efectivo'] ?? 0; ?></span>
        </div>
        <div class="resumen-card">
            <span class="resumen-label">Yape</span>
            <span class="resumen-valor"><?php echo $resumen['pagos_yape'] ?? 0; ?></span>
        </div>
        <div class="resumen-card resumen-total">
            <span class="resumen-label">Recaudado hoy</span>
            <span class="resumen-valor">S/ <?php echo number_format($resumen['total_recaudado'] ?? 0, 2); ?></span>
        </div>
    </div>

    <!-- ===================== TABLA PAGOS ===================== -->
    <section class="seccion-carta">
        <div class="seccion-header">
            <div class="seccion-titulo">
                <i class="fa-solid fa-money-bill-wave"></i>
                <h2>Historial de pagos</h2>
            </div>
        </div>

        <div class="table-responsive">
            <?php if (empty($pagos)): ?>
                <p class="empty-msg">No hay pagos registrados.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pedido</th>
                            <th>Tipo</th>
                            <th>Mesa</th>
                            <th>Monto</th>
                            <th>Método</th>
                            <th>Cajero</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pagos as $pago): ?>
                            <tr>
                                <td><?php echo $pago['id_pago']; ?></td>
                                <td>#<?php echo $pago['id_pedido']; ?></td>
                                <td>
                                    <span class="tipo-badge <?php echo strtolower($pago['tipo']); ?>">
                                        <?php echo htmlspecialchars($pago['tipo']); ?>
                                    </span>
                                </td>
                                <td><?php echo $pago['numero_mesa'] ?? '—'; ?></td>
                                <td class="precio">S/ <?php echo number_format($pago['monto'], 2); ?></td>
                                <td>
                                    <span class="metodo-badge <?php echo strtolower($pago['metodo_pago']); ?>">
                                        <?php if ($pago['metodo_pago'] === 'Yape'): ?>
                                            <i class="fa-solid fa-mobile-screen-button"></i>
                                        <?php else: ?>
                                            <i class="fa-solid fa-money-bill"></i>
                                        <?php endif; ?>
                                        <?php echo htmlspecialchars($pago['metodo_pago']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($pago['nombre_cajero'] ?? '—'); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($pago['fecha_pago'])); ?></td>
                                <td>
                                    <button class="btn-eliminar btn-eliminar-pago"
                                        data-id="<?php echo $pago['id_pago']; ?>">
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

<!-- ===== MODAL NUEVO PAGO ===== -->
<div class="modal-overlay" id="overlayNuevoPago"></div>
<div class="modal-editar" id="modalNuevoPago">
    <button class="modal-cerrar" id="cerrarNuevoPago">&times;</button>
    <h2 class="modal-title"><i class="fa-solid fa-money-bill-wave"></i> Registrar Pago</h2>
    <form class="modal-form" id="formNuevoPago" enctype="multipart/form-data">
        <div class="modal-group">
            <label for="np-pedido">Pedido:</label>
            <select id="np-pedido" name="id_pedido" required>
                <option value="">Seleccione un pedido</option>
                <?php foreach ($pedidos as $pedido): ?>
                    <option value="<?php echo $pedido['id_pedido']; ?>">
                        #<?php echo $pedido['id_pedido']; ?> —
                        <?php echo $pedido['tipo']; ?>
                        <?php echo $pedido['tipo'] === 'Mesa' ? '(Mesa ' . $pedido['numero_mesa'] . ')' : ''; ?>
                        — S/ <?php echo number_format($pedido['total'], 2); ?>
                        — <?php echo $pedido['estado']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="modal-group">
            <label for="np-monto">Monto (S/):</label>
            <input type="number" id="np-monto" name="monto" step="0.01" min="0" required placeholder="0.00">
        </div>
        <div class="modal-group">
            <label for="np-metodo">Método de pago:</label>
            <select id="np-metodo" name="metodo_pago" required>
                <option value="">Seleccione método</option>
                <option value="Efectivo">Efectivo</option>
                <option value="Yape">Yape</option>
            </select>
        </div>
        <div class="modal-group" id="grupoFotoYape" style="display:none;">
            <label for="np-foto">Foto del comprobante:</label>
            <input type="file"
                id="np-foto"
                name="foto_yape"
                accept="image/*"
              capture="environment">
        </div>
        <button type="submit" class="btn-guardar">Registrar Pago</button>
    </form>
</div>

<script>const BASE_URL = "<?php echo BASE_URL; ?>";</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
<script src="<?php echo BASE_URL; ?>/public/js/pagos.js"></script>
</body>
</html>