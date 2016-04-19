<!--////////////////////////////////////����� �����������, �����������//////////////////////////// -->
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
                        $("#auth_form .form_error").css("display","block").html("�������� ����� ��� ������!");
                    }                          
                })
                break;

            case "reg":
                $("#auth_form .form_error").html("");
                var email = $("#reg_email").val();
                var pass = $("#reg_password").val();
                var pass_confirm = $("#reg_password_confirm").val();

                //������� ��� ��������� email
                var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i; 

                //�������� ���������� ���� �����
                if (email == "" || pass == "" || pass_confirm == "") {
                    $("#reg_form .form_error").css("display","block");  
                    $("#reg_form .form_error").html("��������� ��� ����!"); 

                    return false;
                }

                //��������� email

                else if (!pattern.test(email)) {
                    $("#reg_form .form_error").css("display","block");  
                    $("#reg_form .form_error").html("�������� email!"); 
                    return false;
                }          

                //������� ������
                else if (pass != pass_confirm) {
                    $("#reg_form .form_error").css("display","block");  
                    $("#reg_form .form_error").html("������ �� ���������!");  
                }

                //���������� �� �����������
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
    <div class="form_title">�����������</div>
    <div class="form_title_separator"></div>

    <input type="text" class="form_input" id="auth_email" placeholder="Email" rel="Email">
    <input type="password" class="form_input" id="auth_password" placeholder="������" rel="������">
    
    <div class="form_text"><a id="reg_form_link" href="/auth/?forgot_password=yes">������������ ������</a></div>

    <button type="button" class="form_button" onclick="personalFormSubmit('auth')">�����</button>

    <div class="form_error">�������� email ��� ������!</div>

    <div class="form_line form_line_left"></div>
    <div class="form_line form_line_right"></div>
    <div class="form_bottom_text">������� �� �����? <a id="reg_form_link" href="#reg_form">�����������������!</a></div>
</div>       


<div id="reg_form">

    <div class="form_title">�����������</div>
    <div class="form_title_separator"></div>

    <input type="text" class="form_input" id="reg_email" placeholder="Email" rel="Email">
    <input type="password" class="form_input" id="reg_password" placeholder="������" rel="������">
    <input type="password" class="form_input" id="reg_password_confirm" placeholder="��������� ������" rel="��������� ������">

    <button type="button" class="form_button" onclick="personalFormSubmit('reg')">���������</button>

    <div class="form_error"></div>

    <div class="form_line form_line_left"></div>
    <div class="form_line form_line_right"></div>
    <div class="form_bottom_text">��� ��������� ��������? <a id="auth_form_link" href="#auth_form">�������������!</a></div>      

</div>

<div id="error_message">

    <div class="form_title">������</div>
    <div class="form_title_separator"></div>
    <div class="form_text">��������, ����� ���������� �� <font style="color:#D6911C">��������������� </font>� ����� ����. ����������, ���������� <br> � �������������� ��� ��������� ��������� ����������.</div>
    <div class="form_error"></div>
    <a href="/contacts/" class="close_button">��������</a>
    <!--<div class="form_line form_line_left"></div>
    <div class="form_line form_line_right"></div>-->

</div>




<!--////////////////////////////////////����� �����������, �����������//////////////////////////// -->