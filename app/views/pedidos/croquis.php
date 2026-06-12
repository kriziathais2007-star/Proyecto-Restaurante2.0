<!DOCTYPE html>
<html lang="Es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Panel de Administración</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/dashboard.css">
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
        <span id="breadcrumb-page">Mesa</span>
    </nav>

    <div class="main-content">

        <div class="croquis-legend">
            <div class="legend-item"><span class="legend-color legend-libre"></span> Libre</div>
            <div class="legend-item"><span class="legend-color legend-ocupada"></span> Ocupada</div>
            <div class="legend-item"><span class="legend-color legend-urgente"></span> Pendiente de pago</div>
        </div>

        <div class="croquis-grid">
            <?php foreach ($mesas as $mesa): ?>
                <?php if ($mesa['ocupada']): ?>
                    <?php if ($mesa['estado'] === 'Entregado'): ?>
                        <!-- Mesa roja: pendiente de pago → abre modal -->
                        <div class="mesa-card ocupada urgente"
                             data-id-pedido="<?php echo $mesa['id_pedido']; ?>"
                             onclick="abrirModalMesa(<?php echo $mesa['id_pedido']; ?>)">
                            <i class="fa-solid fa-chair"></i>
                            <span>Mesa <?php echo $mesa['numero_mesa']; ?></span>
                            <span class="mesa-estado-badge"><?php echo htmlspecialchars($mesa['estado']); ?></span>
                        </div>
                    <?php else: ?>
                        <!-- Mesa ocupada normal: va a detalle -->
                        <a href="<?php echo BASE_URL; ?>/pedidos/detalle/<?php echo $mesa['id_pedido']; ?>"
                           class="mesa-card ocupada">
                            <i class="fa-solid fa-chair"></i>
                            <span>Mesa <?php echo $mesa['numero_mesa']; ?></span>
                            <span class="mesa-estado-badge"><?php echo htmlspecialchars($mesa['estado']); ?></span>
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Mesa libre: crear pedido -->
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

<!-- MODAL: Editar / Pagar -->
<div class="modal-overlay" id="modalMesa">
    <div class="modal-content modal-mesa-acciones">
        <div class="modal-header">
            <h3><i class="fa-solid fa-receipt"></i> Pedido #<span id="modalMesaPedidoId"></span></h3>
            <button type="button" id="btnCerrarModalMesa" class="btn-cerrar-modal">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body modal-mesa-body">

            <!-- Botón editar -->
            <a href="#" id="linkEditarPedido" class="btn-modal-accion btn-editar">
                <i class="fa-solid fa-pen"></i> Editar pedido
            </a>

            <!-- Formulario de pago -->
            <div id="formPagoWrapper">
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

                <!-- Solo visible si Yape -->
                <div id="yapeWrapper" style="display:none; margin-top: 12px;">
                    <label><i class="fa-solid fa-camera"></i> Foto del comprobante:</label>
                    <input type="file" id="fotoYape" accept="image/*" capture="environment"
                           style="margin-top: 6px; width: 100%;">
                </div>

                <button type="button" id="btnConfirmarPago" class="btn-modal-accion btn-pagar" style="margin-top: 16px;">
                    <i class="fa-solid fa-money-bill-wave"></i> Confirmar Pago
                </button>
            </div>

        </div>
    </div>
</div>
<script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
<script>
    const BASE_URL = "<?php echo BASE_URL; ?>";
    let pedidoSeleccionado = null;
    let metodoSeleccionado = 'Efectivo';

    function abrirModalMesa(idPedido) {
        pedidoSeleccionado = idPedido;
        document.getElementById('modalMesaPedidoId').textContent = idPedido;
        document.getElementById('linkEditarPedido').href = BASE_URL + '/pedidos/detalle/' + idPedido;
        // Reset
        setMetodo('Efectivo');
        document.getElementById('fotoYape').value = '';
        document.getElementById('modalMesa').classList.add('activo');
    }

    function setMetodo(metodo) {
        metodoSeleccionado = metodo;
        document.querySelectorAll('.btn-metodo').forEach(b => b.classList.remove('activo'));
        document.querySelector('.btn-metodo[data-metodo="' + metodo + '"]').classList.add('activo');
        document.getElementById('yapeWrapper').style.display = metodo === 'Yape' ? 'block' : 'none';
    }

    document.querySelectorAll('.btn-metodo').forEach(btn => {
        btn.addEventListener('click', () => setMetodo(btn.dataset.metodo));
    });

    document.getElementById('btnCerrarModalMesa').addEventListener('click', () => {
        document.getElementById('modalMesa').classList.remove('activo');
        pedidoSeleccionado = null;
    });

    document.getElementById('modalMesa').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('activo');
            pedidoSeleccionado = null;
        }
    });

    document.getElementById('btnConfirmarPago').addEventListener('click', () => {
        if (!pedidoSeleccionado) return;
        if (!confirm('¿Confirmar pago del pedido #' + pedidoSeleccionado + ' con ' + metodoSeleccionado + '?')) return;

        const formData = new FormData();
        formData.append('metodo', metodoSeleccionado);

        if (metodoSeleccionado === 'Yape') {
            const foto = document.getElementById('fotoYape').files[0];
            if (!foto) {
                alert('Por favor adjunta la foto del comprobante Yape.');
                return;
            }
            formData.append('foto_yape', foto);
        }

        document.getElementById('btnConfirmarPago').disabled = true;

        fetch(BASE_URL + '/pedidos/pagar/' + pedidoSeleccionado, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'No se pudo registrar el pago');
                document.getElementById('btnConfirmarPago').disabled = false;
            }
        })
        .catch(() => {
            alert('Error de conexión');
            document.getElementById('btnConfirmarPago').disabled = false;
        });
    });
</script>
</body>
</html>