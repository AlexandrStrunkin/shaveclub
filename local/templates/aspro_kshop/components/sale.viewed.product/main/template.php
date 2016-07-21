<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (count($arResult) > 0){?>
<div class="viewed_products_column">
	<div class="view-list">
		<div class="view-header">
			<span><?=GetMessage("VIEW_HEADER");?></span>
			<i class="triangle"></i>
		</div>
		<?foreach( $arResult as $key => $arItem ){?>
			<div class="view-item <?if(!$arResult[$key+1]):?> last<?endif;?>">
				<?if($arParams["VIEWED_IMAGE"]=="Y"):?>
					<a class="image" href="<?=$arItem["DETAIL_PAGE_URL"]?>">
						<?if(is_array($arItem["PICTURE"]) ):?>
							<img src="<?=$arItem["PICTURE"]["src"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>">
						<?else:?>
							<img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
						<?endif;?>
					</a>
				<?endif;?>
				<?if( $arParams["VIEWED_NAME"]=="Y" ){?>
					<div class="item-title"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$arItem["NAME"]?></span></a></div>
				<?}?>
				<?if( $arParams["VIEWED_PRICE"]=="Y" && $arItem["CAN_BUY"]=="Y" ){?>
					<div class="price">
						<?if($arItem["OFFERS"]):?>
							<?=GetMessage("CATALOG_FROM");?> <?=$arItem["MIN_PRODUCT_OFFER_PRICE_PRINT"]?>
						<?else:?>
							<?=$arItem["PRICE_FORMATED"]?>
						<?endif;?>
					</div>
				<?}?>
				<div class="clearboth"></div>
				<!--noindex-->
					<?if( $arParams["VIEWED_CANBUY"]=="Y" && $arItem["CAN_BUY"]=="Y" ){?>
						<a class="basket_button add" rel="nofollow" element_id="#<?=$arItem["ID"]?>" href="<?=$arItem["BUY_URL"]?>">
							<span><?=GetMessage("PRODUCT_BUY")?></span>
						</a>
					<?}?>
					<?if( $arParams["VIEWED_CANBASKET"]=="Y" && $arItem["CAN_BUY"]=="Y" ){?>
						<a class="basket_button add" rel="nofollow" element_id="#<?=$arItem["ID"]?>" href="<?=$arItem["ADD_URL"]?>">
							<span><?=GetMessage("PRODUCT_BUSKET")?></span>
						</a>
					<?}?>
				<!--/noindex-->
			</div>
		<?}?>
	</div>
</div>
<?}?>
