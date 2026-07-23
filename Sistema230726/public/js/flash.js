document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('flash-notification');
    if (el) {
        Swal.fire({
            position: 'top-end',
            icon: el.dataset.icon,
            title: el.dataset.message,
            showConfirmButton: false,
            timer: 2000
        });
    }
});
