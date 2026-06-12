document.addEventListener("DOMContentLoaded", () => {

    // ==========================
    // CLICK EN MESAS
    // ==========================
    document.querySelectorAll(".mesa").forEach(mesa => {

        mesa.addEventListener("click", function () {

            const numeroMesa = this.dataset.mesa;

            // MESA LIBRE
            if (this.classList.contains("libre")) {

                Swal.fire({
                    title: `Mesa ${numeroMesa}`,
                    text: "¿Deseas registrar un nuevo pedido?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Registrar Pedido",
                    cancelButtonText: "Cancelar"
                }).then((result) => {

                    if (result.isConfirmed) {
                        abrirModalPedido(numeroMesa);
                    }

                });

            }

            // MESA OCUPADA
            else {

                Swal.fire({
                    title: `Mesa ${numeroMesa}`,
                    text: "Seleccione una opción",
                    icon: "info",
                    showCancelButton: true,
                    showDenyButton: true,
                    confirmButtonText: "✏️ Editar Pedido",
                    denyButtonText: "💳 Pagar Pedido",
                    cancelButtonText: "Cancelar"

                }).then((result) => {

                    if (result.isConfirmed) {
                        editarPedidoMesa(numeroMesa);
                    }

                    else if (result.isDenied) {
                        pagarPedidoMesa(numeroMesa);
                    }

                });

            }

        });

    });


    // ==========================
    // METODO DE PAGO
    // ==========================
    const metodoPago = document.getElementById("metodoPago");

    if (metodoPago) {

        metodoPago.addEventListener("change", function () {

            document.getElementById("grupoYape").style.display =
                this.value === "Yape"
                    ? "block"
                    : "none";

        });

    }

});


// ==================================================
// MODAL NUEVO PEDIDO
// ==================================================
function abrirModalPedido(numeroMesa) {

    document.getElementById("numero_mesa").value = numeroMesa;
    document.getElementById("mesaSeleccionada").value = numeroMesa;

    document.getElementById("overlayPedido")
        .classList.add("show");

    document.getElementById("modalPedido")
        .classList.add("show");

}

function cerrarModalPedido() {

    document.getElementById("overlayPedido")
        .classList.remove("show");

    document.getElementById("modalPedido")
        .classList.remove("show");

}


// ==================================================
// MODAL EDITAR
// ==================================================
function abrirModalEditar() {

    document.getElementById("overlayEditar")
        .classList.add("show");

    document.getElementById("modalEditar")
        .classList.add("show");

}

function cerrarModalEditar() {

    document.getElementById("overlayEditar")
        .classList.remove("show");

    document.getElementById("modalEditar")
        .classList.remove("show");

}


// ==================================================
// MODAL PAGO
// ==================================================
function abrirModalPago(idPedido, total) {

    document.getElementById("pago_id_pedido").value = idPedido;
    document.getElementById("pago_total").value = total;

    document.getElementById("overlayPago")
        .classList.add("show");

    document.getElementById("modalPago")
        .classList.add("show");

}

function cerrarModalPago() {

    document.getElementById("overlayPago")
        .classList.remove("show");

    document.getElementById("modalPago")
        .classList.remove("show");

}


// ==================================================
// EDITAR PEDIDO
// ==================================================
function editarPedidoMesa(numeroMesa) {

    fetch(
        `${BASE_URL}/pedidos/detalle_mesa?numero_mesa=${numeroMesa}`
    )
        .then(response => response.json())
        .then(data => {

            if (!data.ok) {

                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: data.mensaje
                });

                return;
            }

            document.getElementById("editar_id_pedido").value =
                data.pedido.id_pedido;

            let html = `
                <h3>Pedido #${data.pedido.id_pedido}</h3>
                <p>Total: S/ ${data.pedido.total}</p>
                <hr>
            `;

            if (data.detalles.length > 0) {

                html += `
                    <h4>Items</h4>
                    <ul>
                `;

                data.detalles.forEach(item => {

                    html += `
                        <li>
                            ${item.nombre_plato ?? item.nombre_entrada}
                            x ${item.cantidad}
                        </li>
                    `;

                });

                html += "</ul>";
            }

            if (data.extras.length > 0) {

                html += `
                    <h4>Entradas Extra</h4>
                    <ul>
                `;

                data.extras.forEach(extra => {

                    html += `
                        <li>
                            ${extra.nombre_entrada}
                            x ${extra.cantidad}
                        </li>
                    `;

                });

                html += "</ul>";
            }

            document.getElementById("detallePedidoActual")
                .innerHTML = html;

            abrirModalEditar();

        })
        .catch(error => {

            console.error(error);

            Swal.fire({
                icon: "error",
                title: "Error",
                text: "No se pudo obtener el pedido."
            });

        });

}


// ==================================================
// PAGAR PEDIDO
// ==================================================
function pagarPedidoMesa(numeroMesa) {

    fetch(
        `${BASE_URL}/pedidos/detalle_mesa?numero_mesa=${numeroMesa}`
    )
        .then(response => response.json())
        .then(data => {

            if (!data.ok) {

                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: data.mensaje
                });

                return;
            }

            abrirModalPago(
                data.pedido.id_pedido,
                data.pedido.total
            );

        })
        .catch(error => {

            console.error(error);

            Swal.fire({
                icon: "error",
                title: "Error",
                text: "No se pudo obtener el pedido."
            });

        });

}