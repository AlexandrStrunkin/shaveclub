<?require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");?>

<?if($_POST["id"]):
$ID = intVal($_POST["id"]);
$arFields = array("CANCELED" => "Y");

CSaleOrder::Update($ID, $arFields);
echo "Y";

else:
echo "N";

endif;?>