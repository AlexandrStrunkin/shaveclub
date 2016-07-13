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
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/responsive.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/animate.css">
    <link href="css/how.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=PT+Serif:400,700,400italic,700italic&subset=latin,cyrillic,latin-ext,cyrillic-ext' rel='stylesheet' type='text/css'>
    <script src="/js/wow.js"></script>
    <script type="text/javascript" src="/js/how.js"></script>
    <link type="text/css" href="/css/jquery.jscrollpane.css" rel="stylesheet" media="all"/>
    <script type="text/javascript" src="/js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="/js/jquery.jscrollpane.min.js"></script>

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
<div class="howGridContainer">
    <div>
        <div>
            <div>
                <h2>как это работает</h2>
                <span>Как получить бритвы и пользоваться планами<br> 
                    бритья в «Просто Клуб Бритв»?</span>
            </div>
            <div>
                <div>
                    <div class="whiteBG"></div>
                    <h2>радуйся</h2>
                    <span>Комфортному и свежему бритью в течение 
                        срока плана бритья!</span>
                    <div class="orangeSep"></div>
                </div>
            </div>
        </div>
        <div>
            <div class="steps_block">
                <h3>01</h3>
                <div class="block_description">
                    выбери подходящие бритву и план бритья
                </div>
                <img src="/images/how/first_step_block_icon.png">
                <div class="description_box">
                    <div></div>
                    <p><?$APPLICATION->IncludeFile(SITE_DIR."include/how_block_1.php",Array(),Array("MODE"=>"html"));?></p>
                </div>
            </div>
            <div class="steps_block">
                <h3>02</h3>
                <div class="block_description">
                    Оформи и оплати заказ удобным способом
                </div>
                <img src="/images/how/second_step_block_icon.png">
                <div class="description_box">
                    <div></div>
                    <p><?$APPLICATION->IncludeFile(SITE_DIR."include/how_block_2.php",Array(),Array("MODE"=>"html"));?></p>
                </div>
            </div>
            <div class="steps_block">
                <h3>03</h3>
                <div class="block_description">
                    Получи бритвы и кассеты 
                    курьером или по почте
                </div>
                <img src="/images/how/third_step_block_icon.png">
                <div class="description_box">
                    <div></div>
                    <p><?$APPLICATION->IncludeFile(SITE_DIR."include/how_block_3.php",Array(),Array("MODE"=>"html"));?></p>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div >
            <span>Будем напоминать заменить «тупые»<br> 
                бритвенные кассеты.</span>
        </div>
        <div id="smileBlock">

            <div class="smileLayer">
                <img src="/images/how/smile.png" alt="">
                <span>сделаем вашу жизнь удобнее<br> 
                    и приятнее</span>
            </div>
            <div class="smileHoverOverlay">
                <img src="/images/how/smileOverlay.png" alt="">
                <span>а в это время мы</span>
            </div>
        </div>
        <div >
            <span>Поймем, когда у Вас кончатся «свежие»<br> 
                бритвы, и предложим купить новые.</span>
        </div>
    </div>
    <div>
        <div>И именно так «Просто Клуб Бритв» станет <span>Вашим помощником</span> в этом бренном мире 
            бритья, наполненном никому не нужной рекламы и обмана!</div>
    </div>
</div>
