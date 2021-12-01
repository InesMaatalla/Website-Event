$(document).ready(function () {
    $('#dtDynamicVerticalScrollExample').DataTable({
        "scrollY": "50vh",
        "scrollCollapse": true,
        "bPaginate": false,
        "bFilter": false,
        "bInfo": false,
    });
    $('.dataTables_length').addClass('bs-select');
});

