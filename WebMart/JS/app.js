window.addEventListener("DOMContentLoaded",function (e){

    //header
    let profileIcon = document.getElementById("profileIcon");
    let profile = document.getElementById("profile");
    profileIcon.addEventListener("click",function (e){
        if (profileIcon.lastElementChild.textContent==="expand_more"){
        profileIcon.lastElementChild.textContent="expand_less";
        profile.style.display="block";
        }

        else{
            profileIcon.lastElementChild.textContent="expand_more";
            profile.style.display="none";
        }
    });

    //Section 2 Popup
    let grid2 = document.querySelector(".sIndex2>form>.grid2");
    let popUp = document.querySelector(".sIndex2>form>.popUp");
    grid2.addEventListener("click", function (e){
        popUp.style.display="grid";
    });

    popUp.addEventListener("click",function (e){
        popUp.style.display="none";
    });

    popUp.firstElementChild.firstElementChild.lastElementChild.addEventListener("click",function (e){
        popUp.style.display="none";
    });

    popUp.firstElementChild.addEventListener("click",function (e){
        e.stopPropagation();
    });

    let divsCat = document.querySelectorAll(".sIndex2>form>section>div>section>div");
    divsCat.forEach(divCat =>{
        divCat.addEventListener("click",function (e){
            divsCat.forEach(div =>{
                div.style.display="none";
            divCat.nextElementSibling.style.display="block";
            });
        });
    });

    let indexHidden = document.getElementById("indexHidden");
    let divsSub = document.querySelectorAll(".sIndex2>form>section>div>section>aside>div");
    let indexPtitle = document.getElementById("indexPtitle");
    divsSub.forEach(divSub =>{
        divSub.addEventListener("click",function (e){
            let id=divSub.id;
            if (divSub.classList.contains("typeAtr")){
                divSub.parentElement.style.display="none";
                divsCat.forEach(div =>{
                    div.style.display="flex";
                });
            }

            else{
                let updateDisplay = (id, name) => {
                    indexHidden.value = id;
                    indexHidden.name = name;
                    indexPtitle.textContent = divSub.firstElementChild.textContent;
                    divSub.parentElement.style.display = "none";
                    divsCat.forEach(div => {
                        div.style.display = "flex";
                    });
                    popUp.style.display = "none";
                };

                if (divSub.classList.contains("typeCat")) {
                    updateDisplay(id, "id_cat");
                } else {
                    updateDisplay(id, "id_sub");
                }
            }
        });
    });


});

