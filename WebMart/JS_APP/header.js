window.addEventListener("DOMContentLoaded",function (e){

    let profileIcon = document.getElementById("profileIcon");
    let profile = document.getElementById("profile");

    let nav = document.querySelector("header>nav>section>section");
    let fDesplig = document.getElementById("fDesplig");

    let main = document.querySelector("main");

    profileIcon.addEventListener("click",function (e){
        nav.classList.remove("show");

        if (profileIcon.lastElementChild.textContent==="expand_more"){
        profileIcon.lastElementChild.textContent="expand_less";
        profile.style.display="block";
        }

        else{
            profileIcon.lastElementChild.textContent="expand_more";
            profile.style.display="none";
        }

    });

    main.addEventListener("click",function (e){
        profileIcon.lastElementChild.textContent="expand_more";
        profile.style.display="none";
        nav.classList.remove("show");
    });

    fDesplig.addEventListener("click", function (e){

        nav.classList.toggle("show");

        profileIcon.lastElementChild.textContent="expand_more";
        profile.style.display="none";
    });

});

