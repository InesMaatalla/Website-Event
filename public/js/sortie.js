$(document).ready(function () {

    $('#sortie_search_form_defiltrer').click(function (){
        $('#sortie_search_form_campus').clean()
    })
    var $ville = $('#sortie_form_ville');
    console.log($ville)
    $ville.change(function () {
        $.ajax({
            url: "{{ path('sortie_liste_lieux') }}",
            method: "GET",
            dataType: "JSON",
            data: {
                "idVille": $ville.val(),
            }
        })
            .done(function (tablieux) {
                var lieuchoisie = $('#sortie_form_lieu');
                lieuchoisie.html('');
                lieuchoisie.append('<option value> Choisissez un lieu </option>');
                $.each(tablieux, function (key, lieu) {
                    lieuchoisie.append('<option value="' + lieu.id + '">' + lieu.nom + '</option>');
                    var cpchoisi = $('#sortie_form_code_postal');
                    cpchoisi.html('');
                    cpchoisi.val(lieu.cp);
                });

            })
            .fail(() => {
                alert("Une erreur s'est produite en chargeant les données")
            })
    });

    var $lieu = $('#sortie_form_lieu');
    $lieu.change(function () {
        $.ajax({
            url: "{{ path('sortie_lieu_details') }}",
            type: "GET",
            dataType: "JSON",
            data: {
                "lieuId": $lieu.val()
            }
        })
            .done(function (tabreponse) {
                $.each(tabreponse, function (key, valeur) {
                    var rueAffichee = $('#sortie_form_rue');
                    rueAffichee.html('');
                    rueAffichee.val(valeur.Rue);
                    var latitudeAffichee = $('#sortie_form_latitude');
                    latitudeAffichee.html('');
                    latitudeAffichee.val(valeur.xlatitude);
                    var longitudeAffichee = $('#sortie_form_longitude');
                    longitudeAffichee.html('');
                    longitudeAffichee.val(valeur.xlongitude);
                });
            })
            .fail(function () {
                alert("Une erreur s'est produite en chargeant les lieux");
            })
    });
    var nom = $('#sortie_form_nom');
    var dateHeureDebut = $('#sortie_form_dateHeureDebut');
    var duree = $('#sortie_form_duree');
    var dateLimite = $('#sortie_form_dateLimiteInscription');
    var participants = $('#sortie_form_nbInscriptionsMax');
    var descr = $('#sortie_form_infosSortie');
    var campus = $('#sortie_form_campus');
    var ville = $('#sortie_form_ville');
    var cp = $('#sortie_form_code_postal');
    $("#lieu_form_submit").click(function () {
        setSessionStorage();
    });
    ($("#sortie_form_publier")).click(function () {
        setSessionStorage();
    });
    ($("#sortie_form_enregistrer")).click(function () {
        setSessionStorage();
    });
    nom.val(sessionStorage.getItem('nom'));
    dateHeureDebut.val(sessionStorage.getItem('dateHeureDebut'));
    duree.val(sessionStorage.getItem('duree'));
    dateLimite.val(sessionStorage.getItem('dateLimite'));
    participants.val(sessionStorage.getItem('participants'));
    descr.val(sessionStorage.getItem('descr'));
    campus.val(sessionStorage.getItem('campus'));
    sessionStorage.clear();

    function setSessionStorage() {
        sessionStorage.setItem('nom', nom.val() );
        sessionStorage.setItem('dateHeureDebut', dateHeureDebut.val());
        sessionStorage.setItem('duree', duree.val());
        sessionStorage.setItem('dateLimite', dateLimite.val());
        sessionStorage.setItem('participants', participants.val());
        sessionStorage.setItem('descr', descr.val());
        sessionStorage.setItem('campus', campus.val());
    }

});