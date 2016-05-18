$(document).ready(function(){
    $("form#registraion-page-form").validate
    ({
        rules:{ emails: "email"}
    });
})