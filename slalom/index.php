<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Просто клуб бритв - Бритвы Slalom");
$lineCount = (COption::GetOptionString("main", "wizard_template_id", "eshop_adapt_horizontal", SITE_ID) == "eshop_adapt_vertical" ? "3" : "4");
?>    
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>