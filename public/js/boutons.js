$(document).ready(function () {

    $('.btnRegister').on('click', function () {
        let entityhref = $(this).attr('data-href');
        let entityId = $(this).attr('data-id');
        $('.btnOk').attr('href', entityhref);
        $('.btnOk').attr('data-href', entityhref);
        $('.btnOk').attr('data-id', entityId);
        console.log(entityhref);
        console.log(entityId);

    });

    $(".btnOk").click(function () {
        let entityhref = $(this).attr('data-href');
        let entityId = $(this).attr('data-id');
        $.ajax({
            url: entityhref,
            type: 'POST',
            data: {
                "idSortie": entityId.attr(),
            }
        })
            .done(function () {
            })
            .fail(() => alert("Une erreur s'est produite lors de l'inscription"))
    });

    $('.btnUnregister').on('click', function () {
        let entityhref = $(this).attr('data-href');
        let entityId = $(this).attr('data-id');
        $('.btnDesist').attr('href', entityhref);
        $('.btnDesist').attr('data-href', entityhref);
        $('.btnDesist').attr('data-id', entityId);
        console.log(entityhref);
        console.log(entityId);

    });

    $(".btnDesist").click(function () {
        let entityhref = $(this).attr('data-href');
        let entityId = $(this).attr('data-id');
        $.ajax({
            url: entityhref,
            type: 'POST',
            data: {
                "idSortie": entityId.attr(),
            }
        })
            .done(function () {
            })
            .fail(() => alert("Une erreur s'est produite lors du désistement"))
    });
    $('.btnPublish').on('click', function () {
        let entityhref = $(this).attr('data-href');
        let entityId = $(this).attr('data-id');
        $('.btnconfirmpublish').attr('href', entityhref);
        $('.btnconfirmpublish').attr('data-href', entityhref);
        $('.btnconfirmpublish').attr('data-id', entityId);
        console.log(entityhref);
        console.log(entityId);

    });

    $(".btnconfirmpublish").click(function () {
        let entityhref = $(this).attr('data-href');
        let entityId = $(this).attr('data-id');
        $.ajax({
            url: entityhref,
            type: 'POST',
            data: {
                "idSortie": entityId.attr(),
            }
        })
            .done(function () {
            })
            .fail(() => alert("Une erreur s'est produite lors de la publication"))
    });
    $('.btnCancel').on('click', function () {
        let entityhref = $(this).attr('data-href');
        let entityId = $(this).attr('data-id');
        $('.btnconfirmCancel').attr('href', entityhref);
        $('.btnconfirmCancel').attr('data-href', entityhref);
        $('.btnconfirmCancel').attr('data-id', entityId);
        console.log(entityhref);
        console.log(entityId);

    });

    $(".btnconfirmCancel").click(function () {
        let entityhref = $(this).attr('data-href');
        let entityId = $(this).attr('data-id');
        $.ajax({
            url: entityhref,
            type: 'POST',
            data: {
                "idSortie": entityId.attr(),
            }
        })
            .done(function () {
            })
            .fail(() => alert("Une erreur s'est produite lors de la publication"))
    });
});