
document.getElementById('modalEditarPlato').addEventListener('show.bs.modal', function (e) {
    const btn = e.relatedTarget;
    document.getElementById('edit_id').value             = btn.dataset.id;
    document.getElementById('edit_nombre').value         = btn.dataset.nombre;
    document.getElementById('edit_precio').value         = btn.dataset.precio;
    document.getElementById('edit_descripcion').value    = btn.dataset.descripcion;
    document.getElementById('edit_disponibilidad').value = btn.dataset.disponibilidad;
});

document.getElementById('modalEliminarPlato').addEventListener('show.bs.modal', function (e) {
    const btn = e.relatedTarget;
    document.getElementById('eliminar_id').value             = btn.dataset.id;
    document.getElementById('eliminar_nombre').textContent   = btn.dataset.nombre;
});
