
function addNewMsg(id_chat){

    let msg = document.getElementById("messageAJAX").value;

    $.ajax({
        url: 'addNewMsg.php',
        type: 'POST',
        data: {
            id_chat: id_chat,
            msg : msg,
        },

        success: function (e) {
            document.getElementById("messageAJAX").value="";
            document.getElementById("messageAJAX").focus();
        },

        error: function (e) {
            console.log("Ha habido un fallo. ¿Que has tocado ya? >.<");
        }

    });
}