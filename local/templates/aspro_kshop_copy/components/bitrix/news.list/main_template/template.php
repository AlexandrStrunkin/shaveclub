<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<? if (count($arResult["ITEMS"])){ ?>
    <img class="shadow t30" src="<?= SITE_TEMPLATE_PATH ?>/images/shadow_bottom.png" />
    <div class="articles-list news">
        <? foreach($arResult["ITEMS"] as $arItem){
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <section class="item clearfix" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                <?if($arItem["PREVIEW_PICTURE"]){?>
                    <?$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_EXACT, true );?>
                    <div class="left-data">
                        <a href="<?= $arItem["DETAIL_PAGE_URL"]?>" class="thumb"><img src="<?= $img["src"] ?>" alt="<?= ($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"]) ?>" title="<?= ($arItem["PREVIEW_PICTURE"]["TITLE"] ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"]) ?>" /></a>
                    </div>
                <?}elseif($arItem["DETAIL_PICTURE"]){?>
                    <?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_EXACT, true );?>
                    <div class="left-data">
                        <a href="<?= $arItem["DETAIL_PAGE_URL"]?>" class="thumb"><img src="<?= $img["src"] ?>" alt="<?= ($arItem["DETAIL_PICTURE"]["ALT"] ? $arItem["DETAIL_PICTURE"]["ALT"] : $arItem["NAME"]) ?>" title="<?= ($arItem["DETAIL_PICTURE"]["TITLE"] ? $arItem["DETAIL_PICTURE"]["TITLE"] : $arItem["NAME"]) ?>" /></a>
                    </div>
                <?}else{?>
                    <div class="left-data">
                        <a href="<?= $arItem["DETAIL_PAGE_URL"]?>" class="thumb"><img src="<?= SITE_TEMPLATE_PATH ?>/images/no_photo_medium.png" alt="<?= $arItem["NAME"] ?>" title="<?= $arItem["NAME"] ?>" height="90" /></a>
                    </div>
                <?};?>
                <div class="right-data">
                    <div class="item-title"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?= $arItem["NAME"] ?></span></a></div>
                    <div class="preview-text"><?=$arItem["PREVIEW_TEXT"]?></div>
                    <div class="date_small"><?//=$arItem["DISPLAY_ACTIVE_FROM"]?></div>
                    <div class="news_date_time_detail"><?=$arItem["PROPERTIES"]["PERIOD"]["VALUE"]?></div>
                </div>
            </section>
        <?}?>
    </div>
    <?if( $arParams["DISPLAY_BOTTOM_PAGER"] ){?><?=$arResult["NAV_STRING"]?><?}?>
<?};?>