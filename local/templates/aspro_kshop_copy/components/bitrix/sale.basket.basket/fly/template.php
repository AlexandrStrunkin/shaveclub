<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(!function_exists('declOfNum')){
	function declOfNum($number, $titles) {
		$cases = array (2, 0, 1, 1, 1, 2);
		return sprintf($titles[ ($number%100>4 && $number%100<20)? 2 : $cases[min($number%10, 5)] ], $number);
	}
}

$normalCount = count($arResult["ITEMS"]["AnDelCanBuy"]);
$delayCount = count($arResult["ITEMS"]["DelDelCanBuy"]);
$subscribeCount = count($arResult["ITEMS"]["ProdSubscribe"]);
$naCount = count($arResult["ITEMS"]["nAnCanBuy"]);
$paramsString = urlencode(serialize($arParams));
?>
<?$frame = $this->createFrame()->begin('');?>
<div class="basket_fly<?if (strlen($arResult["ERROR_MESSAGE"]) > 0):?> basket_empty<?endif;?>">
	<input type="hidden" name="total_price" value="<?=$summ?>" />
	<input type="hidden" name="total_discount_price" value="<?=$summ?>" />
	<input type="hidden" name="total_count" value="<?=$normalCount;?>" />
	<input type="hidden" name="delay_count" value="<?=$delayCount;?>" />

	<div class="opener">
		<div class="basket_count<?=($normalCount?"":" empty")?>"><span class="icon"><i></i></span><div class="count"><?=$normalCount?></div></div>
		<div class="wish_count<?=($delayCount?"":" empty")?>"><span class="icon"><i></i></span><div class="count"><?=$delayCount?></div></div>
	</div>
	<script src="<?=$templateFolder.'/script.js'?>" type="text/javascript"></script>
	<?
		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/functions.php");
		$arUrls = Array("delete" => SITE_DIR."ajax/show_basket_fly.php?action=delete&id=#ID#",
						"delay" => SITE_DIR."ajax/show_basket_fly.php?action=delay&id=#ID#",
						"add" => SITE_DIR."ajax/show_basket_fly.php?action=add&id=#ID#");


			if (is_array($arResult["WARNING_MESSAGE"]) && !empty($arResult["WARNING_MESSAGE"])) { foreach ($arResult["WARNING_MESSAGE"] as $v) { echo ShowError($v); } }

			$arMenu = array(array("ID"=>"AnDelCanBuy", "TITLE"=>GetMessage("SALE_BASKET_ITEMS"), "COUNT"=>$normalCount, "FILE"=>"/basket_items.php"));
			if ($delayCount) { $arMenu[] = array("ID"=>"DelDelCanBuy", "TITLE"=>GetMessage("SALE_BASKET_ITEMS_DELAYED"), "COUNT"=>$delayCount, "FILE"=>"/basket_items_delayed.php"); }
			if ($subscribeCount) { $arMenu[] = array("ID"=>"ProdSubscribe", "TITLE"=>GetMessage("SALE_BASKET_ITEMS_SUBSCRIBED"), "COUNT"=>$subscribeCount, "FILE"=>"/basket_items_subscribed.php"); }
			if ($naCount) { $arMenu[] = array("ID"=>"nAnCanBuy", "TITLE"=>GetMessage("SALE_BASKET_ITEMS_NOT_AVAILABLE"), "COUNT"=>$naCount, "FILE"=>"/basket_items_not_available.php"); }

	?>
		<div class="basket_sort">
			<span class="basket_title"><?=GetMessage("BASKET_TITLE");?></span>
			<ul class="tabs">
				<?if (strlen($arResult["ERROR_MESSAGE"]) <= 0){?>
					<?foreach($arMenu as $key => $arElement){?>
						<li<?=($arElement["SELECTED"] ? ' class="cur"' : '');?> item-section="<?=$arElement["ID"]?>">
							<span><?=$arElement["TITLE"]?></span>
							<span class="quantity">&nbsp;(<span class="count"><?=$arElement["COUNT"]?></span>)</span>
							<i class="triangle"></i>
						</li>
					<?}?>
				<?}?>
			</ul>
		</div>

		<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form" id="basket_form" class="basket_wrapp">
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
			$("#basket_line .basket_fly").ready(function()
			{
				$("#basket_line .basket_fly").live('submit', function(e) {
					e.preventDefault();
				});
				$('#basket_line .basket_fly a.apply-button').click(function()
				{
					$('#basket_line .basket_fly form[name^=basket_form]').prepend('<input type="hidden" name="BasketRefresh" value="Y" />');
					$.post( arKShopOptions['SITE_DIR']+'basket/', $("#basket_line .basket_fly form[name^=basket_form]").serialize(), $.proxy(
					function( data)
					{
						$('#basket_line .basket_fly form[name^=basket_form] input[name=BasketRefresh]').remove();
						animateBasketLine();
						postAnimateResult(data, 333, 'update');
					}));
				});

				$("#basket_line .basket_fly .opener > div, .button30.close_basket_popup").live("click", function()
				{
					if (parseInt($("#basket_line .basket_fly").css("right"))<0)
					{
						$("#basket_line .basket_fly").stop().animate({"right": "0"}, 333);
						$("#basket_line .basket_fly .tabs li").removeClass("cur");
						$("#basket_line .basket_fly .tabs_content li").removeClass("cur");
						if (!$(this).is(".wish_count.empty"))
						{
							$("#basket_line  .basket_fly .tabs_content li:eq("+$(this).index()+")").addClass("cur");
							$("#basket_line  .basket_fly .tabs li:eq("+$(this).index()+")").first().addClass("cur");
						}
						else
						{
							$("#basket_line .basket_fly .tabs li").first().addClass("cur").siblings().removeClass("cur");
							$("#basket_line .basket_fly .tabs_content li").first().addClass("cur").siblings().removeClass("cur");
						}
					}
					else if($(this).is(".wish_count:not(.empty)") && !$("#basket_line .basket_fly .basket_sort ul.tabs li.cur").is("[item-section=DelDelCanBuy]"))
					{
						$("#basket_line .basket_fly .tabs li").removeClass("cur");
						$("#basket_line .basket_fly .tabs_content li").removeClass("cur");
						$("#basket_line  .basket_fly .tabs_content li:eq("+$(this).index()+")").addClass("cur");
						$("#basket_line  .basket_fly .tabs li:eq("+$(this).index()+")").first().addClass("cur");
					}
					else if($(this).is(".basket_count") && $("#basket_line .basket_fly .basket_sort ul.tabs li.cur").length && !$("#basket_line .basket_fly .basket_sort ul.tabs li.cur").is("[item-section=AnDelCanBuy]"))
					{
						$("#basket_line .basket_fly .tabs li").removeClass("cur");
						$("#basket_line .basket_fly .tabs_content li").removeClass("cur");
						$("#basket_line  .basket_fly .tabs_content li:eq("+$(this).index()+")").addClass("cur");
						$("#basket_line  .basket_fly .tabs li:eq("+$(this).index()+")").first().addClass("cur");
					}
					else
					{
						$("#basket_line .basket_fly").stop().animate({"right": -$("#basket_line .basket_fly").outerWidth()}, 150);
					}

					checkRowValuesFly("#basket_line .basket_fly");
				});

				$("#basket_line .basket_fly .tabs > li").live("click", function()
				{
					$("#basket_line .basket_fly .tabs > li").removeClass("cur");
					$("#basket_line .basket_fly .tabs_content > li").removeClass("cur");
					$("#basket_line .basket_fly .tabs > li:eq("+$(this).index()+")").addClass("cur");
					$("#basket_line .basket_fly .tabs_content > li:eq("+$(this).index()+")").addClass("cur");
				});

				$("#basket_line .basket_fly .back_button, #basket_line .basket_fly .button30.close").live("click", function(){
					$("#basket_line .basket_fly").stop().animate({"right": -$("#basket_line .basket_fly").outerWidth()}, 150);
				});
			});


			<?if ($arParams["AJAX_MODE_CUSTOM"]=="Y"):?>
				var animateRow = function(row)
				{
					$(row).find("td.thumb-cell img").css({"maxHeight": "inherit", "maxWidth": "inherit"}).fadeTo(50, 0);
					var columns = $(row).find("td");
					$(columns).wrapInner('<div class="slide"></div>');
					$(row).find(".summ-cell").wrapInner('<div class="slide"></div>');
					setTimeout(function(){$(columns).animate({"paddingTop": 0, "paddingBottom": 0}, 50)}, 0);
					$(columns).find(".slide").slideUp(333);
				}

				$("#basket_form").ready(function()
				{
					$('form[name^=basket_form] .counter_block input[type=text]').change( function(e)
					{
						e.preventDefault();
						updateQuantity($(this).attr("id"), $(this).attr("data-id"), $(this).attr("step"));
					});

					$('form[name^=basket_form] .remove').live("click", function(e)
					{
						e.preventDefault();
						animateRow($(this).parents("tr[data-id]"));
						deleteProduct($(this).parents("tr[data-id]").attr('data-id'), $(this).parents("li").attr("item-section"));
						var productId = $(this).parents("tr[data-id]").attr('product-id');
						$('.basket_button.in-cart[data-item='+productId+']').hide();
						$('.basket_button.to-cart[data-item='+productId+']').show();
						$('.basket_button.in-subscribe[data-item='+productId+']').hide();
						$('.basket_button.to-subscribe[data-item='+productId+']').show();
						$('.wish_item[data-item='+productId+']').removeClass("added");
					});

					$('form[name^=basket_form] .delay .wish_item').live("click", function(e)
					{
						e.preventDefault();
						animateRow($(this).parents("tr[data-id]"));
						delayProduct($(this).parents("tr[data-id]").attr('data-id'), $(this).parents("li").attr("item-section"));
						var productId = $(this).parents("tr[data-id]").attr('product-id');
						$('.basket_button.in-cart[data-item='+productId+']').hide();
						$('.basket_button.to-cart[data-item='+productId+']').show();
					})

					$('form[name^=basket_form] .add .wish_item').live("click", function(e)
					{
						e.preventDefault();
						animateRow($(this).parents("tr[data-id]"));
						var basketId = $(this).parents("tr[data-id]").attr('data-id');
						var controlId =  "QUANTITY_INPUT_"+basketId;
						var ratio =  $(this).parents("tr[data-id]").find("#"+controlId).attr("step");
						var quantity =  $(this).parents("tr[data-id]").find("#"+controlId).attr("value");
						updateQuantity(controlId, basketId, ratio, false);
						addProduct(basketId, $(this).parents("li").attr("item-section"));
						var productId = $(this).parents("tr[data-id]").attr('product-id');
						$('.basket_button.to-cart[data-item='+productId+']').hide();
						$('.basket_button.in-cart[data-item='+productId+']').show();
					})
				});
			<?endif;?>
		</script>
</div>
<?$frame->end();?>