<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
    IncludeTemplateLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/header.php");
    $wizTemplateId = COption::GetOptionString("main", "wizard_template_id", "eshop_adapt_horizontal", SITE_ID);
    CUtil::InitJSCore();
    CJSCore::Init(array("fx"));
    $curPage = $APPLICATION->GetCurPage(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
<!--    <meta name="viewport" content="user-scalable=yes, initial-scale=0.5, maximum-scale=0.8, width=device-width">  -->
    <link rel="shortcut icon" type="image/x-icon" href="<?=SITE_DIR?>/favicon.ico" />
    <?//$APPLICATION->ShowHead();
        echo '<meta http-equiv="Content-Type" content="text/html; charset='.LANG_CHARSET.'"'.(true ? ' /':'').'>'."\n";
        $APPLICATION->ShowMeta("robots", false, true);
        $APPLICATION->ShowMeta("keywords", false, true);
        $APPLICATION->ShowMeta("description", false, true);
        $APPLICATION->ShowCSS(true, true);
    ?>
    <link rel="stylesheet" type="text/css" href="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH."/colors.css")?>" />
    <?
        $APPLICATION->ShowHeadStrings();
        $APPLICATION->ShowHeadScripts();
        $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/script.js");
    ?>
    <title><?$APPLICATION->ShowTitle()?></title>

    <script src="https://code.jquery.com/jquery-1.8.0.min.js"></script>
    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/responsive.css" >
    <link rel="stylesheet" href="/css/animate.css">
    <link rel="stylesheet" href="/css/inside.css">
    <link rel="stylesheet" href="/css/contacts.css" > 
    <link rel="stylesheet" href="/css/cusel.css" >

    <link href='https://fonts.googleapis.com/css?family=PT+Serif:400,700,400italic,700italic&subset=latin,cyrillic,latin-ext,cyrillic-ext' rel='stylesheet' type='text/css'>

    <script src="/js/wow.js"></script>

    <script type="text/javascript" src="/js/cusel.js"></script>
    <script type="text/javascript" src="/js/jScrollPane.js"></script>
    <script type="text/javascript" src="/js/jquery.mousewheel.js"></script> 

    <link type="text/css" href="/css/jquery.jscrollpane.css" rel="stylesheet" media="all"/>
    <script type="text/javascript" src="/js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="/js/jquery.jscrollpane.min.js"></script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

    <script type="text/javascript" src="/js/jquery.fancybox.js"></script>      
    <link type="text/css" href="/css/jquery.fancybox.css" rel="stylesheet" media="all"/>

    <script src="/js/index.js"></script>

    <script type="text/javascript">
        //alert(screen.width) ;
        if (screen.width<=360) {
            $('head').append('<meta name="viewport" content="user-scalable=yes, initial-scale=0.3, maximum-scale=0.8, width=device-width">');
        } else if(screen.width<=960){
            $('head').append('<meta name="viewport" content="user-scalable=yes, initial-scale=0.8, maximum-scale=0.8, width=device-width">');
        } else if (screen.width<1024) {
            $('head').append('<meta name="viewport" content="user-scalable=yes, initial-scale=0.5, maximum-scale=0.8, width=device-width">');
        }
    </script>

</head>
<body>
<div id="panel"><?$APPLICATION->ShowPanel();?></div>
<div class="wrapper">

<?include($_SERVER["DOCUMENT_ROOT"]."/include/forms.php");?>

<?include($_SERVER["DOCUMENT_ROOT"]."/include/left_menu.php");?>

<div class="main-container inside-page oform-page">

<?include($_SERVER["DOCUMENT_ROOT"]."/include/mobile_top.php");?> 


<div class="inside-page-col">
    <div class="div">
        <div class="">
            <h1 class="h1">контакты</h1>
            <table class="contacts-page-table">
                <?
                    //собираем контакты
                    $contacts = CIBlockElement::GetList(array("SORT"=>"ASC"), array("IBLOCK_CODE"=>"contacts"),false, false,array("NAME","CODE"));
                    while($arContact = $contacts->Fetch()){
                    ?>
                    <tr>
                        <td><?=$arContact["NAME"]?></td>
                        <td><?=$arContact["CODE"]?></td>
                    </tr>
                    <?}?>

            </table>
            <?$APPLICATION->IncludeComponent(
                    "bitrix:iblock.element.add.form", 
                    "feedback_form", 
                    array(
                        "SEF_MODE" => "N",
                        "IBLOCK_TYPE" => "content",
                        "IBLOCK_ID" => "24",
                        "PROPERTY_CODES" => array(
                            0 => "NAME",
                            1 => "PREVIEW_TEXT",
                            2 => "168",
                        ),
                        "PROPERTY_CODES_REQUIRED" => array(
                            0 => "NAME",
                            1 => "PREVIEW_TEXT",
                            2 => "168",
                        ),
                        "GROUPS" => array(
                            0 => "2",
                        ),
                        "STATUS_NEW" => "N",
                        "STATUS" => "ANY",
                        "LIST_URL" => "",
                        "ELEMENT_ASSOC" => "CREATED_BY",
                        "MAX_USER_ENTRIES" => "100000",
                        "MAX_LEVELS" => "100000",
                        "LEVEL_LAST" => "Y",
                        "USE_CAPTCHA" => "N",
                        "USER_MESSAGE_EDIT" => "",
                        "USER_MESSAGE_ADD" => "Ваше сообщение отправлено! Спасибо! Мы свяжемся с вами в ближайшее время.",
                        "DEFAULT_INPUT_SIZE" => "30",
                        "RESIZE_IMAGES" => "N",
                        "MAX_FILE_SIZE" => "0",
                        "PREVIEW_TEXT_USE_HTML_EDITOR" => "N",
                        "DETAIL_TEXT_USE_HTML_EDITOR" => "N",
                        "CUSTOM_TITLE_NAME" => "Ваше имя",
                        "CUSTOM_TITLE_TAGS" => "",
                        "CUSTOM_TITLE_DATE_ACTIVE_FROM" => "",
                        "CUSTOM_TITLE_DATE_ACTIVE_TO" => "",
                        "CUSTOM_TITLE_IBLOCK_SECTION" => "",
                        "CUSTOM_TITLE_PREVIEW_TEXT" => "Сообщение",
                        "CUSTOM_TITLE_PREVIEW_PICTURE" => "",
                        "CUSTOM_TITLE_DETAIL_TEXT" => "",
                        "CUSTOM_TITLE_DETAIL_PICTURE" => "",
                        "SEF_FOLDER" => "/test/"
                    ),
                    false
                );?>

        </div>

    </div>

</div>


<div class="inside-page-col">

    <span class="inside-page-col-shadow"></span>    

    <?$APPLICATION->IncludeComponent(
	"bitrix:map.google.view", 
	"contacts_map", 
	array(
		"INIT_MAP_TYPE" => "ROADMAP",
		"MAP_DATA" => "a:4:{s:10:\"google_lat\";d:55.896523398615;s:10:\"google_lon\";d:37.39050231530996;s:12:\"google_scale\";i:20;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:4:\"TEXT\";s:0:\"\";s:3:\"LON\";d:37.39054888486862;s:3:\"LAT\";d:55.896422916288216;}}}",
		"MAP_WIDTH" => "600",
		"MAP_HEIGHT" => "500",
		"CONTROLS" => array(
			0 => "SMALL_ZOOM_CONTROL",
			1 => "TYPECONTROL",
			2 => "SCALELINE",
		),
		"OPTIONS" => array(
			0 => "ENABLE_DBLCLICK_ZOOM",
			1 => "ENABLE_DRAGGING",
			2 => "ENABLE_KEYBOARD",
		),
		"MAP_ID" => "",
		"COMPONENT_TEMPLATE" => "contacts_map"
	),
	false
);?>

</div>