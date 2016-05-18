<?


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
    //добавляем в письмо о заказе дополнительную информацию
    AddEventHandler('main', 'OnBeforeEventSend', Array("newOrderAdmin", "orderDataChange"));
    //AddEventHandler('sale', 'OnOrderNewSendEmail', Array("newOrder", "orderDataChange"));

    class newOrderAdmin
    {
        function orderDataChange(&$arFields, &$arTemplate)
        //function orderDataChange($order_id, &$arFields, &$arTemplate)
        {

            if ($arFields["ORDER_ID"] > 0) {
                if ($_SERVER["HTTP_HOST"]=="shaveclub.ru"){
                    //общая инфо о зказе
                    $order = CSaleOrder::GetById($arFields["ORDER_ID"]);


                    //служба доставки
                    //$delivery = CSaleDelivery::GetById($order["DELIVERY_ID"]);
                    if ($order["DELIVERY_ID"]=='pickpoint:postamat') {
                        $order["DELIVERY_ID"]=37;
                        $delivery = Services\Manager::getById($order["DELIVERY_ID"]);

                    } else {
                        $delivery = CSaleDelivery::GetById($order["DELIVERY_ID"]);
                    }


                    //платежная система
                    $paysystem = CSalePaysystem::GetById($order["PAY_SYSTEM_ID"]);
                    //убираем лишние символы в цене
                    $pattern = "/(\D)/";
                    $price = preg_replace($pattern,'',$arFields["PRICE"]);

                    /*if ($order["PAY_SYSTEM_ID"]==17) {
                    $arFields["PAYSYSTEM"] = $paysystem['NAME'].' +50 руб';
                    $price = $price + 50;
                    } else {

                    }
                    */
                    $arFields["PAYSYSTEM"] = $paysystem['NAME'];

                    //вычисляем стоимость доставки
                    /*if(!$_SESSION['startBundle']){
                    $price = $price - $arFields['DELIVERY_PRICE'];
                    $arFields["DELIVERY_PRICE"] -= 200;
                    if($arFields["DELIVERY_PRICE"]<0){
                    $arFields["DELIVERY_PRICE"] = 0;
                    }
                    $price = $price + $arFields["DELIVERY_PRICE"];
                    } */

                    $arFields["PRICE"] = $price;

                    //свойства заказа
                    $orderProps = array();
                    $db_props = CSaleOrderPropsValue::GetList(array(),array("ORDER_ID" => $order["ID"]));
                    while($orderProp = $db_props->Fetch()) {
                        $orderProps[$orderProp["CODE"]] = $orderProp["VALUE"];
                    }
                    //местоположение
                    $location = CSaleLocation::GetByID($orderProps["LOCATION"]);

                    //состав заказа
                    $basket =  CSaleBasket::GetList(array(), array("ORDER_ID"=>$order["ID"]));
                    $basketItem = $basket->Fetch();
                    $arIblockItem = CIBlockElement::GetList(array(), array("ID"=>$basketItem["PRODUCT_ID"]))->Fetch();
                    $arSection =  CIBlockSection::GetList(array(), array("ID"=>$arIblockItem["IBLOCK_SECTION_ID"]))->Fetch();

                    //$data = serialize($basketItem);
                    //file_put_contents($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/test.txt",$data);

                    //собираем полученные данные
                    //            $arFields["PRICE"] = $arFields["PRICE"] + 50;
                    $arFields["DELIVERY_TYPE"] = $delivery["NAME"];
                    $arFields["PHONE"] = $orderProps["PHONE"];
                    $arFields["ZIP"] = $orderProps["ZIP"];
                    // $arFields["ADDRESS"] = $location["COUNTRY_NAME"].", ".$location["REGION_NAME"].", ".$location["CITY_NAME"].", ".$orderProps["ADDRESS"];
                    $arFields["ADDRESS"] = $location["COUNTRY_NAME"].", ".$location["CITY_NAME"].", ".$orderProps["ADDRESS"];
                    //                $arFields["ORDER_LIST"] = /*"Бритва ".$arSection["NAME"]; , ".*/$arFields["ORDER_LIST"];

                    $arFields["ORDER_LIST"] = $basketItem["NAME"].' - '.round($basketItem["QUANTITY"]).' шт.: '.round($basketItem["PRICE"]).' руб.';
                    if ($arFields['DELIVERY_PRICE']=='Бесплатно') {
                        $arFields['DELIVERY_PRICE']=0;
                    }


                }
            }
        }
    }


    $site = SITE_ID;
    if ($site == "ru") {
        $site = "s2";
    }
    //для каждого сайта init.php свой и находится в папке, соответствующей ID сайта (s1 или s2) и весь уникальный для сайта код писать туда
    include ($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/".$site."/init.php");
    include ($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/.config.php");




?>