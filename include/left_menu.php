<div class="menu">
    <div class="menu-arrow"></div>
    <a href="/" class="menu-logo">
        <?$APPLICATION->IncludeFile(SITE_DIR."include/company_logo.php",Array(),Array("MODE"=>"html"));?>
    </a>
    <span class="menu-logo-text"> 
        <?$APPLICATION->IncludeFile(SITE_DIR."include/about_title.php",Array(),Array("MODE"=>"html"));?>
    </span>

    <?$APPLICATION->IncludeComponent("bitrix:menu", "left_menu", Array(
            "ROOT_MENU_TYPE" => "left",    // Тип меню для первого уровня
            "MAX_LEVEL" => "1",    // Уровень вложенности меню
            "CHILD_MENU_TYPE" => "left",    // Тип меню для остальных уровней
            "USE_EXT" => "N",    // Подключать файлы с именами вида .тип_меню.menu_ext.php
            "DELAY" => "N",    // Откладывать выполнение шаблона меню
            "ALLOW_MULTI_SELECT" => "N",    // Разрешить несколько активных пунктов одновременно
            "MENU_CACHE_TYPE" => "N",    // Тип кеширования
            "MENU_CACHE_TIME" => "3600",    // Время кеширования (сек.)
            "MENU_CACHE_USE_GROUPS" => "Y",    // Учитывать права доступа
            "MENU_CACHE_GET_VARS" => "",    // Значимые переменные запроса
            ),
            false
        );?>


    <?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"left_menu", 
	array(
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"AJAX_MODE" => "N",
		"IBLOCK_TYPE" => "content",
		"IBLOCK_ID" => "18",
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_STATUS_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "Y",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "Y",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);?>

    <div class="menu-soc">
        <ul>
            <?$soc = CIBlockElement::GetList(array(),array("IBLOCK_CODE"=>"social","ACTIVE"=>"Y"),false,false,array("NAME","PREVIEW_PICTURE"));
                while($arSoc = $soc->Fetch()) {
                ?>      
                <li><a href="<?=$arSoc["NAME"]?>" target="_blank" style="background: url(<?=CFile::GetPath($arSoc["PREVIEW_PICTURE"])?>) no-repeat ;"></a></li>     
                <?}?>   
        </ul>
    </div>
    <div class="menu-phone">       
        <?$APPLICATION->IncludeFile(SITE_DIR."include/company_phone.php",Array(),Array("MODE"=>"html"));?>
    </div>    
    
    <div class="webgk">создание сайта - <a href="http://webgk.ru" target="_blank">webgk.ru</a></div>
</div>