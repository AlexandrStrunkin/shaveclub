<?
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	//echo ShowError($arResult["ERROR_MESSAGE"]);
	$bDelayColumn  = false;
	$bDeleteColumn = false;
	$bWeightColumn = false;
	$bPropsColumn  = false;
	use Bitrix\Sale\DiscountCouponsManager;
	if ($normalCount > 0):
?>

<div class="module-cart">
	<table class="colored">
		<thead>
			<tr>
				<?
					foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader){
						if ($arHeader["id"] == "DELETE"){$bDeleteColumn = true;}	
						if ($arHeader["id"] == "TYPE"){$bTypeColumn = true;}
						if ($arHeader["id"] == "QUANTITY"){$bQuantityColumn = true;}
						if ($arHeader["id"] == "DISCOUNT"){$bDiscountColumn = true;}
					}
				?>
				<?foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):
					if (in_array($arHeader["id"], array("TYPE", "DISCOUNT"))) {continue;} // some header columns are shown differently
					elseif ($arHeader["id"] == "PROPS"){$bPropsColumn = true; continue;}
					elseif ($arHeader["id"] == "DELAY"){$bDelayColumn = true; continue;}
					elseif ($arHeader["id"] == "WEIGHT"){ $bWeightColumn = true;}
					elseif ($arHeader["id"] == "DELETE"){ continue;}
					if ($arHeader["id"] == "NAME"):?>
						<td class="thumb-cell"></td><td class="name-th">
					<?else:?><td class="<?=strToLower($arHeader["id"])?>-th"><?endif;?><?=getColumnName($arHeader)?></td>
				<?endforeach;?>
				<?if ($bDelayColumn):?><td class="delay-cell"></td><?endif;?>
				<?if ($bDeleteColumn):?><td class="remove-cell"></td><?endif;?>
			</tr>
		</thead>

		<tbody>
			<?foreach ($arResult["GRID"]["ROWS"] as $k => $arItem):
				$currency = $arItem["CURRENCY"];
				if ($arItem["DELAY"] == "N" && $arItem["CAN_BUY"] == "Y"):?>
				<tr data-id="<?=$arItem["ID"]?>" data-product_id="<?=$arItem["PRODUCT_ID"]?>"  <?if($arItem["QUANTITY"]>$arItem["AVAILABLE_QUANTITY"]):?>data-error="no_amounth"<?endif;?>>
					
					<?foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):
						if (in_array($arHeader["id"], array("PROPS", "DELAY", "DELETE", "TYPE", "DISCOUNT"))) continue; // some values are not shown in columns in this template
						if ($arHeader["id"] == "NAME"):
						?>
							<td class="thumb-cell">		
								<?if( strlen($arItem["PREVIEW_PICTURE"]["SRC"])>0 ){?>
									<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb"><?endif;?>
										<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=(is_array($arItem["PREVIEW_PICTURE"]["ALT"])?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=(is_array($arItem["PREVIEW_PICTURE"]["TITLE"])?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
									<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
								<?}elseif( strlen($arItem["DETAIL_PICTURE"]["SRC"])>0 ){?>
									<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb"><?endif;?>
										<img src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="<?=(is_array($arItem["DETAIL_PICTURE"]["ALT"])?$arItem["DETAIL_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=(is_array($arItem["DETAIL_PICTURE"]["TITLE"])?$arItem["DETAIL_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
									<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
								<?}else{?>
									<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb"><?endif;?>
										<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" width="80" height="80" />
									<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
								<?}?>	
								<?if (!empty($arItem["BRAND"])):?><div class="ordercart_brand"><img src="<?=$arItem["BRAND"]?>" /></div><?endif;?>
							</td>
							<td class="name-cell">
								<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?endif;?><?=$arItem["NAME"]?><?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?><br />
								<?if ($arItem["PROPS"]):?>
									<div class="item_props">

										<? foreach ($arItem["PROPS"] as $val) {
												if (is_array($arItem["SKU_DATA"])) {
													$bSkip = false;
													foreach ($arItem["SKU_DATA"] as $propId => $arProp) { if ($arProp["CODE"] == $val["CODE"]) { $bSkip = true; break; } } 
													if ($bSkip) continue;
												} echo '<span class="item_prop"><span class="name">'.$val["NAME"].':&nbsp;</span><span class="property_value">'.$val["VALUE"].'</span></span>';
											}?>
									</div>
								<?endif;?>
								<?if (is_array($arItem["SKU_DATA"])):
									foreach ($arItem["SKU_DATA"] as $propId => $arProp):
										$isImgProperty = false; // is image property
										foreach ($arProp["VALUES"] as $id => $arVal) { if (isset($arVal["PICT"]) && !empty($arVal["PICT"])) { $isImgProperty = true; break; } }
										$full = (count($arProp["VALUES"]) > 5) ? "full" : "";
										if ($isImgProperty): // iblock element relation property
										?>
											<div class="bx_item_detail_scu_small_noadaptive <?=$full?>">
												<span class="titles"><?=$arProp["NAME"]?>:</span>
												<div class="bx_scu_scroller_container">
													<div class="bx_scu values">
														<ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>">
														<?	foreach ($arProp["VALUES"] as $valueId => $arSkuValue){
																$selected = "";
																foreach ($arItem["PROPS"] as $arItemProp) { 
																	if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"])
																		{ if ($arItemProp["VALUE"] == $arSkuValue["NAME"] || $arItemProp["VALUE"] == $arSkuValue["XML_ID"]) $selected = "class=\"bx_active\""; }
																};?>
																<li <?=$selected?>>
																	<span><?=$arSkuValue["NAME"]?></span>
																</li>
														<?}?>
														</ul>
													</div>
												</div>
											</div>
										<?else:?>
											<div class="bx_item_detail_size_small_noadaptive <?=$full?>">
												<span class="titles">
													<?=$arProp["NAME"]?>:
												</span>

												<div class="bx_size_scroller_container">
													<div class="bx_size values">
														<ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>">
															<?foreach ($arProp["VALUES"] as $valueId => $arSkuValue) {
																$selected = "";
																foreach ($arItem["PROPS"] as $arItemProp) {
																	if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"]) 
																	{ if ($arItemProp["VALUE"] == $arSkuValue["NAME"]) $selected = "class=\"bx_active\""; }
																}?>
																<li <?=$selected?>><span><?=$arSkuValue["NAME"]?></span></li>
															<?}?>
														</ul>
													</div>
												</div>
											</div>
										<?endif;
									endforeach;
								endif;
								?>
							</td>
						<?elseif ($arHeader["id"] == "QUANTITY"):?>
							<td class="count-cell">
								<?$q=(float)$arItem["QUANTITY"];?>
								<?
									$ratio = isset($arItem["MEASURE_RATIO"]) ? $arItem["MEASURE_RATIO"] : 0;
									$max = isset($arItem["AVAILABLE_QUANTITY"]) ? "max=\"".$arItem["AVAILABLE_QUANTITY"]."\"" : "";
									if (!isset($arItem["MEASURE_RATIO"])){
										$arItem["MEASURE_RATIO"] = 1;
									}
								?>
								<div class="counter_block basket big_basket" data-float="<?=(is_double($ratio) ? "Y" : "N")?>">
									<?if (isset($arItem["AVAILABLE_QUANTITY"])/*&& floatval($arItem["AVAILABLE_QUANTITY"]) != 0*/ && !CSaleBasketHelper::isSetParent($arItem)):?><span onclick="setQuantity('<?=$arItem["ID"]?>', '<?=$arItem["MEASURE_RATIO"]?>', 'down')" class="minus">-</span><?endif;?>
									<input
										type="text"
										class="text" 
										id="QUANTITY_INPUT_<?=$arItem["ID"]?>"
										name="QUANTITY_INPUT_<?=$arItem["ID"]?>"
										size="2"
										data-id="<?=$arItem["ID"];?>" 
										maxlength="18"
										min="0"
										<?=$max?>
										step="<?=$ratio?>"
										value="<?=$q?>"
										onchange="updateQuantity('QUANTITY_INPUT_<?=$arItem["ID"]?>', '<?=$arItem["ID"]?>', '<?=$ratio?>')"
									>	
									<?if (isset($arItem["AVAILABLE_QUANTITY"])/*&& floatval($arItem["AVAILABLE_QUANTITY"]) != 0*/ && !CSaleBasketHelper::isSetParent($arItem)):?><span onclick="setQuantity('<?=$arItem["ID"]?>', '<?=$arItem["MEASURE_RATIO"]?>', 'up')" class="plus">+</span><?endif;?>
									<?if (isset($arItem["MEASURE_TEXT"]) && $arParams["SHOW_MEASURE"]=="Y"):?><div class="measure"><?=$arItem["MEASURE_TEXT"];?></div><?endif;?>
								</div>
								<?if($arItem["QUANTITY"]>$arItem["AVAILABLE_QUANTITY"]):?><div class="error"><?=GetMessage("NO_NEED_AMMOUNT")?></div><?endif;?>

								<input type="hidden" id="QUANTITY_<?=$arItem['ID']?>" name="QUANTITY_<?=$arItem['ID']?>" value="<?=$q;?>" />
								<?//=getQuantitySelectControl("QUANTITY_SELECT_".$arItem["ID"], "QUANTITY_SELECT_".$arItem["ID"], $arItem["QUANTITY"], $arItem["AVAILABLE_QUANTITY"], $arItem["MEASURE_RATIO"], $arItem["MEASURE_TEXT"]); // quantity selector for mobile ?>
							</td>
						<?elseif ($arHeader["id"] == "SUM"):?>							
							<td class="summ-cell"><div class="cost prices"><div class="price"><?=SaleFormatCurrency(($arItem["PRICE"]*$arItem["QUANTITY"]), $arItem["CURRENCY"]);?><?//=$arItem["SUMM_FORMATED"];?></div></div>
							</td>
						<?elseif ($arHeader["id"] == "PRICE"):?>
							<td class="cost-cell <?=( $bTypeColumn ? 'notes' : '' );?>">
								<div class="cost prices clearfix">
									<?if (strlen($arItem["NOTES"]) > 0 && $bTypeColumn):?>
										<div class="price_name"><?=$arItem["NOTES"]?></div>
									<?endif;?>
									<?if( doubleval($arItem["DISCOUNT_PRICE_PERCENT"]) > 0 && $bDiscountColumn ){?>
										<div class="price"><?=$arItem["PRICE_FORMATED"]?></div>
										<div class="price discount"><strike><?=$arItem["FULL_PRICE_FORMATED"]?></strike></div>
										<div class="sale_block">
											<?if($arItem["DISCOUNT_PRICE_PERCENT"] && $arItem["DISCOUNT_PRICE_PERCENT"]<100){?>
												<div class="value">-<?=$arItem["DISCOUNT_PRICE_PERCENT_FORMATED"];?></div>
											<?}?>
											<div class="text"><?=GetMessage("ECONOMY")?> <?=SaleFormatCurrency(round($arItem["DISCOUNT_PRICE"]), $arItem["CURRENCY"]);?></div>
											<div class="clearfix"></div>
										</div>
										<input type="hidden" name="item_price_<?=$arItem["ID"]?>" value="<?=$arItem["PRICE"]?>" />
										<input type="hidden" name="item_price_discount_<?=$arItem["ID"]?>" value="<?=$arItem["FULL_PRICE"]?>" />
									<?}else{?>
										<div class="price"><?=$arItem["PRICE_FORMATED"];?></div>
										<input type="hidden" name="item_price_<?=$arItem["ID"]?>" value="<?=$arItem["PRICE"]?>" />
									<?}?>
									<input type="hidden" name="item_summ_<?=$arItem["ID"]?>" value="<?=$arItem["PRICE"]*$arItem["QUANTITY"]?>" />
								</div>
							</td>
						<?elseif ($arHeader["id"] == "WEIGHT"):?>
							<td class="weight-cell"><?=$arItem["WEIGHT_FORMATED"]?></td>
						<?else:?>
							<td class="cell"><?=$arItem[$arHeader["id"]]?></td>
						<?endif;?>
					<?endforeach;?>

					<?if ($bDelayColumn ):?>
						<td class="delay-cell delay">
							<a class="wish_item" href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["delay"])?>" title="<?=GetMessage("SALE_DELAY")?>">
								<span class="icon"><i></i></span>
							</a>
						</td>
					<?endif;?>
					<?if ($bDeleteColumn):?>
						<td class="remove-cell"><a class="remove" href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["delete"])?>" title="<?=GetMessage("SALE_DELETE")?>"><i></i></a></td>
					<?endif;?>
				</tr>
				<?
				endif;
			endforeach;
			?>
			<?
				$arTotal = array();
				if ($bWeightColumn) { $arTotal["WEIGHT"]["NAME"] = GetMessage("SALE_TOTAL_WEIGHT"); $arTotal["WEIGHT"]["VALUE"] = $arResult["allWeight_FORMATED"];}
				if ($arParams["PRICE_VAT_SHOW_VALUE"] == "Y") 
				{ 
					$arTotal["VAT_EXCLUDED"]["NAME"] = GetMessage("SALE_VAT_EXCLUDED"); $arTotal["VAT_EXCLUDED"]["VALUE"] = $arResult["allSum_wVAT_FORMATED"];
					$arTotal["VAT_INCLUDED"]["NAME"] = GetMessage("SALE_VAT_INCLUDED"); $arTotal["VAT_INCLUDED"]["VALUE"] = $arResult["allVATSum_FORMATED"];
				}
				if (doubleval($arResult["DISCOUNT_PRICE_ALL"]) > 0)
				{
					$arTotal["PRICE"]["NAME"] = GetMessage("SALE_TOTAL"); 
					$arTotal["PRICE"]["VALUES"]["ALL"] = str_replace(" ", "&nbsp;", $arResult["allSum_FORMATED"]);
					$arTotal["PRICE"]["VALUES"]["WITHOUT_DISCOUNT"] = $arResult["PRICE_WITHOUT_DISCOUNT"];
				}
				else
				{
					$arTotal["PRICE"]["NAME"] = GetMessage("SALE_TOTAL"); 
					$arTotal["PRICE"]["VALUES"]["ALL"] = $arResult["allSum_FORMATED"];
				}			
			?>
			</tbody>
			</table>
			<?$totalCols = 1 + ($arParams["SHOW_FULL_ORDER_BUTTON"] == "Y" ? 1 : 0) + ($arParams["SHOW_FAST_ORDER_BUTTON"] == "Y" ? 1 : 0)?>
			<?$arError = CKshop::checkAllowDelivery($arResult["allSum"],$currency);?>
			<table class="bottom middle <?=($arError["ERROR"] ? 'error' : '');?> <?if ($arParams["AJAX_MODE_CUSTOM"]!="Y"){?> refresh_block<?}?>">
				<tfoot>
					<tr data-id="total_row" class="top_total_row">
						<td colspan="<?=($totalCols - 1)?>" class="row_titles">
							<?if( $arParams["HIDE_COUPON"] != "Y" ){?>
								<div class="coupon<?if ($arParams["AJAX_MODE_CUSTOM"]!="Y"):?> b16<?endif;?> form-control bg">
									<div class="input_coupon">
										<span class="coupon-t"><?=GetMessage("STB_COUPON_LABEL");?></span>
										<? $class=""; //if ($_REQUEST["COUPON"]) { if ($arResult["COUPON"]) $class=' class="good"'; else $class=' class="good"'; } ?>
										<span class="coupon_wrap">
											<input type="text" id="COUPON" size="21" value="<?//=$arResult["COUPON"]?>" name="COUPON"<?=$class?>>
										</span>
										<?if ($arParams["AJAX_MODE_CUSTOM"]=="Y"){?><button class="button30 gradient big_btn long apply-button"><?=GetMessage("SALE_APPLY")?></button><?}?>
									</div>
									<?if (!empty($arResult['COUPON_LIST'])){?>
										<div class="coupons_list">
											<?foreach ($arResult['COUPON_LIST'] as $oneCoupon){
												$couponClass = 'disabled bad not_apply';
												switch ($oneCoupon['STATUS']){
													case DiscountCouponsManager::STATUS_NOT_FOUND:
														$couponClass = 'bad not_found';
														break;
													case DiscountCouponsManager::STATUS_FREEZE:
														$couponClass = 'bad not_apply';
														break;
													case DiscountCouponsManager::STATUS_APPLYED:
														$couponClass = 'good';
														break;
												}?>
												<div class="bx_ordercart_coupon <? echo $couponClass; ?>">
													<input disabled readonly type="hidden" name="OLD_COUPON[]" data-coupon="<?=htmlspecialcharsbx($oneCoupon['COUPON']);?>" value="<?=htmlspecialcharsbx($oneCoupon['COUPON']);?>" class="<? echo $couponClass; ?>">
													<span class="coupon_text"><?=htmlspecialcharsbx($oneCoupon['COUPON']);?></span>
													<span class="del_btn remove <? echo $couponClass; ?>" data-coupon="<? echo htmlspecialcharsbx($oneCoupon['COUPON']); ?>"><i></i></span>
													<div class="bx_ordercart_coupon_notes">
														<?if (isset($oneCoupon['CHECK_CODE_TEXT'])){
															echo (is_array($oneCoupon['CHECK_CODE_TEXT']) ? implode('<br>', $oneCoupon['CHECK_CODE_TEXT']) : $oneCoupon['CHECK_CODE_TEXT']);
														}?>
													</div>
												</div>
											<?}?>
											<?unset($couponClass, $oneCoupon);?>
										</div>
									<?}?>
								</div>
							<?}?>
							<div class="total item_title"><?=GetMessage("SALE_TOTAL");?></div>
						</td>
						<td style="width: 260px;" class="row_values">
							<div class="total item_title"><?=GetMessage("SALE_TOTAL");?></div>
							<div class="wrap_prices">
								<?foreach($arTotal as $key => $value):?>
									<?if ($value["VALUES"] && $value["NAME"]):?>
										<?if ($key=="PRICE"):?>
											<?if ($arResult["DISCOUNT_PRICE_ALL"]):?>
												<div data-type="price_discount">
													<span class="price"><?=$value["VALUES"]["ALL"];?></span>
													<div class="price discount"><strike><?=$value["VALUES"]["WITHOUT_DISCOUNT"];?></strike></div>
												</div>
											<?else:?>
												<div  data-type="price_normal"><span class="price"><?=$arResult["allSum_FORMATED"];?></span></div>
											<?endif;?>
										<?elseif ($value["VALUE"]):?>
											<div data-type="<?=strToLower($key)?>"><span class="price"><?=$value["VALUE"]?></span></div>
										<?endif;?>
									<?endif;?>
								<?endforeach;?>
							</div>
						</td>
					</tr>
					<tr data-id="total_buttons" class="bottom_btn ">
						<?if($arError["ERROR"]){?>
							<td class="backet_back_wrapp line <?=($arError["ERROR"] ? 'error' : '');?>" colspan="3">
								<div class="iblock back_btn">
									<div class="basket_back">
										<a class="button30 gradient transparent big_btn grey_br" href="<?=SITE_DIR?>catalog/"><span><?=GetMessage("SALE_BACK")?></span></a>
										<div class="description"><?=GetMessage("SALE_BACK_DESCRIPTION");?></div>
									</div>
								</div>
                                <??>
								<?if ($arParams["AJAX_MODE_CUSTOM"]!="Y"):?>
									<div class="iblock upd_btn">
										<div class="basket_update clearfix">
											<button type="submit"  name="BasketRefresh" class="button30 gradient refresh"><span><?=GetMessage("SALE_REFRESH")?></span></button>
											<div class="description"><?=GetMessage("SALE_REFRESH_DESCRIPTION");?></div>
										</div>
									</div>
								<?endif;?>
								<div class="icon_error_block"><?=$arError["TEXT"];?></div>
							</td>
						<?}else{?>
							<td class="backet_back_wrapp <?=($arError["ERROR"] ? 'error' : '');?>">
								<?if ($arParams["AJAX_MODE_CUSTOM"]!="Y"){?>
									<div class="wrap_md">
								<?}?>
								<div class="iblock back_btn">
									<div class="basket_back">
										<a class="button30 gradient transparent big_btn grey_br" href="<?=SITE_DIR?>catalog/"><span><?=GetMessage("SALE_BACK")?></span></a>
										<div class="description"><?=GetMessage("SALE_BACK_DESCRIPTION");?></div>
									</div>
									<?if($arError["ERROR"]){?>
										<div class="icon_error_block"><?=$arError["TEXT"];?></div>
									<?}else{?>
										<?if ($arParams["SHOW_FULL_ORDER_BUTTON"]=="Y"){?>
											<div class="basket_checkout clearfix">
												<span class="button30 gradient big_btn checkout" data-text="<?=GetMessage("ORDER_START");?>" data-href="<?=$arParams["PATH_TO_ORDER"];?>" onclick="checkOut(event);"><span><?=GetMessage("SALE_ORDER")?></span></span>
												<input type="hidden" value="BasketOrder" name="BasketOrder1">
											</div>
										<?}
										if ($arParams["SHOW_FAST_ORDER_BUTTON"]=="Y"){?>
											<div class="basket_fast_order clearfix">
												<span onclick="oneClickBuyBasket()" class="button30 gradient fast_order"><span><?=GetMessage("SALE_FAST_ORDER")?></span></span>
											</div>
										<?}?>
									<?}?>
								</div>
								<?if ($arParams["AJAX_MODE_CUSTOM"]!="Y"):?>
									<div class="iblock upd_btn">
										<div class="basket_update clearfix">
											<button type="submit"  name="BasketRefresh" class="button30 gradient refresh"><span><?=GetMessage("SALE_REFRESH")?></span></button>
											<div class="description"><?=GetMessage("SALE_REFRESH_DESCRIPTION");?></div>
										</div>
									</div>
								<?endif;?>
								<?if ($arParams["AJAX_MODE_CUSTOM"]!="Y"){?>
									</div>
								<?}?>
							</td>
							<?if($arError["ERROR"]){?>
								<td colspan="2">
									<div class="icon_error_block"></div>
								</td>	
							<?}else{?>
								<?if ($arParams["SHOW_FULL_ORDER_BUTTON"]=="Y"){?>
									<td class="basket_checkout_wrapp">
										<div class="basket_checkout clearfix">
											<span class="button30 gradient big_btn checkout" data-text="<?=GetMessage("ORDER_START");?>" data-href="<?=$arParams["PATH_TO_ORDER"];?>" onclick="checkOut(event);"><span><?=GetMessage("SALE_ORDER")?></span></span>
											<div class="description"><?=GetMessage("SALE_ORDER_DESCRIPTION");?></div>
											<input type="hidden" value="BasketOrder" name="BasketOrder1">
										</div>
									</td>
								<?}
								if ($arParams["SHOW_FAST_ORDER_BUTTON"]=="Y"){?>
									<td class="basket_fast_order_wrapp" <?/*if ($bDelayColumn ):?> colspan="2"<?endif;*/?>>
										<div class="basket_fast_order clearfix">
											<span onclick="oneClickBuyBasket()" class="button30 gradient fast_order"><span><?=GetMessage("SALE_FAST_ORDER")?></span></span>
											<div class="description"><?=GetMessage("SALE_FAST_ORDER_DESCRIPTION");?></div>
										</div>
									</td>
								<?}?>
							<?}?>
						<?}?>
					</tr>
				</tfoot>
			</table>
</div>
<?else:?>
	<div class="cart_empty">
		<table cellspacing="0" cellpadding="0" width="100%" border="0"><tr><td class="img_wrapp">
			<div class="img">
				<img src="<?=SITE_TEMPLATE_PATH?>/images/empty_cart.png" alt="<?=GetMessage("BASKET_EMPTY")?>" />
			</div>
		</td><td>
			<div class="text">
				<?$APPLICATION->IncludeFile(SITE_DIR."include/empty_cart.php", Array(), Array("MODE"      => "html", "NAME"      => GetMessage("SALE_BASKET_EMPTY"),));?>
			</div>
		</td></tr></table>
		<div class="clearboth"></div>
	</div>
<?endif;?>
<div class="one_click_buy_basket_frame"></div>