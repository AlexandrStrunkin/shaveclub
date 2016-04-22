<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$normalCount = count($arResult["ITEMS"]["AnDelCanBuy"]);
$delayCount = count($arResult["ITEMS"]["DelDelCanBuy"]);
$subscribeCount = count($arResult["ITEMS"]["ProdSubscribe"]);
$naCount = count($arResult["ITEMS"]["nAnCanBuy"]);
$arParamsExport=$arParams;
unset($arParamsExport['INNER']);
$paramsString = urlencode(serialize($arParamsExport));
?>
<?$frame = $this->createFrame()->begin('');?>
<?if ($arParams['INNER']!==true):?>
	<div class="basket_fly<?if (strlen($arResult["ERROR_MESSAGE"]) > 0):?> basket_empty<?endif;?>">
<?endif;?>
	<input type="hidden" name="total_price" value="<?=$summ?>" />
	<input type="hidden" name="total_discount_price" value="<?=$summ?>" />
	<input type="hidden" name="total_count" value="<?=$normalCount;?>" />
	<input type="hidden" name="delay_count" value="<?=$delayCount;?>" />

	<div class="opener">
		<div class="basket_count<?=($normalCount?"":" empty")?> small1" data-type="AnDelCanBuy"><span class="icon"><i></i></span><div class="count"><?=$normalCount?></div></div>
		<div class="wish_count<?=($delayCount?"":" empty")?> small1" data-type="DelDelCanBuy"><span class="icon"><i></i></span><div class="count"><?=$delayCount?></div></div>
	</div>
	<script src="<?=$templateFolder.'/script.js'?>" type="text/javascript"></script>
	<? 
		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/functions.php");
		$arUrls = Array("delete" => $arParams["PATH_TO_BASKET"]."?action=delete&id=#ID#",
						"delay" => $arParams["PATH_TO_BASKET"]."?action=delay&id=#ID#",
						"add" => $arParams["PATH_TO_BASKET"]."?action=add&id=#ID#");
						
		
			if (is_array($arResult["WARNING_MESSAGE"]) && !empty($arResult["WARNING_MESSAGE"])) { foreach ($arResult["WARNING_MESSAGE"] as $v) { echo ShowError($v); } }
			
			$arMenu = array(array("ID"=>"AnDelCanBuy", "TITLE"=>GetMessage("SALE_BASKET_ITEMS"), "COUNT"=>$normalCount, "FILE"=>"/basket_items.php"));
			if ($delayCount) { $arMenu[] = array("ID"=>"DelDelCanBuy", "TITLE"=>GetMessage("SALE_BASKET_ITEMS_DELAYED"), "COUNT"=>$delayCount, "FILE"=>"/basket_items_delayed.php"); }
			//if ($subscribeCount) { $arMenu[] = array("ID"=>"ProdSubscribe", "TITLE"=>GetMessage("SALE_BASKET_ITEMS_SUBSCRIBED"), "COUNT"=>$subscribeCount, "FILE"=>"/basket_items_subscribed.php"); }
			//if ($naCount) { $arMenu[] = array("ID"=>"nAnCanBuy", "TITLE"=>GetMessage("SALE_BASKET_ITEMS_NOT_AVAILABLE"), "COUNT"=>$naCount, "FILE"=>"/basket_items_not_available.php"); }

	?>	
		<div class="basket_sort">
			<span class="basket_title"><?=GetMessage("BASKET_TITLE");?></span>
			<ul class="tabs">
				<?if (strlen($arResult["ERROR_MESSAGE"]) <= 0){?>
					<?foreach($arMenu as $key => $arElement){?>
						<li<?=($arElement["SELECTED"] ? ' class="cur"' : '');?> item-section="<?=$arElement["ID"]?>" data-type="<?=$arElement["ID"]?>">
							<span><?=$arElement["TITLE"]?></span>
							<span class="quantity">&nbsp;(<span class="count"><?=$arElement["COUNT"]?></span>)</span>
							<i class="triangle"></i>
						</li>
					<?}?>
				<?}?>
			</ul>
			<span class="wrap_remove_button">
				<?if($normalCount){?>
					<span class="button grey_br transparent remove_all_basket AnDelCanBuy cur" data-type="basket"><?=GetMessage('CLEAR_BASKET')?></span>
				<?}?>
				<?if($delayCount){?>
					<span class="button grey_br transparent remove_all_basket DelDelCanBuy" data-type="delay"><?=GetMessage('CLEAR_BASKET')?></span>
				<?}?>
			</span>
		</div>
	
		<form method="post" action="<?=$arParams["PATH_TO_BASKET"]?>" name="basket_form" id="basket_form" class="basket_wrapp">
			<?if (strlen($arResult["ERROR_MESSAGE"]) <= 0){?>
				<ul class="tabs_content basket">
					<?foreach($arMenu as $key => $arElement){?>
						<li<?=($arElement["SELECTED"] ? ' class="cur"' : '');?> item-section="<?=$arElement["ID"]?>"><?include($_SERVER["DOCUMENT_ROOT"].$templateFolder.$arElement["FILE"]);?></li>
					<?}?>
				</ul>
			<?}else{?>
				<ul class="tabs_content basket"><li class="cur" item-section="AnDelCanBuy"><?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");?></ul>
			<?}?>
			<input id="fly_basket_params" type="hidden" name="PARAMS" value='<?=$paramsString?>' />
		</form>

		<script>
			$("#basket_line .basket_fly").ready(function(){	
				<?if ($arParams["AJAX_MODE_CUSTOM"]=="Y"){?>
					$("#basket_line .basket_fly").submit(function(e) {
						e.preventDefault();
					});
				<?}?>
				$('#basket_line .basket_fly a.apply-button').click(function(){
					$('#basket_line .basket_fly form[name^=basket_form]').prepend('<input type="hidden" name="BasketRefresh" value="Y" />');
					$.post( arKShopOptions['SITE_DIR']+'basket/', $("#basket_line .basket_fly form[name^=basket_form]").serialize(), $.proxy(
					function( data)
					{			
						$('#basket_line .basket_fly form[name^=basket_form] input[name=BasketRefresh]').remove();						
					}));
				});
				if (parseInt($("#basket_line .basket_fly").css("right"))<0){
					$("#basket_line .basket_fly .opener > div").addClass('small');
				}else{
					$("#basket_line .basket_fly .opener > div").removeClass('small');
				}
				$("#basket_line .basket_fly .opener > div").click(function(){
					if (parseInt($("#basket_line .basket_fly").css("right"))<0){
						$("#basket_line .basket_fly").stop().animate({"right": "0"}, 333);
						$("#basket_line .basket_fly .tabs li").removeClass("cur");
						$("#basket_line .basket_fly .tabs_content li").removeClass("cur");
						$("#basket_line .basket_fly .remove_all_basket").removeClass("cur");
						if (!$(this).is(".wish_count.empty"))
						{							
							$("#basket_line .basket_fly .tabs_content li[item-section="+$(this).data("type")+"]").addClass("cur");
							$("#basket_line .basket_fly .tabs li:eq("+$(this).index()+")").addClass("cur");
							$("#basket_line .basket_fly .remove_all_basket."+$(this).data("type")).addClass("cur");
						}
						else
						{
							$("#basket_line .basket_fly .tabs li").first().addClass("cur").siblings().removeClass("cur");
							$("#basket_line .basket_fly .tabs_content li").first().addClass("cur").siblings().removeClass("cur");
							$("#basket_line .basket_fly .remove_all_basket").first().addClass("cur");
						}
						$("#basket_line .basket_fly .opener > div").removeClass('small');
						// $(this).removeClass('small');
					}else if($(this).is(".wish_count:not(.empty)") && !$("#basket_line .basket_fly .basket_sort ul.tabs li.cur").is("[item-section=DelDelCanBuy]")){
						$("#basket_line .basket_fly .tabs li").removeClass("cur");
						$("#basket_line .basket_fly .tabs_content li").removeClass("cur");
						$("#basket_line .basket_fly .remove_all_basket").removeClass("cur");						
						$("#basket_line .basket_fly .tabs_content li[item-section="+$(this).data("type")+"]").addClass("cur");
						$("#basket_line  .basket_fly .tabs li:eq("+$(this).index()+")").first().addClass("cur");
						$("#basket_line .basket_fly .remove_all_basket."+$(this).data("type")).first().addClass("cur");
					}else if($(this).is(".basket_count") && $("#basket_line .basket_fly .basket_sort ul.tabs li.cur").length && !$("#basket_line .basket_fly .basket_sort ul.tabs li.cur").is("[item-section=AnDelCanBuy]")){
						$("#basket_line .basket_fly .tabs li").removeClass("cur");
						$("#basket_line .basket_fly .tabs_content li").removeClass("cur");
						$("#basket_line .basket_fly .remove_all_basket").removeClass("cur");
						$("#basket_line  .basket_fly .tabs_content li:eq("+$(this).index()+")").addClass("cur");
						$("#basket_line  .basket_fly .tabs li:eq("+$(this).index()+")").first().addClass("cur");
						$("#basket_line .basket_fly .remove_all_basket."+$(this).data("type")).first().addClass("cur");
					}else{
						$("#basket_line .basket_fly").stop().animate({"right": -$("#basket_line .basket_fly").outerWidth()-5}, 150);
						$("#basket_line .basket_fly .opener > div").addClass('small');
					}
										
				});
				
				$("#basket_line .basket_fly .tabs > li").click(function(){					
					$("#basket_line .basket_fly .tabs > li").removeClass("cur");
					$("#basket_line .basket_fly .tabs_content > li").removeClass("cur");
					$("#basket_line .basket_fly .basket_sort .remove_all_basket").removeClass("cur");
					$("#basket_line .basket_fly .tabs > li:eq("+$(this).index()+")").addClass("cur");
					$("#basket_line .basket_fly .tabs_content > li:eq("+$(this).index()+")").addClass("cur");
					$("#basket_line .basket_fly .basket_sort .remove_all_basket."+$(this).data('type')).addClass("cur");
				});
				
				$("#basket_line .basket_fly .back_button, #basket_line .basket_fly .close").click(function(){
					$("#basket_line .basket_fly").stop().animate({"right": -$("#basket_line .basket_fly").outerWidth()-5}, 150);
					$("#basket_line .basket_fly .opener > div").addClass('small');
				});
				<?if ($arParams["AJAX_MODE_CUSTOM"]!="Y"):?>
					$('.basket_sort .remove_all_basket').click(function(e){
						if(!$(this).hasClass('disabled')){
							$(this).addClass('disabled');
							delete_all_items($(this).data("type"), $(".tabs_content li:eq("+$(this).index()+")").attr("item-section"), 333);
						}
						$(this).removeClass('disabled');
					})
				<?endif;?>
			});
			
			
			<?if ($arParams["AJAX_MODE_CUSTOM"]=="Y"):?>			
				var animateRow = function(row){
					row.fadeTo(100 , 0.05, function() {});
				}
			
				$("#basket_form").ready(function(){				
					$('form[name^=basket_form] .counter_block input[type=text]').change( function(e){
						e.preventDefault();
						updateQuantity($(this).attr("id"), $(this).attr("data-id"), $(this).attr("step"));
					});
					
					$('.basket_sort .remove_all_basket').click(function(e){
						if(!$(this).hasClass('disabled')){
							$(this).addClass('disabled');
							delete_all_items($(this).data("type"), $(".tabs_content li:eq("+$(this).index()+")").attr("item-section"), 333);
						}
						$(this).removeClass('disabled');
					})
					
					$('form[name^=basket_form] .remove-cell .remove').unbind('click').click(function(e){
						e.preventDefault();
						animateRow($(this).closest("tr"));
						deleteProduct($(this).parents("tr[data-id]").attr('data-id'), $(this).parents("li").attr("item-section"), $(this).parents("tr[data-id]").attr('product-id'));
						markProductRemoveBasket($(this).parents("tr[data-id]").attr('product-id'));
						return false;
					});
					
					$('form[name^=basket_form] .delay .wish_item').unbind('click').click(function(e){
						e.preventDefault();
						animateRow($(this).closest("tr"));
						delayProduct($(this).parents("tr[data-id]").attr('data-id'), $(this).parents("li").attr("item-section"));					
						markProductDelay($(this).parents("tr[data-id]").attr('product-id'));						
					});
					
					$('form[name^=basket_form] .add .wish_item').unbind('click').click(function(e){
						var basketId = $(this).parents("tr[data-id]").attr('data-id');
						var controlId =  "QUANTITY_INPUT_"+basketId;
						var ratio =  $(this).parents("tr[data-id]").find("#"+controlId).attr("step");
						var quantity =  $(this).parents("tr[data-id]").find("#"+controlId).attr("value");	

						e.preventDefault();
						animateRow($(this).closest("tr"));				
						addProduct(basketId, $(this).parents("li").attr("item-section"));						
						markProductAddBasket($(this).parents("tr[data-id]").attr('product-id'));
					});
				});
			<?endif;?>
		</script>
<?if ($arParams['INNER']!==true):?>
	</div>
<?endif;?>
<?$frame->end();?>