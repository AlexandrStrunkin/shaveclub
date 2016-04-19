<?
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
    $APPLICATION->SetTitle("FAQ");
?>   
<?$APPLICATION->IncludeComponent(
	"bitrix:support.faq.section.list", 
	"faq", 
	array(
		"IBLOCK_TYPE" => "content",
		"IBLOCK_ID" => "25",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_NOTES" => "",
		"CACHE_GROUPS" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"SECTION" => "-",
		"EXPAND_LIST" => "N",
		"SECTION_URL" => "#SECTION_ID#"
	),
	false
);?>