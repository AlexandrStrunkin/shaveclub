<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
global $APPLICATION;
if (isset($templateData['TEMPLATE_THEME']))
{
	$APPLICATION->SetAdditionalCSS($templateData['TEMPLATE_THEME']);
}
CJSCore::Init(array("popup"));

?>
<?
if(CModule::IncludeModule("sale")){
	$dbBasketItems = CSaleBasket::GetList(array("NAME" => "ASC", "ID" => "ASC"), array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"), false, false, array("ID", "PRODUCT_ID", "DELAY", "SUBSCRIBE", "CAN_BUY"));
	$basket_items = array();
	$delay_items = array();
	$subscribe_items = array();
	$compare_items = array();
	while($arBasketItems = $dbBasketItems->GetNext()){			
		if($arBasketItems["DELAY"]=="N" && $arBasketItems["CAN_BUY"] == "Y" && $arBasketItems["SUBSCRIBE"] == "N"){
			$basket_items[] = $arBasketItems["PRODUCT_ID"];
		} 
		elseif($arBasketItems["DELAY"]=="Y" && $arBasketItems["CAN_BUY"] == "Y" && $arBasketItems["SUBSCRIBE"] == "N"){
			$delay_items[] = $arBasketItems["PRODUCT_ID"];
		}
		elseif($arBasketItems["SUBSCRIBE"]=="Y"){
			$subscribe_items[] = $arBasketItems["PRODUCT_ID"];
		}
	}	
}
$arCompare=array_keys($_SESSION[$arParams["COMPARE_NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"]);
?>
<script type="text/javascript">
	<?if(is_array($basket_items) && !empty($basket_items)):?>
		<?foreach( $basket_items as $item ){?>
			$('.basket_button.to-cart[data-item=<?=$item?>]').hide();
			$('.basket_button.in-cart[data-item=<?=$item?>]').show();
		<?}?>
	<?endif;?>
	<?if(is_array($delay_items) && !empty($delay_items)):?>
		<?foreach( $delay_items as $item ){?>
			$('.wish_item[data-item=<?=$item?>]').addClass("added");
			if ($('.wish_item[data-item=<?=$item?>]').find(".value.added").length) { $('.wish_item[data-item=<?=$item?>]').find(".value").hide(); $('.wish_item[data-item=<?=$item?>]').find(".value.added").show(); }
		<?}?>
	<?endif;?>
	<?if(is_array($subscribe_items) && !empty($subscribe_items)):?>
		<?foreach( $subscribe_items as $item ){?>
			$('.basket_button.to-subscribe[data-item=<?=$item?>]').hide();
			$('.basket_button.in-subscribe[data-item=<?=$item?>]').show();
		<?}?>
	<?endif;?>
	<?if(is_array($arCompare) && !empty($arCompare)):?>
		<?foreach( $arCompare as $item ){?>
			$('.compare_item[data-item=<?=$item?>]').addClass("added");
			if ($('.compare_item[data-item=<?=$item?>]').find(".value.added").length) { $('.compare_item[data-item=<?=$item?>]').find(".value").hide(); $('.compare_item[data-item=<?=$item?>]').find(".value.added").show(); }
		<?}?>
	<?endif;?>
</script>