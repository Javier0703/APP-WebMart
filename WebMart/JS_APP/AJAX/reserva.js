
function reservarProd(id_prod,id_usu){

    $.ajax({
        url: 'reqReserv.php',
        type: 'POST',
        data: {
            id_prod: id_prod,
            id_usu: id_usu
        },

        success: function (e) {

            let reservar = document.getElementById("bReservar");
            reservar.disabled= true;
            reservar.firstElementChild.lastElementChild.textContent="Reserva enviada";
        },

        error: function (e) {
            console.log("Ha habido un fallo. ¿Que has tocado ya? >.<");
        }

    });
}


function addFav(id_prod,id_usu){
    $.ajax({
        url: 'reqFavorito.php',
        type: 'POST',
        data: {
            id_prod: id_prod,
            id_usu: id_usu
        },

        success: function (e) {
            let span= document.getElementById("spanFavourite");
            span.classList.toggle("fav");
        },

        error: function (e) {
            console.log("Ha habido un fallo. ¿Que has tocado ya? >.<");
        }

    });
}


function removeReserve(id_prod){
    $.ajax({
        url: 'removeReserve.php',
        type: 'POST',
        data: {
            id_prod: id_prod
        },

        success: function (e) {
           let aside = document.getElementById("asideRemove");
           aside.parentElement.style.display="none";
        },

        error: function (e) {
            console.log("Ha habido un fallo. ¿Que has tocado ya? >.<");
        }

    });
}
