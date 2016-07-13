<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
    IncludeTemplateLangFile('__FILE__');
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
    <link rel="stylesheet" href="/css/index.css">
    <link href='https://fonts.googleapis.com/css?family=PT+Serif:400,700,400italic,700italic&subset=latin,cyrillic,latin-ext,cyrillic-ext' rel='stylesheet' type='text/css'>
    <script src="/js/wow.js"></script>

    <link type="text/css" href="/css/jquery.jscrollpane.css" rel="stylesheet" media="all"/>
    <script type="text/javascript" src="/js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="/js/jquery.jscrollpane.min.js"></script>

    <script type="text/javascript" src="/js/jquery.fancybox.js"></script>
    <link type="text/css" href="/css/jquery.fancybox.css" rel="stylesheet" media="all"/>


    <!--    <meta name="viewport" content="user-scalable=yes, initial-scale=0.5, maximum-scale=0.8, width=device-width">  -->
    <script type="text/javascript">
        //                alert(screen.width) ;
        if (screen.width<=360) {
            $('head').append('<meta name="viewport" content="user-scalable=yes, initial-scale=0.3, maximum-scale=0.8, width=device-width">');
        } else if(screen.width<=415){
            $('head').append('<meta name="viewport" content="user-scalable=yes, initial-scale=0.5, maximum-scale=0.8, width=device-width">');
        } else if(screen.width<=960){
            $('head').append('<meta name="viewport" content="user-scalable=yes, initial-scale=0.8, maximum-scale=0.8, width=device-width">');
        } else if (screen.width<1024) {
            $('head').append('<meta name="viewport" content="user-scalable=yes, initial-scale=0.5, maximum-scale=0.8, width=device-width">');
        }
    </script>

    <script src="/js/inputmask.js"></script>

    <script>
        $(function() {
            //создаем маску ввода для поля активации сертификата
            $(".sertActivate").inputmask("****-****-****-****",{ "placeholder": "xxxx-xxxx-xxxx-xxxx", greedy: false });
        })
    </script>

    <script src="/js/index.js"></script>
    <?
        //получаем ID нужного баннера
        if ($APPLICATION->GetCurPage() == "/") {
            $banner_code = "man";
        }
        else {

            $banner_code = trim(str_replace("/","",$APPLICATION->GetCurPage()));

            //в зависимости от раздела подключаются дополнительные стили (только для каталожных разделов)
            $catalogSections = array("woman","gift","slalom","gillette");
            if (in_array($banner_code,$catalogSections)) {
            ?>
            <link href="/css/<?=$banner_code?>.css" rel="stylesheet">
            <?
            }
        }

        $mainBanner = CIBlockElement::GetList(array(),array("IBLOCK_ID"=>13,"CODE"=>$banner_code),false, false, array("ID"));
        $arMainBanner = $mainBanner->Fetch();
    ?>

</head>
<body>
<div id="panel"><?$APPLICATION->ShowPanel();?></div>



<div class="wrapper">
<?include($_SERVER["DOCUMENT_ROOT"]."/include/forms.php");?>

<?include($_SERVER["DOCUMENT_ROOT"]."/include/left_menu.php");?>

<div class="main-container">

<?include($_SERVER["DOCUMENT_ROOT"]."/include/mobile_top.php");?>


<?

?>
<?$APPLICATION->IncludeComponent(
        "bitrix:news.detail",
        "main_banner",
        array(
            "IBLOCK_TYPE" => "content",
            "IBLOCK_ID" => "13",
            "ELEMENT_ID" => $arMainBanner["ID"],
            "ELEMENT_CODE" => "",
            "CHECK_DATES" => "Y",
            "FIELD_CODE" => array(
                0 => "NAME",
                1 => "DETAIL_PICTURE",
                2 => "",
            ),
            "PROPERTY_CODE" => array(
                0 => "PROP_1",
                1 => "PROP_2",
                2 => "PROP_3",
                3 => "DESC",
                4 => "NAME_COLOR",
                5 => "DESC_COLOR",
                6 => "",
            ),
            "IBLOCK_URL" => "",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_GROUPS" => "N",
            "META_KEYWORDS" => "-",
            "META_DESCRIPTION" => "-",
            "BROWSER_TITLE" => "-",
            "SET_STATUS_404" => "N",
            "SET_TITLE" => "Y",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
            "ADD_SECTIONS_CHAIN" => "Y",
            "ADD_ELEMENT_CHAIN" => "N",
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "USE_PERMISSIONS" => "N",
            "PAGER_TEMPLATE" => ".default",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "Y",
            "PAGER_TITLE" => "Страница",
            "PAGER_SHOW_ALL" => "Y",
            "DISPLAY_DATE" => "Y",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "Y",
            "USE_SHARE" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "COMPONENT_TEMPLATE" => "main_banner",
            "DETAIL_URL" => "",
            "SET_CANONICAL_URL" => "N",
            "SET_BROWSER_TITLE" => "Y",
            "SET_META_KEYWORDS" => "Y",
            "SET_META_DESCRIPTION" => "Y",
            "SET_LAST_MODIFIED" => "N",
            "PAGER_BASE_LINK_ENABLE" => "N",
            "SHOW_404" => "N",
            "MESSAGE_404" => ""
        ),
        false
    );?>


<!-- .header-->
<main class="content">

    <?//получаем товары для данного раздела
        if ($banner_code == "gift") {
            $postfix = "_half_year";
        }
        else {
            $postfix = "_start";
        }

        //получаем главный раздел
        $section = CIBlockSection::GetList(array(), array("CODE"=>$banner_code));
        $arSection = $section->Fetch();

        $subsection = CIBlockSection::GetList(array("SORT"=>"ASC"), array("SECTION_ID"=>$arSection["ID"]));

        $i = 1;
        while($arSubsection = $subsection->Fetch()) {

            //arshow($arSubsection);
            //получаем для раздела стоимость комплекта СТАРТ

            $element = CIBlockElement::GetList(array(),array("CODE"=>$arSubsection["CODE"].$postfix),false, false, array("ID","CODE"));
            $arElement = $element->Fetch();
            //arshow($arElement);
            //получаем цену
            $price = "";
            $product = CPrice::GetList( array(), array("PRODUCT_ID"=>$arElement["ID"]),false, false,array());
            while($arProduct = $product->Fetch()) {
                if ($arProduct["PRICE"] > 0 && $arProduct["CAN_ACCESS"] == "Y") {
                    $price = intval($arProduct["PRICE"]);
                    break;
                }
            }


            //в зависимости от раздела меняется содержимое данного блока
            if ($banner_code != "slalom"){?>

            <?if ($i == 1){?>
                <div class="main-shave1">
                    <div>
                        <img class="img" src="<?=CFile::GetPath($arSubsection["PICTURE"])?>" alt=""/>

                        <h2 class="h2 wow fadeIn"><?=$arSubsection["NAME"]?></h2>

                        <p class="p1 wow fadeInLeft" data-wow-delay="500ms">
                            <?=$arSubsection["DESCRIPTION"]?>
                        </p>
                        <span class="line wow fadeInLeft" data-wow-delay="500ms"></span>
                        <?
                            if ($banner_code == "gift") {
                            ?>
                            <p class="p2 wow fadeInLeft" data-wow-delay="500ms"><span class="bold"><?if ($price > 0){?><?=$price?> <span class="rouble">i</span><?}?> </span><span class="gray">/ 9 кассет </span>+ станок!</p>
                            <?
                            }  else {
                            ?>
                            <p class="p2 wow fadeInLeft" data-wow-delay="500ms"><span class="bold"><?if ($price > 0){?><?=$price?> <span class="rouble">i</span><?}?> </span><span class="gray">/ 2 кассеты + станок!</span></p>

                            <?
                            }
                        ?>
                        <a href="/<?=$banner_code?>/<?=$arSubsection["CODE"]?>/" class="btn wow fadeInUp" data-wow-delay="1500ms">выбрать</a>
                    </div>
                </div>
                <?} else if ($i == 2){?>

                <div class="main-shave2">
                    <div>
                        <img class="img" src="<?=CFile::GetPath($arSubsection["PICTURE"])?>" alt=""/>

                        <h2 class="h2 wow fadeIn"><?=$arSubsection["NAME"]?></h2>

                        <p class="p1 wow fadeInRight" data-wow-delay="500ms">
                            <?=$arSubsection["DESCRIPTION"]?>
                        </p>
                        <span class="line wow fadeInRight" data-wow-delay="500ms"></span>
                        <?
                            if ($banner_code == "gift") {
                            ?>
                            <p class="p2 wow fadeInLeft" data-wow-delay="500ms"><span class="bold"><?if ($price > 0){?><?=$price?> <span class="rouble">i</span><?}?> </span><span class="gray">/ 9 кассет </span>+ станок!</p>
                            <?
                            }  else {
                            ?>
                            <p class="p2 wow fadeInLeft" data-wow-delay="500ms"><span class="bold"><?if ($price > 0){?><?=$price?> <span class="rouble">i</span><?}?> </span><span class="gray">/ 2 кассеты + станок!</span></p>

                            <?
                            }
                        ?>
                        <a href="/<?=$banner_code?>/<?=$arSubsection["CODE"]?>/" class="btn wow fadeInUp" data-wow-delay="1500ms">выбрать</a>
                    </div>
                </div>

                <?}?>


            <?} else {?>
            <?if ($i == 1){?>
                <div class="main-shave1"><span class="main-shave1-arr"></span>
                    <div>
                        <h2 class="h2 wow fadeIn">slalom</h2>

                        <p class="p1 wow fadeInLeft" data-wow-delay="500ms">Бритвенный комплект специально разработан для чистого, долгого
                            и комфортного бритья</p>
                        <span class="line wow fadeInLeft" data-wow-delay="500ms"></span>

                        <p class="p2 wow fadeInLeft" data-wow-delay="500ms"><span class="bold"><?if ($price > 0){?><?=$price?> <span class="rouble">i</span><?}?> </span>/ <span class="black">рекомендованная</span> розничная цена</p>
                        <a href="/<?=$banner_code?>/<?=$arSubsection["CODE"]?>/" class="btn wow fadeInUp" data-wow-delay="1500ms">подробнее</a>
                    </div>
                </div>
                <?} else if ($i == 2) {?>
                <div class="main-shave2">
                    <img class="img" src="/images/slalom/shave2.jpg" />
                </div>
                <?}?>
            <?}?>

        <?$i++;}?>



    <div class="main-block1">

        <?//получаем баннер для текущего раздела
            $banner = CIBlockElement::GetList(array(), array("IBLOCK_CODE"=>"middle_banner", "PROPERTY_RAZOR"=>$arSection["ID"]),false, false, array("ID","NAME","PREVIEW_PICTURE","PREVIEW_TEXT","DETAIL_TEXT"));
            $arBanner = $banner->Fetch();

        ?>
        <?if (is_array($arBanner)){?>

            <div class="main-block1-col">
                <img class="img" src="<?=CFile::GetPath($arBanner["PREVIEW_PICTURE"])?>" alt=""/>

                <div>
                    <span class="quotes wow fadeInLeft">
                        <img src="/images/quotes.png" alt=""/>
                    </span>

                    <a id="question_form_link" href="#question_form"><h2 class="h2 wow fadeInLeft">
                            <?=$arBanner["PREVIEW_TEXT"]?>
                        </h2></a>

                    <p class="p wow fadeInLeft"><?=$arBanner["NAME"]?></p>

                </div>
            </div>
            <?if(!empty($arBanner["DETAIL_TEXT"])):?>
                <script>
                    $(function(){

                        /*$('.question_text').show().jScrollPane({


                        });*/


                        var element = $(".question_text").jScrollPane({
                            showArrows: true,
                            arrowScrollOnHover: true

                        });
                        var api = element.data('jsp');
                        var etalon = parseInt($(".question_text").children().children(".jspPane").css("top"));
                        $('.question_text').scroll(function(){

                            if(etalon !== parseInt($(".question_text").children().children(".jspPane").css("top")) || etalon ==0){
                                etalon = parseInt($(".question_text").children().children(".jspPane").css("top"));
                                $("#question_form img").removeClass("transform_180");
                            }
                            else{
                                $("#question_form img").addClass("transform_180");
                            }
                        });
                        $('#question_form').hide();
                        $("#question_form_link").fancybox({

                        });
                    })
                </script>
                <div id="question_form">
                    <div class="form_title title_question"><?=$arBanner["PREVIEW_TEXT"]?></div>
                    <div class="form_title_separator"></div>
                    <div class="question_text">
                        <?=$arBanner["DETAIL_TEXT"]?>
                    </div>
                    <div class="form_line_question form_line_left"></div>
                    <div class="form_line_question form_line_right"></div>
                    <div class="form_bottom_text"><img src="/images/question_form_arrow.png"></div>

                </div>
                <?
                    endif;

            ?>

            <?} else {
                //баннер по умолчанию
            ?>
            <div class="main-block1-col"><img class="img" src="/images/bg1.jpg" alt=""/>

                <div>
                    <span class="quotes wow fadeInLeft">
                        <img src="/images/quotes.png" alt=""/>
                    </span>

                    <h2 class="h2 wow fadeInLeft">Почему люди<br/>
                        выбирают <span>нас и наши<br/>
                            <span>бритвы?</span></span></h2>

                    <p class="p wow fadeInLeft">Владимир Мохте, CEO ShaveClub</p>
                </div>
            </div>

            <?}?>




        <div class="main-block1-col">
            <?

                //собираем преимущества бритв
                $razorPro = CIBlockElement::GetList(array("SORT"=>"ASC"), array("IBLOCK_CODE"=>"our_advantages","PROPERTY_SECTION"=>$arSection["ID"]),false,false,array("ID","NAME","PREVIEW_PICTURE","PREVIEW_TEXT"));

            ?>
            <ul>
                <?
                    $i = 1;
                    while($arRazorPro = $razorPro->Fetch()){?>
                    <li class="desc">
                        <a href="#">
                            <img class="wow fadeInDown" data-wow-delay="500ms" src="/images/icon<?=$i?>.png" alt=""/>
                            <span class="wow fadeInDown" data-wow-delay="500ms">
                            <?=$arRazorPro["NAME"]?></span>
                            <span class="line"></span>
                            <p><?=$arRazorPro["PREVIEW_TEXT"]?></p>
                        </a>
                    </li>
                    <?$i++;}?>
            </ul>


        </div>
    </div>


    <?//получаем отзывы
        $arFilter = array("IBLOCK_CODE"=>"reviews");
        if ($banner_code != "gift") {
            $arFilter["PROPERTY_RAZOR"] = $arSection["ID"];
        }
        $reviews = CIBlockElement::GetList(array("SORT"=>"DESC"), $arFilter,false, false, array("ID","NAME","PREVIEW_PICTURE","PREVIEW_TEXT","PROPERTY_PROF","DETAIL_PICTURE"));
        if ($reviews->SelectedRowsCount() > 0) {
        ?>
        <div class="main-block2" id="reviews">
            <div class="main-block2-col">
                <div class="scroll-slider">
                    <div>
                        <ul>

                            <li>
                                <?
                                    $i = 0;
                                    while ($arReview = $reviews->Fetch()) {?>

                                    <?if ($i%2 == 0 && $i > 0){?>
                                    </li><li>
                                        <?}?>

                                    <?if ($i%2 != 0){?>
                                        <div class="review">
                                            <?if ($arReview["DETAIL_PICTURE"] > 0){?>
                                                <img src="<?=CFile::GetPath($arReview["DETAIL_PICTURE"])?>" class="imgForReviews" title="<?=$arReview["NAME"]?>"/>
                                                <?} else {?>

                                                <div class="review-left">
                                                    <span class="review-title wow fadeInDown"> <?=$arReview["NAME"]?></span><span class="line"></span>

                                                    <div>
                                                        <img class="wow fadeInLeft" src="<?=CFile::GetPath($arReview["PREVIEW_PICTURE"])?>" alt=""/></div>
                                                    <span class="review-text wow fadeInLeft"><?=$arReview["PROPERTY_PROF_VALUE"]?></span>
                                                </div>
                                                <div class="review-center"><span class="review-center-arr"></span>

                                                    <div class="quotes wow fadeInRight">“</div>
                                                    <p class=" wow fadeInRight"><?=$arReview["PREVIEW_TEXT"]?></p>

                                                    <span class="number wow fadeInRight">Побритый клиент №<?=$arReview["ID"]?></span>
                                                </div>
                                            </div>
                                            <?}?>
                                        <?} else {?>

                                        <div class="review1">

                                            <?if ($arReview["DETAIL_PICTURE"] > 0){?>
                                                <img src="<?=CFile::GetPath($arReview["DETAIL_PICTURE"])?>" class="imgForReviews" title="<?=$arReview["NAME"]?>"/>
                                                <?} else {?>

                                                <div class="review-left"><span class="review-center-arr"></span>
                                                    <span class="review-title wow fadeInDown"> <?=$arReview["NAME"]?></span><span class="line"></span>

                                                    <div>
                                                        <img class="wow fadeInLeft" src="<?=CFile::GetPath($arReview["PREVIEW_PICTURE"])?>" alt=""/></div>
                                                    <span class="review-text wow fadeInLeft"><?=$arReview["PROPERTY_PROF_VALUE"]?></span>
                                                </div>
                                                <div class="review-center">
                                                    <div class="quotes wow fadeInRight">“</div>
                                                    <p class="wow fadeInRight"><?=$arReview["PREVIEW_TEXT"]?></p>
                                                    <span class="number wow fadeInRight">Побритый клиент №<?=$arReview["ID"]?></span>
                                                </div>

                                                <?}?>

                                        </div>
                                        <?}?>
                                    <?$i++;}?>

                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="main-block2-col"><span class="arrow-left"></span><span class="arrow-right"></span>

                <?if ($banner_code == "gift"){?>
                    <img class="img" src="/images/gift/bg2.jpg" alt=""/>
                    <?} else if ($banner_code != "woman"){?>
                    <img class="img" src="/images/bg2.jpg" alt=""/>
                    <?} else {?>
                    <img class="img" src="/images/girl/bg2.jpg" alt=""/>
                    <?}?>

                <span class="text wow fadeIn"><span class="title"> отзывы</span>
                    <span class="line wow fadeIn"></span>
                    <span class="p wow fadeIn"> У нас свыше 10 000 довольных клиентов,
                        <br/>
                        в этом вы можете убедиться сами.</span>
                </span>
                <span class="count"></span>
            </div>
        </div>
        <?}?>




    <?

        if ($banner_code != "gift"){
            //собираем новости
            $news = CIBlockElement::GetList(array(), array("IBLOCK_CODE"=>"articles"),false, false, array("ID","NAME","PREVIEW_PICTURE","PREVIEW_TEXT","PROPERTY_PROF","DETAIL_PICTURE","PROPERTY_LOGO","PROPERTY_TYPE","CODE"));
            if ($news->SelectedRowsCount() > 0) {

                //переформировываем результирующий массив с новостями

                $i=1; //счетчик для нечетных новостей
                $k=2; //счетчик для четных новостей
                $newNews = array(); //новый массив новостей

                while($arNews = $news->Fetch())
                {
                    //arshow($arNews);
                    if ($arNews["PROPERTY_TYPE_VALUE"] == "big") {
                        $newNews[$i] = $arNews;
                        $i = $i + 2;
                    }
                    else  if ($arNews["PROPERTY_TYPE_VALUE"] == "small") {
                        $newNews[$k] = $arNews;
                        $k = $k + 2;
                    }

                }

                ksort($newNews);
                //arshow($newNews);


            ?>
            <div class="main-block3" id="press">
                <div>
                    <div class="scroll-slider"><span class="arrow-left"></span><span class="arrow-right"></span>

                        <div>
                            <ul>

                                <li>
                                    <?
                                        foreach ($newNews as $id=>$new) {
                                        ?>
                                        <?if (($id-1)%2 == 0 && $id-1 > 0){?>
                                        </li><li>
                                            <?}?>
                                        <?if ($id%2 != 0){?>
                                            <div class="main-block3-col overlook">
                                                <?if ($new["DETAIL_PICTURE"] > 0) {?>
                                                    <img class="img" src="<?=CFile::GetPath($new["DETAIL_PICTURE"])?>" alt=""/>
                                                    <?}?>

                                                <div>
                                                    <h2 class="h2 wow fadeInLeft"><?=$new["NAME"]?></h2>

                                                    <p class="p wow fadeInLeft">
                                                        <?=$new["PREVIEW_TEXT"]?>
                                                    </p>
                                                    <a class="btn wow fadeInLeft" href="<?=$new["CODE"]?>" target="_blank">читать статью</a>
                                                </div>
                                            </div>

                                            <?} else {?>

                                            <div class="main-block3-col">

                                                <div class="text-content">
                                                    <span class="title wow fadeInRight"><a href="<?=$new["CODE"]?>"><?=$new["NAME"]?></a></span>

                                                    <p class="p wow fadeInRight">
                                                        <?=$new["PREVIEW_TEXT"]?>
                                                    </p>
                                                </div>
                                                <?if ($newNews[$id-1]["PROPERTY_LOGO_VALUE"] > 0){?>
                                                    <div class="times" style="background-image: url(<?=CFile::GetPath($newNews[$id-1]["PROPERTY_LOGO_VALUE"])?>); background-repeat:  no-repeat; background-position:  center center ;">
                                                        <span class="times-arr"></span>
                                                    </div>
                                                    <?}?>
                                                <?if ($new["PREVIEW_PICTURE"] > 0){?>
                                                    <a href="<?=$new["CODE"]?>" target="_blank">
                                                        <div class="triangle" style="background: url(<?=CFile::GetPath($new["PREVIEW_PICTURE"])?>) no-repeat center center ;">
                                                            <span class="triangle-arr"></span>
                                                        </div>
                                                    </a>
                                                    <?}?>
                                            </div>

                                            <?}?>

                                        <?}?>
                                </li>

                            </ul>
                        </div>

                    </div>
                </div>

            </div>

            <?}

    }?>


    <?if ($banner_code != "gift"){?>
        <div class="main-block4">
            <div class="main-block4-col subs">
                <div><span class="main-block4-arr"></span>

                    <h2 class="h2 wow fadeInLeft">ПОДПИСКА НА БРИТВЫ</h2>

                    <p class="p wow fadeInLeft">подписка на регулярное получение новых кассет<br/>
                        для бритья <strong>от 200 руб. в месяц.</strong></p>
                    <ul class=" wow fadeInLeft">
                        <li>Бесплатная доставка</li>
                        <li>Всегда острые и свежие бритвы</li>
                        <li>Автоматическая оплата</li>
                    </ul>


                    <a href="#" class="btn white-btn wow fadeInDown">оформить</a>
                    <a href="#" class="btn wow fadeInDown">подробнее</a>
                    <span class="text wow fadeIn" data-wow-delay="500ms">Новым подписчикам станок <span>в ПОДАРОК!</span></span>
                </div>
            </div>
            <div class="main-block4-col">
                <img class="img" src="/images/bg4.jpg" alt=""/>
            </div>
        </div>

        <?} else {?>
        <div class="main-block6">
            <div class="main-block6-col">
                <img class="img" src="/images/gift/bg6.jpg" alt="" />
            </div>
            <div class="main-block6-col"><div>
                    <h2 class="h2 wow fadeIn">Не знаете что подарить своему мужчине?</h2>

                    <p class="p1 wow fadeInRight" data-wow-delay="500ms">Наши бритвы – лучший подарок!</p>
                    <a class="btn" href="#">Подробнее</a>
                </div>
            </div>
        </div>


        <div class="sertificateInputBlock">
            <div class="sertInputDesc">
                Активируйте полученный сертификат и начните получать новые и свежие бритвы!
            </div>
            <input class="sertInput sertActivate" value="" size="19" autocomplete="off" placeholder="xxxx-xxxx-xxxx-xxxx" type="text"/>
            <a class="btn" href="javascript:void(0)" onclick="sertSubmit()">активировать</a>
        </div>
        <?}?>


                </main>
