<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

?>
<div class="inside-page-col">
    <div class="inner_page_content">
        <?
            if (!empty($arResult["ORDER"]))
            {
            ?>
            <table class="sale_order_full_table">
                <tr>
                    <td>
                        <div class="final_step_title">заказ <font style="color: #DD9C42">№<?=$arResult["ORDER"]["ID"]?></font> успешно оформлен</div>
                        <br /><br />
                        <? //основные свойства заказа
                            $rsOrder = CSaleOrder::GetList(array('ID' => 'ASC'), array('ID' => $arResult["ORDER"]["ID"]), false, false, array());
                            while($arOrder = $rsOrder->Fetch()) {
                                $order=$arOrder;
                            };
                            $rsPropOrder = CSaleOrderPropsValue::GetList(array('ID' => 'ASC'), array('ORDER_ID' => $arResult["ORDER"]["ID"]), false, false, array());
                            while($arPropOrder = $rsPropOrder->Fetch()) {
                                $propOrder[]=$arPropOrder;
                            };

                            //состав корзины для текущего пользователя
                            $arID = array();
                            $arBasketItems = array();
                            $dbBasketItems = CSaleBasket::GetList(array("NAME" => "ASC", "ID" => "ASC"), array( "FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => $arResult["ORDER"]["ID"]), false, false, array());

                            while ($arItems = $dbBasketItems->Fetch())
                            {
                                if ('' != $arItems['PRODUCT_PROVIDER_CLASS'] || '' != $arItems["CALLBACK_FUNC"])
                                {
                                   // CSaleBasket::UpdatePrice($arItems["ORDER_ID"], $arItems["CALLBACK_FUNC"], $arItems["MODULE"], $arItems["PRODUCT_ID"], $arItems["QUANTITY"], "N",  $arItems["PRODUCT_PROVIDER_CLASS"]);
                                    $arID[] = $arItems["ID"];
                                }
                            }

                            if (!empty($arID))
                            {
                                $dbBasketItems = CSaleBasket::GetList( array("NAME" => "ASC", "ID" => "ASC"), array("ID" => $arID, "ORDER_ID" => $arResult["ORDER"]["ID"]), false, false, array());
                                while ($arItems = $dbBasketItems->Fetch())
                                {  $arBasketItems[] = $arItems;
                                }
                            }

                            //получаем картинку бритвы
                            $prosuct = CIBlockElement::GetList(array(), array("ID"=>$arBasketItems[0]["PRODUCT_ID"]), false, false, array("IBLOCK_SECTION_ID"));
                            $arProduct = $prosuct->Fetch();
                            $photo = CIBlockSection::GetList(array(), array("IBLOCK_ID"=>12,"ID"=>$arProduct["IBLOCK_SECTION_ID"]),false,array("UF_DETAIL_PICTURE"));
                            $arPhoto = $photo->Fetch();

                            //Печатаем массив, содержащий актуальную на текущий момент корзину
                            //Выбранная доставка
                            $db_dtype = CSaleDelivery::GetList( array( "SORT" => "ASC", "NAME" => "ASC"), array( "ID" => $order["DELIVERY_ID"]), false, false, array());
                            $delivery = $db_dtype->Fetch();
                            //Выбранная платежная система
                            $payment = CSalePaySystem::GetByID($order["PAY_SYSTEM_ID"]);
                            //Свойства товара
                            $db_itype = CIBlockElement::GetList( array(), array( "ID" => $arBasketItems[0]["PRODUCT_ID"]), false, false, array("IBLOCK_SECTION_ID", "PROPERTY_CASSETTE"));
                            $item = $db_itype->Fetch();
                            $casseteProp = explode(" ", $item["PROPERTY_CASSETTE_VALUE"]);
                            //Свойства секции
                            $db_stype = CIBlockSection::GetList( array(), array( "ID" => $item["IBLOCK_SECTION_ID"]), false, array("UF_*"), false);
                            $section = $db_stype->Fetch();
                        ?>
                        <div class="table-title">контактные данные</div>
                        <table class="contact-date">
                            <tr>
                                <td>фио</td>
                                <td><?= $propOrder[0]['VALUE'] ?></td>
                            </tr>
                            <tr>
                                <td>адрес доставки</td>
                                <td><?= $propOrder[4]['VALUE'] ?></td>
                            </tr>
                            <tr>
                                <td>телефон</td>
                                <td><?= $propOrder[2]['VALUE'] ?></td>
                            </tr>
                            <tr>
                                <td>почта</td>
                                <td><?= $propOrder[1]['VALUE'] ?></td>
                            </tr>
                        </table>
                        <br><br>
                        <div class="table-title">состав заказа</div>
                        <table class="contact-date table-with-image">
                            <tr>
                                <td>бритва</td>
                                <td><?=$section["NAME"]?></td>
                                <td rowspan="4"><img class="shave-image" width="111" height="300" src="<?=CFIle::GetPath($arPhoto["UF_DETAIL_PICTURE"])?>" alt=""></td>
                            </tr>
                            <tr>
                                <td>план бритья</td>
                                <? //Проверка выбран созданный план и создан свой план бритья
                                    if (count($arBasketItems)==1) {
                                        $shavePlan = $arBasketItems[0]["NAME"];
                                        //Получаем количество кассет для выбранного плана
                                        $casseteQuantity = $casseteProp[0];
                                        $machineQuantity = 1;
                                    } else {
                                        $shavePlan = 'Свой';
                                        $casseteQuantity = (float)$arBasketItems[0]["QUANTITY"];
                                        $machineQuantity = (float)$arBasketItems[1]["QUANTITY"];
                                } ?>
                                <td><?=$shavePlan?></td>
                            </tr>
                            <tr>
                                <td>комплектация</td>
                                <td>
                                    <table class="shave-equip">
                                        <tr>
                                            <td>Бритвенный станок</td>
                                            <td style="white-space: nowrap">x <?=$machineQuantity?></td>
                                        </tr>
                                        <tr>
                                            <td>Сменные кассеты</td>
                                            <td style="white-space: nowrap">x <?=$casseteQuantity?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>доставка</td>
                                <td><?=$delivery['NAME']?></td>

                            </tr>
                        </table>
                        <!--                        <img class="shave-image" width="130" src="<?=CFile::GetPath($section["PICTURE"])?>" alt="">
                        -->                        <br> <br>
                        <?// arshow($order['PRICE']);
                            if ($order['PRICE']!=0) {
                            ?>
                            <div class="table-title">сумма заказа</div>
                            <table class="contact-date sum-order">
                                <?//Подсчет стоимости без учета доставки
                                    $cartPrice=$order['PRICE']-$order['PRICE_DELIVERY'];
                                    //                            arshow($payment);
                                     //наличные
                                    /*if($payment["ID"]==17 || $payment["ID"]==19) {
                                        $cartPrice=$cartPrice-50;
                                        $cashAlert='+50 руб.';
                                    }
                                    */

                                ?>

                                <tr>
                                    <td>товар</td>
                                    <td><span class="cartPrice"><?=$cartPrice?></span> <font class="rouble">i</font></td>
                                </tr>

                                <tr>
                                    <td>доставка</td>
                                    <td><span class="cartPrice"><?=(float)$order['PRICE_DELIVERY']?></span> <font class="rouble">i</font></td>
                                </tr>

                                <tr>
                                    <td>способ оплаты</td>
                                    <td><?=$payment['NAME'].' '.$cashAlert?></td>
                                </tr>

                                <tr class="totalPrice">

                                    <td>итого:</td>
                                    <td><?=(float)$order["PRICE"]?> <font class="rouble">i</font>
                                    </td>
                                </tr>

                            </table>
                            <?   }
                        ?>

                        <!--<?= GetMessage("SOA_TEMPL_ORDER_SUC1", Array("#LINK#" => $arParams["PATH_TO_PERSONAL"])) ?> -->
                    </td>
                </tr>
            </table>
            <?
                if (!empty($arResult["PAY_SYSTEM"]))
                {
                ?>


                <table class="sale_order_full_table payment-type">
                    <!-- <tr>
                    <td class="ps_logo">
                    <div class="pay_name"><?=GetMessage("SOA_TEMPL_PAY")?></div>
                    <?=CFile::ShowImage($arResult["PAY_SYSTEM"]["LOGOTIP"], 100, 100, "border=0", "", false);?>
                    <div class="paysystem_name"><?= $arResult["PAY_SYSTEM"]["NAME"] ?></div><br>
                    </td>
                    </tr>   -->
                    <?

                        if (strlen($arResult["PAY_SYSTEM"]["ACTION_FILE"]) > 0)
                        {
                        ?>
                        <tr>
                            <td>
                                <?
                                    if ($arResult["PAY_SYSTEM"]["NEW_WINDOW"] == "Y")
                                    {
                                    ?>
                                    <script language="JavaScript">
                                        window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))?>');
                                    </script>
                                    <?= GetMessage("SOA_TEMPL_PAY_LINK", Array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))))?>
                                    <?
                                        if (CSalePdf::isPdfAvailable() && CSalePaySystemsHelper::isPSActionAffordPdf($arResult['PAY_SYSTEM']['ACTION_FILE']))
                                        {
                                        ?><br />
                                        <?= GetMessage("SOA_TEMPL_PAY_PDF", Array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))."&pdf=1&DOWNLOAD=Y")) ?>
                                        <?
                                        }
                                    }
                                    else
                                    {
                                        if (strlen($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"])>0)
                                        {
                                           // echo $arResult["PAY_SYSTEM"]["PATH_TO_ACTION"];
                                                     //                                   include($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"]);
                                        }
                                    }
                                ?>
                            </td>
                        </tr>
                        <?
                        }
                    ?>
                </table>
                <br> <br>
                <?
                }
            }
            else
            {
            ?>
            <b><?=GetMessage("SOA_TEMPL_ERROR_ORDER")?></b><br /><br />

            <table class="sale_order_full_table">
                <tr>
                    <td>
                        <?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST", Array("#ORDER_ID#" => $arResult["ACCOUNT_NUMBER"]))?>
                        <?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST1")?>
                    </td>
                </tr>
            </table>
            <?
            }
        ?>

    </div>
</div>
<div class="inside-page-col info-final-step">
    <!--    <div><span class="inside-page-col-shadow-faq"></span></div>-->
    <img class="smile-image" src="/images/smile-shave.png" alt="" <?if ($order["PAY_SYSTEM_ID"] == 48) {?>style="margin-top:100px;"<?}?>>
    <div class="title-info-final">спасибо за заказ</div>
    <div class="note-info-final">Осталось совсем немного и Ваши бритвы приедут к Вам домой!</div>
    <?
     //arshow($arResult["PAY_SYSTEM"]);    include($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"]);
   //  arshow($arResult);                    //bitrix/php_interface/include/sale_payment/uniteller.sale/payment.php
   ?>
   <div class="payment_button">
       <?
       if ($order["PAY_SYSTEM_ID"] == PAY_SYSTEM_ELECTRONIC_PAYMENT) {
        if (!empty($arResult["PAYMENT"])) {
            foreach ($arResult["PAYMENT"] as $payment) {
                if ($payment["PAID"] != 'Y') {
                    if (!empty($arResult['PAY_SYSTEM_LIST'])
                        && array_key_exists($payment["PAY_SYSTEM_ID"], $arResult['PAY_SYSTEM_LIST'])) {
                            $arPaySystem = $arResult['PAY_SYSTEM_LIST'][$payment["PAY_SYSTEM_ID"]];

                            if (empty($arPaySystem["ERROR"])) {
                            ?>
                                <?= $arPaySystem["BUFFERED_OUTPUT"] ?>
                            <?}
                    }
                }
            }
           }
       }?>
   </div>
    <?if($order["PAY_SYSTEM_ID"]==26){ ?>
           <div class="pay-block"><?php
/**
 * Формирует пакет данных для отправки в систему Uniteller.
 * Форма с данными вставляется на страницы:
 *  - "Оформление заказа" в форму "Заказ сформирован".
 *  - "Мой заказ №???" в раздел "Оплата и доставка".
 * @author r.smoliarenko
 * @author r.sarazhyn
 */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
include(GetLangFileName(dirname(__FILE__) . '/', '/uniteller.php'));
if (!class_exists('ps_uniteller')) {
    include(dirname(__FILE__) . '/tools.php');
}

$sOrderID = (strlen(CSalePaySystemAction::GetParamValue('ORDER_ID')) > 0) ? CSalePaySystemAction::GetParamValue('ORDER_ID') : $GLOBALS['SALE_INPUT_PARAMS']['ORDER']['ID'];
$sOrderID = trim($sOrderID);

$arOrder = CSaleOrder::GetByID($sOrderID);
$aCheckData = array();
ps_uniteller::doSyncStatus($arOrder, $aCheckData);

// Получаем данные из констант
ps_uniteller::setMerchantData($sOrderID);

// Если есть платёж, то выводим статус заказа.
if ($aCheckData['response_code'] !== '') {
    $arCurrentStatus = CSaleStatus::GetByID($arOrder['STATUS_ID']);
    echo '<br><strong>' . $arCurrentStatus['NAME'] . '</strong>';
} else {
    // Если оплата еще не была произведена, то выводим форму для оплаты заказа.
    $sDateInsert = (strlen(CSalePaySystemAction::GetParamValue('DATE_INSERT')) > 0) ? CSalePaySystemAction::GetParamValue('DATE_INSERT') : $GLOBALS['SALE_INPUT_PARAMS']['ORDER']['DATE_INSERT'];
    $sDateInsert = trim($sDateInsert);
    $fHouldPay = (strlen(CSalePaySystemAction::GetParamValue('SHOULD_PAY')) > 0) ? CSalePaySystemAction::GetParamValue('SHOULD_PAY') : $GLOBALS['SALE_INPUT_PARAMS']['ORDER']['SHOULD_PAY'];
    $sHouldPay = sprintf('%01.2f', $fHouldPay);
    $sCurrency = (strlen(CSalePaySystemAction::GetParamValue('CURRENCY')) > 0) ? CSalePaySystemAction::GetParamValue('CURRENCY') : $GLOBALS['SALE_INPUT_PARAMS']['ORDER']['CURRENCY'];
    $sCurrency = trim($sCurrency);

    $iLiftime = (int)CSalePaySystemAction::GetParamValue('LIFE_TIME');
    $URL_RETURN_OK = trim(CSalePaySystemAction::GetParamValue('SUCCESS_URL'));
    $URL_RETURN_NO = trim(CSalePaySystemAction::GetParamValue('FAIL_URL'));

    if ($iLiftime > 0) {
        $sLiftime = (string)$iLiftime;
//        $signature = strtoupper(md5(ps_uniteller::$Shop_ID . $sOrderID . $sHouldPay . $iLiftime . ps_uniteller::$Password));
    } else {
        $sLiftime = '';
//        $signature = strtoupper(md5(ps_uniteller::$Shop_ID . $sOrderID . $sHouldPay . ps_uniteller::$Password));
    }
    $signature = strtoupper(md5(md5(ps_uniteller::$Shop_ID) . '&' . md5($sOrderID) . '&' . md5($sHouldPay)
        . '&' . md5('') . '&' . md5('') . '&' . md5($sLiftime) . '&' . md5('') . '&' . md5('') . '&' . md5('')
        . '&' . md5('') . '&' . md5(ps_uniteller::$Password)));


//    da(ps_uniteller::$Shop_ID);
//    da($sOrderID);
//    da($sHouldPay);
//    da($sLiftime);
//    da(ps_uniteller::$Password);
?>
<form action="<?= ps_uniteller::$url_uniteller_pay ?>" method="post" target="_blank">
    <font class="tablebodytext"><br>
    <?
       /*
     ?><?= GetMessage('SUSP_ACCOUNT_NO') ?>
    <?= $sOrderID . GetMessage('SUSP_ORDER_FROM') . $sDateInsert ?><br> <?= GetMessage('SUSP_ORDER_SUM') ?><b><?= SaleFormatCurrency($sHouldPay, $sCurrency) ?>
    <?*/?>
    </b><br> <br>
        <input type="hidden" name="Shop_IDP"
        value="<?= ps_uniteller::$Shop_ID ?>">
        <input type="hidden" name="Order_IDP" value="<?= $sOrderID ?>"> <input
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
        <?endif;?> <input type="submit" name="Submit"
        value="<?echo GetMessage('SUSP_UNITELLER_PAY_BUTTON') ?>"> </font>
</form>
<?
  /*
 ?>
<p align="justify">
    <font class="tablebodytext"><b><?echo GetMessage('SUSP_DESC_TITLE') ?>
    </b> </font>
</p>
<p align="justify">
    <font class="tablebodytext"><?echo CSalePaySystemAction::GetParamValue('DESC') ?>
    </font>
</p>
<?
  */
 ?>
<?php
}
?></div>
        <? } else {?>
    <a href="/gift/" class="btn close-info-final">закрыть</a>
    <?}?>
    <div class="bottom-note-info-final">просто брейся. жизнь не бреет.</div>
</div>