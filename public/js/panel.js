document.addEventListener('DOMContentLoaded', function () {
    const btnAbrir = document.getElementById('btnToggleFiltros');
    const btnCerrar = document.getElementById('btnCerrarFiltros');
    const modal = document.getElementById('modalFiltros');

    if (btnAbrir && modal) {
        btnAbrir.addEventListener('click', function () {
            modal.classList.add('mostrar');
        });
    }

    if (btnCerrar && modal) {
        btnCerrar.addEventListener('click', function () {
            modal.classList.remove('mostrar');
        });
    }

    // Cerrar al hacer clic fuera del contenido del modal
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.classList.remove('mostrar');
            }
        });
    }
});