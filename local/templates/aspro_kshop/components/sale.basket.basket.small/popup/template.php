<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(!CModule::IncludeModule("iblock")||!CModule::IncludeModule("catalog")) break;
if(!function_exists('declOfNum')){
	function declOfNum($number, $titles){
		$cases = array (2, 0, 1, 1, 1, 2); 
		return sprintf($titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]], $number);
		}
	}

$count = $delayCount =  $summ = 0;
$arBasketIDs=array();
foreach($arResult["ITEMS"] as $arItem){ 
	if($arItem["CAN_BUY"] == 'Y'){ 
		if($arItem["DELAY"] == 'N'){
			++$count; 
			$summ += $arItem["~PRICE"]*$arItem["QUANTITY"]; 
			if(!CSaleBasketHelper::isSetItem($arItem))
				$arBasketIDs[$arItem["ID"]]=$arItem;
		}
		else{
			++$delayCount;
		}
	} 
}
if($arBasketIDs){
	$propsIterator = CSaleBasket::GetPropsList(
		array('BASKET_ID' => 'ASC', 'SORT' => 'ASC', 'ID' => 'ASC'),
		array('BASKET_ID' => array_keys($arBasketIDs))
	);
	while ($property = $propsIterator->GetNext()){
		$property['CODE'] = (string)$property['CODE'];
		if ($property['CODE'] == 'CATALOG.XML_ID' || $property['CODE'] == 'PRODUCT.XML_ID')
			continue;
		if (!isset($arBasketIDs[$property['BASKET_ID']]))
			continue;
		$arBasketIDs[$property['BASKET_ID']]['PROPS'][] = $property;
	}
}
unset($arResult["ITEMS"]);
$cur = CCurrencyLang::GetCurrencyFormat(CSaleLang::GetLangCurrency(SITE_ID));
$summ_formated = FormatCurrency($summ, $cur["CURRENCY"]);
$symb = substr($summ_formated, strrpos($summ_formated, ' '));
usort($arBasketIDs, 'CKShop::cmpByID');
?>
<?$frame = $this->createFrame()->begin('');?>
<div class="basket_normal load <?=(!$count ? 'empty_cart' : '')?>">
	<div class="card_popup_frame1">
		<div class="popup-intro"><div class="pop-up-title"><?=GetMessage("ADDED_TO_BASKET");?></div></div>
		<div class="popup-intro grey"><div class="pop-up-title"><?=GetMessage("BASKET_EMPTY_TITLE");?></div></div>
		<a class="close jqmClose"><i></i></a>
		<div class="basket_popup_wrapp">
			<table class="cart_shell" width="100%" border="0">
				<tbody>
					<?
					if($arParams["CACHE_TYPE"] != "N"){
						$cache = new CPHPCache();
						$cache_time = 100000;
						$cache_path = SITE_DIR.'kshop_basket/';
					}
					$i = 0;
					// foreach($arResult["ITEMS"] as $arItem){	
					foreach($arBasketIDs as $arItem){	
						if(($arItem["CAN_BUY"] == "Y") && ($arItem["DELAY"] == "N")){
							++$i;
							if($i > 3) break;
							$cache_id = 'aspro_basket_'.$arItem["PRODUCT_ID"];
							if($arParams["CACHE_TYPE"] != "N" && $cache_time > 0 && $cache->InitCache($cache_time, $cache_id, $cache_path)){ 
								$res = $cache->GetVars(); 
								$item = $res["item"]; 
							}
							else{
								if($objRes = CIBlockElement::GetList(array(), array("ID" => $arItem["PRODUCT_ID"]))->GetNextElement()){
									$item = $objRes->GetFields();
									$item["PROPERTIES"] = $objRes->GetProperties();
									$arSelect = array("PREVIEW_PICTURE", "DETAIL_PICTURE", "ID", "DETAIL_PAGE_URL");
									if($item["PROPERTIES"]["CML2_LINK"]["VALUE"]){ 
										if($itemLink = CIBlockElement::GetList(array(), array("ID" => $item["PROPERTIES"]["CML2_LINK"]["VALUE"]), false, false, $arSelect)->GetNext()){
											$item["ID"] = $itemLink["ID"];
											$item["DETAIL_PAGE_URL"] = $itemLink["DETAIL_PAGE_URL"];
											if(!$item["PREVIEW_PICTURE"] && $itemLink["PREVIEW_PICTURE"]){
												$item["PREVIEW_PICTURE"] = $itemLink["PREVIEW_PICTURE"];
											}
											if(!$item["DETAIL_PICTURE"] && $itemLink["DETAIL_PICTURE"]){
												$item["DETAIL_PICTURE"] = $itemLink["DETAIL_PICTURE"];
											}
										}
									}
									
									if($item["PREVIEW_PICTURE"]){
										$item["PREVIEW_PICTURE"] = CFile::ResizeImageGet($item["PREVIEW_PICTURE"], array('width' => 70, 'height' => 70), BX_RESIZE_IMAGE_PROPORTIONAL, true); 
									}
									elseif($item["DETAIL_PICTURE"]){
										$item["DETAIL_PICTURE"] = CFile::ResizeImageGet($item["DETAIL_PICTURE"], array('width' => 70, 'height' => 70), BX_RESIZE_IMAGE_PROPORTIONAL, true);
									}

									if($arParams["CACHE_TYPE"] != "N" && $cache_time > 0){ 
										$cache->StartDataCache($cache_time, $cache_id, $cache_path); 
										$cache->EndDataCache(array("item" => $item)); 
									}
								}
							}
							?>
							<tr class="catalog_item" product-id="<?=$arItem["ID"]?>" catalog-product-id="<?=$item["ID"]?>">
								<td class="thumb-cell">									
									<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">		
										<?if($item["PREVIEW_PICTURE"]):?>
											<img src="<?=$item["PREVIEW_PICTURE"]["src"]?>" alt="<?=($item["PREVIEW_PICTURE"]["alt"] ? $item["PREVIEW_PICTURE"]["alt"] : $arItem["NAME"]);?>" title="<?=($item["PREVIEW_PICTURE"]["title"] ? $item["PREVIEW_PICTURE"]["title"] : $arItem["NAME"]);?>" />
										<?elseif($item["DETAIL_PICTURE"]):?>
											<img src="<?=$item["DETAIL_PICTURE"]["src"]?>" alt="<?=($item["PREVIEW_PICTURE"]["alt"] ? $item["PREVIEW_PICTURE"]["alt"] : $arItem["NAME"]);?>" title="<?=($item["PREVIEW_PICTURE"]["title"] ? $item["PREVIEW_PICTURE"]["title"] : $arItem["NAME"]);?>" />	
										<?else:?>
											<img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=($item["PREVIEW_PICTURE"]["alt"] ? $item["PREVIEW_PICTURE"]["alt"] : $arItem["NAME"]);?>" title="<?=($item["PREVIEW_PICTURE"]["title"] ? $item["PREVIEW_PICTURE"]["title"] : $arItem["NAME"]);?>" />
										<?endif;?>
									</a>
								</td>
								<td class="item-title">
									<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$arItem["NAME"]?></span></a>

									<?if($arItem["PROPS"]){?>
										<div class="props">
											<?foreach($arItem["PROPS"] as $arProp){?>
												<div class="item_prop"><span class="title"><?=$arProp["NAME"];?>:</span><span class="value"> <?=$arProp["VALUE"];?></span></div>
											<?}?>
										</div>
									<?}?>
								</td>					
								<td class="cost-cell">
									<input type="hidden" name="item_price_<?=$arItem["ID"]?>" value="<?=($arItem["PRICE"] * $arItem["QUANTITY"])?>">
									<input type="hidden" name="item_price_discount_<?=$arItem["ID"]?>" value="<?=$arItem["DISCOUNT_PRICE"]?>">
									<span class="price"><?=FormatCurrency($arItem["~PRICE"] * $arItem["QUANTITY"], $arItem["CURRENCY"]);?></span>
								</td>
								<td class="remove-cell"><a class="remove" data-id="<?=$arItem["ID"]?>" href="<?=SITE_DIR?>basket/?action=delete&id=<?=$arItem["ID"]?>" title="<?=GetMessage("SALE_DELETE_PRD")?>"><i></i></a></td>
							</tr>
						<?}?>
					<?}?>
				</tbody>
			</table>
			<div class="basket_empty clearfix" >
				<table cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						<td class="image"><div></div></td>
						<td class="description"><div class="basket_empty_subtitle"><?=GetMessage("BASKET_EMPTY_SUBTITLE")?></div><div class="basket_empty_description"><?=GetMessage("BASKET_EMPTY_DESCRIPTION")?></div></td>
					</tr>
				</table>	
			</div>
			<div class="total_wrapp clearfix">
				<?if($count > 3):?>
					<a href="<?=$arParams["PATH_TO_BASKET"]?>" class="more_row">
						<span class="text"><?=GetMessage("STILL")?> <span class="count"><?=($count - 3)?></span> <span class="count_message"><?=declOfNum(($count - 3), array(GetMessage("PRODUCTS_ONE"), GetMessage("PRODUCTS_TWO"), GetMessage("PRODUCTS_FIVE")))?> <?=GetMessage("IN_BASKET_SMALL")?></span></span>
						<span class="icon"><i></i></span>
					</a>		
				<?endif;?>
				<div class="total"><?=GetMessage("TOTAL_SUMM_TITLE")?>:<span class="price"><?=$summ_formated?></span></div>
				<hr />		
				<input type="hidden" name="total_price" value="<?=$summ?>" />
				<input type="hidden" name="total_count" value="<?=$count;?>" />
				<input type="hidden" name="delay_count" value="<?=$delayCount;?>" />						
				<div class="but_row">
					<a class="button30 close_btn"><span><?=GetMessage("CLOSE");?></span></a>
					<a href="<?=$arParams["PATH_TO_BASKET"]?>" class="to_basket"><span class="icon"><i></i></span><span class="text"><?=GetMessage("GO_TO_BASKET");?></span></a>
				</div>
			</div>
		</div>	
	</div>
	
	<script type="text/javascript">
	$(document).ready(function(){
		$('.card_popup_frame').jqmAddClose('a.jqmClose');  
		$('.card_popup_frame').jqmAddClose('a.button30.close_btn');
		$('.card_popup_frame a.remove').unbind('click').click(function(e){
			e.preventDefault();
			var row = $(this).closest("tr");	
			row.fadeTo(500 , 0.05, function() {});
			delFromBasketCounter($(this).closest('tr').attr('catalog-product-id'));
			reloadTopBasket('wish', $('#basket_line'), $('.card_popup_frame'), 500, 'N',$(this));
			markProductRemoveBasket($(this).closest(".catalog_item").attr('catalog-product-id'));
		});
	});
	</script>
</div>
<?$frame->end();?>