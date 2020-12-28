require('./bootstrap');

$(document).ready(function(){

    $(".redirect").click(function(){
        let url = $(this).data("url");
        if(typeof url === "string" && url != ""){
            window.location.href = url;
        }
        console.log("error redirect " + url);
    });
});