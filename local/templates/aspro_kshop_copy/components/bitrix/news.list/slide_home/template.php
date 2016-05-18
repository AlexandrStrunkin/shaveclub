<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<div class="news_block <?=$arResult["CODE"]?>">
    <div class="news_slider_navigation news_navigation_<?=$arResult["CODE"]?>"></div>
    <div class="news_slider_wrapp slide_<?=$arResult["CODE"]?>">
        <ul class="news_slider ">
            <?foreach($arResult["ITEMS"] as $arItem){
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <li id="<?=$this->GetEditAreaId($arItem['ID']);?>" >
                    <div class="date"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></div>
                    <a class="name" href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
                    <div class="preview"><?=$arItem['~PREVIEW_TEXT'];?></div>
                </li>
            <?}?>
        </ul>

    </div>
    <ul class="flex-control-nav flex-control-paging_<?=$arResult["CODE"]?>"><?foreach($arResult["ITEMS"] as $arItem){?><li><a></a></li><?}?></ul>
</div>
<script>
        $(".slide_<?=$arResult["CODE"]?>").flexslider({
            animation: "slide",
            selector: ".news_slider > li",
            slideshow: false,
            slideshowSpeed: 6000,
            animationSpeed: 600,
            directionNav: true,
            animationLoop: true,
            itemWidth: 360,
            controlNav: true,
            pauseOnHover: true,
            controlsContainer: ".news_navigation_<?=$arResult["CODE"]?>",
            manualControls: ".news_block.<?=$arResult["CODE"]?> .flex-control-nav.flex-control-paging_<?=$arResult["CODE"]?> li a"
        });
</script>
