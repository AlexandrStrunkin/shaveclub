<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$APPLICATION->SetTitle(GetMessage("CMP_TITLE"));?>


<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.search",
	"main",
	Array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
			"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
			"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
			"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
			"PROPERTY_CODE" => $arParams["SEARCH_PROPERTY_CODE"],
			"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
			"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
			"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
			"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
			"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
			"OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
			"SECTION_URL" => $arParams["SECTION_URL"],
			"DETAIL_URL" => $arParams["DETAIL_URL"],
			"BASKET_URL" => $arParams["BASKET_URL"],
			"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
			"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
			"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
			"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
			"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"USE_COMPARE" => $arParams["USE_COMPARE"],
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
			"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
			"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
			"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
			"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
			"CURRENCY_ID" => $arParams["CURRENCY_ID"],
			"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
			"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
			"PAGER_TITLE" => $arParams["PAGER_TITLE"],
			"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
			"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
			"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
			"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
			"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
			"FILTER_NAME" => "searchFilter",
			"SECTION_ID" => "",
			"SECTION_CODE" => "",
			"SECTION_USER_FIELDS" => array(),
			"INCLUDE_SUBSECTIONS" => "Y",
			"SHOW_ALL_WO_SECTION" => "Y",
			"META_KEYWORDS" => "",
			"META_DESCRIPTION" => "",
			"BROWSER_TITLE" => "",
			"ADD_SECTIONS_CHAIN" => "N",
			"SET_TITLE" => "N",
			"SET_STATUS_404" => "N",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "N",
			"RESTART" => "N",
			"NO_WORD_LOGIC" => "Y",
			"USE_LANGUAGE_GUESS" => "Y",
			"CHECK_DATES" => "Y",
			"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
			"CURRENCY_ID" => $arParams["CURRENCY_ID"],
			"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
			"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
			"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
			"SHOW_HINTS" => $arParams["SHOW_HINTS"],
	),
	$component
);
?>
<?	
	$rsBasket = CSaleBasket::GetList( array( "NAME" => "ASC", "ID" => "ASC" ), array( "FUSER_ID" => CSaleBasket::GetBasketUserID(), 'LID' => SITE_ID , 'ORDER_ID' => 'NULL'), false, false, array("ID", "PRODUCT_ID", "DELAY") );
	while( $arBasket = $rsBasket->GetNext() )
	{
		if( $arBasket["DELAY"] == "Y" ){$delay_items[] = $arBasket["PRODUCT_ID"];}
		else{$basket_items[] = $arBasket["PRODUCT_ID"];}
	}
	global $compare_items;
?>

<script>
	$(document).ready(function()
	{
		<?if (is_array($delay_items) && !empty($delay_items)):?>
			<?foreach( $delay_items as $item_id ){?>
				$('a.wish_item[href^=#<?=$item_id?>]').addClass('active');
			<?}?>
		<?endif;?>
		$('a.in_basket').hide();
		<?if (is_array($basket_items) && !empty($basket_items)):?>
			<?foreach( $basket_items as $item_id ){?>
				$('a.add_item[element_id^=#<?=$item_id?>]').addClass("added").removeAttr("onclick").attr("href", "<?=SITE_DIR?>basket/").find("span").text("<?=GetMessage('CATALOG_IN_CART');?>");
			<?}?>
		<?endif;?>
		<?if (is_array($compare_items) && !empty($compare_items)):?>
			<?foreach( $compare_items as $item_id ){?>
				$('a.compare_item[element_id^=#<?=$item_id?>]').addClass('active');
			<?}?>
		<?endif;?>
	})
</script>