document.addEventListener("DOMContentLoaded",function (ev){

    let titleEmail = document.getElementById("titleEmail");
    let descEmail = document.getElementById("descEmail");

    titleEmail.addEventListener("focus",function (e){
        titleEmail.classList.remove("elementNull");
    })

    descEmail.addEventListener("focus",function (e){
        descEmail.classList.remove("elementNull");
    })

    let generateEmail = document.getElementById("generateEmail");

    generateEmail.addEventListener("click",function (e){

        let titleEmail = document.getElementById("titleEmail");
        let descEmail = document.getElementById("descEmail");

        if (titleEmail.value.trim().length === 0 || descEmail.value.trim().length === 0){
            if (titleEmail.value.trim().length === 0){
                titleEmail.classList.add("elementNull");
            }

            if (descEmail.value.trim().length === 0){
                descEmail.classList.add("elementNull");
            }
        }

        else{
            let email = 'appwebmart@gmail.com';
            let subject = titleEmail.value;
            let body = descEmail.value;

            console.log(subject);
            console.log(body);

            let mailGenerator = 'mailto:' + email + '?subject=' + encodeURIComponent(subject) + '&body=' + encodeURIComponent(body);
            window.location.href = mailGenerator;
            titleEmail.value="";
            descEmail.value="";
        }

    });


});