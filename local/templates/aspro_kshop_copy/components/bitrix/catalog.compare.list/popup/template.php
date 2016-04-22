<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.flexslider-min.js',true)?> 
<div class="popup-intro"><div class="pop-up-title"><?=GetMessage("CATALOG_COMPARE_ELEMENTS")?></div></div>
<a class="jqmClose close"><i></i></a>
<?if(count($arResult) > 0):?>
	<form action="<?=$arParams["COMPARE_URL"]?>" method="get">
		<div class="compare_list">
			<ul class="compare_list_wrapp">
				<?
                foreach($arResult as $arItem):?>
					<li class="compare_list_item">
						<div class="image">					
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
								<?if(!empty($arItem["PREVIEW_PICTURE"])):?>
									<?$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array("width" => 70, "height" => 70), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
									<img border="0" src="<?=$img["src"]?>" alt="<?=((is_array($arItem["PREVIEW_PICTURE"])&&$arItem["PREVIEW_PICTURE"]["ALT"]) ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"]);?>" title="<?=((is_array($arItem["PREVIEW_PICTURE"]) && $arItem["PREVIEW_PICTURE"]["TITLE"]) ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"]);?>" />
								<?elseif(!empty($arItem["DETAIL_PICTURE"])):?>
									<?$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array("width" => 70, "height" => 70), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
									<img border="0" src="<?=$img["src"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"]);?>" title="<?=((is_array($arItem["PREVIEW_PICTURE"]) && $arItem["PREVIEW_PICTURE"]["TITLE"]) ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"]);?>" />		
								<?else:?>
									<img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_small.png" alt="<?=((is_array($arItem["PREVIEW_PICTURE"])&&$arItem["PREVIEW_PICTURE"]["ALT"]) ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"]);?>" title="<?=((is_array($arItem["PREVIEW_PICTURE"])&&$arItem["PREVIEW_PICTURE"]["TITLE"]) ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"]);?>" />
								<?endif;?>
							</a>
						</div>
						<div class="name">
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
						</div>
						<input type="hidden" name="ID[]" value="<?=$arItem["ID"]?>" />
						<!--noindex-->
							<a rel="nofollow" class="delete" title="<?=GetMessage("CATALOG_DELETE")?>" data-iblock="<?=$arItem["IBLOCK_ID"]?>" data-compare-item="<?=$arItem["ID"]?>" href="<?=$arItem["DELETE_URL"]?>"></a>
						<!--/noindex-->
					</li>
				<?endforeach;?>
			</ul>
			<span class="compare_navigation"></span>
		</div>
		<?if(count($arResult) >= 2):?>
			<div class="button_row">
				<button class="compare_button" type="submit" value="<?=GetMessage("CATALOG_COMPARE")?>"><i></i><span><?=GetMessage("CATALOG_COMPARE")?></span></button>
				<input type="hidden" name="action" value="COMPARE" />
				<input type="hidden" name="IBLOCK_ID" value="<?=$arParams["IBLOCK_ID"]?>" />
			</div>
		<?endif;?>
	</form>
<?endif;?>
<script type="text/javascript">
$(".compare_list").ready(function(){
	$('.compare_list').closest('.popup').jqmAddClose('.jqmClose');		
	<?if(count($arResult) > 3):?>
		$(".compare_list").flexslider({
			animation: "slide",
			selector: ".compare_list_wrapp > li",
			slideshow: false,
			animationSpeed: 600,
			directionNav: true,
			controlNav: false,
			pauseOnHover: true,
			itemWidth: 135,
			itemMargin: 15, 
			animationLoop: false, 
			controlsContainer: ".compare_navigation",
		});
	<?endif;?>
	
	$('.compare_list .delete').each(function(){
		$(this).removeAttr('onclick');
	});
	
	$('.compare_list .delete').click(function(e){
		e.preventDefault();
		var item = $(this).attr('data-compare-item');
		if(typeof(item) == 'undefined'){
			item = $(this).attr('item');
		}
		var iblockID = $(this).attr('data-iblock');
		if(typeof(iblockID) == 'undefined'){
			iblockID = $(this).attr('iblock');
		}
		
		$('.compare_item[data-item='+item+']').removeClass("added");
		if($('.compare_item[data-item='+item+']').find(".value.added").length){
			$('.compare_item[data-item='+item+']').find(".value").show();
			$('.compare_item[data-item='+item+']').find(".value.added").hide();
		}
		if($(".compare_list .compare_list_wrapp").find(".compare_list_item").length==1){
			$(".compare_frame.popup").jqmHide();
		}
		
		jsAjaxUtil.LoadData(arKShopOptions['SITE_DIR']+"ajax/show_compare_list.php?action=DELETE_FROM_COMPARE_LIST&id="+item, function(data) {
			$('.compare_frame.popup > div').html(data);
			jsAjaxUtil.InsertDataToNode(arKShopOptions['SITE_DIR']+"ajax/show_compare_preview.php", 'compare_small', false);
		});
	});
});
</script>
