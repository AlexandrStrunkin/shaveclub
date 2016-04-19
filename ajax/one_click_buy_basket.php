<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?$APPLICATION->IncludeComponent("aspro:oneclickbuy", "shop", array(
	"USE_QUANTITY" => "N",
	"PROPERTIES" => array( 0 => "FIO", 1 => "PHONE", 2 => "COMMENT" ),
	"REQUIRED" => array( 0 => "FIO", 1 => "PHONE"),
	"DEFAULT_PERSON_TYPE" => "5",
	"DEFAULT_DELIVERY" => "3",
	"DEFAULT_PAYMENT" => "25",
	"DEFAULT_CURRENCY" => "RUB",
	"PRICE_ID" => "1",
	"USE_SKU" => "N",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000",
	"SEF_FOLDER" => "/catalog/",
	"BUY_ALL_BASKET" => "Y",
	),
	false
);?>
