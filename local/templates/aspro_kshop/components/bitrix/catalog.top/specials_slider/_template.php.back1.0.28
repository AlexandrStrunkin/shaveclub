<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?if($arResult["ITEMS"]):?>
	<div class="specials_slider_wrapp">
		<ul class="tabs">
			<?foreach($arResult["ITEMS"] as $specials_code => $arItems):?>
				<li data-code="<?=$specials_code?>"><span><?=GetMessage($specials_code."_TITLE");?></span><i class="triangle"></i></li>
			<?endforeach;?>
			<li class="stretch"></li>
		</ul>
		<ul class="slider_navigation">
			<?foreach($arResult["ITEMS"] as $specials_code => $arItems):?>
				<li class="specials_slider_navigation <?=$specials_code?>_nav" data-code="<?=$specials_code?>"></li>
			<?endforeach;?>
		</ul>
		<ul class="tabs_content">
			<?foreach($arResult["ITEMS"] as $specials_code => $arItems):?>
				<?			
				if(($arParams["SHOW_MEASURE"] == "Y") && ($arItem["CATALOG_MEASURE"])){
					$arMeasure = CCatalogMeasure::getList(array(), array("ID" => $arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
				}
				?>
				<li class="tab <?=$specials_code?>_wrapp" data-code="<?=$specials_code?>">
					<ul class="specials_slider <?=$specials_code?>_slides">
						<?foreach($arItems as $key => $arItem):?>
							<?
							$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
							$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
							$totalCount = CKShop::GetTotalCount($arItem);
							$arQuantityData = CKShop::GetQuantityArray($totalCount);
							$arAddToBasketData = CKShop::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"]);
							?>
							<li id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="catalog_item">
								<div class="image">
									<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb">
										<?if(!empty($arItem["PREVIEW_PICTURE"])):?>
											<img border="0" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"]?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"]?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
										<?elseif(!empty($arItem["DETAIL_PICTURE"])):?>
											<?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array("width" => 170, "height" => 170), BX_RESIZE_IMAGE_PROPORTIONAL, true );?>
											<img border="0" src="<?=$img["src"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"]?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"]?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
										<?else:?>
											<img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"]?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"]?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
										<?endif;?>
									</a>
								</div>
								<div class="item_info">
									<div class="item-title">
										<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$arItem["NAME"]?></span></a>
									</div>
									<div class="cost clearfix">
										<?
										$frame = $this->createFrame()->begin('');
										$frame->setBrowserStorage(true);
										?>
										<?if($arItem["OFFERS"]):?>
											<div class="price_block">
												<div class="price"><?=GetMessage("CATALOG_FROM");?> <?=$arItem["MIN_PRODUCT_OFFER_PRICE_PRINT"]?></div>
											</div>
										<?elseif($arItem["PRICES"]):?>
											<?
											$arCountPricesCanAccess = 0;
											foreach($arItem["PRICES"] as $key => $arPrice){
												if($arPrice["CAN_ACCESS"]){
													++$arCountPricesCanAccess;
												}
											}?>
											<?foreach($arItem["PRICES"] as $key => $arPrice):?>
												<?if($arPrice["CAN_ACCESS"]):?>
													<?$price = CPrice::GetByID($arPrice["ID"]);?>
													<?if($arCountPricesCanAccess > 1):?>
														<div class="price_name"><?=$price["CATALOG_GROUP_NAME"];?></div>
													<?endif;?>
													<?if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"]):?>
														<div class="price"><?=$arPrice["PRINT_DISCOUNT_VALUE"];?></div>
														<div class="price discount">
															<strike><?=$arPrice["VALUE"];?></strike>
														</div>
													<?else:?>
														<div class="price"><?=$arPrice["PRINT_VALUE"];?></div>
													<?endif;?>
												<?endif;?>
											<?endforeach;?>				
										<?endif;?>
										<?$frame->end();?>
									</div>
									<div class="buttons_block clearfix">
										<!--noindex-->
										<?=$arAddToBasketData["HTML"]?>
										<?if($arItem["CAN_BUY"] && ($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y")):?>
											<div class="like_icons">								
												<?if(empty($arItem["OFFERS"]) && $arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
													<a title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item" rel="nofollow" data-item="<?=$arItem["ID"]?>"><i></i></a>
												<?endif;?>
												<?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
													<a title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item" rel="nofollow" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>" href="<?=$arItem["COMPARE_URL"]?>"><i></i></a>
												<?endif;?>
											</div>
										<?endif;?>
										<!--/noindex-->
									</div>
								</div>
							</li>
						<?endforeach;?>
					</ul>
				</li>
			<?endforeach;?>
		</ul>
		<script type="text/javascript">
		$(document).ready(function(){
			$('.specials_slider_wrapp .tabs > li').first().addClass('cur');
			$('.specials_slider_wrapp .slider_navigation > li').first().addClass('cur');
			$('.specials_slider_wrapp .tabs_content > li').first().addClass('cur');
			
			var flexsliderItemWidth = 184;
			var flexsliderItemMargin = 15;
			var sliderWidth = $('.specials_slider_wrapp').outerWidth();
			var flexsliderMinItems = Math.floor(sliderWidth / (flexsliderItemWidth + flexsliderItemMargin));
			
			$('.specials_slider_wrapp .tabs_content > li.cur').flexslider({
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
				controlsContainer: '.specials_slider_navigation.cur',
			});
			
			$('.specials_slider_wrapp .tabs > li').click(function(){
				if(!$(this).hasClass('active')){
					var sliderIndex = $(this).index();
					$(this).addClass('active').addClass('cur').siblings().removeClass('active').removeClass('cur');
					$('.specials_slider_wrapp .slider_navigation > li:eq(' + sliderIndex + ')').addClass('cur').show().siblings().removeClass('cur');
					$('.specials_slider_wrapp .tabs_content > li:eq(' + sliderIndex + ')').addClass('cur').siblings().removeClass('cur');
					if(!$('.specials_slider_wrapp .tabs_content > li.cur .flex-viewport').length){
						$('.specials_slider_wrapp .tabs_content > li.cur').flexslider({
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
							controlsContainer: '.specials_slider_navigation.cur',
						});
					}
					$(window).resize();
				}
			});
			
			$(window).resize(function(){
				var sliderWidth = $('.specials_slider_wrapp').outerWidth();
				$('.specials_slider_wrapp .tabs_content > li.cur').css('height', '');
				$('.specials_slider_wrapp .tabs_content > li.cur .catalog_item').css('height', '');
				$('.specials_slider_wrapp .tabs_content > li.cur .catalog_item .item-title').css('height', '');
				$('.specials_slider_wrapp .tabs_content > li.cur .catalog_item .item_info').css('height', '');
				$('.specials_slider_wrapp .tabs_content > li.cur').equalize({children: '.item-title'}); 
				$('.specials_slider_wrapp .tabs_content > li.cur').equalize({children: '.item_info'}); 
				$('.specials_slider_wrapp .tabs_content > li.cur').equalize({children: '.catalog_item'});
				$('.specials_slider_wrapp .tabs_content .tab.cur .specials_slider .buttons_block').show();
				var itemsButtonsHeight = $('.specials_slider_wrapp .tabs_content .tab.cur .specials_slider > li .buttons_block').height();
				$('.specials_slider_wrapp .tabs_content .tab.cur .specials_slider .buttons_block').hide();
				var tabsContentUnhover = $('.specials_slider_wrapp .tabs_content .tab.cur').height() * 1;
				var tabsContentHover = tabsContentUnhover + itemsButtonsHeight;
				$('.specials_slider_wrapp .tabs_content .tab.cur').attr('data-unhover', tabsContentUnhover);
				$('.specials_slider_wrapp .tabs_content .tab.cur').attr('data-hover', tabsContentHover);
				$('.specials_slider_wrapp .tabs_content').height(tabsContentUnhover);
				if($('.specials_slider_wrapp .tabs_content > li.cur').length && typeof($('.specials_slider_wrapp .tabs_content > li.cur').data('flexslider')) !== 'undefined'){
					$('.specials_slider_wrapp .tabs_content > li.cur').data('flexslider').vars.minItems = flexsliderMinItems = Math.floor(sliderWidth / (flexsliderItemWidth + flexsliderItemMargin));
					$('.specials_slider_wrapp .tabs_content > li.cur').flexslider(0);
				}
				if($('.specials_slider_wrapp .tabs_content > li.cur').find('.specials_slider > li').length > flexsliderMinItems){
					$('.specials_slider_wrapp .slider_navigation > li.cur').show();
					$('.specials_slider_wrapp .slider_navigation > li.cur > ul').show();
				}
				else{
					$('.specials_slider_wrapp .slider_navigation > li.cur').hide();
					$('.specials_slider_wrapp .slider_navigation > li.cur > ul').hide();
				}
			});
			
			$(window).resize();
		});
		</script>
		<?$frame = $this->createFrame()->begin('');?>
		<script type="text/javascript">
		$(document).ready(function(){
			$(window).resize();
			$('.specials_slider > li').hover(
				function(){
					if($(window).outerWidth()>550){
						var tabsContentHover = $(this).parents('.tab').attr('data-hover') * 1;
						$(this).parents('.tab').fadeTo(100, 1);
						$(this).parents('.tab').stop().css({'height': tabsContentHover});
						$(this).stop().animate({'height': (tabsContentHover-8)}, 100);
						$(this).find('.buttons_block').fadeIn(450, 'easeOutCirc');
					}
				},
				function(){
					if($(window).outerWidth()>550){
						var tabsContentUnhoverHover = $(this).parents('.tab').attr('data-unhover') * 1;
						$(this).parents('.tab').stop().animate({'height': tabsContentUnhoverHover}, 100);
						$(this).stop().animate({height: tabsContentUnhoverHover - 13}, 100);
						$(this).find('.buttons_block').stop().fadeOut(333);
					}
				}
			);
		});
		</script>
		<?$frame->end();?>
	</div>
<?else:?>
	<?$this->setFrameMode(true);?>
<?endif;?>