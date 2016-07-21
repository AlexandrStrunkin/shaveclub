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
    <link href="/css/inside.css" rel="stylesheet">
    <link href="/css/opt_plans_pages.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=PT+Serif:400,700,400italic,700italic&subset=latin,cyrillic,latin-ext,cyrillic-ext' rel='stylesheet' type='text/css'>
    <script src="/js/wow.js"></script>

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

    <?
        $url = array_diff(explode("/",$APPLICATION->GetCurPage()),array(""));

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
            //������� ����������� ��� ������� �������
            $section = CIBlockSection::GetList(array(),array("IBLOCK_ID"=>12,"CODE"=>$url[2]), false, array("UF_DETAIL_PICTURE","UF_RAZOR_NUMBER","UF_FULL_DESCRIPTION", "UF_ZAPAS"));

            $arSection = $section->Fetch();

            //arshow($arSection["IBLOCK_SECTION_ID"]);
            //�������� ������ ������ �� ������� 

            $arFilter = array(
                "SECTION_ID"=>$arSection["IBLOCK_SECTION_ID"],
                "IBLOCK_ID"=> 12,
            );

            $all_raz = CIBlockSection::GetList(array(), $arFilter, false, array("UF_DETAIL_PICTURE","UF_RAZOR_NUMBER","UF_FULL_DESCRIPTION","UF_ZAPAS"));

            //�������� ������
            $sales = CSaleDiscount::GetList(array(),array(), false, array());

            while ($arSales = $sales->Fetch())
            {
                //                arshow($arSales);
            }

            //�������� �������� �������� "���������� ������"
            $razorNumber = CUserFieldEnum::GetList(array(),array("ID"=>$arSection["UF_RAZOR_NUMBER"]));
            $arRazorNumber = $razorNumber->Fetch();
        ?>
        <div class="inside-page-col inside-page-col1">
            <div class="preview-product div">
                <span class="text"><?=$arRazorNumber["VALUE"]?></span>
                <span class="preview-product-title"><?=$arSection["NAME"]?></span>
                <span class="select-plan"><strong>��������</strong> ���������� ���� ������<br/>
                    ��� <strong>��������</strong> ����</span>

                <div class="line"></div>

                <?

                    $rProps = array();
                    $rIds = array();
                    //�������� ������ �������
                    $razorProps = CIBlockElement::GetList(array(),array("PROPERTY_RAZOR"=>$arSection["ID"]),false, false, array("NAME","PROPERTY_PROPERTIES","PROPERTY_COORDS","ID"));
                    while($arRazorProps = $razorProps->Fetch()) {
                        $rIds[$arRazorProps["ID"]][] = $arRazorProps["PROPERTY_PROPERTIES_VALUE"];
                        $rProps[$arRazorProps["ID"]] = $arRazorProps;
                        $rProps[$arRazorProps["ID"]]["PROPERTY_PROPERTIES"]= $rIds[$arRazorProps["ID"]]; 
                    } 

                    if ($arSection["ID"]){?>
                    <div class="img">

                        <?  //������� �����
                            $i = 1;
                            foreach ($rProps as $rProp){
                                $coords = explode(",",$rProp["PROPERTY_COORDS_VALUE"]);
                            ?>
                            <span class="plus <?/*if ($i==1){?> active<?}*/?>" rel="<?=$rProp["ID"]?>" id="plus<?=$rProp["ID"]?>" style="<?if ($coords[0]){?>left:<?=$coords[0]?>px;<?} if ($coords[1]) {?> top: <?=$coords[1]?>px<?}?>"></span>
                            <?$i++;}?>


                        <img src="<?=CFile::GetPath($arSection["UF_DETAIL_PICTURE"])?>" alt=""/>

                    </div>
                    <?if ($arSection["UF_FULL_DESCRIPTION"]){?>
                        <div class="razor_full_description">
                            <?=html_entity_decode($arSection["UF_FULL_DESCRIPTION"])?>
                        </div>
                        <?}?>

                    <ul class="preview-product-ul">
                        <?//�������� �������� �� ������
                            $k = 1;
                            foreach ($rProps as $rProp) {
                                $groupProps = CIBlockElement::GetList(array(), array("ID"=>$rProp["PROPERTY_PROPERTIES"]),false,false,array());
                                while($arGroupProps = $groupProps->Fetch()) {
                                ?>
                                <li id="block<?=$rProp["ID"]?>" rel="<?=$rProp["ID"]?>" <?/*if ($k == 1){?> style="display: block;"<?}*/?>>
                                    <span class="img1" style="background-image: url(<?=CFile::GetPath($arGroupProps["PREVIEW_PICTURE"])?>);">
                                        <?/*<img class="no-active" src="/images/inside/img1.png" alt=""/>  */?>
                                        <img class="active" src="/images/inside/img2.png" alt=""/>
                                    </span>
                                    <p>
                                        <span>
                                            <span>
                                                <?=$arGroupProps["PREVIEW_TEXT"]?>     
                                            </span>
                                        </span>
                                    </p>
                                </li> 
                                <?}?> 
                            <?$k++;}?> 
                    </ul>
                    <?}?>

                <?//�������� ���������� ��� ����� ������
                    $otherSection = CIBlockSection::GetList(array(),array("SECTION_ID"=>$arSection["IBLOCK_SECTION_ID"],"!ID"=>$arSection["ID"]));
                    $arOtherSection = $otherSection->Fetch();                  
                ?>
                <a href="<?="/".$url[1]."/".$arOtherSection["CODE"]."/"?>" class="btn change-product">������� ������</a>

            </div>

        </div>

        <span class="inside-page-col-close" onclick="makePlan()"></span>

        <div class="inside-page-col">

            <span class="inside-page-col-shadow"></span>

            <div class="plans">

                <?
                    //�������� ����� ��� ������� ������
                    $plansArr = array($url[2]."_start",$url[2]."_half_year",$url[2]."_year");
                    $plans = CIBlockElement::GetList(array("ORDER"=>"ASC"), array("CODE"=>$plansArr,"IBLOCK_ID"=>12),false,false,array("ID","NAME","PROPERTY_ECONOMY","CODE","PROPERTY_LENGTH","PROPERTY_CASSETTE","PROPERTY_RAZOR", "PROPERTY_DELIVERY"));

                ?>

                <ul>
                    <?
                        $i = 1;
                        while($arPlan = $plans->Fetch()){?>

                        <?
                            if (!$arPlan["PROPERTY_ECONOMY_VALUE"]) {$arPlan["PROPERTY_ECONOMY_VALUE"] = 0;}



                            $product = CPrice::GetList( array(), array("PRODUCT_ID"=>$arPlan["ID"]),false, false,array());
                            while($arProduct = $product->Fetch()) {                
                                if ($arProduct["PRICE"] > 0 && $arProduct["CAN_ACCESS"] == "Y") {
                                    $price = intval($arProduct["PRICE"]); 
                                    break;  
                                }
                            }
                        ?>
                        <li <?if ($i%2 == 0){?> class="orange"<?}?>>
                            <div>
                                <span class="plans-name"><?=$arPlan["NAME"]?></span>
                                <span class="plans-desc">����� ������ �� <?=$arPlan["PROPERTY_LENGTH_VALUE"]?> <?=month_name($arPlan["PROPERTY_LENGTH_VALUE"])?></span>
                                <div class="plans-line"></div>
                                <span class="plans-title"><span>������� �������</span></span>
                                <span class="plans-text"><?=$arPlan["PROPERTY_CASSETTE_VALUE"]?> <span class="rouble">i</span> </span>
                                <span class="plans-title"><span>���������� ������</span></span>
                                <span class="plans-text"><?=$arPlan["PROPERTY_RAZOR_VALUE"]?> <?if ($arPlan["PROPERTY_RAZOR_VALUE"] != "���������"){?> <span class="rouble">i</span><?}?> </span>
                                <span class="plans-title"><span>��������</span></span>
                                <span class="plans-text"><?=$arPlan["PROPERTY_DELIVERY_VALUE"]?> </span>
                            </div>
                            <div class="plans-container">
                                <div class="arr"></div>
                                <span class="price"><?=$price?> <span class="rouble">i</span></span>
                                <span class="economy">�������� <span><?=$arPlan["PROPERTY_ECONOMY_VALUE"]?>%</span></span>

                                <a class="btn" href="javascript:void(0)" onclick="addToBasket(<?=$arPlan["ID"]?>)">�������</a>
                            </div>
                        </li>

                        <?$i++;}?>                        

                </ul>


            </div>   
            <div class="plans-create">
                <div class="div"><img src="/images/inside/plans-bg.jpg" class="plans-create-img" alt="" />
                    <a href="javascript:void(0)" class="btn" onclick="makePlan()">�������</a>
                    <span class="plans-create-title">��� ����</span>
                    <p>�� �������� �� ���� ���� ������ � ������ ����!</p>

                </div>
            </div>    



            <?
                //�������� ���� ��� ������ � ������� ������� ���� ������
                $britvaParts = array("cassette_".$url[2],"razor_".$url[2]);                  
                $britva = CIBlockElement::GetList(array(), array("=CODE"=>$britvaParts),false, false, array("ID","CODE"));
                $britvaPartsData = array();
                while($arBritva = $britva->Fetch()) {
                    $partPrice = CPrice::GetList(array(), array("PRODUCT_ID"=>$arBritva["ID"],">PRICE"=>0),false, false, array());
                    $arPartPrice = $partPrice->Fetch();
                    $britvaPartsData[$arBritva["CODE"]] = intval($arPartPrice["PRICE"]);
                }



            ?>

            <div class="svoy-plan inside-page">
                <span class="inside-page-col-shadow"></span>
                <div class="div">
                    <div class="svoy-plan-arr"></div>
                    <span class="svoy-plan-title">���� ���� ������</span>
                    <input type="hidden" id="razor_type" value="<?=$url[2]?>"/>
                    <ul class="svoy-plan-ul">
                        <li class="active">
                            <span class="title">������� ������</span>
                            <span class="minus">-</span>
                            <input type="text" class="count" value="1" id="cassette_count"/>
                            <span class="plus">+</span>

                            <div class="sum-container">  
                                <input type="hidden" class="price" value="<?=$britvaPartsData["cassette_".$url[2]]?>" id="cassette_price"/>
                                <span class="price"><span><?=$britvaPartsData["cassette_".$url[2]]?></span> <img src="/images/inside/rub3.png" alt=""/> / ��.</span>
                                <span class="sum"><span><?=$britvaPartsData["cassette_".$url[2]]?></span> 
                                    <span class="rub"></span>
                                </span>
                            </div>

                        </li>
                        <li>
                            <span class="title">���������� ������</span>
                            <span class="minus">-</span>
                            <input type="text" class="count" value="1" id="razor_count"/>
                            <span class="plus">+</span>

                            <div class="sum-container">
                                <input type="hidden" class="price" value="<?=$britvaPartsData["razor_".$url[2]]?>" id="razor_price"/>
                                <span class="price"><span><?=$britvaPartsData["razor_".$url[2]]?></span> <img src="/images/inside/rub3.png" alt=""/> / ��.</span>
                                <span class="sum"><span><?=$britvaPartsData["razor_".$url[2]]?></span> 
                                    <span class="rub"></span>
                                </span>
                            </div>

                        </li>
                    </ul>



                    <div class="shaving-stock">����� ������: <span>2 ������</span></div>
                    <div class="calc-shaving-stock">
                        <span class="quest gray">��� ��������� ����� ������?</span>

                        <div class="col-container"><span class="arr"></span><span class="close"></span>
                            <?
                                while($arAll_raz = $all_raz->Fetch()) {
                                    //                arshow($arAll_raz); 
                                ?>
                                <span class="col"><span>
                                    <strong><?=$arAll_raz['NAME']?></strong> � 1 ������� �� <?=$arAll_raz['UF_ZAPAS']?> ������
                                    <span>������: 4 ������� �� 2 ������.</span></span></span>

                                <?}
                            ?>
                            <!--<span class="col"><span>
                            <strong>����� 4</strong> � 1 ������� �� 2 ������
                            <span>������: 4 ������� �� 2 ������.</span></span></span>

                            <span class="col"><span>
                            <strong>����� 6</strong> � 1 ������� �� 3 ������
                            <span>������: 3 ������� �� 2 ������.</span></span></span> -->
                        </div>
                    </div>
                    <div class="shaving-stock"><span class="sum">- 19 8100 <img src="/images/inside/rub7.png" alt=""/></span> ������: <span>20%</span></div>
                    <div class="calc-shaving-stock"><span class="quest gray">�� ���� ������� ������?</span>



                        <div class="col-container"><span class="arr"></span><span class="close"></span>
                            <span class="col">
                                <span>
                                    <strong>����� 4</strong> � 1 ������� �� 2 ������
                                    <span>������: 4 ������� �� 2 ������.</span>
                                </span>
                            </span>
                            <span class="col">
                                <span>
                                    <strong>����� 6</strong> � 1 ������� �� 3 ������
                                    <span>������: 3 ������� �� 2 ������.</span>
                                </span>
                            </span>
                        </div>
                    </div>




                </div>
                <span class="inside-page-col-close close-bg-black" onclick="makePlan()"></span>

                <div class="gen-sum-block">
                    <div class="div">
                        <a href="javascript:void(0)" onclick="planCreate()" class="btn">��������</a>
                        <div>
                            �����: <span class="gen-sum"><?=$britvaPartsData["cassette_".$url[2]]+$britvaPartsData["razor_".$url[2]]?></span> 
                            <img src="/images/inside/rub6.png" alt=""/>
                        </div> 
                    </div>
                </div>
            </div>

        </div>    

        <!-- .content -->
        <div class="clear"></div>
    </div>


    <!-- .footer -->
</div>


               
