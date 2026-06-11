document.addEventListener("DOMContentLoaded", () => {

    // ====== ELIMINAR USUARIO ======
    document.querySelectorAll(".btn-eliminar").forEach(function (btn) {
        btn.addEventListener("click", function () {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "Esta acción no se puede deshacer.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (!result.isConfirmed) return;
                fetch(BASE_URL + "/usuarios/eliminar_usuario", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "id_usuario=" + btn.dataset.id,
                })
                .then(res => res.json())
                .then(datos => {
                    if (datos.eliminar) {
                        Swal.fire({ title: "¡Eliminado!", text: "Usuario eliminado correctamente.", icon: "success", confirmButtonText: "Aceptar" })
                        .then(() => location.reload());
                    } else {
                        Swal.fire({ title: "Error", text: "No se pudo eliminar el usuario.", icon: "error", confirmButtonText: "Aceptar" });
                    }
                });
            });
        });
    });

    // ====== MODAL EDITAR ======
    const overlayEditar = document.getElementById("modalEditarOverlay");
    const modalEditar   = document.getElementById("modalEditar");
    const btnCerrar     = document.getElementById("modalCerrar");

    function abrirModalEditar() {
        overlayEditar.classList.add("show");
        modalEditar.classList.add("show");
    }
    function cerrarModalEditar() {
        overlayEditar.classList.remove("show");
        modalEditar.classList.remove("show");
    }

    document.querySelectorAll(".btn-editar").forEach(function (btn) {
        btn.addEventListener("click", function () {
            document.getElementById("id_usuario").value = btn.dataset.id;
            document.getElementById("nombre").value     = btn.dataset.nombre;
            document.getElementById("usuario").value    = btn.dataset.usuario;
            document.getElementById("clave").value      = btn.dataset.clave;
            document.getElementById("rol").value        = btn.dataset.rol;
            abrirModalEditar();
        });
    });

    btnCerrar.addEventListener("click", cerrarModalEditar);
    overlayEditar.addEventListener("click", (e) => { if (e.target === overlayEditar) cerrarModalEditar(); });

    // ====== GUARDAR EDICIÓN ======
    document.getElementById("formEditar").addEventListener("submit", function (e) {
        e.preventDefault();
        const body = new URLSearchParams({
            id_usuario: document.getElementById("id_usuario").value,
            nombre:     document.getElementById("nombre").value,
            usuario:    document.getElementById("usuario").value,
            clave:      document.getElementById("clave").value,
            rol:        document.getElementById("rol").value,
        });
        fetch(BASE_URL + "/usuarios/editar_usuario", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: body.toString(),
        })
        .then(res => res.json())
        .then(datos => {
            if (datos.ok) {
                Swal.fire({ title: "¡Actualizado!", text: "Usuario editado correctamente.", icon: "success", confirmButtonText: "Aceptar" })
                .then(() => location.reload());
            } else {
                Swal.fire({ title: "Error", text: datos.mensaje || "No se pudo actualizar.", icon: "error", confirmButtonText: "Aceptar" });
            }
        });
    });

    // ====== MODAL REGISTRO ======
    const overlayRegistro = document.getElementById("modalRegistroOverlay");
    const modalRegistro   = document.getElementById("modalRegistro");
    const btnCerrarReg    = document.getElementById("modalRegistroCerrar");

    function abrirModalRegistro() {
        overlayRegistro.classList.add("show");
        modalRegistro.classList.add("show");
    }
    function cerrarModalRegistro() {
        overlayRegistro.classList.remove("show");
        modalRegistro.classList.remove("show");
    }

    document.getElementById("btnAbrirRegistro").addEventListener("click", abrirModalRegistro);
    btnCerrarReg.addEventListener("click", cerrarModalRegistro);
    overlayRegistro.addEventListener("click", (e) => { if (e.target === overlayRegistro) cerrarModalRegistro(); });

    // ====== GUARDAR REGISTRO ======
    document.getElementById("formRegistro").addEventListener("submit", function (e) {
        e.preventDefault();
        const body = new URLSearchParams({
            nombre:  document.getElementById("reg-nombre").value,
            usuario: document.getElementById("reg-usuario").value,
            clave:   document.getElementById("reg-clave").value,
            rol:     document.getElementById("reg-rol").value,
        });
        fetch(BASE_URL + "/usuarios/guardar", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: body.toString(),
        })
        .then(res => res.json())
        .then(datos => {
            if (datos.ok) {
                Swal.fire({ title: "¡Registrado!", text: "Usuario creado correctamente.", icon: "success", confirmButtonText: "Aceptar" })
                .then(() => location.reload());
            } else {
                Swal.fire({ title: "Error", text: datos.mensaje || "No se pudo registrar.", icon: "error", confirmButtonText: "Aceptar" });
            }
        });
    });

});