<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$frame = $this->createFrame()->begin("");
$templateData = array(
	//'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME']
);
$injectId = 'bigdata_recommeded_products_'.rand();?>
<script type="application/javascript">
	BX.cookie_prefix = '<?=CUtil::JSEscape(COption::GetOptionString("main", "cookie_name", "BITRIX_SM"))?>';
	BX.cookie_domain = '<?=$APPLICATION->GetCookieDomain()?>';
	BX.current_server_time = '<?=time()?>';

	BX.ready(function(){
		bx_rcm_recommendation_event_attaching(BX('<?=$injectId?>_items'));
	});
</script>
<?if (isset($arResult['REQUEST_ITEMS'])){
	CJSCore::Init(array('ajax'));
	// component parameters
	$signer = new \Bitrix\Main\Security\Sign\Signer;
	$signedParameters = $signer->sign(
		base64_encode(serialize($arResult['_ORIGINAL_PARAMS'])),
		'bx.bd.products.recommendation'
	);
	$signedTemplate = $signer->sign($arResult['RCM_TEMPLATE'], 'bx.bd.products.recommendation');?>

	<span id="<?=$injectId?>" class="bigdata_recommended_products_container"></span>
	<script type="application/javascript">
		BX.ready(function(){
			bx_rcm_get_from_cloud(
				'<?=CUtil::JSEscape($injectId)?>',
				<?=CUtil::PhpToJSObject($arResult['RCM_PARAMS'])?>,
				{
					'parameters':'<?=CUtil::JSEscape($signedParameters)?>',
					'template': '<?=CUtil::JSEscape($signedTemplate)?>',
					'site_id': '<?=CUtil::JSEscape(SITE_ID)?>',
					'rcm': 'yes'
				}
			);
		});
	</script>

	<?$frame->end();
	return;
}
if($arResult['ITEMS']){?>
	<input type="hidden" name="bigdata_recommendation_id" value="<?=htmlspecialcharsbx($arResult['RID'])?>">
	<?if($arParams["USE_TITLE_BLOCK"]=="Y"){?>
		<img class="shadow big_data" src="/bitrix/templates/aspro_kshop/images/shadow_bottom.png">
	<?}?>
	<div id="<?=$injectId?>_items" class="bigdata_recommended_products_items">
		<?if($arParams["USE_TITLE_BLOCK"]=="Y"){?>
			<div class="top_block">
				<?$title_block=($arParams["TITLE_BLOCK"] ? $arParams["TITLE_BLOCK"] : GetMessage('RECOMENDATION_TITLE'));?>
				<div class="title_block"><?=$title_block;?></div>
			</div>
		<?}?>
		<span class="slider_navigation"></span>
		<?/*<ul class="tabs_slider RECOMENDATION_slides wr">*/?>
		<ul class="specials_slider product_slider">
			<?foreach ($arResult['ITEMS'] as $key => $arItem){
				$strTitle = (
					isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) && '' != isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"])
					? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]
					: $arItem['NAME']
				);
				$totalCount = CKShop::GetTotalCount($arItem);
				$arQuantityData = CKShop::GetQuantityArray($totalCount);
				if($arParams["CAN_BUY"]!="Y"){
					$arItem["BASKET_ITEM"] = "Y";
				}
				$arAddToBasketData = CKShop::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"]);
				$strMainID = $this->GetEditAreaId($arItem['ID'] . $key);
				
				$strMeasure='';
				if($arItem["OFFERS"]){
					$strMeasure=$arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
				}else{
					if (($arParams["SHOW_MEASURE"]=="Y")&&($arItem["CATALOG_MEASURE"])){
						$arMeasure = CCatalogMeasure::getList(array(), array("ID"=>$arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
						$strMeasure=$arMeasure["SYMBOL_RUS"];
					}
				}
				?>
				<li class="catalog_item" id="<?=$strMainID;?>">
					<div class="ribbons">
						<?if(is_array($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?>
							<?if(in_array("HIT", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"]) ):?><span class="ribon_hit"></span><?endif;?>
							<?if(in_array("RECOMMEND", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?><span class="ribon_recomend"></span><?endif;?>
							<?if(in_array("NEW", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?><span class="ribon_new"></span><?endif;?>
							<?if(in_array("STOCK", $arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?><span class="ribon_action"></span><?endif;?>
						<?endif;?>
					</div>
					<div class="image">
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb">
							<?if(!empty($arItem["PREVIEW_PICTURE"])):?>
								<img border="0" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"] ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"]);?>" />
							<?elseif(!empty($arItem["DETAIL_PICTURE"])):?>
								<?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array("width" => 170, "height" => 170), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
								<img border="0" src="<?=$img["src"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"]?$arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"]);?>" />
							<?else:?>
								<img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"] ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"]);?>" />
							<?endif;?>
						</a>
					</div>
					<div class="item_info">
						<div class="item-title">
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$arItem["NAME"]?></span></a>
						</div>
						<div class="cost clearfix">
							<?if($arItem["OFFERS"]):?> 
								<div class="price_block">
									<div class="price"><?=GetMessage("CATALOG_FROM");?> <?=$arItem["MIN_PRICE"]["PRINT_VALUE"]?></div>
								</div>
							<?elseif($arItem["PRICES"]):?>
								<?
								$arCountPricesCanAccess = 0;
								foreach($arItem["PRICES"] as $key => $arPrice){
									if($arPrice["CAN_ACCESS"]){
										++$arCountPricesCanAccess;
									}
								}
								?>
								<?foreach($arItem["PRICES"] as $key => $arPrice):?>
									<?if($arPrice["CAN_ACCESS"]):?>
										<?$price = CPrice::GetByID($arPrice["ID"]); ?>
										<?if($arCountPricesCanAccess > 1):?>
											<div class="price_name"><?=$price["CATALOG_GROUP_NAME"];?></div>
										<?endif;?>
										<?if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"]):?>
											<div class="price"><?=$arPrice["PRINT_DISCOUNT_VALUE"];?></div>
											<div class="price discount">
												<strike><?=$arPrice["PRINT_VALUE"];?></strike>
											</div>
										<?else:?>
											<div class="price"><?=$arPrice["PRINT_VALUE"];?></div>
										<?endif;?>
									<?endif;?>
								<?endforeach;?>				
							<?else:?>
								<span class="by_order"><?=GetMessage("BY_ORDER");?></span>
							<?endif;?>
						</div>
						<div class="buttons_block clearfix">
							<!--noindex-->
							<?=$arAddToBasketData["HTML"]?>
							<?if($arItem["CAN_BUY"] && ($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y")):?>
								<div class="like_icons">								
									<?if(empty($arItem["OFFERS"]) && $arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
										<a title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item" rel="nofollow" data-item="<?=$arItem["ID"]?>"><i></i></a>
									<?endif;?>
									<?if(empty($arItem["OFFERS"]) && $arParams["DISPLAY_COMPARE"] == "Y"):?>
										<a title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item" rel="nofollow" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>" href="<?=$arItem["COMPARE_URL"]?>"><i></i></a>
									<?endif;?>
								</div>
							<?endif;?>
							<!--/noindex-->
						</div>
					</div>
				</li>
			<?}?>
		</ul>
	</div>
	
	<script type="text/javascript">
		$(document).ready(function(){
			// var flexsliderItemWidth = 178;
			var flexsliderItemWidth = 183;
			var flexsliderItemMargin = 15;
			var sliderWidth = $('#<?=$injectId?>_items').outerWidth();
			var flexsliderMinItems = Math.floor(sliderWidth / (flexsliderItemWidth + flexsliderItemMargin));
				$('#<?=$injectId?>_items').flexslider({
					animation: 'slide',
					selector: '.specials_slider > li',
					slideshow: false,
					animationSpeed: 600,
					directionNav: true,
					controlNav: false,
					pauseOnHover: true,
					animationLoop: false, 
					itemWidth: flexsliderItemWidth,
					itemMargin: flexsliderItemMargin, 
					minItems: flexsliderMinItems,
					controlsContainer: '#<?=$injectId?>_items .slider_navigation',
				});
				
				$(window).resize(function(){
					var sliderWidth = $('.specials_slider_wrapp').outerWidth();
					if($('#<?=$injectId?>_items').length && typeof($('#<?=$injectId?>_items').data('flexslider')) !== 'undefined'){
						$('#<?=$injectId?>_items').data('flexslider').vars.minItems = flexsliderMinItems = Math.floor(sliderWidth / (flexsliderItemWidth + flexsliderItemMargin));
						$('#<?=$injectId?>_items').flexslider(0);
					}
					if($('#<?=$injectId?>_items').find('.product_slider > li').length > flexsliderMinItems){
						$('#<?=$injectId?>_items .slider_navigation').show();
						$('#<?=$injectId?>_items .slider_navigation > ul').show();
					}
					else{
						$('#<?=$injectId?>_items .slider_navigation').hide();
						$('#<?=$injectId?>_items .slider_navigation > ul').hide();
					}
					<?if($arParams["USE_TITLE_BLOCK"]=="Y" || $arParams["OTHER_TAB"]=="Y"){?>
						if($('#<?=$injectId?>_items .product_slider').is(':visible')){
							$('#<?=$injectId?>_items .product_slider').equalize({children: '.item-title'}); 
							$('#<?=$injectId?>_items .product_slider').equalize({children: '.item_info'}); 
							$('#<?=$injectId?>_items .product_slider').equalize({children: 'li.catalog_item'});
							/*$('#<?=$injectId?>_items .product_slider').height($('#<?=$injectId?>_items .product_slider li:first-child').outerHeight());*/
						}
					<?}?>
				});
				
		})
	</script>
	<script type="text/javascript">
		$("#<?=$injectId?>_items").ready(function(){
			$(window).resize();
				<?/*if($arParams["USE_TITLE_BLOCK"]=="Y" || $arParams["OTHER_TAB"]=="Y"){?>
					$('#<?=$injectId?>_items .product_slider').equalize({children: '.item-title'}); 
					$('#<?=$injectId?>_items .product_slider').equalize({children: '.item_info'}); 
					$('#<?=$injectId?>_items .product_slider').equalize({children: 'li.catalog_item'});
					/*$('#<?=$injectId?>_items .product_slider').height($('#<?=$injectId?>_items .product_slider li:first-child').outerHeight());/
				<?}*/?>
			if($('#<?=$injectId?>_items .slider_navigation .flex-disabled').length > 1){
				$('#<?=$injectId?>_items .slider_navigation').hide();
			}
		});
	</script>
<?}
$frame->end();?>