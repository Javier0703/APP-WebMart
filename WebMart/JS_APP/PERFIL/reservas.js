
window.document.addEventListener("DOMContentLoaded",function (e){

    let lis=document.querySelectorAll(".sReservas .menus li");
    let sections = document.querySelectorAll(".sReservas .menus section");

    lis.forEach((li, array) =>{
        li.addEventListener("click",function (e){
            sections.forEach(section =>{
                section.classList.remove("active");
            });

            lis.forEach(li =>{
                li.classList.remove("active");
            });

            lis[array].classList.add("active");
            sections[array].classList.add("active");
        });
    });

});