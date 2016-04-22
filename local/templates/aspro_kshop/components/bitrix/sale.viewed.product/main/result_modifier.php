<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
foreach($arResult as $key => $arItem){
	foreach($arItem["OFFERS"] as $arOffer){		
		foreach($arOffer["PRICES"] as $code=>$arPrice)
		{
			if($arPrice["CAN_ACCESS"])
			{
				if ($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"])
				{
					$minOfferPrice = $arPrice["DISCOUNT_VALUE"];
					$minOfferPriceFormat = $arPrice["PRINT_DISCOUNT_VALUE"];
				}
				else
				{
					$minOfferPrice = $arPrice["VALUE"];
					$minOfferPriceFormat = $arPrice["PRINT_VALUE"];
				}

				if ($minItemPrice > 0 && $minOfferPrice < $minItemPrice)
				{
					$minItemPrice = $minOfferPrice;
					$minItemPriceFormat = $minOfferPriceFormat;
				}
				elseif ($minItemPrice == 0)
				{
					$minItemPrice = $minOfferPrice;
					$minItemPriceFormat = $minOfferPriceFormat;
				}
			}
		}
		
		$arResult[$key]["MIN_PRODUCT_OFFER_PRICE"] = $minItemPrice;
		$arResult[$key]["MIN_PRODUCT_OFFER_PRICE_PRINT"] = $minItemPriceFormat;
	}
}

$arElementsID = array();
foreach($arResult as $key => $val){
	$arElementsID[] = $val["PRODUCT_ID"];
	$img = "";
	if ($val["DETAIL_PICTURE"] > 0)
		$img = $val["DETAIL_PICTURE"];
	elseif ($val["PREVIEW_PICTURE"] > 0)
		$img = $val["PREVIEW_PICTURE"];

	$file = CFile::ResizeImageGet($img, array('width'=>$arParams["VIEWED_IMG_WIDTH"], 'height'=>$arParams["VIEWED_IMG_HEIGHT"]), BX_RESIZE_IMAGE_PROPORTIONAL, true);

	$val["PICTURE"] = $file;
	$arResult[$key] = $val;
}


$db_res = CIBlockElement::GetList(Array("SORT"=>"ASC"),  Array("ID"=>$arElementsID), false, false, Array("ID", "DETAIL_PAGE_URL"));
while($arElement = $db_res->GetNext()){
	foreach($arResult as $key => $val){
		if ($arElement["ID"] == $val["PRODUCT_ID"]){
			$arResult[$key]["DETAIL_PAGE_URL"] = $arElement["DETAIL_PAGE_URL"];
			break;
		}
	}
}
?>