<?
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    $arSend = array(
        "NAME" => htmlspecialchars($_POST['name']),
        "EMAIL" => htmlspecialchars($_POST['email']),
        "PHONE" => htmlspecialchars($_POST['phone']),
        "TEXT" => htmlspecialchars($_POST['text']),
    );
    
    CEvent::Send('ORDER',SITE_ID,$arSend);
}
?>