<!DOCTYPE html>
<html lang="Es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Panel de Administración</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/platos.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/pedidos.css">
</head>
<body>

<?php include __DIR__ . '/../layouts/sidebar-dashboard.php'; ?>

<main>
    <nav class="breadcrumb">
        <span>Inicio</span>
        <i class="fa-solid fa-chevron-right"></i>
        <span>Pedidos</span>
        <i class="fa-solid fa-chevron-right"></i>
        <span id="breadcrumb-page">Pedido #<?php echo $pedido['id_pedido']; ?></span>
    </nav>

    <div class="main-content">

        <div class="pedido-header">
            <h2>
                Pedido #<?php echo $pedido['id_pedido']; ?>
                — <?php echo htmlspecialchars($pedido['tipo']); ?>
                <?php if ($pedido['numero_mesa']): ?>
                    (Mesa <?php echo $pedido['numero_mesa']; ?>)
                <?php endif; ?>
            </h2>
            <span class="badge badge-<?php echo strtolower($pedido['estado']); ?>">
                <?php echo htmlspecialchars($pedido['estado']); ?>
            </span>
        </div>

        <div class="detalle-layout">

            <!-- MENÚ -->
            <div class="detalle-menu">

                <!-- PLATOS -->
                <section class="seccion-carta">
                    <div class="seccion-header">
                        <div class="seccion-titulo">
                            <i class="fa-solid fa-utensils"></i>
                            <h2>Platos <span class="precio-badge">S/ 8.00</span></h2>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Precio</th>
                                    <th>Agregar al pedido</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($platos)): ?>
                                    <tr><td colspan="4" class="empty-msg">No hay platos activos.</td></tr>
                                <?php endif; ?>
                                <?php foreach ($platos as $plato): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($plato['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($plato['descripcion'] ?? '—'); ?></td>
                                        <td class="precio">S/ <?php echo number_format($plato['precio'], 2); ?></td>
                                        <td>
                                            <form method="POST" action="<?php echo BASE_URL; ?>/pedidos/agregarPlato/<?php echo $pedido['id_pedido']; ?>" class="form-agregar">
                                                <input type="hidden" name="id_plato" value="<?php echo $plato['id_plato']; ?>">
                                                <select name="id_entrada" class="select-entrada">
                                                    <option value="">Sin entrada</option>
                                                    <?php foreach ($entradas as $entrada): ?>
                                                        <option value="<?php echo $entrada['id_entrada']; ?>">
                                                            <?php echo htmlspecialchars($entrada['nombre']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <input type="number" name="cantidad" value="1" min="1" class="input-cantidad">
                                                <button type="submit" class="btn-agregar">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- ENTRADAS ADICIONALES -->
                <section class="seccion-carta">
                    <div class="seccion-header">
                        <div class="seccion-titulo">
                            <i class="fa-solid fa-bowl-food"></i>
                            <h2>Entradas adicionales <span class="precio-badge">S/ 3.00</span></h2>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Precio extra</th>
                                    <th>Agregar al pedido</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($entradas)): ?>
                                    <tr><td colspan="3" class="empty-msg">No hay entradas activas.</td></tr>
                                <?php endif; ?>
                                <?php foreach ($entradas as $entrada): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($entrada['nombre']); ?></td>
                                        <td class="precio">S/ <?php echo number_format($entrada['precio'], 2); ?></td>
                                        <td>
                                            <form method="POST" action="<?php echo BASE_URL; ?>/pedidos/agregarExtra/<?php echo $pedido['id_pedido']; ?>" class="form-agregar">
                                                <input type="hidden" name="id_entrada" value="<?php echo $entrada['id_entrada']; ?>">
                                                <input type="number" name="cantidad" value="1" min="1" class="input-cantidad">
                                                <button type="submit" class="btn-agregar">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

            </div>

            <!-- RESUMEN DEL PEDIDO -->
            <aside class="detalle-resumen">
                <h3><i class="fa-solid fa-receipt"></i> Resumen del pedido</h3>

                <ul class="resumen-lista">
                    <?php foreach ($items as $item): ?>
                        <li>
                            <span class="item-nombre">
                                <?php echo $item['cantidad']; ?>x <?php echo htmlspecialchars($item['nombre_plato'] ?? 'Plato eliminado'); ?>
                                <small>
                                    <?php echo $item['nombre_entrada_incluida']
                                        ? 'con ' . htmlspecialchars($item['nombre_entrada_incluida'])
                                        : 'sin entrada'; ?>
                                </small>
                            </span>
                            <span class="item-subtotal">S/ <?php echo number_format($item['subtotal'], 2); ?></span>
                        </li>
                    <?php endforeach; ?>

                    <?php foreach ($extras as $extra): ?>
                        <li>
                            <span class="item-nombre">
                                <?php echo $extra['cantidad']; ?>x <?php echo htmlspecialchars($extra['nombre_entrada'] ?? 'Entrada eliminada'); ?>
                                <small>entrada extra</small>
                            </span>
                            <span class="item-subtotal">S/ <?php echo number_format($extra['subtotal'], 2); ?></span>
                        </li>
                    <?php endforeach; ?>

                    <?php if (empty($items) && empty($extras)): ?>
                        <li class="resumen-vacio">Aún no se han agregado items.</li>
                    <?php endif; ?>
                </ul>

                <div class="resumen-total">
    <span>Total</span>
    <span>S/ <?php echo number_format((float) $pedido['total'], 2); ?></span>
</div>

<!-- BOTÓN DE ACCIÓN SEGÚN ESTADO -->
<?php if ($pedido['estado'] === 'Pendiente'): ?>
    <form method="POST" action="<?php echo BASE_URL; ?>/pedidos/cambiarEstado/<?php echo $pedido['id_pedido']; ?>">
        <input type="hidden" name="estado" value="Preparando">
        <button type="submit" class="btn-realizar-pedido" <?php echo (empty($items) && empty($extras)) ? 'disabled' : ''; ?>>
            <i class="fa-solid fa-check"></i> Realizar Pedido
        </button>
    </form>

<?php elseif ($pedido['estado'] === 'Preparando'): ?>
    <form method="POST" action="<?php echo BASE_URL; ?>/pedidos/cambiarEstado/<?php echo $pedido['id_pedido']; ?>">
        <input type="hidden" name="estado" value="Entregado">
        <button type="submit" class="btn-realizar-pedido">
            <i class="fa-solid fa-utensils"></i> Marcar como Entregado
        </button>
    </form>

<?php elseif ($pedido['estado'] === 'Entregado'): ?>
    <form method="POST" action="<?php echo BASE_URL; ?>/pedidos/cambiarEstado/<?php echo $pedido['id_pedido']; ?>">
        <input type="hidden" name="estado" value="Pagado">
        <button type="submit" class="btn-realizar-pedido btn-pagar">
            <i class="fa-solid fa-money-bill"></i> Marcar como Pagado
        </button>
    </form>

<?php elseif ($pedido['estado'] === 'Pagado'): ?>
    <p class="pedido-finalizado"><i class="fa-solid fa-circle-check"></i> Pedido pagado</p>
<?php endif; ?>
            </aside>

        </div>

    </div>
</main>
<script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
</body>
</html>