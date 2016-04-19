<?require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");?>
<?
    if ($_POST["email"] && $_POST["pass"]) {
        switch ($_POST["form"]) {
            case "auth":
                //авторизация пользователя
                if (!is_object($USER)) $USER = new CUser;
                $res = $USER->Login($_POST["email"],$_POST["pass"],'N','Y');
                if (!is_array($res)) {
                    echo "OK";
                }        
                break;

                //////////////////////
                         
                //регистрация пользователя
            case "reg":
                //проверяем email на существование
               // $userCheck = CUser::GetList(($by="id"), ($order="asc"), array("EMAIL"=>$_POST["email"]))->Fetch();
             //   if ($userCheck["ID"] > 0) {echo "Пользователь с данным email-адресом уже зарегистрирован!";} else {
                    $USER = new CUser;
                    $userData = array(
                        "LOGIN" => $_POST["email"],
                        "EMAIL" => $_POST["email"],
                        "PASSWORD" => $_POST["pass"],
                        "CONFIRM_PASSWORD" => $_POST["pass"],
                        "ACTIVE" => "Y",
                        "GROUP_ID" => array(3,4,5) 
                    );
                    $res = $USER->Add($userData);
                    if ($res > 0) {
                        $USER->Login($_POST["email"],$_POST["pass"],'N','Y');
                        echo "OK";
                    } 
                    else {
                        echo $USER->LAST_ERROR; 
                    }

              //  }

                break;

        }

    }

?>