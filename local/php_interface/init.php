<?

    define('DELIVERY_ID', 13);     //  Доставка курьером по России сайта  DORCO-razors.ru
    define('S1', 'shaveclub.ru');
    define('S2', 'dorco-razors.ru');

    CModule::IncludeModule("iblock");
    CModule::IncludeModule("sale");
    CModule::IncludeModule("main");
    CModule::IncludeModule("catalog");

    function arshow($array, $adminCheck = false){
        global $USER;
        $USER = new Cuser;
        if ($adminCheck) {
            if (!$USER->IsAdmin()) {
                return false;
            }
        }
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

    //Создали событие
    AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("AfterElementAdd", "AfterElementAddSendMail"));
    class AfterElementAdd
    {
        function AfterElementAddSendMail(&$arFields)
        {
            //Проверили номер инфоблока
            if($arFields["IBLOCK_ID"] == 24)
            {

                //Выбрали нужные свойства
                $arEventFields = array(
                    "AUTHOR"         => $arFields["NAME"],
                    "AUTHOR_EMAIL"        => $arFields["PROPERTY_VALUES"]["168"],
                    "TEXT"         => $arFields["PREVIEW_TEXT"],
                    "FEED_ID" => $arFields["ID"]
                );

                //Отправили нужное письмо с вышеуказанными данными
                CEvent::Send("FEEDBACK_FORM", SITE_ID, $arEventFields);
            }


        }
    }


    $site = SITE_ID;

    if($site == 's1' || $_SERVER["SERVER_NAME"] == S1){
       //добавляем в письмо о заказе дополнительную информацию
    AddEventHandler('sale', 'OnOrderNewSendEmail', Array("cEventHandler", "bxModifySaleMails"));
    class cEventHandler {
        function bxModifySaleMails($orderID, &$eventName, &$arFields) {

            CModule::IncludeModule("iblock");
            CModule::IncludeModule("catalog");
            CModule::IncludeModule("sale");
            $path = $_SERVER["HTTP_HOST"];
            $arOrder_new = array();
            $arItems = CSaleOrderPropsValue::GetList(array(), array("ORDER_ID" => $orderID));
            while($item = $arItems -> Fetch()){
                if($item["CODE"] == "store_pickup"){
                    $store = CCatalogStore::GetList(array(),array("ID"=>$item["VALUE"]))->Fetch();
                    $arOrder_new["pickup"] = ' <a href="http://'.$path.'/store/'.$item["VALUE"].'/">'.$store["TITLE"].'</a>';
                }elseif($item["CODE"] == "EMAIL"){
                    $arOrder_new["email"] = $item["VALUE"];
                }elseif($item["CODE"] == "quick_order"){
                    $arOrder_new["quick_order"] = $item["VALUE"];
                }
            }

            $ORDER_ID = $orderID;
            $arOrderProps = array();
            $order_props = CSaleOrderPropsValue::GetOrderProps($ORDER_ID);//Свойства заказа
            while ($arProps = $order_props->Fetch()) {
                if(!empty($arProps["CODE"]))
                    $arOrderProps[$arProps["CODE"]] = $arProps;
                else
                    $arOrderProps[$arProps["ID"]] = $arProps;
            }

            $arFields["ZIP"] = $arOrderProps["ZIP"]["VALUE"];
            $arFields["ADDRESS"] = $arOrderProps["ADDRESS"]["VALUE"];
            $arFields["PHONE"] = $arOrderProps["PHONE"]["VALUE"];
            if(!empty($arOrderProps["LOCATION"]["VALUE"])) {
                $arLocs = CSaleLocation::GetByID((int)$arOrderProps["LOCATION"]["VALUE"], LANGUAGE_ID);
                $arFields["ADDRESS"] = $arLocs["COUNTRY_NAME"] . ', ' . $arLocs["CITY_NAME"];
                if(!empty($arOrderProps["ADDRESS"]["VALUE"]))
                    $arFields["ADDRESS"] .= ', ' . $arOrderProps["ADDRESS"]["VALUE"];
            }
            if(!empty($arFields["ZIP"]))
                $arFields["ADDRESS"] = $arFields["ZIP"] . ', ' . $arFields["ADDRESS"];

            $dbOrder = CSaleOrder::GetList(
                array("ID" => "DESC"),
                array("ID" => $ORDER_ID),
                false,
                false
            );
            $arOrder = $dbOrder->Fetch();

            $arFields["EMAIL"] = $arOrderProps["EMAIL"]["VALUE"];
            $arFields["FIO"] = $arOrderProps["FIO"]["VALUE"];
            $arFields["USER_DESCRIPTION"] = $arOrderProps["KOMMENT"]["VALUE"];
            $arFields["ORDER_PRICE"] = round($arOrder["PRICE"], 0);
            $arFields["PRICE"] = round($arOrder["PRICE"], 0);
            $arFields["DISCOUNT_VALUE"] = round($arOrder["DISCOUNT_VALUE"], 0);
            $arFields["BASKET_PRICE"] = round($arOrder["PRICE"] - $arOrder["PRICE_DELIVERY"], 0);

            $DELIVERY_NAME = '';
            if (strpos($arOrder["DELIVERY_ID"], ":") !== false)    {
                $arId = explode(":", $arOrder["DELIVERY_ID"]);
                $dbDelivery = CSaleDeliveryHandler::GetBySID($arId[0]);
                $arDelivery = $dbDelivery->Fetch();
                $DELIVERY_NAME = htmlspecialcharsEx($arDelivery["NAME"])." - ".htmlspecialcharsEx($arDelivery["PROFILES"][$arId[1]]["TITLE"]);
            }
            elseif (IntVal($arOrder["DELIVERY_ID"]) > 0) {
                $arDelivery = CSaleDelivery::GetByID($arOrder["DELIVERY_ID"]);
                $DELIVERY_NAME = $arDelivery["NAME"];
            }
            $arFields["DELIVERY_NAME"] = $DELIVERY_NAME;

            if($arOrder["DELIVERY_ID"] == 'pickpoint:postamat')
                $arFields["DELIVERY_NAME"] = 'PickPoint';

            $arFields["DELIVERY_PRICE"] = round($arOrder["PRICE_DELIVERY"], 0);
            /* if(IntVal($arFields["DELIVERY_PRICE"]) <= 0)
                $arFields["DELIVERY_PRICE"] = 'Бесплатно';
            else
                $arFields["DELIVERY_PRICE"] .= ' руб.'; */

            $arFields["PAYMENT_NAME"] = '';
            //if (IntVal($arOrder["PAY_SYSTEM_ID"]) > 0) {
                $arPaySys = CSalePaySystem::GetByID($arOrder["PAY_SYSTEM_ID"]);
                $arFields["PAYMENT_NAME"] = htmlspecialcharsEx($arPaySys["NAME"]);
            //}

            if(!empty($arOrder["USER_DESCRIPTION"]))
                $arFields["USER_DESCRIPTION"] = $arOrder["USER_DESCRIPTION"];

            $dbBasketTmp = CSaleBasket::GetList(
                array("NAME" => "ASC"),
                array("ORDER_ID" => $ORDER_ID),
                false,
                false
            );
            $DISCOUNT_PRICE = $BASE_PRICE = 0;
            $isProductInSet = false;
            while ($arBasketTmp = $dbBasketTmp->GetNext()) {
                $arBasketId[] = $arBasketTmp["PRODUCT_ID"];
                if(strstr($arBasketTmp['PRODUCT_XML_ID'],'#')) {
                    $arVals = array();
                    $tmp = explode('#',$arBasketTmp['PRODUCT_XML_ID']);
                    $id = (int)$tmp[1];
                    $res = CIblockElement::GetById($id)->GetNextElement();
                    $props = $res->GetProperties();
                    foreach($props as $val) {
                        if(!strstr($val['CODE'],'CML2_') && !empty($val['VALUE']))
                            $arVals[] = $val['NAME'].': '.$val['VALUE'];
                    }
                    if(!empty($arVals)) {
                        $arBasketTmp['NAME'] .= ' ('.implode(', ',$arVals).')';
                    }
                }
                if($arBasketTmp['DISCOUNT_PRICE'] > 0)
                    $DISCOUNT_PRICE += $arBasketTmp['DISCOUNT_PRICE'];

                if($arBasketTmp['BASE_PRICE'] > 0)
                    $BASE_PRICE += $arBasketTmp['BASE_PRICE'] * $arBasketTmp['QUANTITY'];

                $arBasketValue[] = $arBasketTmp;

                if(CCatalogProductSet::isProductHaveSet($arBasketTmp["PRODUCT_ID"])) {
                    $isProductInSet = true;
                }
            }
            /* if($BASE_PRICE > 0) {
                $DISCOUNT_VALUE = $BASE_PRICE - $arOrder["PRICE"] + $arOrder["PRICE_DELIVERY"];
                $arFields["DISCOUNT_VALUE"] = round($DISCOUNT_VALUE, 0);
            } */

            $BasketListStr = '';
            if(!empty($arBasketId)) {
                $arLinks = $arPictures = $arItemsTMP = array();
                $res = CIblockElement::GetList(array(),array("ID" => $arBasketId), false, false, array("ID", "IBLOCK_ID", "DETAIL_PAGE_URL", "PREVIEW_PICTURE", "DETAIL_PICTURE", "IBLOCK_SECTION_ID"));
                while($ob = $res->GetNextElement()) {
                    $arItemFields = $ob->GetFields();
                    $arItemsTMP[] = $arItemFields;
                    $arLinks[$arItemFields["ID"]] = "http://".SITE_SERVER_NAME . $arItemFields["DETAIL_PAGE_URL"];
                    $arPictures[$arItemFields["ID"]] = !empty($arItemFields["PREVIEW_PICTURE"]) ? $arItemFields["PREVIEW_PICTURE"] : $arItemFields["DETAIL_PICTURE"];
                    if(empty($arPictures[$arItemFields["ID"]]) && !empty($arItemFields["IBLOCK_SECTION_ID"])) {
                        $section = CIBlockSection::GetList(array(), array("IBLOCK_ID" => $arItemFields["IBLOCK_ID"],"ID" => $arItemFields["IBLOCK_SECTION_ID"]), false, array("UF_DETAIL_PICTURE"));
                        $arSection = $section->Fetch();
                        $arPictures[$arItemFields["ID"]] = $arSection["UF_DETAIL_PICTURE"];
                    }
                }
                $i = 0;
                $arFields["BASKET_COUNT"] = count($arBasketValue);
                foreach($arBasketValue as $arBasket) {
                    if($isProductInSet) {
                        if(!CCatalogProductSet::isProductHaveSet($arBasket["PRODUCT_ID"])) {
                            $arFields["BASKET_COUNT"]--;
                            continue;
                        }
                    }
                    $arFile = CFile::ResizeImageGet($arPictures[$arBasket["PRODUCT_ID"]], array('width' => 117, 'height' => 117), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                    $ItemPrice = round($arBasket["PRICE"], 0);
                    $ItemPriceAdd = '';
                    if($arBasket["QUANTITY"] > 1) {
                        $ItemPrice = round($arBasket["QUANTITY"] * $arBasket["PRICE"], 0);
                        $ItemPriceAdd = '(' . round($arBasket["PRICE"], 0) . ' руб./шт.)';
                    }

                    $i++;
                    $BasketListStr .= "

                        <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\">
                            <tr>
                                <td class=\"img\" style=\"font-size:0pt; line-height:0pt; text-align:center\" width=\"9\" bgcolor=\"#ffffff\"></td>
                                <td width=\"50%\">
                                    <div style=\"font-size:0pt; line-height:0pt; text-align:center\"><img src=\"http://" . S1 . '/' . $arFile['src'] . "\" border=\"0\" width=\"" . $arFile['width'] . "\" height=\"" . $arFile['height'] . "\" alt=\"\" /></div>
                                </td>
                                <td width=\"50%\" align=\"center\">
                                    <div style=\"display: inline-block;color:#1e1e1e; font-family:Arial,Helvetica, serif; min-width:auto !important; font-size:14pt; line-height:16pt; text-align:center;font-weight: bold;\">" . $arBasket['~NAME'] . "<br/ >" . round($arBasket['QUANTITY'], 0) . " шт.<br/ >" . $ItemPrice . " руб. " . $ItemPriceAdd . "</div>
                                </td>
                            </tr>
                        </table>
                        <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"spacer\" style=\"font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%\"><tr><td height=\"11\" class=\"spacer\" style=\"font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%\">&nbsp;</td></tr></table>
                        <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#f2e7ce\" class=\"spacer\" style=\"font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%\"><tr><td height=\"4\" class=\"spacer\" style=\"font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%\">&nbsp;</td></tr></table>
                    ";
                    if($i < $arFields["BASKET_COUNT"]) {
                        $BasketListStr .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"spacer\" style=\"font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%\"><tr><td height=\"11\" class=\"spacer\" style=\"font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%\">&nbsp;</td></tr></table>";
                    }
                }
            }
            $arFields["ORDER_LIST"] = $BasketListStr;

            return $arFields;
        }
    }
    }

    if($site == 's2' || $_SERVER["SERVER_NAME"] == S2){

        AddEventHandler("forum", "onAfterMessageAdd", "notifyNewItemFeedback");
        function notifyNewItemFeedback($ID, $arFields) {
            $res = CIBlockElement::GetList(
                array(),
                array(
                    'IBLOCK_ID' => 9,
                    'PROPERTY_FORUM_TOPIC_ID' => $arFields["TOPIC_ID"]
                ),
                false,
                false,
                array('*', 'IBLOCK_ID', 'ID', 'NAME', 'PROPERTY_FORUM_TOPIC_ID', 'CODE', 'SECTION', 'SECTIONS')
            );
            if($ar_res = $res->GetNext()) {
                $TYPE_MAIL_EVENT = 'NEW_ITEM_REVIEW';
                $arMail = array(
                    'ITEM_NAME' => $ar_res['NAME'],
                    'AUTHOR_NAME' => $arFields['AUTHOR_NAME'],
                    'POST_DATE' => date('d.m.Y H:i:s'),
                    'POST_MESSAGE' => $arFields['POST_MESSAGE'],
                    'PATH2ITEM' => '/catalog/' . $ar_res["IBLOCK_SECTION_ID"] . '/' . $ar_res['ID'],
                );
                $ID_MAIL_EVENT = "NEW_ITEM_REVIEW";
                print_r($arFields);
                $ok = CEvent::Send($TYPE_MAIL_EVENT, "s2", $arMail,"N");
            }
        }

        AddEventHandler("main", "OnAfterUserAdd", "OnAfterUserAddHandler");

        function OnAfterUserAddHandler(&$arFields) {
            $user = new CUser;
            $fields = Array(
                "UF_SITE" => 23,
            );
            $user->Update($arFields["ID"], $fields);
        }

        /**
        * Разрешение доставки при смене статуса заказа на "Передан в СД" или "Возврат"
        */
        AddEventHandler('sale', 'OnSaleStatusOrder', 'updatingDeducting');

        function updatingDeducting ($ID, $val) {
            if ($val == "R" || $val == "V" || $val == "S") {
                $ar_order_new_fields = array (
                    "ALLOW_DELIVERY" => "Y"
                );
                CSaleOrder::Update($ID, $ar_order_new_fields);
            }
        }

        /**
        * Переключение флага оплаты заказа в значение "Да" при смене статуса заказа на "Выполнен"
        */
        AddEventHandler('sale', 'OnSaleStatusOrder', 'updatingPaymentProp');

        function updatingPaymentProp ($ID, $ar_fields) {
            if ($ar_fields["STATUS_ID"] == "F") {
                $order = CSaleOrder::GetById($ID);
                //если флаг оплаты не стоит - ставим
                if ($order["PAYED"] != "Y") {
                    CSaleOrder::PayOrder($ID, "Y", false, false, 0);
                }
            }
        }

        /**
        * обработка статусов заказа при получении оплаты
        */
        AddEventHandler('sale', 'OnSalePayOrder', "updOrderStatus");

        function updOrderStatus ($ID, $val) {
            //при получении оплаты
            if ($val == "Y") {
                $order = CSaleOrder::GetById($ID);
                //если текущий статус закана - не "Выполнен", ставим статус "новый, оплачен"
                if ($order["STATUS_ID"] != "F") {
                    CSaleOrder::StatusOrder($ID, "P");
                }
            }
        }

        /**
        * Переключение флага отгрузки в значение "Да" при смене статуса заказа на "Выполнен"
        */
        AddEventHandler('sale', 'OnSaleStatusOrder', 'deductingItem');

        function deductingItem ($ID, $val) {
            if ($val == "R" || $val == "S") {
                CSaleOrder::DeductOrder($ID, "Y");

                \Bitrix\Main\Loader::includeModule('sale');

                $order = \Bitrix\Sale\Order::load($ID);

                /** @var \Bitrix\Sale\ShipmentCollection $shipmentCollection */
                $shipmentCollection = $order->getShipmentCollection();
                /** @var \Bitrix\Sale\Shipment $shipment */
                foreach ($shipmentCollection as $shipment) {

                    echo $shipment->setStoreId("1");
                }

                $order->save();
            }
        }
        AddEventHandler('main', 'OnBeforeEventSend', 'SentMail');

        function SentMail(&$arFields, &$arTemplate) {
            if($arTemplate["EVENT_NAME"] != "USER_PASS_REQUEST"){
                CModule::IncludeModule("iblock");
                CModule::IncludeModule("catalog");
                CModule::IncludeModule("sale");
                $path = $_SERVER["HTTP_HOST"] ;
                $arItems = CSaleOrderPropsValue::GetList(array(), array("ORDER_ID" => $arFields["ORDER_ID"]));

                while ($item = $arItems -> Fetch()) {
                    if ($item["CODE"] == "store_pickup") {
                        $ar_filter = is_numeric($item["VALUE"]) ? array("ID" => $item["VALUE"]) : array("TITLE" => $item["VALUE"]);
                        $store = CCatalogStore::GetList(array(), $ar_filter)->Fetch();
                        $arOrder_new["pickup"] = ' <a href="http://' . $path . '/store/' . $store["ID"] . '/">' . $store["TITLE"] . '</a>';
                    } elseif ($item["CODE"] == "EMAIL") {
                        $arOrder_new["email"] = $item["VALUE"];
                    } elseif ($item["CODE"] == "quick_order") {
                        $arOrder_new["quick_order"] = $item["VALUE"];
                    }
                }

                if ($arOrder_new["quick_order"] && $arOrder_new["quick_order"] != "Y") {
                    $ORDER_ID = $arFields["ORDER_ID"];
                    $arOrderProps = array();
                    $order_props = CSaleOrderPropsValue::GetOrderProps($ORDER_ID);//Свойства заказа
                    while ($arProps = $order_props->Fetch()) {
                        if (!empty($arProps["CODE"])){
                            $arOrderProps[$arProps["CODE"]] = $arProps;
                        } else {
                            $arOrderProps[$arProps["ID"]] = $arProps;
                        }
                    }

                    $arFields["PHONE"] = $arOrderProps["PHONE"]["VALUE"];
                    if (!empty($arOrderProps["LOCATION"]["VALUE"])) {
                        $arLocs = CSaleLocation::GetByID((int) $arOrderProps["LOCATION"]["VALUE"], LANGUAGE_ID);
                        $arFields["ADDRESS"] = $arLocs["COUNTRY_NAME"] . ", " . $arLocs["REGION_NAME_LANG"] . ', ' . $arLocs["CITY_NAME"];
                        if (!empty($arOrderProps["ADDRESS"]["VALUE"])){
                            $arFields["ADDRESS"] .= ', ' . $arOrderProps["ADDRESS"]["VALUE"];
                        }
                    }

                    $dbOrder = CSaleOrder::GetList(
                        array("ID" => "DESC"),
                        array("ID" => $ORDER_ID),
                        false,
                        false
                    );
                    $arOrder = $dbOrder -> Fetch();
                    //email из настроек юзера

                    $arFields["EMAIL"] = $arOrder_new["email"];
                    $arFields["ORDER_PRICE"] = round($arOrder["PRICE"], 0);
                    $arFields["DISCOUNT_VALUE"] = round($arOrder["DISCOUNT_VALUE"], 0);
                    $arFields["BASKET_PRICE"] = round($arOrder["PRICE"] - $arOrder["PRICE_DELIVERY"], 0);


                    $arFields["PICKUP"] = $arOrder_new["pickup"];

                    $DELIVERY_NAME = '';
                    if (strpos($arOrder["DELIVERY_ID"], ":") !== false) {
                        $arId = explode(":", $arOrder["DELIVERY_ID"]);
                        $dbDelivery = CSaleDeliveryHandler::GetBySID($arId[0]);
                        $arDelivery = $dbDelivery -> Fetch();
                        $DELIVERY_NAME = htmlspecialcharsEx($arDelivery["NAME"])." - ".htmlspecialcharsEx($arDelivery["PROFILES"][$arId[1]]["TITLE"]);
                    }
                    elseif (IntVal($arOrder["DELIVERY_ID"]) > 0) {
                        $arDelivery = CSaleDelivery::GetByID($arOrder["DELIVERY_ID"]);
                        $DELIVERY_NAME = $arDelivery["NAME"];
                    }

                    $arFields["DELIVERY_NAME"] = $DELIVERY_NAME.'<br>'.$arOrder_new["pickup"];
                    $arFields["DELIVERY_PRICE"] = round($arOrder["PRICE_DELIVERY"], 0);
                    if (IntVal($arFields["DELIVERY_PRICE"]) <= 0) {
                        $arFields["DELIVERY_PRICE"] = 'Бесплатно';
                    }

                    if (IntVal($arOrder["PAY_SYSTEM_ID"]) > 0) {
                        $arPaySys = CSalePaySystem::GetByID($arOrder["PAY_SYSTEM_ID"], $arOrder["PERSON_TYPE_ID"]);
                        if ($arPaySys) {
                            $arFields["PAYMENT_NAME"] = htmlspecialcharsEx($arPaySys["NAME"]);
                        }
                    }

                    $arFields["USER_DESCRIPTION"] = '';
                    if (!empty($arOrder["USER_DESCRIPTION"])) {
                        $arFields["USER_DESCRIPTION"] = $arOrder["USER_DESCRIPTION"];
                    }

                    $dbBasketTmp = CSaleBasket::GetList(
                        array("NAME" => "ASC"),
                        array("ORDER_ID" => $ORDER_ID, "SET_PARENT_ID" => false),
                        false,
                        false
                    );
                    $DISCOUNT_PRICE = $BASE_PRICE = 0;
                    while ($arBasketTmp = $dbBasketTmp->GetNext()) {

                        $arBasketId[] = $arBasketTmp["PRODUCT_ID"];
                        if(strstr($arBasketTmp['PRODUCT_XML_ID'],'#')) {
                            $arVals = array();
                            $tmp = explode('#',$arBasketTmp['PRODUCT_XML_ID']);
                            $id = (int)$tmp[1];
                            $res = CIblockElement::GetById($id)->GetNextElement();
                            if ($res) {
                                $props = $res->GetProperties();
                            }
                            foreach ($props as $val) {
                                if (!strstr($val['CODE'],'CML2_') && !empty($val['VALUE'])) {
                                    $arVals[] = $val['NAME'].': '.$val['VALUE'];
                                }
                            }
                            if (!empty($arVals)) {
                                $arBasketTmp['NAME'] .= ' ('.implode(', ',$arVals).')';
                            }
                        }
                        if ($arBasketTmp['DISCOUNT_PRICE'] > 0){
                            $DISCOUNT_PRICE += $arBasketTmp['DISCOUNT_PRICE'];
                        }

                        if ($arBasketTmp['BASE_PRICE'] > 0) {
                            $BASE_PRICE += $arBasketTmp['BASE_PRICE'] * $arBasketTmp['QUANTITY'];
                        }

                        $arBasketValue[] = $arBasketTmp;
                    }
                    if ($BASE_PRICE > 0) {
                        $DISCOUNT_VALUE = $BASE_PRICE - $arOrder["PRICE"] + $arOrder["PRICE_DELIVERY"];
                        $arFields["DISCOUNT_VALUE"] = round($DISCOUNT_VALUE, 0);
                    }

                    $BasketListStr = '';
                    if (!empty($arBasketId)) {
                        $arLinks = $arPictures = array();
                        $res = CIblockElement::GetList(array(),array("ID" => $arBasketId), false, false, array("ID", "IBLOCK_ID", "DETAIL_PAGE_URL", "PREVIEW_PICTURE", "DETAIL_PICTURE"));
                        while ($ob = $res->GetNextElement()) {
                            $arItemFields = $ob->GetFields();
                            $arLinks[$arItemFields["ID"]] = "http://".$_SERVER["SERVER_NAME"] . $arItemFields["DETAIL_PAGE_URL"];
                            $arPictures[$arItemFields["ID"]] = !empty($arItemFields["PREVIEW_PICTURE"]) ? $arItemFields["PREVIEW_PICTURE"] : $arItemFields["DETAIL_PICTURE"];
                        }
                        foreach ($arBasketValue as $arBasket) {
                            $arFile = CFile::ResizeImageGet($arPictures[$arBasket["PRODUCT_ID"]], array('width' => 130, 'height' => 200), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                            $ItemPrice = round($arBasket["PRICE"], 0) . ' руб.<span></span>';
                            if ($arBasket["QUANTITY"] > 1) {
                                $ItemPrice = round($arBasket["QUANTITY"] * $arBasket["PRICE"], 0) . ' (' . round($arBasket["PRICE"], 0) . '/шт.)';
                            }

                            $BasketListStr .= "
                            <div class=\"row\" style=\"width: 100%; overflow: hidden; border-bottom: 2px solid #f0f0f0; height: 140px;\">
                            <table style=\"width:800px;\">
                            <tr>
                            <td width=\"20%\">
                            <img src=\"http://" . $_SERVER["SERVER_NAME"] . $arFile['src'] . "\" alt=\"" . str_replace("&quot;","",$arBasket["~NAME"]) . "\" style=\"display:block\" width=\"130\">
                            </td>
                            <td width=\"40%\">
                            <div class=\"title\" style=\"margin-top: 40px; font-family: HelveticaBold; font-size: 16px !important;font-weight: bold;\">
                            "  . $arBasket["~NAME"]  . "
                            </div>
                            </td>
                            <td width=\"100px\">
                            <div class=\"count\" style=\"margin-top: 40px; font-family: HelveticaBold; font-size: 16px !important;\">
                            <span style=\"font-family: HelveticaBold; font-size: 16px !important;\">".round($arBasket["QUANTITY"], 0)." шт.</span>
                            </div>
                            </td>
                            <td width=\"230px\">
                            <div class=\"price\" style=\"margin-top: 40px; font-family: HelveticaBold; font-size: 16px !important;font-weight: bold;text-align: left;\">
                            <span style=\"display: block; padding-right:80px;\">".$ItemPrice."</span>
                            </div>
                            </td>
                            </tr>
                            </table>
                            </div>
                            ";
                        }

                        $arFields["BASKET_COUNT"] = count($arBasketValue);
                    }
                    $arFields["ORDER_LIST"] = $BasketListStr;

                } else {
                    return false;
                }
            }
        }
    }
    //для каждого сайта init.php свой и находится в папке, соответствующей ID сайта (s1 или s2) и весь уникальный для сайта код писать туда

    include ($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/".$site."/init.php");
    include ($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/.config.php");




?>