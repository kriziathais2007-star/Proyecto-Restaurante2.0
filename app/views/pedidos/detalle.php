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

    <!-- Cabecera -->
    <div class="pedido-header">
        <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
            <h2 style="margin:0; font-size:clamp(1rem,4vw,1.2rem); color:#5a4048;">
                Pedido #<?php echo $pedido['id_pedido']; ?>
            </h2>
            <span class="badge badge-<?php echo strtolower($pedido['estado']); ?>">
                <?php echo htmlspecialchars($pedido['estado']); ?>
            </span>
            <?php if ($pedido['numero_mesa']): ?>
                <span style="font-size:0.8rem; color:#a98c94;">Mesa <?php echo $pedido['numero_mesa']; ?></span>
            <?php endif; ?>
        </div>
        <a href="<?php echo BASE_URL; ?>/pedidos/<?php echo $pedido['tipo'] === 'Mesa' ? 'croquis' : 'llevar'; ?>"
           class="btn-volver"
           id="btnVolver"
           data-pedido="<?php echo $pedido['id_pedido']; ?>"
           data-estado="<?php echo $pedido['estado']; ?>"
           data-destino="<?php echo BASE_URL; ?>/pedidos/<?php echo $pedido['tipo'] === 'Mesa' ? 'croquis' : 'llevar'; ?>">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="detalle-layout">

        <!-- ══ CARTA ══ -->
        <div class="detalle-menu">

            <?php if (in_array($pedido['estado'], ['Pendiente', 'Preparando'])): ?>

            <!-- PLATOS -->
            <section class="seccion-carta">
                <div class="seccion-header">
                    <div class="seccion-titulo">
                        <i class="fa-solid fa-utensils"></i>
                        <h2>Platos <span class="precio-badge">S/ 8.00 c/u</span></h2>
                    </div>
                </div>

                <div class="carta-lista">
                    <?php if (empty($platos)): ?>
                        <p class="sin-datos">No hay platos activos.</p>
                    <?php endif; ?>
                    <?php foreach ($platos as $plato): ?>
                        <div class="carta-item">
                            <div class="carta-item-info">
                                <span class="carta-item-nombre"><?php echo htmlspecialchars($plato['nombre']); ?></span>
                                <?php if (!empty($plato['descripcion'])): ?>
                                    <span class="carta-item-desc"><?php echo htmlspecialchars($plato['descripcion']); ?></span>
                                <?php endif; ?>
                            </div>
                            <form method="POST"
                                  action="<?php echo BASE_URL; ?>/pedidos/agregarPlato/<?php echo $pedido['id_pedido']; ?>"
                                  class="carta-item-form">
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
                                <button type="submit" class="btn-agregar" title="Agregar">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- ENTRADAS SUELTAS -->
            <section class="seccion-carta">
                <div class="seccion-header">
                    <div class="seccion-titulo">
                        <i class="fa-solid fa-bowl-food"></i>
                        <h2>Entrada suelta <span class="precio-badge">S/ 3.00 c/u</span></h2>
                    </div>
                </div>

                <div class="carta-lista">
                    <?php if (empty($entradas)): ?>
                        <p class="sin-datos">No hay entradas activas.</p>
                    <?php endif; ?>
                    <?php foreach ($entradas as $entrada): ?>
                        <div class="carta-item">
                            <div class="carta-item-info">
                                <span class="carta-item-nombre"><?php echo htmlspecialchars($entrada['nombre']); ?></span>
                                <span class="carta-item-precio">S/ <?php echo number_format($entrada['precio'], 2); ?></span>
                            </div>
                            <form method="POST"
                                  action="<?php echo BASE_URL; ?>/pedidos/agregarExtra/<?php echo $pedido['id_pedido']; ?>"
                                  class="carta-item-form">
                                <input type="hidden" name="id_entrada" value="<?php echo $entrada['id_entrada']; ?>">
                                <input type="number" name="cantidad" value="1" min="1" class="input-cantidad">
                                <button type="submit" class="btn-agregar" title="Agregar">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <?php else: ?>
                <div class="seccion-carta">
                    <p style="color:#a98c94; font-style:italic; padding:1rem;">
                        El pedido está en estado <strong><?php echo $pedido['estado']; ?></strong> — no se pueden agregar más ítems.
                    </p>
                </div>
            <?php endif; ?>

        </div>

        <!-- ══ RESUMEN ══ -->
        <aside class="detalle-resumen">
            <h3><i class="fa-solid fa-receipt"></i> Resumen</h3>

            <ul class="resumen-lista">
                <?php foreach ($items as $item): ?>
                    <li>
                        <span class="item-nombre">
                            <?php echo $item['cantidad']; ?>x <?php echo htmlspecialchars($item['nombre_plato'] ?? 'Plato'); ?>
                            <small><?php echo $item['nombre_entrada_incluida'] ? '+ '.htmlspecialchars($item['nombre_entrada_incluida']) : 'sin entrada'; ?></small>
                        </span>
                        <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;">
                            <span class="item-subtotal">S/ <?php echo number_format($item['subtotal'], 2); ?></span>
                            <?php if (in_array($pedido['estado'], ['Pendiente','Preparando'])): ?>
                                <button type="button" class="btn-eliminar-item"
                                        data-id="<?php echo $item['id_detalle']; ?>"
                                        data-tipo="plato" title="Quitar">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
                <?php foreach ($extras as $extra): ?>
                    <li>
                        <span class="item-nombre">
                            <?php echo $extra['cantidad']; ?>x <?php echo htmlspecialchars($extra['nombre_entrada'] ?? 'Entrada'); ?>
                            <small>entrada suelta</small>
                        </span>
                        <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;">
                            <span class="item-subtotal">S/ <?php echo number_format($extra['subtotal'], 2); ?></span>
                            <?php if (in_array($pedido['estado'], ['Pendiente','Preparando'])): ?>
                                <button type="button" class="btn-eliminar-item"
                                        data-id="<?php echo $extra['id_detalle_extra']; ?>"
                                        data-tipo="extra" title="Quitar">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
                <?php if (empty($items) && empty($extras)): ?>
                    <li class="resumen-vacio">Sin ítems aún.</li>
                <?php endif; ?>
            </ul>

            <div class="resumen-total">
                <span>Total</span>
                <span>S/ <?php echo number_format((float)$pedido['total'], 2); ?></span>
            </div>

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
                        <i class="fa-solid fa-utensils"></i> Marcar Entregado
                    </button>
                </form>
            <?php elseif ($pedido['estado'] === 'Entregado'): ?>
                <p style="color:#946c00; font-weight:600; font-size:0.85rem; margin-top:1rem;">
                    <i class="fa-solid fa-clock"></i> Esperando pago...
                </p>
                <a href="<?php echo BASE_URL; ?>/pedidos/<?php echo $pedido['tipo'] === 'Mesa' ? 'croquis' : 'llevar'; ?>"
                   class="btn-realizar-pedido btn-pagar" style="text-decoration:none; text-align:center; margin-top:0.5rem;">
                    <i class="fa-solid fa-money-bill"></i> Ir a cobrar
                </a>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const BASE_URL = "<?php echo BASE_URL; ?>";

// ── Volver: eliminar si vacío ────────────────────────
document.getElementById('btnVolver').addEventListener('click', function (e) {
    const estado   = this.dataset.estado;
    const idPedido = this.dataset.pedido;
    const destino  = this.dataset.destino;
    if (estado !== 'Pendiente') return;
    e.preventDefault();
    fetch(BASE_URL + '/pedidos/cancelarSiVacio/' + idPedido, {
        method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).finally(() => { window.location.href = destino; });
});

// ── Eliminar ítem del resumen ────────────────────────
document.querySelectorAll('.btn-eliminar-item').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const id   = this.dataset.id;
        const tipo = this.dataset.tipo;
        const ruta = tipo === 'plato'
            ? BASE_URL + '/pedidos/eliminarItem/' + id
            : BASE_URL + '/pedidos/eliminarExtra/' + id;

        Swal.fire({
            title: '¿Quitar ítem?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, quitar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#388e3c',
            background: '#f1f8e9',
            color: '#1b5e20'
        }).then(result => {
            if (!result.isConfirmed) return;
            fetch(ruta, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) location.reload();
                else Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo quitar el ítem.', confirmButtonColor: '#388e3c' });
            });
        });
    });
});
</script>
</body>
</html>
