
function reserUsu(id_prod,id_usu,target){

    $.ajax({
        url: 'reserv.php',
        type: 'POST',
        data: {
            id_prod: id_prod,
            id_usu: id_usu
        },

        success: function (e) {
            document.getElementById(target).style.display="none";
        },

        error: function (e) {
            console.log("Ha habido un fallo. ¿Que has tocado ya? >.<");
        }

    });
}