<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Mesas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/pedidos.css">
</head>
<body>

<?php include __DIR__ . '/../layouts/sidebar-dashboard.php'; ?>

<main>
    <div class="main-content">

        <!-- Leyenda -->
        <div class="croquis-legend">
            <div class="legend-item">
                <span class="legend-color legend-libre"></span> Libre
            </div>
            <div class="legend-item">
                <span class="legend-color legend-ocupada"></span> Ocupada
            </div>
            <div class="legend-item">
                <span class="legend-color legend-urgente"></span> Pendiente de pago
            </div>
        </div>

        <!-- Grid de mesas -->
        <div class="croquis-grid">
            <?php foreach ($mesas as $mesa): ?>
                <?php if ($mesa['ocupada']): ?>

                    <?php if ($mesa['estado'] === 'Entregado'): ?>
                        <!-- Rojo urgente: pendiente de pago → abre modal acciones -->
                        <div class="mesa-card ocupada urgente"
                             onclick="abrirModalAcciones(<?php echo $mesa['id_pedido']; ?>, <?php echo $mesa['numero_mesa']; ?>, <?php echo $mesa['total']; ?>)">
                            <i class="fa-solid fa-chair"></i>
                            <span>Mesa <?php echo $mesa['numero_mesa']; ?></span>
                            <span class="mesa-estado-badge"><?php echo htmlspecialchars($mesa['estado']); ?></span>
                        </div>
                    <?php else: ?>
                        <!-- Ocupada normal: ir a detalle -->
                        <a href="<?php echo BASE_URL; ?>/pedidos/detalle/<?php echo $mesa['id_pedido']; ?>"
                           class="mesa-card ocupada">
                            <i class="fa-solid fa-chair"></i>
                            <span>Mesa <?php echo $mesa['numero_mesa']; ?></span>
                            <span class="mesa-estado-badge"><?php echo htmlspecialchars($mesa['estado']); ?></span>
                        </a>
                    <?php endif; ?>

                <?php else: ?>
                    <!-- Libre: crear pedido -->
                    <a href="<?php echo BASE_URL; ?>/pedidos/crear/Mesa/<?php echo $mesa['numero_mesa']; ?>"
                       class="mesa-card libre">
                        <i class="fa-solid fa-chair"></i>
                        <span>Mesa <?php echo $mesa['numero_mesa']; ?></span>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

    </div>
</main>

<!-- ═══════════════════════════════════════════════════════
     MODAL: Acciones sobre mesa ocupada (Editar / Pagar)
════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modalAcciones">
    <div class="modal-content" style="max-width:380px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-chair"></i> Mesa <span id="modalNumMesa"></span> — Pedido #<span id="modalIdPedido"></span></h3>
            <button type="button" class="btn-cerrar-modal" onclick="cerrarModal('modalAcciones')">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-acciones-body">
            <a href="#" id="linkEditarPedido" class="modal-accion-btn modal-accion-editar">
                <div class="modal-accion-icon"><i class="fa-solid fa-pen-to-square"></i></div>
                <div class="modal-accion-info">
                    <span class="modal-accion-titulo">Editar pedido</span>
                    <span class="modal-accion-desc">Agregar o quitar items</span>
                </div>
                <i class="fa-solid fa-chevron-right modal-accion-arrow"></i>
            </a>
            <button type="button" class="modal-accion-btn modal-accion-pagar" onclick="abrirModalPago()">
                <div class="modal-accion-icon"><i class="fa-solid fa-receipt"></i></div>
                <div class="modal-accion-info">
                    <span class="modal-accion-titulo">Registrar pago</span>
                    <span class="modal-accion-desc">Efectivo o Yape</span>
                </div>
                <i class="fa-solid fa-chevron-right modal-accion-arrow"></i>
            </button>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     MODAL: Formulario de pago
════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modalPago">
    <div class="modal-content" style="max-width:420px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-receipt"></i> Pagar — Mesa <span id="pagoNumMesa"></span></h3>
            <button type="button" class="btn-cerrar-modal" onclick="cerrarModal('modalPago')">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body modal-mesa-body">

            <p style="color:#5a4048; font-size:0.95rem; margin:0;">
                Monto a cobrar: <strong>S/ <span id="pagoMonto"></span></strong>
            </p>

            <!-- Selector de método -->
            <div class="pago-metodo-selector">
                <label>Método de pago:</label>
                <div class="metodo-opciones">
                    <button type="button" class="btn-metodo activo" data-metodo="Efectivo">
                        <i class="fa-solid fa-money-bill-wave"></i> Efectivo
                    </button>
                    <button type="button" class="btn-metodo" data-metodo="Yape">
                        <i class="fa-solid fa-mobile-screen"></i> Yape
                    </button>
                </div>
            </div>

            <!-- Foto Yape (solo visible si método = Yape) -->
            <div id="yapeWrapper" style="display:none;">
                <label style="font-size:0.85rem; font-weight:600; color:#8a6a73;">
                    <i class="fa-solid fa-camera"></i> Foto del comprobante:
                </label>
                <input type="file" id="fotoYape" accept="image/*" capture="environment"
                       style="margin-top:6px; width:100%;">
            </div>

            <button type="button" id="btnConfirmarPago" class="btn-modal-accion btn-pagar">
                <i class="fa-solid fa-check"></i> Confirmar Pago
            </button>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const BASE_URL          = "<?php echo BASE_URL; ?>";
let pedidoActivo        = null;
let mesaActiva          = null;
let montoActivo         = null;
let metodoSeleccionado  = 'Efectivo';

// ── Abrir modal de acciones ──────────────────────────
function abrirModalAcciones(idPedido, numMesa, monto) {
    pedidoActivo = idPedido;
    mesaActiva   = numMesa;
    montoActivo  = monto;

    document.getElementById('modalNumMesa').textContent  = numMesa;
    document.getElementById('modalIdPedido').textContent = idPedido;
    document.getElementById('linkEditarPedido').href     = BASE_URL + '/pedidos/detalle/' + idPedido;

    abrirModal('modalAcciones');
}

// ── Abrir modal de pago ──────────────────────────────
function abrirModalPago() {
    cerrarModal('modalAcciones');
    document.getElementById('pagoNumMesa').textContent = mesaActiva;
    document.getElementById('pagoMonto').textContent   = parseFloat(montoActivo).toFixed(2);
    setMetodo('Efectivo');
    document.getElementById('fotoYape').value = '';
    abrirModal('modalPago');
}

// ── Helpers abrir / cerrar ───────────────────────────
function abrirModal(id) {
    document.getElementById(id).style.display = 'flex';
}
function cerrarModal(id) {
    document.getElementById(id).style.display = 'none';
}

// Cerrar al hacer clic en el overlay
document.querySelectorAll('.modal-overlay').forEach(function (overlay) {
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) overlay.style.display = 'none';
    });
});

// ── Selector de método ───────────────────────────────
function setMetodo(metodo) {
    metodoSeleccionado = metodo;
    document.querySelectorAll('.btn-metodo').forEach(b => b.classList.remove('activo'));
    document.querySelector('.btn-metodo[data-metodo="' + metodo + '"]').classList.add('activo');
    document.getElementById('yapeWrapper').style.display = metodo === 'Yape' ? 'block' : 'none';
}

document.querySelectorAll('.btn-metodo').forEach(function (btn) {
    btn.addEventListener('click', () => setMetodo(btn.dataset.metodo));
});

// ── Confirmar pago ───────────────────────────────────
document.getElementById('btnConfirmarPago').addEventListener('click', function () {
    if (!pedidoActivo) return;

    if (metodoSeleccionado === 'Yape') {
        const foto = document.getElementById('fotoYape').files[0];
        if (!foto) {
            Swal.fire({
                icon: 'warning',
                title: 'Falta el comprobante',
                text: 'Por favor adjunta la foto del comprobante Yape.',
                confirmButtonColor: '#f3a8c2',
                background: '#fff5f8',
                color: '#6b3b4f'
            });
            return;
        }
    }

    const btn = this;
    btn.disabled = true;

    const formData = new FormData();
    formData.append('metodo', metodoSeleccionado);
    if (metodoSeleccionado === 'Yape') {
        formData.append('foto_yape', document.getElementById('fotoYape').files[0]);
    }

    fetch(BASE_URL + '/pedidos/pagar/' + pedidoActivo, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Pago registrado!',
                text: 'El pago se registró correctamente.',
                confirmButtonColor: '#f3a8c2',
                background: '#fff5f8',
                color: '#6b3b4f'
            }).then(() => location.reload());
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'No se pudo registrar el pago.',
                confirmButtonColor: '#f3a8c2',
                background: '#fff5f8',
                color: '#6b3b4f'
            });
            btn.disabled = false;
        }
    })
    .catch(() => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión.',
            confirmButtonColor: '#f3a8c2',
            background: '#fff5f8',
            color: '#6b3b4f'
        });
        btn.disabled = false;
    });
});
</script>

<script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
</body>
</html>
