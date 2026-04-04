document.addEventListener('DOMContentLoaded', function () {
    var sidebarToggle = document.getElementById('sidebarToggle');

    if (!sidebarToggle) {
        return;
    }

    sidebarToggle.addEventListener('click', function (event) {
        event.preventDefault();

        if (window.innerWidth <= 992) {
            document.body.classList.toggle('sidebar-open');
            return;
        }

        document.body.classList.toggle('sidebar-hidden');
    });

    document.addEventListener('click', function (event) {
        if (window.innerWidth > 992 || !document.body.classList.contains('sidebar-open')) {
            return;
        }

        var sidebar = document.getElementById('sidebar');
        var clickedInSidebar = sidebar && sidebar.contains(event.target);
        var clickedToggle = sidebarToggle.contains(event.target);

        if (!clickedInSidebar && !clickedToggle) {
            document.body.classList.remove('sidebar-open');
        }
    });
});
