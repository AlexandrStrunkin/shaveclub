<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? if (!function_exists('declOfNum')) { function declOfNum($number, $titles) { $cases = array (2, 0, 1, 1, 1, 2); return sprintf($titles[ ($number%100>4 && $number%100<20)? 2 : $cases[min($number%10, 5)] ], $number); }}?>
<!--noindex-->  
	<?if(count($arResult) > 0):?>
		<?	
			global $compare_items;
			foreach($arResult as $arItem){ $compare_items[] = $arItem["ID"]; }		
		?>	
		<script>
			$("h1").addClass("shifted");
			$(document).ready(function(){
				<?foreach( $compare_items as $item ){?>
					$('.compare_item[data-item=<?=$item?>]').addClass("added");
					if ($('.compare_item[data-item=<?=$item?>]').find(".value.added").length) { $('.compare_item[data-item=<?=$item?>]').find(".value").hide(); $('.compare_item[data-item=<?=$item?>]').find(".value.added").show(); }
				<?}?>
			});
		</script>
		<div class="compare_wrapp<?if( count($arResult) == 1 ) echo " centered"?>">
			<form action="<?=$arParams["COMPARE_URL"]?>" method="get">
				<input type="hidden" name="action" value="COMPARE" />
				<input type="hidden" name="IBLOCK_ID" value="<?=$arParams["IBLOCK_ID"]?>" />
				<?if( count($arResult) > 1 ){?><button type="submit" name="web_form_submit" class="compare_button"><i></i><span><?=GetMessage("CATALOG_COMPARE")?></span></button><?}?>
				<a rel="nofollow" class="compare_link"><span><?if(count($arResult)==1){echo GetMessage("IN_COMPARE")."&nbsp;";}?><?=count($arResult).' '.declOfNum(count($arResult), array( GetMessage("ONE_ITEM"), GetMessage("TWO_ITEM"), GetMessage("MORE_ITEM") ))?></span></a>
			</form>
		</div>
	<?endif;?>
	
	<script>
		$('.compare_frame').jqmAddTrigger('a.compare_link');	
	</script>  
<!--/noindex-->
