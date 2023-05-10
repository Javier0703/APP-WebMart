window.addEventListener("DOMContentLoaded",function (e){
    let lis=document.querySelectorAll(".mPerfil #a2 .s2Prod li");
    let sections = document.querySelectorAll(".mPerfil #a2 .s3Prod>section");

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