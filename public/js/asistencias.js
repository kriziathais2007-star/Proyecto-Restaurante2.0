document.addEventListener('DOMContentLoaded', () => {

    const btnEntrada = document.getElementById('btnEntrada');
    const btnSalida = document.getElementById('btnSalida');

    btnEntrada?.addEventListener('click', registrarEntrada);
    btnSalida?.addEventListener('click', registrarSalida);

});

async function registrarEntrada() {

    const response = await fetch(`${BASE_URL}/asistencias/registrarEntrada`, {
        method: 'POST'
    });

    const data = await response.json();

    Swal.fire({
        icon: data.success ? 'success' : 'error',
        title: data.success ? 'OK' : 'Error',
        text: data.message
    });

    if (data.success) location.reload();
}

async function registrarSalida() {

    const response = await fetch(`${BASE_URL}/asistencias/registrarSalida`, {
        method: 'POST'
    });

    const data = await response.json();

    Swal.fire({
        icon: data.success ? 'success' : 'error',
        title: data.success ? 'OK' : 'Error',
        text: data.message
    });

    if (data.success) location.reload();
}
document.addEventListener('DOMContentLoaded', () => {

    // BOTONES ELIMINAR
    document.querySelectorAll('.btn-eliminar').forEach(btn => {

        btn.addEventListener('click', async () => {

            const id = btn.dataset.id;

            const result = await Swal.fire({
                title: '¿Eliminar asistencia?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });

            if (!result.isConfirmed) return;

            const formData = new FormData();
            formData.append('id_asistencia', id);

            try {

                const response = await fetch(`${BASE_URL}/asistencias/eliminar`, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                Swal.fire({
                    icon: data.success ? 'success' : 'error',
                    title: data.success ? 'Eliminado' : 'Error',
                    text: data.message
                });

                if (data.success) {
                    location.reload();
                }

            } catch (error) {

                console.error(error);

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo conectar con el servidor'
                });
            }

        });

    });

});