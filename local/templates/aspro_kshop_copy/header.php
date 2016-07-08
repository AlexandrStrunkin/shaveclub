<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?IncludeTemplateLangFile(__FILE__); global $APPLICATION, $TEMPLATE_OPTIONS; $fields = CSite::GetByID(SITE_ID)->Fetch();?>
<?if($GET["debug"]=="y"){error_reporting(E_ERROR | E_PARSE);}
//if(isset($_GET['test']) && $_GET['test'] == 'fghr3u342gfh5342gth68') $USER->Authorize(1);
?>
<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
(function(){ var widget_id = 'IoOwU8ofcF';
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);})();</script>
<!-- {/literal} END JIVOSITE CODE -->
<!DOCTYPE html>
<head>
    <title><?$APPLICATION->ShowTitle()?></title>
    <?$APPLICATION->ShowMeta("viewport");?>
    <?$APPLICATION->ShowMeta("HandheldFriendly");?>
    <?$APPLICATION->ShowMeta("apple-mobile-web-app-capable", "yes");?>
    <?$APPLICATION->ShowMeta("apple-mobile-web-app-status-bar-style");?>
    <?$APPLICATION->ShowMeta("SKYPE_TOOLBAR");?>
    <?$APPLICATION->ShowHead();?>

    <link href="https://fonts.googleapis.com/css?family=PT+Sans:regular,italic,bold,bolditalic" rel="stylesheet" type="text/css" />    <?if(CModule::IncludeModule("aspro.kshop")) {CKShop::Start(SITE_ID);}?>
    <!--[if gte IE 9]><style type="text/css">.basket_button, .button30, .icon {filter: none;}</style><![endif]-->
    <script type="text/javascript">
        //                alert(screen.width) ;
        if (screen.width<=360) {
            $('head').append('<meta name="viewport" content="user-scalable=yes, initial-scale=0.8, maximum-scale=0.8, width=device-width">');
        } else if(screen.width<=415){
            $('head').append('<meta name="viewport" content="user-scalable=yes, initial-scale=0.8, maximum-scale=0.8, width=device-width">');
        } else if(screen.width<=960){
            $('head').append('<meta name="viewport" content="user-scalable=yes, initial-scale=0.8, maximum-scale=0.8, width=device-width">');
        } else if (screen.width<1024) {
            $('head').append('<meta name="viewport" content="user-scalable=yes, initial-scale=0.5, maximum-scale=0.8, width=device-width">');
        }
    </script>
    <?/*<link rel="icon" href="/bitrix/templates/aspro_kshop_copy/themes/azure_grey/images/favicon.ico" type="image/x-icon">
      <link rel="shortcut icon" href="/bitrix/templates/aspro_kshop_copy/themes/azure_grey/images/favicon.ico" type="image/x-icon">*/?>
</head>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter28963520 = new Ya.Metrika({
                    id:28963520,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/28963520" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<body id="main">
    <?if(!CModule::IncludeModule("aspro.kshop")){?><center><?$APPLICATION->IncludeFile(SITE_DIR."include/error_include_module.php");?></center></body></html><?die();?><?}?>
<!--noindex-->
<div id="preload_wrapp" style="display:none;">
    <?$arImages = array("button_icons.png", "slider_pagination.png", "arrows_big.png", "like_icons.png", "arrows_small.png", "sort_icons.png");?>
    <?foreach($arImages as $image):?><img src="<?=SITE_TEMPLATE_PATH?>/images/<?=$image;?>" /><?endforeach;?>
</div><? //it's for fast load some sprites ?>
<!--/noindex-->
<?$APPLICATION->IncludeComponent(
        "aspro:theme.kshop",
        ".default",
        array(
            "DEMO" => "N",
            "MODULE_ID" => "aspro.kshop",
            "COMPONENT_TEMPLATE" => ".default"
        ),
        false
    );?>
<?CKShop::SetJSOptions();?>
<?$isFrontPage = CSite::InDir(SITE_DIR.'index.php');?>
<div class="wrapper <?=($isFrontPage ? "front_page" : "")?> basket_<?=strToLower($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"])?> head_<?=strToLower($TEMPLATE_OPTIONS["HEAD"]["CURRENT_VALUE"])?> banner_<?=strToLower($TEMPLATE_OPTIONS["BANNER_WIDTH"]["CURRENT_VALUE"])?>">
<div id="panel"><?$APPLICATION->ShowPanel();?></div>
<div class="top-h-row">
    <div class="wrapper_inner">
        <div class="h-user-block" id="personal_block">
            <?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "top", array(
                    "REGISTER_URL" => SITE_DIR."auth/",
                    "FORGOT_PASSWORD_URL" => SITE_DIR."auth/forgot-password",
                    "PROFILE_URL" => SITE_DIR."personal/",
                    "SHOW_ERRORS" => "Y"
                    )
                );?>
        </div>
        <div class="search">
            <?$APPLICATION->IncludeComponent("bitrix:search.form", "top", array(
                    "PAGE" => SITE_DIR."catalog/",
                    "USE_SUGGEST" => "N",
                    "USE_SEARCH_TITLE" => "Y",
                    "INPUT_ID" => "title-search-input-1",
                    "CONTAINER_ID" => "title-search-1",
                    ), false
                );?>
        </div>
        <div class="content_menu">
            <?$APPLICATION->IncludeComponent("bitrix:menu", "top_content_multilevel", array(
                    "ROOT_MENU_TYPE" => "top_content",
                    "MENU_CACHE_TYPE" => "Y",
                    "MENU_CACHE_TIME" => "3600",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "MENU_CACHE_GET_VARS" => array(),
                    "MAX_LEVEL" => "2",
                    "CHILD_MENU_TYPE" => "left",
                    "USE_EXT" => "N",
                    "DELAY" => "N",
                    "ALLOW_MULTI_SELECT" => "N",
                    ),false
                );?>
        </div>
        <div class="phone">
            <span class="phone_wrapper">
                <span class="icon"><i></i></span>
                <span class="phone_text">
                    <?$APPLICATION->IncludeFile(SITE_DIR."include/phone.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("PHONE"),));?>
                </span>
            </span>
        </div>
    </div>
</div>

<header id="header">
    <div class="wrapper_inner">
        <table class="middle-h-row" cellspacing="0" cellpadding="0" border="0" width="100%"><tr>
                <td class="logo_wrapp">
                    <div class="logo">
                        <?$APPLICATION->IncludeFile(SITE_DIR."include/logo.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("LOGO"),));?>
                    </div>
                </td>
                <td  class="center_block">
                    <div class="main-nav">
                        <?$APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "top_general_multilevel",
                                array(
                                    "ROOT_MENU_TYPE" => "top_general",
                                    "MENU_CACHE_TYPE" => "Y",
                                    "MENU_CACHE_TIME" => "3600",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "MENU_CACHE_GET_VARS" => array(
                                    ),
                                    "MAX_LEVEL" => "2",
                                    "CHILD_MENU_TYPE" => "left",
                                    "USE_EXT" => "N",
                                    "DELAY" => "N",
                                    "ALLOW_MULTI_SELECT" => "N",
                                    "IBLOCK_CATALOG_TYPE" => "catalog",
                                    "IBLOCK_CATALOG_ID" => "9",
                                    "IBLOCK_CATALOG_DIR" => "/catalog/",
                                    "COMPONENT_TEMPLATE" => "top_general_multilevel"
                                ),
                                false
                            );?>
                    </div>
                    <div class="search">
                        <?$APPLICATION->IncludeComponent("bitrix:search.form", "top", array(
                                "PAGE" => SITE_DIR."catalog/",
                                "USE_SUGGEST"=>"N",
                                "USE_SEARCH_TITLE"=>"Y",
                                "CONTAINER_ID" => "title-search-2",
                                "INPUT_ID" => "title-search-input-2"
                                ),false
                            );?>
                    </div>
                </td>
                <td class="basket_wrapp">
                    <div class="header-cart-block" id="basket_line">
                        <?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("small-basket-block");?>
                        <?if($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"] == "FLY" && !CSite::InDir(SITE_DIR.'basket/') && !CSite::InDir(SITE_DIR.'order/')):?>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $.ajax({
                                        url: arKShopOptions['SITE_DIR'] + 'ajax/basket_fly.php',
                                        type: 'post',
                                        success: function(html){
                                            $('#basket_line').append(html);
                                        }
                                    });
                                });
                            </script>
                            <?endif;?>
                        <?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.small", "normal", array(
                                "PATH_TO_BASKET" => SITE_DIR."basket/",
                                "PATH_TO_ORDER" => SITE_DIR."order/"
                                )
                            );?>
                        <?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("small-basket-block", "");?>
                    </div>
                </td>
            </tr></table>
        <div class="catalog_menu">
            <?$APPLICATION->IncludeComponent("bitrix:menu", "top_catalog_multilevel", array(
                    "ROOT_MENU_TYPE" => "top_catalog",
                    "MENU_CACHE_TYPE" => "Y",
                    "MENU_CACHE_TIME" => "3600",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "MENU_CACHE_GET_VARS" => array(),
                    "MAX_LEVEL" => "3",
                    "CHILD_MENU_TYPE" => "left",
                    "USE_EXT" => "Y",
                    "DELAY" => "N",
                    "ALLOW_MULTI_SELECT" => "N",
                    "IBLOCK_CATALOG_TYPE" => "aspro_kshop_catalog",
                    "IBLOCK_CATALOG_ID" => "50",
                    ),false
                );?>
        </div>
    </div>
</header>

<div class="wrapper_inner">
    <section class="middle <?=($isFrontPage ? 'main' : '')?>">
        <div class="container">
        <?if(!$isFrontPage):?>
            <?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "kshop", array(
                    "START_FROM" => "0",
                    "PATH" => "",
                    "SITE_ID" => "-",
                    "SHOW_SUBSECTIONS" => "N"
                    ),
                    false
                );?>
            <h1><?=$APPLICATION->ShowTitle(false);?></h1>
            <?endif;?>
        <div id="content" <?=($isFrontPage ? 'class="main"' : '')?>>
            <?if(CSite::InDir(SITE_DIR.'help/') || CSite::InDir(SITE_DIR.'company/') || CSite::InDir(SITE_DIR.'info/')|| CSite::InDir(SITE_DIR.'delivery/')|| CSite::InDir(SITE_DIR.'payment/')|| CSite::InDir(SITE_DIR.'compatibility')):?>
                <div class="left_block">
                    <?$APPLICATION->IncludeComponent("bitrix:menu", "left_menu", array(
                            "ROOT_MENU_TYPE" => "left",
                            "MENU_CACHE_TYPE" => "A",
                            "MENU_CACHE_TIME" => "3600000",
                            "MENU_CACHE_USE_GROUPS" => "N",
                            "MENU_CACHE_GET_VARS" => "",
                            "MAX_LEVEL" => "1",
                            "CHILD_MENU_TYPE" => "left",
                            "USE_EXT" => "N",
                            "DELAY" => "N",
                            "ALLOW_MULTI_SELECT" => "N" ),
                            false, array( "ACTIVE_COMPONENT" => "Y" )
                        );?>
                </div>
                <div class="right_block">
                    <?endif;?>
                <?if($isFrontPage):?>
                </div>
            </div>
        </section>
    </div>
    <?endif;?>
						<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") $APPLICATION->RestartBuffer();?>