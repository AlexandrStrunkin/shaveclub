<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Политика конфиденциальности");
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:support.faq.element.list", 
	"about", 
	array(
		"IBLOCK_TYPE" => "content",
		"IBLOCK_ID" => "30",
		"SHOW_RATING" => "N",
		"RATING_TYPE" => "",
		"PATH_TO_USER" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_NOTES" => "",
		"CACHE_GROUPS" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"SECTION_ID" => "97"
	),
	false
);?>