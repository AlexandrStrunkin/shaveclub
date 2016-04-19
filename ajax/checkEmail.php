<?require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");?>
<?
    if (strlen($_POST["email"])>5) {
        $userCheck = CUser::GetList(($by="id"), ($order="asc"), array("=EMAIL"=>$_POST["email"]))->Fetch();
        if ($userCheck["ID"] > 0) {   
            echo "Y";
        }
        else {
            echo "N";
        }

    }
    else {
        echo "N";
    }
?>