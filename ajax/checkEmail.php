<?require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");?>
<?
    $_SESSION["FIO"] = $_POST["name"];
    $_SESSION["PHONE"] = $_POST["phone"];
    $_SESSION["ADDRESS"] = $_POST["address"];
    $_SESSION["COMMENT"] = $_POST["comment"];
    if(strlen($_POST["email"]) > 5) {
        $userCheck = CUser::GetList(($by = "id"), ($order = "asc"), array("=EMAIL" => $_POST["email"])) -> Fetch();
        if($userCheck["ID"] > 0) {
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