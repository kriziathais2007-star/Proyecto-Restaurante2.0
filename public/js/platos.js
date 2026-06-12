document.addEventListener("DOMContentLoaded", () => {

    // ================================================================
    //  HELPERS — modal genérico
    // ================================================================
    function abrirModal(overlayId, modalId) {
        document.getElementById(overlayId).classList.add("show");
        document.getElementById(modalId).classList.add("show");
    }

    function cerrarModal(overlayId, modalId) {
        document.getElementById(overlayId).classList.remove("show");
        document.getElementById(modalId).classList.remove("show");
    }

    function bindCerrar(btnId, overlayId, modalId) {
        document.getElementById(btnId).addEventListener("click", () => cerrarModal(overlayId, modalId));
        document.getElementById(overlayId).addEventListener("click", (e) => {
            if (e.target === document.getElementById(overlayId)) cerrarModal(overlayId, modalId);
        });
    }

    // ================================================================
    //  PLATOS — abrir / cerrar modales
    // ================================================================
    document.getElementById("btnAbrirPlato").addEventListener("click", () =>
        abrirModal("overlayNuevoPlato", "modalNuevoPlato")
    );
    bindCerrar("cerrarNuevoPlato",  "overlayNuevoPlato",  "modalNuevoPlato");
    bindCerrar("cerrarEditarPlato", "overlayEditarPlato", "modalEditarPlato");

    // ================================================================
    //  PLATOS — editar (abrir modal con datos)
    // ================================================================
    document.querySelectorAll(".btn-editar-plato").forEach((btn) => {
        btn.addEventListener("click", () => {
            document.getElementById("ep-id").value          = btn.dataset.id;
            document.getElementById("ep-nombre").value      = btn.dataset.nombre;
            document.getElementById("ep-descripcion").value = btn.dataset.descripcion;
            document.getElementById("ep-precio").value      = btn.dataset.precio;
            document.getElementById("ep-activo").value      = btn.dataset.activo;
            abrirModal("overlayEditarPlato", "modalEditarPlato");
        });
    });

    // ================================================================
    //  PLATOS — guardar nuevo
    // ================================================================
    document.getElementById("formNuevoPlato").addEventListener("submit", (e) => {
        e.preventDefault();
        const body = new URLSearchParams({
            nombre:      document.getElementById("np-nombre").value,
            descripcion: document.getElementById("np-descripcion").value,
            precio:      document.getElementById("np-precio").value,
            activo:      document.getElementById("np-activo").value,
        });
        fetch(BASE_URL + "/platos/guardar", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: body.toString(),
        })
        .then((r) => r.json())
        .then((datos) => {
            if (datos.ok) {
                Swal.fire({ title: "¡Registrado!", text: "Plato creado correctamente.", icon: "success", confirmButtonText: "Aceptar" })
                .then(() => location.reload());
            } else {
                Swal.fire({ title: "Error", text: datos.mensaje || "No se pudo registrar.", icon: "error", confirmButtonText: "Aceptar" });
            }
        });
    });

    // ================================================================
    //  PLATOS — guardar edición
    // ================================================================
    document.getElementById("formEditarPlato").addEventListener("submit", (e) => {
        e.preventDefault();
        const body = new URLSearchParams({
            id_plato:    document.getElementById("ep-id").value,
            nombre:      document.getElementById("ep-nombre").value,
            descripcion: document.getElementById("ep-descripcion").value,
            precio:      document.getElementById("ep-precio").value,
            activo:      document.getElementById("ep-activo").value,
        });
        fetch(BASE_URL + "/platos/editar", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: body.toString(),
        })
        .then((r) => r.json())
        .then((datos) => {
            if (datos.ok) {
                Swal.fire({ title: "¡Actualizado!", text: "Plato editado correctamente.", icon: "success", confirmButtonText: "Aceptar" })
                .then(() => location.reload());
            } else {
                Swal.fire({ title: "Error", text: datos.mensaje || "No se pudo actualizar.", icon: "error", confirmButtonText: "Aceptar" });
            }
        });
    });

    // ================================================================
    //  PLATOS — eliminar
    // ================================================================
    document.querySelectorAll(".btn-eliminar-plato").forEach((btn) => {
        btn.addEventListener("click", () => {
            Swal.fire({
                title: "¿Eliminar plato?",
                text: "Esta acción no se puede deshacer.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (!result.isConfirmed) return;
                fetch(BASE_URL + "/platos/eliminar", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "id_plato=" + btn.dataset.id,
                })
                .then((r) => r.json())
                .then((datos) => {
                    if (datos.eliminar) {
                        Swal.fire({ title: "¡Eliminado!", text: "Plato eliminado.", icon: "success", confirmButtonText: "Aceptar" })
                        .then(() => location.reload());
                    } else {
                        Swal.fire({ title: "Error", text: "No se pudo eliminar el plato.", icon: "error", confirmButtonText: "Aceptar" });
                    }
                });
            });
        });
    });

    // ================================================================
    //  ENTRADAS — abrir / cerrar modales
    // ================================================================
    document.getElementById("btnAbrirEntrada").addEventListener("click", () =>
        abrirModal("overlayNuevaEntrada", "modalNuevaEntrada")
    );
    bindCerrar("cerrarNuevaEntrada",  "overlayNuevaEntrada",  "modalNuevaEntrada");
    bindCerrar("cerrarEditarEntrada", "overlayEditarEntrada", "modalEditarEntrada");

    // ================================================================
    //  ENTRADAS — editar (abrir modal con datos)
    // ================================================================
    document.querySelectorAll(".btn-editar-entrada").forEach((btn) => {
        btn.addEventListener("click", () => {
            document.getElementById("ee-id").value     = btn.dataset.id;
            document.getElementById("ee-nombre").value = btn.dataset.nombre;
            document.getElementById("ee-precio").value = btn.dataset.precio;
            document.getElementById("ee-activo").value = btn.dataset.activo;
            abrirModal("overlayEditarEntrada", "modalEditarEntrada");
        });
    });

    // ================================================================
    //  ENTRADAS — guardar nueva
    // ================================================================
    document.getElementById("formNuevaEntrada").addEventListener("submit", (e) => {
        e.preventDefault();
        const body = new URLSearchParams({
            nombre: document.getElementById("ne-nombre").value,
            precio: document.getElementById("ne-precio").value,
            activo: document.getElementById("ne-activo").value,
        });
        fetch(BASE_URL + "/platos/guardar_entrada", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: body.toString(),
        })
        .then((r) => r.json())
        .then((datos) => {
            if (datos.ok) {
                Swal.fire({ title: "¡Registrada!", text: "Entrada creada correctamente.", icon: "success", confirmButtonText: "Aceptar" })
                .then(() => location.reload());
            } else {
                Swal.fire({ title: "Error", text: datos.mensaje || "No se pudo registrar.", icon: "error", confirmButtonText: "Aceptar" });
            }
        });
    });

    // ================================================================
    //  ENTRADAS — guardar edición
    // ================================================================
    document.getElementById("formEditarEntrada").addEventListener("submit", (e) => {
        e.preventDefault();
        const body = new URLSearchParams({
            id_entrada: document.getElementById("ee-id").value,
            nombre:     document.getElementById("ee-nombre").value,
            precio:     document.getElementById("ee-precio").value,
            activo:     document.getElementById("ee-activo").value,
        });
        fetch(BASE_URL + "/platos/editar_entrada", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: body.toString(),
        })
        .then((r) => r.json())
        .then((datos) => {
            if (datos.ok) {
                Swal.fire({ title: "¡Actualizada!", text: "Entrada editada correctamente.", icon: "success", confirmButtonText: "Aceptar" })
                .then(() => location.reload());
            } else {
                Swal.fire({ title: "Error", text: datos.mensaje || "No se pudo actualizar.", icon: "error", confirmButtonText: "Aceptar" });
            }
        });
    });

    // ================================================================
    //  ENTRADAS — eliminar
    // ================================================================
    document.querySelectorAll(".btn-eliminar-entrada").forEach((btn) => {
        btn.addEventListener("click", () => {
            Swal.fire({
                title: "¿Eliminar entrada?",
                text: "Esta acción no se puede deshacer.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (!result.isConfirmed) return;
                fetch(BASE_URL + "/platos/eliminar_entrada", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "id_entrada=" + btn.dataset.id,
                })
                .then((r) => r.json())
                .then((datos) => {
                    if (datos.eliminar) {
                        Swal.fire({ title: "¡Eliminada!", text: "Entrada eliminada.", icon: "success", confirmButtonText: "Aceptar" })
                        .then(() => location.reload());
                    } else {
                        Swal.fire({ title: "Error", text: "No se pudo eliminar la entrada.", icon: "error", confirmButtonText: "Aceptar" });
                    }
                });
            });
        });
    });

});