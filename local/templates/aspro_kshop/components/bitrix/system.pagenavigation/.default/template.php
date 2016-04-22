<?$this->setFrameMode(true);?>
<?if($arResult["NavPageCount"] > 1):?>
	<?
	$count_item = 5;
	$arResult["nStartPage"] = $arResult["NavPageNomer"] - $count_item;
	$arResult["nStartPage"] = $arResult["nStartPage"] <= 0 ? 1 : $arResult["nStartPage"];
	$arResult["nEndPage"] = $arResult["NavPageNomer"] + $count_item;
	$arResult["nEndPage"] = $arResult["nEndPage"] > $arResult["NavPageCount"] ? $arResult["NavPageCount"] : $arResult["nEndPage"];
	$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
	$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
	if($arResult["NavPageNomer"] == 1){
		$bPrevDisabled = true;
	}
	elseif($arResult["NavPageNomer"] < $arResult["NavPageCount"]){
		$bPrevDisabled = false;
	}
	if($arResult["NavPageNomer"] == $arResult["NavPageCount"]){
		$bNextDisabled = true;
	}
	else{
		$bNextDisabled = false;
	}
	?>
	<hr class="pagination-separator" />
	<div class="module-pagination">
		<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>" class="prev<?if($bPrevDisabled){echo " disabled";}?>"></a>
		<span class="nums">
			<?if($arResult["nStartPage"] > 1):?>
				<a class='point_sep'></a>
			<?endif;?>
			<?while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>
				<?if($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
					<a href="#" class="cur"><?=$arResult["nStartPage"]?></a>
				<?elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
					<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a>
				<?else:?>
					<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a>
				<?endif;?>
				<?$arResult["nStartPage"]++;?>
			<?endwhile;?>
			<?if($arResult["nEndPage"] < $arResult["NavPageCount"]):?>
				<a class='point_sep'></a>
			<?endif;?>
		</span>
		<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>" class="next<?if($bNextDisabled){echo " disabled";}?>"></a>
	</div>
<?endif;?>
<script type="text/javascript">
	$(document).ready(function(){
		$(".module-pagination span.nums a").live("click", function(){
			if(!$(this).is(".cur")){
				$(".module-pagination span.nums a").removeClass("cur"); 
				$(this).addClass("cur");
			}
		});
		$(".module-pagination .next").live("click", function(){
			if(!$(this).is(".disabled")){
				element = $(".module-pagination span.nums a.cur");
				$(".module-pagination span.nums a").removeClass("cur"); 
				element.next("span.nums a").addClass("cur");
			}
		});
		$(".module-pagination .prev").live("click", function(){
			if(!$(this).is(".disabled")){
				element = $(".module-pagination span.nums a.cur");
				$(".module-pagination span.nums a").removeClass("cur"); 
				element.prev("span.nums a").addClass("cur");
			}
		});
	});
</script>