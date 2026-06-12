document.addEventListener('DOMContentLoaded', function () {

    const dropbtns = document.querySelectorAll('.sidebar .dropbtn');

    dropbtns.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const dropdown = this.closest('.dropdown');
            dropdown.classList.toggle('show');
        });
    });

});
