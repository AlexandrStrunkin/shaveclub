<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<ul class="left_menu stores_list">
	<?foreach( $arResult["ITEMS"] as $arItem ){
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
		<li <?if (CSite::InDir($arItem["DETAIL_PAGE_URL"])||($arItem['ID'] == $arParams["VARIABLES"]["CURRENT_ELEMENT"])):?>class="current"<?endif;?> id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
				<span><?=$arItem["NAME"]?></span>
			</a>
		</li>
	<?}?>
</ul>
<script>$(".left_menu.stores_list li:not(.current)").click(function() { $(this).addClass("current").siblings().removeClass("current"); });</script>