document.addEventListener("DOMContentLoaded", () => {

    // ================================================================
    //  HELPERS
    // ================================================================
    function abrirModal(overlayId, modalId) {
        document.getElementById(overlayId).classList.add("show");
        document.getElementById(modalId).classList.add("show");
    }
    function cerrarModal(overlayId, modalId) {
        document.getElementById(overlayId).classList.remove("show");
        document.getElementById(modalId).classList.remove("show");
    }

    // ================================================================
    document.getElementById("btnAbrirPago").addEventListener("click", () =>
        abrirModal("overlayNuevoPago", "modalNuevoPago")
    );
    document.getElementById("cerrarNuevoPago").addEventListener("click", () =>
        cerrarModal("overlayNuevoPago", "modalNuevoPago")
    );
    document.getElementById("overlayNuevoPago").addEventListener("click", (e) => {
        if (e.target === document.getElementById("overlayNuevoPago"))
            cerrarModal("overlayNuevoPago", "modalNuevoPago");
    });

    // Mostrar/ocultar campo foto_yape según método
    document.getElementById("np-metodo").addEventListener("change", function () {
        const grupoFoto = document.getElementById("grupoFotoYape");
        grupoFoto.style.display = this.value === "Yape" ? "flex" : "none";
    });

    // ================================================================
//  GUARDAR PAGO
// ================================================================
document.getElementById("formNuevoPago").addEventListener("submit", (e) => {
    e.preventDefault();

    const formData = new FormData();

    formData.append("id_pedido", document.getElementById("np-pedido").value);
    formData.append("monto", document.getElementById("np-monto").value);
    formData.append("metodo_pago", document.getElementById("np-metodo").value);

    const foto = document.getElementById("np-foto").files[0];

    if (foto) {
        formData.append("foto_yape", foto);
    }

    fetch(BASE_URL + "/pagos/guardar", {
        method: "POST",
        body: formData
    })
    .then((r) => r.json())
    .then((datos) => {
        if (datos.ok) {
            Swal.fire({
                title: "¡Registrado!",
                text: "Pago registrado correctamente.",
                icon: "success",
                confirmButtonText: "Aceptar"
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                title: "Error",
                text: datos.mensaje || "No se pudo registrar.",
                icon: "error",
                confirmButtonText: "Aceptar"
            });
        }
    })
    .catch((error) => {
        console.error(error);
        Swal.fire({
            title: "Error",
            text: "Ocurrió un error al registrar el pago.",
            icon: "error"
        });
    });
});

    // ================================================================
    //  ELIMINAR PAGO
    // ================================================================
    document.querySelectorAll(".btn-eliminar-pago").forEach((btn) => {
        btn.addEventListener("click", () => {
            Swal.fire({
                title: "¿Eliminar pago?",
                text: "Esta acción no se puede deshacer.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (!result.isConfirmed) return;
                fetch(BASE_URL + "/pagos/eliminar", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "id_pago=" + btn.dataset.id,
                })
                .then((r) => r.json())
                .then((datos) => {
                    if (datos.eliminar) {
                        Swal.fire({ title: "¡Eliminado!", text: "Pago eliminado.", icon: "success", confirmButtonText: "Aceptar" })
                        .then(() => location.reload());
                    } else {
                        Swal.fire({ title: "Error", text: "No se pudo eliminar el pago.", icon: "error", confirmButtonText: "Aceptar" });
                    }
                });
            });
        });
    });

});