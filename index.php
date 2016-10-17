<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Ïðîñòî ÊËÓÁ ÁÐÈÒÂ");
$APPLICATION->SetTitle("Ïðîñòî ÊËÓÁ ÁÐÈÒÂ");
$lineCount = (COption::GetOptionString("main", "wizard_template_id", "eshop_adapt_horizontal", SITE_ID) == "eshop_adapt_vertical" ? "3" : "4");

?>
<?$arGroups = $USER->GetUserGroupArray();
if (in_array(11, $arGroups ))
$APPLICATION->ShowPanel = true; ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
