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
    <link rel="stylesheet" href="/css/inside.css">
    <link href="/css/cusel.css" rel="stylesheet">
    <link href="/css/opt_plans_pages.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=PT+Serif:400,700,400italic,700italic&subset=latin,cyrillic,latin-ext,cyrillic-ext' rel='stylesheet' type='text/css'>
    <script src="/js/wow.js"></script>
    <link href="/css/kabinet_page.css" rel="stylesheet">

    <script type="text/javascript" src="/js/cusel.js"></script>
    <link type="text/css" href="/css/jquery.jscrollpane.css" rel="stylesheet" media="all"/>
    <script type="text/javascript" src="/js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="/js/jquery.jscrollpane.min.js"></script>

    <script type="text/javascript" src="/js/jquery.fancybox.js"></script>
    <link type="text/css" href="/css/jquery.fancybox.css" rel="stylesheet" media="all"/>

    <script src="/js/index.js"></script>
    <script src="/js/callback.js"></script>
    <script src="/js/inputmask.js"></script>


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

<div class="main-container inside-page kabinet-page">

<?include($_SERVER["DOCUMENT_ROOT"]."/include/mobile_top.php");?>

<div class="inside-page-col">
    <div class="div">
        <?/*
            <form action="#" method="post" id="kabinet_form">
            <div class="contacts-block1">
            <h1 class="h1">личный кабинет</h1>
            <span class="title">личные данные</span>

            <div class="input-container">
            <label><input type="text" name="fio" class="input" value="Викторов Олег Александрович"/></label>
            <label><input type="text" name="email" class="input gray" value="victorovoleg@gmail.com"/></label>
            <label><input type="text" name="password" id="password" class="input empty" onfocus="clearField('password', 'Новый пароль')" onblur="blurField('password', 'Новый пароль')" value="Новый пароль"/></label>
            <label><input type="text" name="confirm_password" id="confirm_password" onfocus="clearField('confirm_password', 'Подтверждение пароля')" onblur="blurField('confirm_password', 'Подтверждение пароля')" class="input empty" value="Подтверждение пароля"/></label>
            <label><input type="text" name="phone" class="input" value=""/><span class="error">Вы не указали контактный телефон<span>!</span></span></label>
            </div>
            </div>

            <div class="delivery-address1">
            <div>

            <a href="#" class="btn">сохранить</a>
            <p class="delivery-address1-text"><span><span>Заполните все помеченные поля для сохранения</span></span></p>

            </div>

            </div>

            </form>
        */?>

        <?$APPLICATION->IncludeComponent("bitrix:main.profile", "user_profile", Array(),false);?>
    </div>

    <div class="main-block4">
        <div class="main-block4-col subs"><div><span class="main-block4-arr"></span>
                <h2 class="h2 fadeInLeft">ПОДПИСКА НА БРИТВЫ</h2>

                <p class="p fadeInLeft">подписка на регулярное получение новых кассет<br />
                    для бритья <strong>от 200 руб. в месяц.</strong></p>
                <ul class=" fadeInLeft">
                    <li>Бесплатная доставка</li>
                    <li>Всегда острые и свежие бритвы</li>
                    <li>Автоматическая оплата</li>
                </ul>

                <?/*
                    <a href="#" class="btn white-btn fadeInDown">оформить</a>
                    <a href="#" class="btn fadeInDown">подробнее</a>
                */?>

                <span class="text fadeIn" data-wow-delay="500ms">Новым подписчикам станок <span>в ПОДАРОК!</span></span>
            </div>
        </div>
    </div>
</div>

<span class="inside-page-col-close" onclick="makePlan()"></span>

<?$resOrder = CSaleOrder::GetList(array('ID' => 'DESC'), array("USER_ID" => $USER->GetID()), false, array("nTopCount" => 1));
    $resOrderFirst = CSaleOrder::GetList(array('ID' => 'ASC'), array("USER_ID" => $USER->GetID()), false, array("nTopCount" => 1));
    if($resOrder ->SelectedRowsCount()>0){
        $obOrderFirst = $resOrderFirst -> Fetch();
        $resDelivery = CSaleDelivery::GetList(array(),array());
        $arDelivery = array();
        while($obDelivery = $resDelivery -> Fetch()){
            $arDelivery[$obDelivery["ID"]] = $obDelivery["NAME"];
        };
        $arStatus = array();
        $resStatus = CSaleStatus::GetList(array(), array("LID" => "ru"));
        while($obStatus = $resStatus -> Fetch()){
            $arStatus[$obStatus["ID"]] = $obStatus["NAME"];
        };

        $obOrder = $resOrder -> Fetch();
        $resBasket = CSaleBasket::GetList(array(),array("ORDER_ID"=>$obOrder["ID"]));
        $obBasket = $resBasket -> Fetch();

        $resElement = CIBlockElement::GetByID($obBasket["PRODUCT_ID"]);
        $obElement = $resElement -> Fetch();

        $resSection = CIBlockSection::GetByID($obElement["IBLOCK_SECTION_ID"]);
        $obSection = $resSection -> Fetch();


        $resThisSection = CIBlockSection::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>12, "SECTION_ID" => $obSection["IBLOCK_SECTION_ID"]),false,
            Array("NAME","CODE","ID", "UF_ZAPAS"), false);
        $resParentSection = CIBlockSection::GetList(Array(""), Array("ID" => $obSection["IBLOCK_SECTION_ID"]),false, Array("NAME","CODE"), false);
        $obParentSection = $resParentSection -> Fetch();

        $arSection = array();

        while($obThisSection = $resThisSection -> Fetch()) {

            if ($obThisSection["ID"] == $obElement["IBLOCK_SECTION_ID"]){
                $arSection["THIS"] = $obThisSection;
            }else{
                $arSection["SIBLINGS"] = $obThisSection;
            };
        };

        $linkSiblings = "/".$obParentSection["CODE"]."/".$arSection["SIBLINGS"]["CODE"]."/";
        $linkThis =  "/".$obParentSection["CODE"]."/".$arSection["THIS"]["CODE"]."/";
        $listOrder = "Y";

    }else{
        $linkSiblings = "/";
        $linkThis = "/";
    };
?>

<div class="inside-page-col ">

    <span class="inside-page-col-shadow"></span>
    <div class="change-block">
        <ul>
            <li>
                <a href="<?=$linkSiblings?>"><img src="/images/inside/icon2.png" alt=""/><span class="title">изменить бритву</span></a>
            </li>
            <li>
                <a href="#"><img src="/images/inside/icon3.png" alt=""/><span class="title">изменить способ доставки</span></a>
            </li>
            <li>
                <a href="<?=$linkThis?>"><img src="/images/inside/icon4.png" alt=""/><span class="title">изменить план бритья</span></a>
            </li>
            <li>
                <a href="#"><img src="/images/inside/icon5.png" alt=""/><span class="title">изменить способ оплаты</span></a>
            </li>

        </ul>
    </div>
    <div class="history history1 div"><span class="arr"></span> <div>
            <?if($listOrder == "Y"):?>
                <a class="btn" href="javascript:void(0)" onclick="makePlan()">открыть</a>
                <?endif;?>
            <span class="title">история заказов</span>
            <p>Вы можете просмотреть весь список сделанных заказов,
                данные о стоимости и типах подписки.</p>
        </div>
    </div>
    <?if($listOrder == "Y") {
            $zapas = intval($arSection["THIS"]["UF_ZAPAS"])*7*24*3600;
            $time = (intval(date("U")) - intval(strtotime($obOrder["DATE_INSERT"])));
            $timeThis = $time/$zapas;
            $zapas_end = date("d.m.Y" ,(strtotime($obOrder["DATE_INSERT"]) + $zapas));

            if ($timeThis<0.1) {
                $procent = 100;
                $back = 0;
            }elseif($timeThis>1) {
                $procent = 0;
                $back = 10;
            }else{
                $procent = 100 - (round($timeThis*10))*10;
                $back = round($timeThis*10);
            };

            $firstTime = explode(" ",$obOrderFirst["DATE_INSERT"]);
            $timeFirst  = explode(".",$firstTime[0]);
            $regTimeDays = intval($timeFirst[0])+intval($timeFirst[1])*30+intval($timeFirst[2])*360;
            $dateThis = explode(".",date("d.m.Y"));
            $nowTimeDays = intval($dateThis[0])+intval($dateThis[1])*30+intval($dateThis[2])*360;

            $pastDaysSum = $nowTimeDays - $regTimeDays;
            $pastDate[] = floor($pastDaysSum/360);
            $pastDaysSum = $pastDaysSum - $pastDate[0]*360;
            $pastDate[] = floor($pastDaysSum/30);
            $pastDaysSum = $pastDaysSum - $pastDate[1]*30;
            $pastDate[] = $pastDaysSum;

            function plural_type($n) {
                return ($n%10==1 && $n%100!=11 ? 0 : ($n%10>=2 && $n%10<=4 && ($n%100<10 || $n%100>=20) ? 1 : 2));
            }

            $_plural_years = array('год', 'года', 'лет');
            $_plural_months = array('месяц', 'месяца', 'месяцев');
            $_plural_days = array('дней', 'дней', 'день');
            $_plural_times = array('раз', 'раза', 'раз');


            if(intval($dateThis[2])-intval($timeFirst[2])<1) {
                $year = intval($dateThis[1]) - intval($timeFirst[1]);
                $day = intval($dateThis[0]) - intval($timeFirst[1]);
            }
            else {
                $year = intval($dateThis[2]) - intval($timeFirst[2]);
                $month = intval($dateThis[1]) - intval($timeFirst[1]);
                $day = intval($dateThis[1]) - intval($timeFirst[1]);
            }

        ?>
        <div class="scheme div" >
            <div>
                <span class="scheme-circle scheme-circle1" style="background-position: <?echo -101*($back);?>px 0"><?=$procent?>%</span>
                <span class="scheme-text">ЗАПАС БРИТЬЯ
                    <?if(date("U") > strtotime($zapas_end)):?>
                        <span>Истек: <?=$zapas_end?></span></span>
                    <?else:?>
                    <span>Истекает: <?=$zapas_end?></span></span>
                    <?endif;?>
            </div>
            <div>

                <span class="scheme-circle scheme-circle2" style="background-position: <?echo -101*($year);?>px 0"><?=$pastDate[0]?></span>
                <span class="scheme-text">ВЫ БРЕЕТЕСЬ С НАМИ
                    <span>
                        <?if($pastDate[1]){
                            echo $_plural_years[plural_type($pastDate[0])].' ';
                            echo $pastDate[1].' '.$_plural_months[plural_type($pastDate[1])];
                        }else{
                            echo $_plural_months[plural_type($pastDate[1])];
                        };?>
                        и <?echo $pastDate[2]." ".$_plural_days[plural_type($pastDate[1])]?></span></span>
            </div>
        </div>
        <?}?>


    <div class="free-shave div">
        <img src="/images/inside/kabinet-bg.jpg" alt="" />
        <div>
            <?/*
                <a href="#" class="btn">пригласить</a>
            */?>
            <p><span><span>— Просто пригласи сюда своих друзей!</span></span></p>
        </div>
    </div>
    <div class="feedback div">
        <form action="#" method="post">
            <span class="title">Обратная связь</span>
            <a class="btn" id="feedback_btn" href="#">отправить</a>
            <div class="input-container">
                <div id="feedMessage"></div>
                <input type="hidden" id=user_mail value="<?=$USER->GetID()?>">
                <input type="text" class="input" id="feedback" onfocus="clearField('feedback', 'Опишите суть Вашего вопроса')" onblur="blurField('feedback', 'Опишите суть Вашего вопроса')" value="Опишите суть Вашего вопроса" /></div>

        </form>
    </div>


    <div class="svoy-plan inside-page orders-history">
        <span class="inside-page-col-shadow"></span>

        <?$resOrder = CSaleOrder::GetList(array('ID' => 'DESC'), array("USER_ID" => $USER->GetID()), false);
            while($obOrder = $resOrder -> Fetch()){
                $dateOrder = explode(" ", $obOrder["DATE_INSERT"]);
            ?>
            <div class="checkout-block div">

                <?if($obOrder["CANCELED"]=="N"):?>
                    <? if ($obOrder["STATUS_ID"]=="N" || $obOrder["STATUS_ID"]=="P" || $obOrder["STATUS_ID"]=="B") {?>
                    <div id="cancel_order">
                        <a href="#" class="cansel-btn" rel="<?=$obOrder["ID"]?>">отменить</a>
                    </div>
                    <?}?>
                    <?else:
                        $cancel = "Y"?>
                    <?endif;?>
                <?$resBasket = CSaleBasket::GetList(array(),array("ORDER_ID"=>$obOrder["ID"]));
                    $obBasket = $resBasket ->Fetch();
                    $resBasketProps =  CSaleBasket::GetPropsList(array(),array("BASKET_ID"=>$obBasket["ID"], "CODE"=>"GIFT"),false,false, array());
                    $arSetItems = CCatalogProductSet::getAllSetsByProduct($obBasket["PRODUCT_ID"], 1);
                    $quant = array();
                    foreach($arSetItems as $arItems):
                        foreach($arItems["ITEMS"] as $key => $value):
                            $quant[$key] = $value;
                            endforeach;
                        endforeach;
                    ksort($quant);
                    $arQuant = array();
                    foreach($quant as $val):
                        $arQuant[] = $val;
                        endforeach;

                    if(!empty($obBasket["TYPE"])){
                    ?>

                    <a href="javascript:void(0)" onclick="addToBasket(<?=$obBasket["PRODUCT_ID"]?>)" class="repeat-btn">повторить</a>

                    <?}else{
                        $resType = CIBlockElement::GetByID($obBasket["PRODUCT_ID"]);
                        $obType = $resType ->Fetch();
                        $resType =  CIBlockSection::GetList(Array(), Array("IBLOCK_ID"=>12, "ID" => $obType["IBLOCK_SECTION_ID"]),false,
                            Array("NAME","CODE"), false);
                        $obType = $resType ->Fetch();


                    ?>
                    <div>
                        <a href="javascript:void(0)" class="repeat-btn repeat_other">повторить</a>
                        <input type="hidden" class="count_machine" value="<?=$arQuant[0]?>" />
                        <input type="hidden" class="count_caseta" value="<?=$arQuant[1]?>" />
                        <input type="hidden" class="el_code" value="<?=$obType["CODE"]?>" />
                    </div>
                    <?};?>
                <div class="text"><strong>ваш заказ:</strong> <span>№<?=$obOrder["ID"]?></span> от <?=$dateOrder[0]?></div>
                <?

                    $resElement = CIBlockElement::GetById($obBasket["PRODUCT_ID"]);
                    $obElement = $resElement -> Fetch();
                    $resSection = CIBlockSection::GetList(Array(), Array("IBLOCK_ID"=> 12,"ID"=>$obElement["IBLOCK_SECTION_ID"]), false, Array("NAME","ID","UF_DETAIL_PICTURE","CODE"));
                    $obSection = $resSection -> Fetch();
                    $resParentSection = CIBlockSection::GetById($obSection["ID"]);
                    $obParentSection = $resParentSection ->Fetch();
                    $resParentSection = CIBlockSection::GetList(Array(), Array("IBLOCK_ID"=> 12,"ID"=>$obParentSection["IBLOCK_SECTION_ID"]), false, Array("NAME","CODE"));                 $obParentSection = $resParentSection ->Fetch();
                    $link = "/".$obParentSection["CODE"]."/".$obSection["CODE"]."/";
                    $picture = CFile::GetPath($obSection["UF_DETAIL_PICTURE"]);
                ?>

                <div class="order-composition">
                    <?if($resBasketProps -> SelectedRowsCount() > 0):?>
                        <img alt="" src="/images/gift_label.png" class="img label">
                        <a href="<?=$link?>"><img class="img" alt="" src="<?=$picture?>" alt=""/></a>
                        <?else:?>
                        <a href="<?=$link?>"><img class="img" alt="" src="<?=$picture?>" alt=""/></a>
                        <?endif;?>
                    <div>
                        <table>
                            <tr><td>бритва</td><td><?=$obSection["NAME"]?></td></tr>
                            <tr><td>план бритья</td><td><?=$obBasket["NAME"]?></td></tr>
                            <tr><td>комплектация</td><td><?=$arQuant[0]["QUANTITY"]?> станок и <?=$arQuant[1]["QUANTITY"]?> сменных кассет</td></tr>
                            <tr><td>доставка</td><td><?=$arDelivery[$obOrder["DELIVERY_ID"]]?></td></tr>
                        </table>

                    </div>



                    <span class="sum"><?=number_format($obOrder["PRICE"], 2)?>&nbsp;<img src="/images/inside/rub.png" alt=""/></span>
                    <?if($cancel == "Y"):?>
                        <span class="not-paid">Отменен</span>
                        <?elseif ($obOrder["PAYED"]!= "Y"):?>
                        <span class="not-paid"> не оплачен</span>

                        <?if ($obOrder["PAY_SYSTEM_ID"]!=17) {  ?>
                            <div style="margin: 0">
                                <!--                                                        <a href="#" class="btn btn1">оплатить</a>-->
                                <form action="<?= ps_uniteller::$url_uniteller_pay ?>" method="post" target="_blank">
                                    <!--<font class="tablebodytext"><br><?= GetMessage('SUSP_ACCOUNT_NO') ?>
                                    <?= $sOrderID . GetMessage('SUSP_ORDER_FROM') . $sDateInsert ?><br> <?= GetMessage('SUSP_ORDER_SUM') ?><b><?= SaleFormatCurrency($sHouldPay, $sCurrency) ?>
                                    </b><br> <br></br>-->
                                    <input type="hidden" name="Shop_IDP"
                                        value="<?= ps_uniteller::$Shop_ID ?>">
                                    <input type="hidden" name="Order_IDP" value="<?= $obOrder["ID"] ?>"> <input
                                        type="hidden" name="Subtotal_P"
                                        value="<?= (str_replace(',', '.', $sHouldPay)) ?>"> <?if ($iLiftime > 0):?>
                                        <input type="hidden" name="Lifetime"
                                            value="<?= $iLiftime ?>"> <?endif;?>
                                    <?if (strlen(CSalePaySystemAction::GetParamValue('LANGUAGE')) > 0):?>
                                        <input type="hidden" name="Language"
                                            value="<?= substr(CSalePaySystemAction::GetParamValue('LANGUAGE'), 0, 2) ?>"> <?endif;?>
                                    <?if (strlen(CSalePaySystemAction::GetParamValue('COMMENT')) > 0):?> <input
                                        type="hidden" name="Comment"
                                        value="<?= substr(CSalePaySystemAction::GetParamValue('COMMENT'), 0, 255) ?>"> <?endif;?>
                                    <?if (strlen(CSalePaySystemAction::GetParamValue('COUNTRY')) > 0):?> <input
                                        type="hidden" name="Country"
                                        value="<?= substr(CSalePaySystemAction::GetParamValue('COUNTRY'), 0, 3) ?>"> <?endif;?>
                                    <?if (strlen(CSalePaySystemAction::GetParamValue('STATE')) > 0):?> <input
                                        type="hidden" name="State"
                                        value="<?= substr(CSalePaySystemAction::GetParamValue('STATE'), 0, 3) ?>"> <?endif;?>
                                    <?if (strlen(CSalePaySystemAction::GetParamValue('FIRST_NAME')) > 0):?>
                                        <input type="hidden" name="FirstName"
                                            value="<?= substr(CSalePaySystemAction::GetParamValue('FIRST_NAME'), 0, 64) ?>"> <?endif;?>
                                    <?if (strlen(CSalePaySystemAction::GetParamValue('LAST_NAME')) > 0):?>
                                        <input type="hidden" name="LastName"
                                            value="<?= substr(CSalePaySystemAction::GetParamValue('LAST_NAME'),0 , 64) ?>"> <?endif;?>
                                    <?if (strlen(CSalePaySystemAction::GetParamValue('MIDDLE_NAME')) > 0): ?>
                                        <input type="hidden" name="MiddleName"
                                            value="<?= substr(CSalePaySystemAction::GetParamValue('MIDDLE_NAME'), 0, 64) ?>"> <?endif;?>
                                    <?if (strlen(CSalePaySystemAction::GetParamValue('EMAIL')) > 0): ?> <input
                                        type="hidden" name="Email"
                                        value="<?= substr(CSalePaySystemAction::GetParamValue('EMAIL'), 0, 64) ?>"> <?endif;?>
                                    <?if (strlen(CSalePaySystemAction::GetParamValue('PHONE')) > 0): ?> <input
                                        type="hidden" name="Phone"
                                        value="<?= substr(CSalePaySystemAction::GetParamValue('PHONE'), 0 , 64) ?>"> <?endif;?>
                                    <?if (strlen(CSalePaySystemAction::GetParamValue('ADDRESS')) > 0): ?>
                                        <input type="hidden" name="Address"
                                            value="<?= substr(CSalePaySystemAction::GetParamValue('ADDRESS'), 0, 128) ?>"> <?endif;?>
                                    <?if (strlen(CSalePaySystemAction::GetParamValue('CITY')) > 0): ?> <input
                                        type="hidden" name="City"
                                        value="<?= substr(CSalePaySystemAction::GetParamValue('CITY'), 0, 64) ?>"> <?endif;?>
                                    <?if (strlen(CSalePaySystemAction::GetParamValue('ZIP')) > 0): ?> <input
                                        type="hidden" name="Zip"
                                        value="<?= substr(CSalePaySystemAction::GetParamValue('ZIP'), 0, 64) ?>"> <?endif;?>
                                    <?if (strlen($signature) > 0): ?> <input type="hidden"
                                            name="Signature" value="<?= $signature ?>"> <?endif;?> <?if (strlen($URL_RETURN_OK) > 0): ?>
                                        <input type="hidden" name="URL_RETURN_OK"
                                            value="<?= substr($URL_RETURN_OK, 0, 128) ?>">
                                        <?endif;?> <?if (strlen($URL_RETURN_NO) > 0): ?>
                                        <input type="hidden" name="URL_RETURN_NO"
                                            value="<?= substr(($URL_RETURN_NO . '?ID=' . $sOrderID), 0, 128) ?>">
                                        <?endif;?> <input class="shaveClubPayButton" type="submit" name="Submit"
                                        value="Оплатить">
                                    <input type="hidden" value="0" name="MeanType">
                                    <input type="hidden" value="0" name="EMoneyType" >

                                    <!--</font>-->
                                </form>
                            </div>
                            <? } ?>
                        <?elseif ($obOrder["PAYED"] == "Y"):?>
                        <span class="not-paid" style="color:green !important; border-color:green">оплачен</span>
                        <?endif;?>


                </div>


            </div>
            <?}?>
        <span class="inside-page-col-close close-bg-black" onclick="makePlan()"></span>


    </div>


</div>