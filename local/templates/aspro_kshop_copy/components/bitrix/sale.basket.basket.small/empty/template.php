<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("catalog")) break;
$normalCount = $summ = $delayCount = $subscribeCount = $naCount = 0;

foreach($arResult["ITEMS"] as $arItem){
	if($arItem["DELAY"] == "Y"){
		++$delayCount;
	}
	elseif($arItem["SUBSCRIBE"] == "Y"){
		++$subscribeCount;
	}
	elseif($arItem["CAN_BUY"] == "Y"){
		++$normalCount;
		$summ += $arItem["PRICE"] * $arItem["QUANTITY"];
	}
	else{
		++$naCount;
	}
}

$cur = CCurrencyLang::GetCurrencyFormat(CCurrency::GetBaseCurrency());
echo json_encode(array("TOTAL_COUNT" => $normalCount, "TOTAL_SUMM" => $summ, "WISH_COUNT" => $delayCount, "SUBSCRIBE_COUNT" => $subscribeCount, "NOT_AVAILABLE_COUNT" => $naCount, "CURRENCY" => $cur["CURRENCY"]));
?>