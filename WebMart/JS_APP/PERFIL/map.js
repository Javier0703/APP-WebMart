window.addEventListener("DOMContentLoaded",function (e){

    let address = document.getElementById('direccion').value;
    let x= 0;
    let y = 0;
    let z = 6;

    if (address.length===0){
        x= 40.4149893;
        y = -3.7059115;
    }

    else{
        let url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&limit=1`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                x = data[0].lat;
                y = data[0].lon;
                z=14;

                map.setView([x, y], z);
                var nuevoMaker = L.latLng(x, y);
                marcador.setLatLng(nuevoMaker)

            })
        .catch(error => console.error(error));
    }

    document.getElementById("comprobar").addEventListener("click", function (e){
        e.preventDefault();

        let address = document.getElementById('direccion').value;
        let url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&limit=1`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                x = data[0].lat;
                y = data[0].lon;
                z = 14;

                map.setView([x, y], z);
                var nuevoMaker = L.latLng(x, y);
                marcador.setLatLng(nuevoMaker)

            })

            .catch(error => console.error(error));
    });

    var map = L.map('map').setView([x, y], z);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> Contributors'
    }).addTo(map);
    var marcador = L.marker([x, y]).addTo(map);



    //Textarea
    let textarea = document.querySelector(".sAPerfil2 textarea");
    let span = document.querySelector(".sAPerfil2>div span");

    span.textContent=textarea.value.length;

    textarea.addEventListener("input", function (e){
        span.textContent=textarea.value.length;
    });

    let correo = document.getElementById("correo");
    let guardar = document.getElementById("guardar");
    let error = document.getElementById("error");

    guardar.addEventListener("click",function (e){
        if(correo.value.length > 0){
            let email= /^(?=.{1,40}$)[\wñÑ-]+(\.[\wñÑ-]+)*@[\wñÑ-]+(\.[\wñÑ-]{2,})+$/;
            if (!email.test(correo.value)){
                e.preventDefault();
                error.textContent="No tiene el formato de correo";
                correo.classList.add("colorRed");
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        }
    });

    correo.parentElement.parentElement.addEventListener("click",function (e){
        error.textContent="";
        correo.classList.remove("colorRed");
    });

});
