<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props_format.php");

$style = (is_array($arResult["ORDER_PROP"]["RELATED"]) && count($arResult["ORDER_PROP"]["RELATED"])) ? "" : "display:none";
?>
<div class="" style="<?= $style ?>">
	<?= PrintPropsForm($arResult["ORDER_PROP"]["RELATED"], $arParams["TEMPLATE_LOCATION"])?>
</div>