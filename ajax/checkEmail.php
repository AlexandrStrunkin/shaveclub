<?require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");?>
<?
    $_SESSION["FIO"] = utf8win1251($_POST["name"]);
    $_SESSION["PHONE"] = $_POST["phone"];
    $_SESSION["ZIP"] = $_POST["zip"];
    $_SESSION["CITY"] = $_POST["city"];
    $_SESSION["ADDRESS"] = utf8win1251($_POST["address"]);
    $_SESSION["COMMENT"] = utf8win1251($_POST["comment"]);
    if(strlen($_POST["email"]) > 5) {
        $userCheck = CUser::GetList(($by = "id"), ($order = "asc"), array("=EMAIL" => $_POST["email"])) -> Fetch();
        if($userCheck["ID"] > 0) {
            echo "Y";
        } else {
            echo "N";
        }

    }

    else {
        echo "N";
    }

?>