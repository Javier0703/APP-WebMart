window.addEventListener("DOMContentLoaded",function (e){

    let ctx = document.getElementById('myChart');
    let nombres= document.querySelectorAll(".upProducts>.datos>p span:first-child");
    let productos= document.querySelectorAll(".upProducts>.datos>p span:last-child");


    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [ nombres[1].textContent, nombres[0].textContent, nombres[2].textContent],
            datasets: [{
                label: 'Productos',
                data: [productos[1].textContent, productos[0].textContent, productos[2].textContent],
                borderWidth: 0,
                backgroundColor: ['#2e3093', '#00adec', '#2e3093']
            }]
        },
        options: {
            responsive: true,
        }
    });

    let ctx2 = document.getElementById('myChart2');
    let nombres2= document.querySelectorAll(".buyProducts>.datos>p span:first-child");
    let compras= document.querySelectorAll(".buyProducts>.datos>p span:last-child");

    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: [ nombres2[1].textContent, nombres2[0].textContent, nombres2[2].textContent],
            datasets: [{
                label: 'Compras',
                data: [compras[1].textContent, compras[0].textContent, compras[2].textContent],
                borderWidth: 0,
                backgroundColor: ['#00adec', '#2e3093', '#00adec']
            }]
        },
        options: {
            responsive: true,
        }
    });

    let ctx3 = document.getElementById('myChart3');
    let personales = document.querySelectorAll("#datosPersolanes>span");

    let cent= 0;
    personales.forEach(p =>{
        p = parseInt(p.textContent);
        cent=cent+p;
    });


    //Hacer que si es 0 no muestre nada, el msg de haz algo y si da algo mostrar el contenido jeje
    console.log(cent);

    new Chart(ctx3, {
        type: 'pie',
        data: {
            labels: [ personales[0].textContent, personales[1].textContent, personales[2].textContent, personales[3].textContent,personales[4].textContent],
            datasets: [{
                data: [ personales[0].textContent, personales[1].textContent, personales[2].textContent, personales[3].textContent,personales[4].textContent],
                borderWidth: 0,
                backgroundColor: ['#2898ee', '#107acc', '#0cbccc', '#15297c', '#142157'],
                label: 'Cantidad'
            }]
        },
        options: {
            responsive: true,
        }
    });


});