<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$isAjax = ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["ajax_action"]) && $_POST["ajax_action"] == "Y");?>
<?$display = (($bDifferent = $_REQUEST["DIFFERENT"] == "Y") ? "differences" : "all");?>

<!--noindex-->
	<div class="sort_to_compare">
		<a rel="nofollow" href="<?=htmlspecialcharsbx($APPLICATION->GetCurPageParam("DIFFERENT=N", array("DIFFERENT")))?>" class="button30 <?=($bDifferent ? "grey" : "")?>"><span><?=GetMessage('CATALOG_ALL_CHARACTERISTICS')?></span></a>&nbsp;&nbsp;
		<a rel="nofollow" href="<?=htmlspecialcharsbx($APPLICATION->GetCurPageParam("DIFFERENT=Y", array("DIFFERENT")))?>" class="button30 <?=(!$bDifferent ? "grey" : "")?>"><span><?=GetMessage('CATALOG_ONLY_DIFFERENT')?></span></a>
	</div>
<!--/noindex-->

<div class="differences_table">
<?if( count( $arResult["ITEMS"] ) > $arResult["END_POSITION"] ):?>
	<input type="hidden" name="start_position" value="<?=$arResult["START_POSITION"]?>" />
	<input type="hidden" name="end_position" value="<?=$arResult["END_POSITION"]?>" />
	<div class="left_arrow dec"></div>
	<div class="right_arrow inc"></div>
<?endif;?>
<table>
	<tr>
		<td class="preview"></td>
		<?$position = 0;?>
		<?foreach( $arResult["ITEMS"] as $arItem ){
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCT_ELEMENT_DELETE_CONFIRM')));
			?>			
			<td class="item_td item_<?=$arItem["ID"]?>" <?=$position >= $arResult["END_POSITION"] ? 'style="display: none;"' : ''?>>
				<div class="catalog_item">
					<div class="ribbons">
						<?if (is_array($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?>
							<?foreach($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"] as $key=>$class){?>
								<span class="ribon_<?=strtolower($class);?>" title="<?=$arItem["PROPERTIES"]["HIT"]["VALUE"][$key]?>"></span>
							<?}?>
						<?endif;?>
					</div>
					<div class="remove_item">
						<!--noindex-->
							<a rel="nofollow"  class="remove"   title="<?=GetMessage("REMOVE_ITEM")?>" data-iblock="<?=$arItem["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>" href="<?=$APPLICATION->GetCurPageParam("action=DELETE_FROM_COMPARE_RESULT&IBLOCK_ID=".$arParams['IBLOCK_ID']."&ID[]=".$arItem['ID'],array("action", "IBLOCK_ID", "ID"))?>"><i></i></a>
						<!--/noindex-->
					</div>
					<div class="image">
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb_cat">
							<?if( !empty($arItem["PREVIEW_PICTURE"]) ){?><img src="<?=$arItem["PREVIEW_PICTURE"]['src']?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
							<?}else{?><img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" /><?}?>
						</a>
					</div>					
					<div class="item_info">
						<div class="item-title"><a class="desc_name" href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$arItem["NAME"]?></span></a></div>
						<div class="cost clearfix">
							<?if( !empty($arItem["OFFERS"]) ){?>
								<?	$count_offers = 0; $min_offer_id = -1; $min_price = 0;
									foreach( $arItem["OFFERS"] as $key_offer => $arOffer ){
										foreach( $arOffer["PRICES"] as $key_price => $arPrice )
										{
											if( $arPrice["CAN_ACCESS"] == 'Y' && $arPrice["CAN_BUY"] == 'Y' && $arPrice["MIN_PRICE"] =="Y" ) 
											{
												if( $min_offer_id == -1 ){ $min_offer_id = $key_offer; $min_price = $arPrice["VALUE"]; }
												elseif( $arPrice["VALUE"] < $min_price ) {$min_offer_id = $key_offer; $min_price = $arPrice["VALUE"];}
											}
										} 
										$count_offers++;
									}
								?>
								<div class="price_block">
									<?$num_prices=count($arItem["OFFERS"][$min_offer_id]["PRICES"])?>
									<?foreach( $arItem["OFFERS"][$min_offer_id]["PRICES"] as $key => $arPrice ){?>
										<?if($num_prices>1){?>
											<p style="padding-bottom: 0; margin-bottom: 5px;"><?=$arItem["OFFERS"][$min_offer_id]["CATALOG_GROUP_NAME_".$arPrice["PRICE_ID"]];?>:</p>
										<?}?>
										<?if($arPrice["DISCOUNT_VALUE"]!=$arPrice["VALUE"]){?>
											<div class="price">
												<?$prefix = count( $arItem["OFFERS"] ) > 1 ? GetMessage("CATALOG_FROM").'&nbsp;' : '';?>
												<?=$prefix?><?=$arPrice["PRINT_DISCOUNT_VALUE"];?>
											</div>
											<div class="price discount" style="display: none;"><strike><?=$prefix?><?=$arPrice["PRINT_VALUE"];?></strike></div>
										<?}else{?>
											<div class="price">
												<?$prefix = count( $arItem["OFFERS"] ) > 1 ? GetMessage("CATALOG_FROM").'&nbsp;' : '';?>
												<?=$prefix?><?=$arPrice["PRINT_VALUE"];?>
											</div>
										<?}?>
									<?}?>
								</div>
							<?}else{?>
								<?$numPrices = count($arItem["PRICES"]);
								foreach($arItem["PRICES"] as $code=>$arPrice):?>
									<?if($arPrice["CAN_ACCESS"]):?>
										<?if ($numPrices>1):?><p style="padding-bottom: 0; margin-bottom: 5px;"><?=$arResult["PRICES"][$code]["TITLE"];?>:</p><?endif?>
										<?if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
											<div class="price"><?=$arPrice["PRINT_DISCOUNT_VALUE"]?></div>
											<div class="price discount"><strike><?=$arPrice["PRINT_VALUE"]?></strike></div>
										<?else:?>
											<div class="price"><?=$arPrice["PRINT_VALUE"]?></div>
										<?endif;?>
									<?endif;?>
								<?endforeach;?>
							<?}?>
						</div>
					</div>
				</div>
			</td>
			<?$position++;?>
		<?}?>
	</tr>
	<?$arUnvisible=array("NAME", "PREVIEW_PICTURE", "DETAIL_PICTURE");?>
	<?if (!empty($arResult["SHOW_FIELDS"])){
		foreach ($arResult["SHOW_FIELDS"] as $code => $arProp){
			if(!in_array($code, $arUnvisible)){
				$showRow = true;
				if (!isset($arResult['FIELDS_REQUIRED'][$code]) || $arResult['DIFFERENT']){
					$arCompare = array();
					foreach($arResult["ITEMS"] as &$arElement){
						$arPropertyValue = $arElement["FIELDS"][$code];
						if (is_array($arPropertyValue)){
							sort($arPropertyValue);
							$arPropertyValue = implode(" / ", $arPropertyValue);
						}
						$arCompare[] = $arPropertyValue;
					}
					unset($arElement);
					$showRow = (count(array_unique($arCompare)) > 1);
				}
				if ($showRow){?>
					<?$position = 0;?>
					<tr class="hovered" >
						<td>
							<?=GetMessage("IBLOCK_FIELD_".$code);?>
						</td>
						<?foreach($arResult["ITEMS"] as $arElement){?>
							<td valign="top" <?=$position >= $arResult["END_POSITION"]  ? 'style="display: none;"' : ''?>>
								<?=$arElement["FIELDS"][$code];?>
								
							</td>
							<?$position++;?>
						<?}
						unset($arElement);?>
					</tr>
				<?}?>
			<?}?>
		<?}
	}?>
	<?if (!empty($arResult["SHOW_OFFER_FIELDS"])){
		foreach ($arResult["SHOW_OFFER_FIELDS"] as $code => $arProp){
			$showRow = true;
			if ($arResult['DIFFERENT']){
				$arCompare = array();
				foreach($arResult["ITEMS"] as &$arElement){
					$Value = $arElement["OFFER_FIELDS"][$code];
					if(is_array($Value)){
						sort($Value);
						$Value = implode(" / ", $Value);
					}
					$arCompare[] = $Value;
				}
				unset($arElement);
				$showRow = (count(array_unique($arCompare)) > 1);
			}
			if ($showRow){?>
				<?$position = 0;?>
				<tr class="hovered">
					<td>
						<?=GetMessage("IBLOCK_OFFER_FIELD_".$code)?>
					</td>
					<?foreach($arResult["ITEMS"] as &$arElement){?>
						<td <?=$position >= $arResult["END_POSITION"]  ? 'style="display: none;"' : ''?>>
							<?=(is_array($arElement["OFFER_FIELDS"][$code])? implode("/ ", $arElement["OFFER_FIELDS"][$code]): $arElement["OFFER_FIELDS"][$code])?>
						</td>
						<?$position++;?>
					<?}
					unset($arElement);
					?>
				</tr>
			<?}
		}
	}?>
	<?
	if (!empty($arResult["SHOW_PROPERTIES"])){
		foreach ($arResult["SHOW_PROPERTIES"] as $code => $arProperty){
			$showRow = true;
			if ($arResult['DIFFERENT']){
				$arCompare = array();
				foreach($arResult["ITEMS"] as &$arElement){
					$arPropertyValue = $arElement["DISPLAY_PROPERTIES"][$code]["VALUE"];
					if (is_array($arPropertyValue)){
						sort($arPropertyValue);
						$arPropertyValue = implode(" / ", $arPropertyValue);
					}
					$arCompare[] = $arPropertyValue;
				}
				unset($arElement);
				$showRow = (count(array_unique($arCompare)) > 1);
			}
			if ($showRow){?>
				<?$position = 0;?>
				<tr class="hovered">
					<td>
					<?=$arProperty["NAME"]?>
					</td>
					<?foreach($arResult["ITEMS"] as &$arElement){?>
						<td <?=$position >= $arResult["END_POSITION"]  ? 'style="display: none;"' : ''?>>
							<?=(is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
						</td>
						<?$position++;?>
					<?}
					unset($arElement);
					?>
				</tr>
			<?}
		}
	}
	if (!empty($arResult["SHOW_OFFER_PROPERTIES"])){
		foreach($arResult["SHOW_OFFER_PROPERTIES"] as $code=>$arProperty){
			$showRow = true;
			if ($arResult['DIFFERENT']){
				$arCompare = array();
				foreach($arResult["ITEMS"] as &$arElement){
					$arPropertyValue = $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["VALUE"];
					if(is_array($arPropertyValue)){
						sort($arPropertyValue);
						$arPropertyValue = implode(" / ", $arPropertyValue);
					}
					$arCompare[] = $arPropertyValue;
				}
				unset($arElement);
				$showRow = (count(array_unique($arCompare)) > 1);
			}
			if ($showRow){?>
				<?$position = 0;?>
				<tr class="hovered">
					<td>
						<?=$arProperty["NAME"]?>
					</td>
					<?foreach($arResult["ITEMS"] as &$arElement){?>
						<td <?=$position >= $arResult["END_POSITION"]  ? 'style="display: none;"' : ''?>>
							<?=(is_array($arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
						</td>
						<?$position++;?>
					<?}
					unset($arElement);
					?>
				</tr>

			<?}
		}
	}?>
</table>
<script>
	$(".remove_item .remove").click(function(e)
	{
		var item = $(this).attr('data-item');
		var iblockID = $(this).attr('data-iblock');	
		$.get( arKShopOptions['SITE_DIR']+"ajax/item.php?item="+item+"&compare_item=Y&iblock_id="+iblockID);
	});
	$('.differences .left_arrow, .differences .right_arrow').live("click", function(){
		var pos_start = $('input[name$="start_position"]').val();
		var pos_end = $('input[name$="end_position"]').val();
		var count_items = $('.differences td.item_td').length;
		if( $(this).hasClass('inc') && pos_end < count_items )
		{
			$('input[name$="start_position"]').val(++pos_start);
			$('input[name$="end_position"]').val(++pos_end);
		}
		else if( $(this).hasClass('dec') && pos_start > 1 )
		{
			$('input[name$="start_position"]').val(--pos_start);
			$('input[name$="end_position"]').val(--pos_end);
		}
		$('.differences td.item_td').each(function()
		{
			var index = $(this).index();
			if( index < pos_start || index > pos_end ) { $(this).hide(); }else{ $(this).show(); }
		})
		$('.differences td.prop_item').each(function()
		{
			var index = $(this).index();
			if( index < pos_start || index > pos_end ){ $(this).hide(); }else{ $(this).show(); }
		})
	});
</script>