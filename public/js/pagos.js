document.addEventListener('DOMContentLoaded', function () {

    // ══════════════════════════════════════════
    // VER COMPROBANTE YAPE
    // ══════════════════════════════════════════
    document.querySelectorAll('.btn-ver-comprobante').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const src = this.dataset.src;
            document.getElementById('imgComprobante').src = src;
            document.getElementById('overlayComprobante').style.display = 'flex';
        });
    });

    // Cerrar modal comprobante al hacer clic fuera
    document.getElementById('overlayComprobante')?.addEventListener('click', function (e) {
        if (e.target === this) this.style.display = 'none';
    });

    // ══════════════════════════════════════════
    // MODAL NUEVO PAGO
    // ══════════════════════════════════════════
    const overlayNuevo = document.getElementById('overlayNuevoPago');
    const modalNuevo   = document.getElementById('modalNuevoPago');

    document.getElementById('btnAbrirPago')?.addEventListener('click', function () {
        overlayNuevo?.classList.add('show');
        modalNuevo?.classList.add('show');
    });

    document.getElementById('cerrarNuevoPago')?.addEventListener('click', function () {
        overlayNuevo?.classList.remove('show');
        modalNuevo?.classList.remove('show');
    });

    overlayNuevo?.addEventListener('click', function (e) {
        if (e.target === overlayNuevo) {
            overlayNuevo.classList.remove('show');
            modalNuevo?.classList.remove('show');
        }
    });

    // Mostrar/ocultar campo foto según método
    document.getElementById('np-metodo')?.addEventListener('change', function () {
        const grupoFoto = document.getElementById('grupoFotoYape');
        if (grupoFoto) {
            grupoFoto.style.display = this.value === 'Yape' ? 'flex' : 'none';
        }
    });

    // ══════════════════════════════════════════
    // GUARDAR PAGO
    // ══════════════════════════════════════════
    document.getElementById('formNuevoPago')?.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch(BASE_URL + '/pagos/guardar', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                Swal.fire({ icon: 'success', title: 'Registrado', text: 'Pago registrado correctamente.', confirmButtonText: 'Aceptar' })
                    .then(() => location.reload());
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.mensaje || 'No se pudo registrar.', confirmButtonText: 'Aceptar' });
            }
        })
        .catch(() => Swal.fire({ icon: 'error', title: 'Error de conexión' }));
    });

    // ══════════════════════════════════════════
    // ELIMINAR PAGO
    // ══════════════════════════════════════════
    document.querySelectorAll('.btn-eliminar-pago').forEach(function (btn) {
        btn.addEventListener('click', function () {
            Swal.fire({
                title: '¿Eliminar pago?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(function (result) {
                if (!result.isConfirmed) return;

                fetch(BASE_URL + '/pagos/eliminar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id_pago=' + btn.dataset.id
                })
                .then(r => r.json())
                .then(data => {
                    if (data.eliminar) {
                        Swal.fire({ icon: 'success', title: 'Eliminado', confirmButtonText: 'Aceptar' })
                            .then(() => location.reload());
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: data.mensaje || 'No se pudo eliminar.' });
                    }
                })
                .catch(() => Swal.fire({ icon: 'error', title: 'Error de conexión' }));
            });
        });
    });

});
