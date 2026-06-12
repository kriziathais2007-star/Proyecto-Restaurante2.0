// ── Eliminar pedido ──────────────────────────────────────────────
document.querySelectorAll('.btn-eliminar-pedido').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;
        if (!confirm('¿Seguro que deseas eliminar el pedido #' + id + '?')) return;

        fetch(BASE_URL + '/pedidos/eliminar/' + id, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Funciona tanto en tabla (reportes) como en tarjeta (llevar)
                const fila    = this.closest('tr');
                const tarjeta = this.closest('.llevar-card-wrapper');
                if (fila)    fila.remove();
                if (tarjeta) tarjeta.remove();
            } else {
                alert(data.message || 'No se pudo eliminar el pedido');
            }
        })
        .catch(() => alert('Error de conexión'));
    });
});

// ── Cambiar estado (Reportes: Pagado → Preparando) ───────────────
document.querySelectorAll('.btn-cambiar-estado').forEach(btn => {
    btn.addEventListener('click', function () {
        const id          = this.dataset.id;
        const nuevoEstado = this.dataset.estado;
        if (!confirm('¿Cambiar pedido #' + id + ' a "' + nuevoEstado + '"?')) return;

        fetch(BASE_URL + '/pedidos/cambiarEstadoAjax/' + id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ estado: nuevoEstado })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) location.reload();
            else alert(data.message || 'No se pudo actualizar');
        })
        .catch(() => alert('Error de conexión'));
    });
});
