<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(strlen($arResult["ERROR_MESSAGE"])<=0):?>
<div class="inside-page-col"> 
    <div class="inner_page_content">
            <table class="sale_order_full_table">
                <tr>
                    <td>
                        <div class="final_step_title">заказ <font style="color: #DD9C42">№<?=$_GET['Order_ID']?></font> успешно оплачен</div>
                        <br /><br />
                        <? //основные свойства заказа 
                            $rsOrder = CSaleOrder::GetList(array('ID' => 'ASC'), array('ID' => $_GET['Order_ID']), false, false, array());
                            while($arOrder = $rsOrder->Fetch()) {
                                $order=$arOrder;
                            };
                            $rsPropOrder = CSaleOrderPropsValue::GetList(array('ID' => 'ASC'), array('ORDER_ID' => $_GET['Order_ID']), false, false, array());
                            while($arPropOrder = $rsPropOrder->Fetch()) {
                                $propOrder[]=$arPropOrder;
                            };
                            //состав корзины для текущего пользователя
                            $arID = array();
                            $arBasketItems = array();
                            $dbBasketItems = CSaleBasket::GetList(array("NAME" => "ASC", "ID" => "ASC"), array( "FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => $_GET['Order_ID']), false, false, array());
                            while ($arItems = $dbBasketItems->Fetch())
                            {
                                if ('' != $arItems['PRODUCT_PROVIDER_CLASS'] || '' != $arItems["CALLBACK_FUNC"])
                                {
                                    CSaleBasket::UpdatePrice($arItems["ID"], $arItems["CALLBACK_FUNC"], $arItems["MODULE"], $arItems["PRODUCT_ID"], $arItems["QUANTITY"], "N",  $arItems["PRODUCT_PROVIDER_CLASS"]);
                                    $arID[] = $arItems["ID"];
                                }
                            }
                            if (!empty($arID))
                            {
                                $dbBasketItems = CSaleBasket::GetList( array("NAME" => "ASC", "ID" => "ASC"), array("ID" => $arID, "ORDER_ID" => $_GET['Order_ID']), false, false, array());
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
                            $db_stype = CIBlockSection::GetList( array(), array( "ID" => $item["IBLOCK_SECTION_ID"]), false, false, array("UF_*"));
                            $section = $db_stype->Fetch();
                        ?>
                        <div class="table-title">контактные данные</div>
                        <table class="contact-date">
                            <tr>
                                <td>фио</td>
                                <td><?=$propOrder[0]['VALUE']?></td>
                            </tr>
                            <tr>
                                <td>адрес доставки</td>
                                <td><?=$propOrder[5]['VALUE']?></td>
                            </tr>
                            <tr>
                                <td>телефон</td>
                                <td><?=$propOrder[2]['VALUE']?></td>
                            </tr>
                            <tr>
                                <td>почта</td>
                                <td><?=$propOrder[1]['VALUE']?></td>
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
                                            <td>x <?=$machineQuantity?></td>
                                        </tr> 
                                        <tr>
                                            <td>Сменные кассеты</td>
                                            <td>x <?=$casseteQuantity?></td>
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
                        <div class="table-title">сумма заказа</div>
                        <table class="contact-date sum-order">
                            <?//Подсчет стоимости без учета доставки
                            $cartPrice=$order['PRICE']-$order['PRICE_DELIVERY'];
//                            arshow($payment);
                            if($payment["ID"]==1) {
                                $cartPrice=$cartPrice;   
                                $cashAlert='+50 руб.';
                            }
                            
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
                                <td><?=(float)$order["PRICE"]?> <font class="rouble">i</font></td>
                            </tr>
                        </table>

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
                                            //echo $arResult["PAY_SYSTEM"]["PATH_TO_ACTION"];    
                                            //include($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"]);
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
            ?>


    </div>
</div>
<div class="inside-page-col info-final-step">
    <!--    <div><span class="inside-page-col-shadow-faq"></span></div>-->    
    <img class="smile-image" src="/images/smile-shave.png" alt="">
    <div class="title-info-final">спасибо за заказ</div>
    <div class="note-info-final">Осталось совсем немного и Ваши бритвы приедут к Вам домой!</div>

    <a href="/gift/" class="btn close-info-final">закрыть</a>

    <div class="bottom-note-info-final">просто брейся. жизнь не бреет.</div>
</div>
<?else:?>
	<?=ShowError($arResult["ERROR_MESSAGE"]);?>
<?endif;?>
