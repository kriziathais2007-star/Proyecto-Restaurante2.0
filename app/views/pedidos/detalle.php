<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Pedido #<?php echo $pedido['id_pedido']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/pedidos.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/platos.css">
</head>
<body>

<?php include __DIR__ . '/../layouts/sidebar-dashboard.php'; ?>

<main>
    <div class="main-content">

        <!-- Cabecera del pedido -->
        <div class="pedido-header">
            <h2>
                Pedido #<?php echo $pedido['id_pedido']; ?>
                <span class="badge badge-<?php echo strtolower($pedido['estado']); ?>">
                    <?php echo htmlspecialchars($pedido['estado']); ?>
                </span>
            </h2>
            <a href="<?php echo BASE_URL; ?>/pedidos/<?php echo $pedido['tipo'] === 'Mesa' ? 'croquis' : 'llevar'; ?>"
               class="btn-volver">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>

        <div class="detalle-layout">

            <!-- ══ CARTA (izquierda) ══════════════════════════ -->
            <div class="detalle-menu">

                <?php if (in_array($pedido['estado'], ['Pendiente', 'Preparando'])): ?>

                <!-- PLATOS (combo con entrada incluida a S/ 8.00) -->
                <section class="seccion-carta">
                    <div class="seccion-header">
                        <div class="seccion-titulo">
                            <i class="fa-solid fa-utensils"></i>
                            <h2>Platos <span class="precio-badge">S/ 8.00 c/u (incluye entrada)</span></h2>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Entrada incluida</th>
                                    <th>Cant.</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($platos)): ?>
                                    <tr><td colspan="5" class="sin-datos">No hay platos activos.</td></tr>
                                <?php endif; ?>
                                <?php foreach ($platos as $plato): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($plato['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($plato['descripcion'] ?? '—'); ?></td>
                                        <td>
                                            <form method="POST"
                                                  action="<?php echo BASE_URL; ?>/pedidos/agregarPlato/<?php echo $pedido['id_pedido']; ?>"
                                                  class="form-agregar">
                                                <input type="hidden" name="id_plato" value="<?php echo $plato['id_plato']; ?>">
                                                <select name="id_entrada" class="select-entrada">
                                                    <option value="">Sin entrada</option>
                                                    <?php foreach ($entradas as $entrada): ?>
                                                        <option value="<?php echo $entrada['id_entrada']; ?>">
                                                            <?php echo htmlspecialchars($entrada['nombre']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                        </td>
                                        <td>
                                                <input type="number" name="cantidad" value="1" min="1" class="input-cantidad">
                                        </td>
                                        <td>
                                                <button type="submit" class="btn-agregar" title="Agregar">
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

                <!-- ENTRADAS SUELTAS (solo entrada a S/ 3.00) -->
                <section class="seccion-carta">
                    <div class="seccion-header">
                        <div class="seccion-titulo">
                            <i class="fa-solid fa-bowl-food"></i>
                            <h2>Entrada suelta <span class="precio-badge">S/ 3.00 c/u</span></h2>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>Cant.</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($entradas)): ?>
                                    <tr><td colspan="4" class="sin-datos">No hay entradas activas.</td></tr>
                                <?php endif; ?>
                                <?php foreach ($entradas as $entrada): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($entrada['nombre']); ?></td>
                                        <td class="precio">S/ <?php echo number_format($entrada['precio'], 2); ?></td>
                                        <td>
                                            <form method="POST"
                                                  action="<?php echo BASE_URL; ?>/pedidos/agregarExtra/<?php echo $pedido['id_pedido']; ?>"
                                                  class="form-agregar">
                                                <input type="hidden" name="id_entrada" value="<?php echo $entrada['id_entrada']; ?>">
                                                <input type="number" name="cantidad" value="1" min="1" class="input-cantidad">
                                        </td>
                                        <td>
                                                <button type="submit" class="btn-agregar" title="Agregar">
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

                <?php else: ?>
                    <div class="seccion-carta">
                        <p style="color:#a98c94; font-style:italic; padding:1rem 0;">
                            El pedido está en estado <strong><?php echo $pedido['estado']; ?></strong> — no se pueden agregar más items.
                        </p>
                    </div>
                <?php endif; ?>

            </div>

            <!-- ══ RESUMEN (derecha) ══════════════════════════ -->
            <aside class="detalle-resumen">
                <h3><i class="fa-solid fa-receipt"></i> Resumen</h3>

                <ul class="resumen-lista">
                    <?php foreach ($items as $item): ?>
                        <li>
                            <span class="item-nombre">
                                <?php echo $item['cantidad']; ?>x
                                <?php echo htmlspecialchars($item['nombre_plato'] ?? 'Plato'); ?>
                                <small>
                                    <?php echo $item['nombre_entrada_incluida']
                                        ? '+ ' . htmlspecialchars($item['nombre_entrada_incluida'])
                                        : 'sin entrada'; ?>
                                </small>
                            </span>
                            <span class="item-subtotal">S/ <?php echo number_format($item['subtotal'], 2); ?></span>
                        </li>
                    <?php endforeach; ?>

                    <?php foreach ($extras as $extra): ?>
                        <li>
                            <span class="item-nombre">
                                <?php echo $extra['cantidad']; ?>x
                                <?php echo htmlspecialchars($extra['nombre_entrada'] ?? 'Entrada'); ?>
                                <small>entrada suelta</small>
                            </span>
                            <span class="item-subtotal">S/ <?php echo number_format($extra['subtotal'], 2); ?></span>
                        </li>
                    <?php endforeach; ?>

                    <?php if (empty($items) && empty($extras)): ?>
                        <li class="resumen-vacio">Sin items aún.</li>
                    <?php endif; ?>
                </ul>

                <div class="resumen-total">
                    <span>Total</span>
                    <span>S/ <?php echo number_format((float) $pedido['total'], 2); ?></span>
                </div>

                <!-- Acciones según estado -->
                <?php if ($pedido['estado'] === 'Pendiente'): ?>
                    <form method="POST" action="<?php echo BASE_URL; ?>/pedidos/cambiarEstado/<?php echo $pedido['id_pedido']; ?>">
                        <input type="hidden" name="estado" value="Preparando">
                        <button type="submit" class="btn-realizar-pedido"
                            <?php echo (empty($items) && empty($extras)) ? 'disabled' : ''; ?>>
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
                    <p style="color:#946c00; font-weight:600; font-size:0.9rem; margin-top:1rem;">
                        <i class="fa-solid fa-clock"></i> Esperando pago...
                    </p>
                    <?php if ($pedido['tipo'] === 'Mesa'): ?>
                        <a href="<?php echo BASE_URL; ?>/pedidos/croquis" class="btn-realizar-pedido btn-pagar" style="text-align:center; text-decoration:none;">
                            <i class="fa-solid fa-money-bill"></i> Ir a Mesas para cobrar
                        </a>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>/pedidos/llevar" class="btn-realizar-pedido btn-pagar" style="text-align:center; text-decoration:none;">
                            <i class="fa-solid fa-money-bill"></i> Ir a Llevar para cobrar
                        </a>
                    <?php endif; ?>

                <?php elseif ($pedido['estado'] === 'Pagado'): ?>
                    <p class="pedido-finalizado">
                        <i class="fa-solid fa-circle-check"></i> Pedido pagado
                    </p>
                <?php endif; ?>

            </aside>

        </div>

    </div>
</main>

<script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
</body>
</html>
