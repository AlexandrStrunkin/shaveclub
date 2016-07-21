<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if($arResult):?>
    <ul class="menu adaptive">
        <li class="menu_opener"><a><?=GetMessage('MENU_NAME')?></a><i class="icon"></i></li>
    </ul>
    <ul class="menu full">
        <?foreach($arResult as $arItem):?>
            <li class="<?=($arItem["SELECTED"] ? ' current' : '')?><?=($arItem["LINK"] == $arParams["IBLOCK_CATALOG_DIR"] ? ' catalog' : '')?>">
                <a href="<?=$arItem["LINK"]?>">
                    <?=$arItem["TEXT"]?>
                    <?=($arItem["LINK"] == $arParams["IBLOCK_CATALOG_DIR"] ? '<i></i>' : '')?>
                    <?=($arItem["LINK"] == $arParams["IBLOCK_CATALOG_DIR"] || $arItem["IS_PARENT"] == 1 ? '<b class="space"></b>' : '')?>
                </a>
                <?if($arItem["IS_PARENT"] == 1):?>
                    <div class="child submenu">
                        <div class="child_wrapp">
                            <?foreach($arItem["CHILD"] as $arSubItem):?>
                                <a class="<?=($arSubItem["SELECTED"] ? ' current' : '')?>" href="<?=$arSubItem["LINK"]?>"><?=$arSubItem["TEXT"]?></a>
                                <?endforeach;?>
                        </div>
                    </div>
                    <?endif;?>
                <?if($arItem["LINK"] == $arParams["IBLOCK_CATALOG_DIR"]):?>
                    <?$APPLICATION->IncludeComponent(
                            "bitrix:catalog.section.list", 
                            "top_menu", 
                            array(
                                "IBLOCK_TYPE" => "aspro_kshop_catalog",
                                "IBLOCK_ID" => $arParams["IBLOCK_CATALOG_ID"],
                                "SECTION_ID" => "",
                                "SECTION_CODE" => "",
                                "COUNT_ELEMENTS" => "N",
                                "TOP_DEPTH" => "2",
                                "SECTION_FIELDS" => array(
                                    0 => "",
                                    1 => "",
                                ),
                                "SECTION_USER_FIELDS" => array(
                                    0 => "",
                                    1 => "",
                                ),
                                "SECTION_URL" => "/catalog/#SECTION_CODE_PATH#/",
                                "CACHE_TYPE" => "A",
                                "CACHE_TIME" => "3600",
                                "CACHE_GROUPS" => "N",
                                "ADD_SECTIONS_CHAIN" => "N",
                                "COMPONENT_TEMPLATE" => "top_menu"
                            ),
                            false
                        );?>
                    <?endif;?>
            </li>
            <?endforeach;?>
        <li class="stretch"></li>
        <li class="search_row">
            <?$APPLICATION->IncludeComponent("bitrix:search.form", "top", array(
                    "PAGE" => SITE_DIR."catalog/",
                    "USE_SUGGEST" => "N",
                    "USE_SEARCH_TITLE" => "Y",
                    "INPUT_ID" => "title-search-input-3",
                    "CONTAINER_ID" => "title-search-3"
                    ), false
                );?>
        </li>
    </ul>
    <script type="text/javascript">
        $(document).ready(function() {
            $("ul.menu.adaptive .menu_opener").click(function(){
                $(this).parents(".menu.adaptive").toggleClass("opened");
                $("ul.menu.full").toggleClass("opened").slideToggle(200);
            });

            $(".main-nav .menu > li:not(.current):not(.menu_opener) > a").click(function(){
                $(this).parents("li").siblings().removeClass("current");
                $(this).parents("li").addClass("current");
            });

            $(".main-nav .menu .child_wrapp a").click(function(){
                $(this).siblings().removeClass("current");
                $(this).addClass("current");
            });
        });
    </script>
    <?endif;?>