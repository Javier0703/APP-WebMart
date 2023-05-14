window.addEventListener("DOMContentLoaded",function (e){

    let address= document.querySelector(".map>div:first-child>p").textContent;
    let x= 0;
    let y = 0;
    let z = 5;

    if (address === 'Sin dirección'){
        x= 40.4149893;
        y = -3.7059115;

        let map = L.map('map').setView([x, y], z);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> Contributors'
        }).addTo(map);
        L.marker([x, y]).addTo(map);

    }

    else{
        let url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&limit=1`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                x = data[0].lat;
                y = data[0].lon;
                z=14;

                let map = L.map('map').setView([x, y], z);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> Contributors'
                }).addTo(map);
                L.marker([x, y]).addTo(map);

            })
            .catch(error => console.error(error));
    }

    let lis=document.querySelectorAll(".sUserP2 li");
    let secs = document.querySelectorAll(".sUserP2>section");

    lis.forEach((li, array) =>{
        li.addEventListener("click",function (e){
            secs.forEach(sec =>{
                sec.classList.remove("active");
            });

            lis.forEach(li =>{
                li.classList.remove("active");
            });

            lis[array].classList.add("active");
            secs[array].classList.add("active");
        });
    });
    
});
