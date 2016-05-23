<?
    use Bitrix\Sale\Delivery\Services;


    //склонение слова "месяц"
    function month_name($num) {
        $month = "";
        $forms = array(
            0 => "месяц",
            1 => "месяца",
            2 => "месяцев",
        );

        if (substr($num,-1) == 1) {
            $month = "месяц";
        }
        else if ($num > 4 && $num <= 20) {
            $month = "месяцев";
        }
        else if (in_array(substr($num,-1),array(2,3,4))) {
            $month = "месяца";
        }
        else {
            $month = "месяцев";
        }

        return $month;
    }


    //функция генерации кода для подарочного сертификата
    function generateSertificate()
    {

        $allchars = 'ABCDEFGHIJKLNMOPQRSTUVWXYZ0123456789';
        $string = '';

        for ($i = 1; $i <= 16; $i++) {
            $string .= substr($allchars, rand(0, StrLen($allchars) - 1), 1);
            if ($i%4==0 && $i != 16) {
                $string .= "-";
            }
        }

        return $string;
    }


    // ---- Класс для работы с разными стоимостями доставки для разных наборов бритв
    class bundlePrice {

        /********************
        *
        * @param string $code
        * @return float $startBundlePrice
        *
        * *****************/

        private static function getStartBundlePrice($code){
            $arSelect = Array("ID");
            $arFilter = Array("IBLOCK_ID"=>12,"ACTIVE"=>"Y","CODE"=>$code);
            $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
            if($ob = $res->GetNextElement()){
                $arFields = $ob->GetFields();
                $startBundleID = $arFields['ID'];
            }
            $startBundlePrice = GetCatalogProductPrice($startBundleID,7);
            $startBundlePrice = $startBundlePrice['PRICE'];
            return $startBundlePrice;

        }

        /********************
        *
        * Функция для поиска CODE для стартового набора,
        * исходной строкой будет что-то типа razor_norma_4,
        * вырезаем все после первого подчеркивания и приклеиваем _start
        *
        * @param string $code
        * @return string
        *
        * *****************/

        private static function getStartBundleCode($code){
            return substr($code,strpos($code, "_")+1)."_start";
        }

        /********************
        *
        * @param array $items
        * @param int $customPrice
        * @return bool
        *
        * *****************/

        public static function isAStartBundle($items,$customPrice){
      //      arshow($items);
            $_SESSION['startBundle'] = "";
            if(// self::getStartBundlePrice($items['CODE']) > $customPrice)
                //&& ($items[0]["CATALOG"]["PROPERTIES"]["PRICE_START"]["VALUE"]<$customPrice)
                substr_count($items['CODE'],'_start') > 0){
                $_SESSION['startBundle'] = "T";
                return true;
            }
        }

    }


    //добавляем к стоимости заказа 50р при выборе оплаты наличными
   /* AddEventHandler("sale", "OnBeforeOrderAdd","addToFinalSum");
    function addToFinalSum(&$arFields) {

        if(($arFields["PAY_SYSTEM_ID"]==17 || $arFields["PAY_SYSTEM_ID"]==19) && $_POST["sertGift"]!="Y"){
            $arFields["PRICE"]+=50;
        }

        if(!$_SESSION['startBundle']){
            $arFields['PRICE'] = $arFields['PRICE'] - $arFields['PRICE_DELIVERY'];
            $arFields['PRICE_DELIVERY'] -= 200;
            if($arFields['PRICE_DELIVERY']<0){
                $arFields['PRICE_DELIVERY'] = 0;
            }
            $arFields['PRICE'] = $arFields['PRICE'] + $arFields['PRICE_DELIVERY'];
        }

        unset($_SESSION['startBundle']);
    }  */


    //меняем логин пользователя на email при создании пользователя
    AddEventHandler("main", "OnBeforeUserAdd","setUserFields");
    function setUserFields(&$arFields) {
        $arFields["LOGIN"] = $arFields["EMAIL"];
        $arFields["FIO"] = "";
        //        $arFields["PERSONAL_PHONE"] = $_POST["ORDER_PROP_3"];
    }


    //генерация подарочного сертификата при его покупке
    AddEventHandler("sale", "OnOrderAdd", Array("AfterOrderAdd", "addSertificate"));
    class AfterOrderAdd
    {
        function addSertificate($ID, $arFields)
        {
            if ($ID > 0) {
                //собираем корзину заказа
                $dbBasketItems = CSaleBasket::GetList(array(),array("FUSER_ID" => CSaleBasket::GetBasketUserID(),"LID" => SITE_ID,"ORDER_ID" => "NULL"),false,false,array());
                $arItems = $dbBasketItems->Fetch();
                //проверяем элемент корзины. если куплен сертификат, то добавляем сертифиакт в инфоблок
                $basketItemProps = CSaleBasket::GetPropsList(array(),array("BASKET_ID"=>$arItems["ID"]),false,false,array());
                while($arBasketItemProps = $basketItemProps->Fetch()) {
                    //если нашли свойство "подарочный сертификат", добавляем его в массив
                    if ($arBasketItemProps["CODE"] == "GIFT") {
                        $sertificate = $arBasketItemProps;
                    }
                }

                if (is_array($sertificate) && $sertificate["VALUE"]) {
                    //получаем код сертификата, чтобы получить по нему ID плана, который нужно привязать к сертификату
                    $arPlanGift = CIBlockElement::GetList(array(), array("ID"=>$arItems["PRODUCT_ID"]),false, false, array("CODE"))->Fetch();
                    $arPlanGift["CODE"] = str_replace("gift_","",$arPlanGift["CODE"]);

                    $arPlan = CIBlockElement::GetList(array(), array("CODE"=>$arPlanGift["CODE"]), false, false, array("ID"))->Fetch();


                    //генерируем скидочный купон
                    $COUPON = CatalogGenerateCoupon();

                    $arCouponFields = array(
                        "DISCOUNT_ID" => 6,
                        "ACTIVE" => "Y",
                        "ONE_TIME" => "Y",
                        "COUPON" => $COUPON,
                        "DATE_APPLY" => false
                    );

                    $CID = CCatalogDiscountCoupon::Add($arCouponFields);

                    //непосредственно добавляем сертификат в инфоблок
                    $el = new CIBlockElement;

                    $PROP = array();
                    $PROP["PLAN"] = $arPlan["ID"];  // план
                    $PROP["ORDER_ID"] = $ID;        // ID заказа, к которому привязывается купон
                    $PROP["COUPON"] = $COUPON;

                    $arLoadProductArray = Array(
                        "IBLOCK_ID"      => 27,
                        "PROPERTY_VALUES"=> $PROP,
                        "NAME"           => $sertificate["VALUE"],
                        "ACTIVE"         => "Y",            // активен
                    );

                    $el->Add($arLoadProductArray);

                }


                //проверяем. присутствует ли в заказе купон на скидку. если есть, проверяем, не привязан ли к нему сертификат. если сертификат найден - обновляем его
                if ($arItems["DISCOUNT_COUPON"])  {
                    $arCoupon = CCatalogDiscountCoupon::GetList(array(),array("COUPON"=>$arItems["DISCOUNT_COUPON"]),false,false,array())->Fetch();
                    $arSertificate = CIBLockElement::GetList(array(), array("PROPERTY_COUPON"=>$arItems["DISCOUNT_COUPON"]), false,false, array("ID", "NAME", "PROPERTY_USER"))->Fetch();
                    //обновляем информацию о сертификате
                    $USER = new CUser;
                    $PROP = array(
                        "USER" => $USER->GetId(),
                        "ACTIVE" => 88,
                    );
                    if ($arSertificate["ID"] > 0) {
                        CIBlockElement::SetPropertyValuesEx($arSertificate["ID"], false, $PROP);
                        $arSend = array(
                            "CERTIF_NAME"        => $arSertificate["NAME"],
                            "ACTIVATING_USER"    => CUser::GetFullName(),
                        );
                        CEvent::Send("SERTIF_ACTIVATE", s1, $arSend);
                    }
                }

            }
        }
    }


    //добавление плана в корзину. если $PRICE = "N" - то у добавляемого товара ставим цену 0 (это нужно для подарочных сертификатов)
    function addPlanToBasket($ID, $PRICE = false) {
        //очищаем корзину
        CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());

        //проверяем ID элемента
        $product = CCatalogProduct::GetList(array(),array("ID"=>$ID /*,"TYPE"=>2*/ /*это для комплекта*/));
        $arProduct = $product->Fetch();

        if ($arProduct["ID"] > 0) {

            // arshow($arProduct);

            $element = CIBlockElement::GetList(array(), array("IBLOCK_ID"=> 12,"ID"=>$arProduct["ID"]), false, false, array("PROPERTY_ECONOMY", "PROPERTY_LENGTH", "PROPERTY_CASSETTE", "PROPERTY_RAZOR", "PROPERTY_DELIVERY", "CODE"));
            $arElement = $element->Fetch();

            //arshow($arElement);

            /*$PROPS[] = array("NAME" => "Экономия, %",
            "CODE" => "ECONOMY",
            "VALUE" => $arElement["PROPERTY_ECONOMY_VALUE"],
            "SORT" => "100"
            );

            $PROPS[] = array("NAME" => "Срок, месяцев",
            "CODE" => "LENGTH",
            "VALUE" => $arElement["PROPERTY_LENGTH_VALUE"],
            "SORT" => "100"
            );

            $PROPS[] = array("NAME" => "Сменные кассеты",
            "CODE" => "CASSETTE",
            "VALUE" => $arElement["PROPERTY_CASSETTE_VALUE"],
            "SORT" => "100"
            );

            $PROPS[] = array("NAME" => "Бритвенный станок",
            "CODE" => "RAZOR",
            "VALUE" => $arElement["PROPERTY_RAZOR_VALUE"],
            "SORT" => "100"
            );

            $PROPS[] = array("NAME" => "Доставка",
            "CODE" => "DELIVERY",
            "VALUE" => $arElement["PROPERTY_DELIVERY_VALUE"],
            "SORT" => "100"
            );    */

            //если кладем сертификат, то добавляем дополнительное свойство
            if (substr_count($arElement["CODE"], "gift") > 0) {
                $PROPS[] = array("NAME" => "Подарочный сертификат",
                    "CODE" => "GIFT",
                    "VALUE" => generateSertificate(),
                    "SORT" => "100"
                );
            }

            if ($PRICE != "N") {
                //получаем цену товара
                $productPrice = CPrice::GetList( array(), array("PRODUCT_ID"=>$arProduct["ID"]),false, false,array());
                while($arProductPrice = $productPrice->Fetch()) {
                    if ($arProductPrice["PRICE"] > 0 && $arProductPrice["CAN_ACCESS"] == "Y") {
                        $price = intval($arProductPrice["PRICE"]);
                        break;
                    }
                }

            }
            else {
                $price = 0;
            }

            //получаем внешний код каталога
            $iblock = CIBlock::GetById($arProduct["ELEMENT_IBLOCK_ID"]);
            $arIblock = $iblock->Fetch();


            $arFields = array(
                "PRODUCT_ID" => $arProduct["ID"],
                "PRODUCT_XML_ID" => $arProduct["ELEMENT_XML_ID"],
                "CATALOG_XML_ID" => $arIblock["XML_ID"],
                "PRICE" => $price,
                "CURRENCY" => "RUB",
                "QUANTITY" => 1,
                "LID" => "s1",
                "NAME" => $arProduct["ELEMENT_NAME"],
                "WEIGHT" => $arProduct["WEIGHT"],
                "WIDTH" => $arProduct["WIDTH"],
                "HEIGHT" => $arProduct["HEIGHT"],
                "LENGTH" => $arProduct["LENGTH"],
                "PROPS" => $PROPS,
                "PRODUCT_PROVIDER_CLASS"=> "CCatalogProductProvider",
                "MODULE"=> "catalog",
                "CAN_BUY" =>"Y"
            );

            //    arshow($arFields);

            $basketID = CSaleBasket::Add($arFields);
            if ($basketID > 0) {
                echo "OK";
            }

            if ($PRICE == "N") {
                CSaleBasket::Update($basketID,array("PRICE"=>0));
            }
        }
    }


    //добавляем в письмо о заказе дополнительную информацию
    AddEventHandler('main', 'OnBeforeEventSend', Array("newOrder", "orderDataChange"));
    //AddEventHandler('sale', 'OnOrderNewSendEmail', Array("newOrder", "orderDataChange"));

    class newOrder
    {
        function orderDataChange(&$arFields, &$arTemplate)
        //function orderDataChange($order_id, &$arFields, &$arTemplate)
        {

            //$data = serialize($arFields)."\n\n".serialize($arTemplate);
            //file_put_contents($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/s1/test.txt",$data);

            if ($arFields["ORDER_ID"] > 0) {
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

                //arshow($paysystem);
                //            die;

                //собираем полученные данные
                //            $arFields["PRICE"] = $arFields["PRICE"] + 50;
                $arFields["DELIVERY_TYPE"] = $delivery["NAME"];
                $arFields["PHONE"] = $orderProps["PHONE"];
                $arFields["ZIP"] = $orderProps["ZIP"];
                // $arFields["ADDRESS"] = $location["COUNTRY_NAME"].", ".$location["REGION_NAME"].", ".$location["CITY_NAME"].", ".$orderProps["ADDRESS"];
                $arFields["ADDRESS"] = $location["COUNTRY_NAME"].", ".$location["CITY_NAME"].", ".$orderProps["ADDRESS"];
                //                $arFields["ORDER_LIST"] = /*"Бритва ".$arSection["NAME"]; , ".*/$arFields["ORDER_LIST"];
                $arFields["ORDER_LIST"] = str_replace(".00 шт.", " шт.", $arFields["ORDER_LIST"]);
            }

        }
    }

    //добавляем в письмо о заказе дополнительную информацию
    /*AddEventHandler('main', 'OnBeforeEventSend', Array("newOrder", "orderDataChange"));

    class newOrder
    {
    function orderDataChange(&$arFields, &$arTemplate)
    {

    $data = serialize($arFields)."\n\n".serialize($arTemplate);
    file_put_contents($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/s1/test.txt",$data);

    if ($arFields["ORDER_ID"] > 0) {
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
    /*$arFields["PAYSYSTEM"] = $paysystem['NAME'];

    //вычисляем стоимость доставки
    /*if(!$_SESSION['startBundle']){
    $price = $price - $arFields['DELIVERY_PRICE'];
    $arFields["DELIVERY_PRICE"] -= 200;
    if($arFields["DELIVERY_PRICE"]<0){
    $arFields["DELIVERY_PRICE"] = 0;
    }
    $price = $price + $arFields["DELIVERY_PRICE"];
    } */
    /*
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

    //arshow($paysystem);
    //            die;

    //собираем полученные данные
    //            $arFields["PRICE"] = $arFields["PRICE"] + 50;
    $arFields["DELIVERY_TYPE"] = $delivery["NAME"];
    $arFields["PHONE"] = $orderProps["PHONE"];
    $arFields["ZIP"] = $orderProps["ZIP"];
    // $arFields["ADDRESS"] = $location["COUNTRY_NAME"].", ".$location["REGION_NAME"].", ".$location["CITY_NAME"].", ".$orderProps["ADDRESS"];
    $arFields["ADDRESS"] = $location["COUNTRY_NAME"].", ".$location["CITY_NAME"].", ".$orderProps["ADDRESS"];
    //                $arFields["ORDER_LIST"] = /*"Бритва ".$arSection["NAME"]; , ".*//*$arFields["ORDER_LIST"];
    $arFields["ORDER_LIST"] = str_replace(".00 шт.", " шт.", $arFields["ORDER_LIST"]);;
    }

    }
    } */
    AddEventHandler("main", "OnAfterUserAdd", "OnAfterUserAddHandler");
    function OnAfterUserAddHandler(&$arFields)
    {
        $user = new CUser;
        $fields = Array(
            "UF_SITE" => 22,
        );
        $user->Update($arFields["ID"], $fields);
    }
?>