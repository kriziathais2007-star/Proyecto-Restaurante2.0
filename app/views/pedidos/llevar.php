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
        <span id="breadcrumb-page">Llevar</span>
    </nav>

    <div class="main-content">

        <div class="llevar-toolbar">
            <a href="<?php echo BASE_URL; ?>/pedidos/crear/Llevar" class="btn-nuevo-pedido">
                <i class="fa-solid fa-plus"></i> Nuevo Pedido para Llevar
            </a>
        </div>

        <div class="llevar-grid">
            <?php if (empty($pedidos)): ?>
                <p class="empty-msg">No hay pedidos para llevar activos.</p>
            <?php else: ?>
                <?php foreach ($pedidos as $pedido): ?>
                    <?php if ($pedido['estado'] === 'Entregado'): ?>
                        <!-- Entregado: abre modal de pago -->
                        <div class="llevar-card urgente"
                             onclick="abrirModalPago(<?php echo $pedido['id_pedido']; ?>)">
                            <div class="llevar-card-header">
                                <span class="llevar-id">#<?php echo $pedido['id_pedido']; ?></span>
                                <span class="badge badge-entregado">
                                    <?php echo htmlspecialchars($pedido['estado']); ?>
                                </span>
                            </div>
                            <p class="llevar-mesero"><i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($pedido['mesero'] ?? 'N/A'); ?></p>
                            <p class="llevar-total">S/ <?php echo number_format((float) $pedido['total'], 2); ?></p>
                        </div>
                    <?php else: ?>
                        <!-- Otros estados: va a detalle -->
                        <a href="<?php echo BASE_URL; ?>/pedidos/detalle/<?php echo $pedido['id_pedido']; ?>"
                           class="llevar-card">
                            <div class="llevar-card-header">
                                <span class="llevar-id">#<?php echo $pedido['id_pedido']; ?></span>
                                <span class="badge badge-<?php echo strtolower($pedido['estado']); ?>">
                                    <?php echo htmlspecialchars($pedido['estado']); ?>
                                </span>
                            </div>
                            <p class="llevar-mesero"><i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($pedido['mesero'] ?? 'N/A'); ?></p>
                            <p class="llevar-total">S/ <?php echo number_format((float) $pedido['total'], 2); ?></p>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</main>

<!-- MODAL PAGO LLEVAR -->
<div class="modal-overlay" id="modalPagoLlevar">
    <div class="modal-content modal-mesa-acciones">
        <div class="modal-header">
            <h3><i class="fa-solid fa-receipt"></i> Pagar Pedido #<span id="modalLlevarPedidoId"></span></h3>
            <button type="button" id="btnCerrarModalLlevar" class="btn-cerrar-modal">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body modal-mesa-body">

            <a href="#" id="linkEditarLlevar" class="btn-modal-accion btn-editar">
                <i class="fa-solid fa-pen"></i> Editar pedido
            </a>

            <div class="pago-metodo-selector">
                <label>Método de pago:</label>
                <div class="metodo-opciones">
                    <button type="button" class="btn-metodo-llevar activo" data-metodo="Efectivo">
                        <i class="fa-solid fa-money-bill-wave"></i> Efectivo
                    </button>
                    <button type="button" class="btn-metodo-llevar" data-metodo="Yape">
                        <i class="fa-solid fa-mobile-screen"></i> Yape
                    </button>
                </div>
            </div>

            <div id="yapeWrapperLlevar" style="display:none; margin-top: 12px;">
                <label><i class="fa-solid fa-camera"></i> Foto del comprobante:</label>
                <input type="file" id="fotoYapeLlevar" accept="image/*" capture="environment"
                       style="margin-top: 6px; width: 100%;">
            </div>

            <button type="button" id="btnConfirmarPagoLlevar" class="btn-modal-accion btn-pagar" style="margin-top: 16px;">
                <i class="fa-solid fa-money-bill-wave"></i> Confirmar Pago
            </button>

        </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
<script>
    const BASE_URL = "<?php echo BASE_URL; ?>";
    let pedidoLlevar = null;
    let metodoLlevar = 'Efectivo';

    function abrirModalPago(idPedido) {
        pedidoLlevar = idPedido;
        document.getElementById('modalLlevarPedidoId').textContent = idPedido;
        document.getElementById('linkEditarLlevar').href = BASE_URL + '/pedidos/detalle/' + idPedido;
        setMetodoLlevar('Efectivo');
        document.getElementById('fotoYapeLlevar').value = '';
        document.getElementById('modalPagoLlevar').classList.add('activo');
    }

    function setMetodoLlevar(metodo) {
        metodoLlevar = metodo;
        document.querySelectorAll('.btn-metodo-llevar').forEach(b => b.classList.remove('activo'));
        document.querySelector('.btn-metodo-llevar[data-metodo="' + metodo + '"]').classList.add('activo');
        document.getElementById('yapeWrapperLlevar').style.display = metodo === 'Yape' ? 'block' : 'none';
    }

    document.querySelectorAll('.btn-metodo-llevar').forEach(btn => {
        btn.addEventListener('click', () => setMetodoLlevar(btn.dataset.metodo));
    });

    document.getElementById('btnCerrarModalLlevar').addEventListener('click', () => {
        document.getElementById('modalPagoLlevar').classList.remove('activo');
        pedidoLlevar = null;
    });

    document.getElementById('modalPagoLlevar').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('activo');
            pedidoLlevar = null;
        }
    });

    document.getElementById('btnConfirmarPagoLlevar').addEventListener('click', () => {
        if (!pedidoLlevar) return;
        if (!confirm('¿Confirmar pago del pedido #' + pedidoLlevar + ' con ' + metodoLlevar + '?')) return;

        const formData = new FormData();
        formData.append('metodo', metodoLlevar);

        if (metodoLlevar === 'Yape') {
            const foto = document.getElementById('fotoYapeLlevar').files[0];
            if (!foto) {
                alert('Por favor adjunta la foto del comprobante Yape.');
                return;
            }
            formData.append('foto_yape', foto);
        }

        document.getElementById('btnConfirmarPagoLlevar').disabled = true;

        fetch(BASE_URL + '/pedidos/pagar/' + pedidoLlevar, {
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
                document.getElementById('btnConfirmarPagoLlevar').disabled = false;
            }
        })
        .catch(() => {
            alert('Error de conexión');
            document.getElementById('btnConfirmarPagoLlevar').disabled = false;
        });
    });
</script>
</body>
</html>