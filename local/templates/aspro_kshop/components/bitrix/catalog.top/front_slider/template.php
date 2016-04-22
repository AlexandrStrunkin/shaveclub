<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?if($arResult["ITEMS"]):?>
	<div class="front_slider_wrapp">
		<ul class="front_slider">
			<?foreach($arResult["ITEMS"] as $key => $arItem):?>
				<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
				if(($arParams["SHOW_MEASURE"] == "Y") && ($arItem["CATALOG_MEASURE"])){
					$arMeasure = CCatalogMeasure::getList(array(), array("ID" => $arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
				}
				?>
				<li id="<?=$this->GetEditAreaId($arItem['ID']);?>">	
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tr>
							<td class="info">
								<div class="item-title">
									<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$arItem["NAME"]?></span></a>
								</div>
								<?if($arItem["PREVIEW_TEXT"]):?>
									<div class="preview_text"><?=$arItem["PREVIEW_TEXT"]?></div>
								<?endif;?>
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
										}
										?> 
										<?foreach($arItem["PRICES"] as $key => $arPrice):?>
											<?if($arPrice["CAN_ACCESS"]):?>
												<?$price = CPrice::GetByID($arPrice["ID"]);?>
												<?if($arCountPricesCanAccess > 1):?>
													<div class="price_name"><?=$price["CATALOG_GROUP_NAME"];?></div>
												<?endif;?>
												<?if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"]):?>
													<div class="price"><?=$arPrice["PRINT_DISCOUNT_VALUE"];?></div>
													<div class="price discount">
														<?=GetMessage("WITHOUT_DISCOUNT");?>&nbsp;<strike><?=$arPrice["VALUE"];?></strike>
													</div>
												<?else:?>
													<div class="price"><?=$arPrice["PRINT_VALUE"];?></div>
												<?endif;?>
											<?endif;?>
										<?endforeach;?>				
									<?else:?> 
                                        <!--<span class="by_order"><?=GetMessage("BY_ORDER");?></span> -->
									<?endif;?>
									<?$frame->end();?>
								</div>       
								<a class="read_more" href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=GetMessage("READ_MORE")?></a>
							</td>
							<td class="image">
								<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb">
									<?if(!empty($arItem["DETAIL_PICTURE"])):?>
										<?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array("width" => 330, "height" => 284), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);?>
										<img border="0" src="<?=$img["src"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
									<?else:?>
										<img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
									<?endif;?>
								</a>
							</td>
						</tr>
					</table>
				</li>
			<?endforeach;?>
		</ul>
		<script type="text/javascript">
		var checkActive = function(slider){
			$(".extended_pagination li").removeClass("active");
			$(".extended_pagination li:eq("+(slider.animatingTo+1)+")").addClass("active");
		}
		$(document).ready(function(){
			$(".front_slider_wrapp").flexslider({
				animation: "slide",
				selector: ".front_slider > li",
				slideshow: false,
				animationSpeed: 900,
				directionNav: true,
				controlNav: true,
				directionNav: false,
				pauseOnHover: true,
				animationLoop: false, 
				before: function(slider){
					checkActive(slider);
				},
			});
			$(".extended_pagination li:eq(1)").addClass("active");
			$(".extended_pagination li:not(.hider)").click(function (i, evt){
				if(!$(this).is(".cur")){
					$(".front_slider_wrapp .flex-control-nav > li:eq("+($(this).index() - 1)+") a").click();
				}
			});
		});
		</script>
		<?$frame = $this->createFrame()->begin('');?>
		<script type="text/javascript">
		$(document).ready(function(){
			$(".extended_pagination li i").each(function(){
				$(this).css({"borderBottomWidth": ($(this).parent("li").outerHeight()/2), "borderTopWidth": ($(this).parent("li").outerHeight()/2)});
			});
		});
		</script>
		<?$frame->end();?>
		<ul class="extended_pagination">
			<li class="hider">&nbsp;</li>
			<?foreach($arResult["ITEMS"] as $key => $arItem):?>
				<li><i class="triangle"></i><span class="pseudo"><?=$arItem["NAME"]?></span></li>
			<?endforeach;?>
		</ul>
	</div>
	<img class="shadow" src="<?=SITE_TEMPLATE_PATH?>/images/shadow_bottom.png">
<?else:?>
	<?$this->setFrameMode(true);?>
<?endif;?>