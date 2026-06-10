document.addEventListener("DOMContentLoaded", () => {
    // Hamburger / Sidebar toggle (móvil) 
    const hamburger = document.querySelector(".hamburger");
    const sidebar   = document.querySelector(".sidebar");
    const overlay   = document.querySelector(".overlay");

    function openSidebar() {
        sidebar.classList.add("open");
        overlay.classList.add("show");
    }

    function closeSidebar() {
        sidebar.classList.remove("open");
        overlay.classList.remove("show");
    }

    hamburger.addEventListener("click", openSidebar);
    overlay.addEventListener("click", closeSidebar);

    // Cerrar sesión
    const btnLogout = document.getElementById("btn-logout");

    // Eliminar listeners anteriores reemplazando con onclick
    btnLogout.onclick = (e) => {
        e.preventDefault();
        if (confirm("¿Seguro que deseas cerrar sesión?")) {
            window.location.href = e.currentTarget.href;
        }
    };

});