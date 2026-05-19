document.addEventListener("DOMContentLoaded", () => {
    const hamburger = document.querySelector(".hamburger");
    const sidebar   = document.querySelector(".sidebar");
    const overlay   = document.querySelector(".overlay");

    function openSidebar()  { sidebar.classList.add("open");    overlay.classList.add("show"); }
    function closeSidebar() { sidebar.classList.remove("open"); overlay.classList.remove("show"); }

    hamburger.addEventListener("click", openSidebar);
    overlay.addEventListener("click", closeSidebar);

    // ✅ Intercepta el botón "atrás" del navegador
    history.pushState(null, "", location.href);
    window.addEventListener("popstate", function () {
        const salir = confirm("¿Deseas cerrar sesión y salir del dashboard?");
        if (salir) {
            window.location.href = BASE_URL + "/logout";
        } else {
            history.pushState(null, "", location.href);
        }
    });

    // Cerrar sesión con botón
    document.getElementById("btn-logout").addEventListener("click", function (e) {
        e.preventDefault();
        if (confirm("¿Seguro que deseas cerrar sesión?")) {
            window.location.href = BASE_URL + "/logout";
        }
    });
});