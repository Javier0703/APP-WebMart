window.addEventListener("DOMContentLoaded",function (e){
    let profileIcon = document.getElementById("profileIcon");
    let profile = document.getElementById("profile");
    let main = document.querySelector("main");
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
    main.addEventListener("click",function (e){
        profileIcon.lastElementChild.textContent="expand_more";
        profile.style.display="none";
    });
});

