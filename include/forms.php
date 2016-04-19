<!--////////////////////////////////////ФОРМЫ АВТОРИЗАЦИИ, РЕГИСТРАЦИИ//////////////////////////// -->
<script>
    function personalFormSubmit(form) {

        $(".form_error").css("display","none");

        switch(form) {
            case "auth":  
                var email = $("#auth_email").val();
                var pass = $("#auth_password").val();

                $.post("/ajax/checkForm.php",{email:email,pass:pass,form:form},function(data){
                    if (data == "OK") {
                        document.location.reload();
                    }
                    else {
                        $("#auth_form .form_error").css("display","block").html("Неверный логин или пароль!");
                    }                          
                })
                break;

            case "reg":
                $("#auth_form .form_error").html("");
                var email = $("#reg_email").val();
                var pass = $("#reg_password").val();
                var pass_confirm = $("#reg_password_confirm").val();

                //паттерн для валидации email
                var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i; 

                //проверка заполнения всех полей
                if (email == "" || pass == "" || pass_confirm == "") {
                    $("#reg_form .form_error").css("display","block");  
                    $("#reg_form .form_error").html("Заполните все поля!"); 

                    return false;
                }

                //валидация email

                else if (!pattern.test(email)) {
                    $("#reg_form .form_error").css("display","block");  
                    $("#reg_form .form_error").html("Неверный email!"); 
                    return false;
                }          

                //сверяем пароли
                else if (pass != pass_confirm) {
                    $("#reg_form .form_error").css("display","block");  
                    $("#reg_form .form_error").html("Пароли не совпадают!");  
                }

                //отправляем на регистрацию
                else {
                    $.post("/ajax/checkForm.php",{email:email,pass:pass,form:form},function(data){
                        if (data == "OK") {
                            document.location.reload();
                        }
                        else {
                            $("#reg_form .form_error").css("display","block");
                            $("#reg_form .form_error").html(data);
                        }                          
                    })  
                }

                break;
        }
    }
    
    $(function(){
       $(".form_input").focus(function(){
               $(this).removeAttr("placeholder"); 
       });
        $(".form_input").blur(function(){
          if ($(this).val() == "") {
              $(this).attr("placeholder",$(this).attr("rel"));
          }  
        })
        
              
        
    })
</script>

<div id="auth_form">
    <div class="form_title">авторизация</div>
    <div class="form_title_separator"></div>

    <input type="text" class="form_input" id="auth_email" placeholder="Email" rel="Email">
    <input type="password" class="form_input" id="auth_password" placeholder="Пароль" rel="Пароль">
    
    <div class="form_text"><a id="reg_form_link" href="/auth/?forgot_password=yes">Восстановить пароль</a></div>

    <button type="button" class="form_button" onclick="personalFormSubmit('auth')">войти</button>

    <div class="form_error">Неверный email или пароль!</div>

    <div class="form_line form_line_left"></div>
    <div class="form_line form_line_right"></div>
    <div class="form_bottom_text">Впервые на сайте? <a id="reg_form_link" href="#reg_form">Зарегистрируйтесь!</a></div>
</div>       


<div id="reg_form">

    <div class="form_title">регистрация</div>
    <div class="form_title_separator"></div>

    <input type="text" class="form_input" id="reg_email" placeholder="Email" rel="Email">
    <input type="password" class="form_input" id="reg_password" placeholder="Пароль" rel="Пароль">
    <input type="password" class="form_input" id="reg_password_confirm" placeholder="Повторите пароль" rel="Повторите пароль">

    <button type="button" class="form_button" onclick="personalFormSubmit('reg')">отправить</button>

    <div class="form_error"></div>

    <div class="form_line form_line_left"></div>
    <div class="form_line form_line_right"></div>
    <div class="form_bottom_text">Уже являетесь клиентом? <a id="auth_form_link" href="#auth_form">Авторизуйтесь!</a></div>      

</div>

<div id="error_message">

    <div class="form_title">Ошибка</div>
    <div class="form_title_separator"></div>
    <div class="form_text">Извините, такой сертификат не <font style="color:#D6911C">зарегистрирован </font>в нашей базе. Пожалуйста, обратитесь <br> к администратору для выяснения возникших сложностей.</div>
    <div class="form_error"></div>
    <a href="/contacts/" class="close_button">контакты</a>
    <!--<div class="form_line form_line_left"></div>
    <div class="form_line form_line_right"></div>-->

</div>




<!--////////////////////////////////////ФОРМЫ АВТОРИЗАЦИИ, РЕГИСТРАЦИИ//////////////////////////// -->