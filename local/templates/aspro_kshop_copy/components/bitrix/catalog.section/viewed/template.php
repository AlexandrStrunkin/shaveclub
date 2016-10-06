<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if($arResult['ITEMS'] > 0):?>
	<div class="viewed_products_column">
		<div class="view-list">
			<div class="view-header">
				<span><?=GetMessage("VIEW_HEADER");?></span>
				<i class="triangle"></i>
			</div>
			<?foreach($arResult['ITEMS'] as $i => $arItem):?>
				<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
				$totalCount = CKShop::GetTotalCount($arItem);
				$arQuantityData = CKShop::GetQuantityArray($totalCount);
				$arAddToBasketData = CKShop::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"]);
				?>
				<div class="catalog_item view-item <?=(!$i ? 'first' : (!$arResult[$i + 1] ? 'last' : ''))?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<?if($arParams["VIEWED_IMAGE"] == "Y"):?>
						<div class="ribbons">
							<?if (is_array($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?>
								<?if( in_array("HIT", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"]) ):?><span class="ribon_hit"></span><?endif;?>
								<?if( in_array("RECOMMEND", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?><span class="ribon_recomend"></span><?endif;?>
								<?if( in_array("NEW", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?><span class="ribon_new"></span><?endif;?>
								<?if( in_array("STOCK", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?><span class="ribon_action"></span><?endif;?>
							<?endif;?>
						</div>
						<div class="image">
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb">
								<?if( !empty($arItem["PREVIEW_PICTURE"]) ):?>
									<img border="0" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"]?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"]?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
								<?elseif( !empty($arItem["DETAIL_PICTURE"])):?>
									<?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array( "width" => 165, "height" => 165 ), BX_RESIZE_IMAGE_PROPORTIONAL,true );?>
									<img border="0" src="<?=$img["src"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"]?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"]?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
								<?else:?>
									<img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"]?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"]?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
								<?endif;?>
							</a>
						</div>
					<?endif;?>
					<div class="item_info">
						<?if($arParams["VIEWED_NAME"] == "Y"):?>
							<div class="item-title">
								<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=mb_strimwidth($arItem["NAME"], 0, 75, "...")?></span></a>
							</div>
						<?endif;?>
						<?if($arParams["VIEWED_PRICE"] == "Y"):?>
							<div class="cost clearfix">
							<?if($arItem["OFFERS"]){?>
								<div class="price_block">
										<div class="price"><?=GetMessage("CATALOG_FROM");?> <?=$arItem["MIN_PRODUCT_OFFER_PRICE_PRINT"]?></div>
									</div>
								<?} elseif ( $arItem["PRICES"] ){?>
									<? $arCountPricesCanAccess = 0; foreach( $arItem["PRICES"] as $key => $arPrice ) { if($arPrice["CAN_ACCESS"]){$arCountPricesCanAccess++;} } ?>
									<?foreach( $arItem["PRICES"] as $key => $arPrice ){?>
										<?if( $arPrice["CAN_ACCESS"] ){?>
											<?$price = CPrice::GetByID($arPrice["ID"]); ?>
											<?if($arCountPricesCanAccess>1):?><div class="price_name"><?=$price["CATALOG_GROUP_NAME"];?></div><?endif;?>
											<?if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"]){?>
												<div class="price"><?=$arPrice["PRINT_DISCOUNT_VALUE"];?></div>
												<div class="price discount"><strike><?=$arPrice["VALUE"]?> <?= GetMessage('PRICE_RUB') ?></strike></div>
											<?}else{?><div class="price"><?=$arPrice["PRINT_VALUE"];?></div><?}?>
										<?}?>
									<?}?>
								<?}?>
							</div>
						<?endif;?>
						<?if($arParams["VIEWED_CANBUY"] == "Y"):?>
							<div class="clearboth"></div>
							<div class="buttons_block clearfix">
								<!--noindex-->
									<?=$arAddToBasketData["HTML"]?>
									<?if((!$arItem["OFFERS"] && $arParams["DISPLAY_WISH_BUTTONS"] != "N" && $arItem["CAN_BUY"]) || ($arParams["DISPLAY_COMPARE"] == "Y")):?>
										<div class="like_icons">
											<?if(!$arItem["OFFERS"] && $arParams["DISPLAY_WISH_BUTTONS"] != "N" && $arItem["CAN_BUY"]):?>
												<a title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item" rel="nofollow" data-item="<?=$arItem["ID"]?>"><i></i></a>
											<?endif;?>
											<?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
												<a title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item" rel="nofollow" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>" href="<?=$arItem["COMPARE_URL"]?>"><i></i></a>
											<?endif;?>
										</div>
									<?endif;?>
								<!--/noindex-->
							</div>
						<?endif;?>
					</div>
				</div>
			<?endforeach;?>
		</div>
	</div>
<?endif;?>