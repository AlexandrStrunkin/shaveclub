<?
    use Bitrix\Sale\Delivery\Services;


    //��������� ����� "�����"
    function month_name($num) {
        $month = "";
        $forms = array(
            0 => "�����",
            1 => "������",
            2 => "�������",
        );

        if (substr($num,-1) == 1) {
            $month = "�����";
        }
        else if ($num > 4 && $num <= 20) {
            $month = "�������";
        }
        else if (in_array(substr($num,-1),array(2,3,4))) {
            $month = "������";
        }
        else {
            $month = "�������";
        }

        return $month;
    }


    //������� ��������� ���� ��� ����������� �����������
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


    // ---- ����� ��� ������ � ������� ����������� �������� ��� ������ ������� �����
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
        * ������� ��� ������ CODE ��� ���������� ������,
        * �������� ������� ����� ���-�� ���� razor_norma_4,
        * �������� ��� ����� ������� ������������� � ����������� _start
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


    //��������� � ��������� ������ 50� ��� ������ ������ ���������
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


    //������ ����� ������������ �� email ��� �������� ������������
    AddEventHandler("main", "OnBeforeUserAdd","setUserFields");
    function setUserFields(&$arFields) {
        $arFields["LOGIN"] = $arFields["EMAIL"];
        $arFields["FIO"] = "";
        //        $arFields["PERSONAL_PHONE"] = $_POST["ORDER_PROP_3"];
    }


    //��������� ����������� ����������� ��� ��� �������
    AddEventHandler("sale", "OnOrderAdd", Array("AfterOrderAdd", "addSertificate"));
    class AfterOrderAdd
    {
        function addSertificate($ID, $arFields)
        {
            if ($ID > 0) {
                //�������� ������� ������
                $dbBasketItems = CSaleBasket::GetList(array(),array("FUSER_ID" => CSaleBasket::GetBasketUserID(),"LID" => SITE_ID,"ORDER_ID" => "NULL"),false,false,array());
                $arItems = $dbBasketItems->Fetch();
                //��������� ������� �������. ���� ������ ����������, �� ��������� ���������� � ��������
                $basketItemProps = CSaleBasket::GetPropsList(array(),array("BASKET_ID"=>$arItems["ID"]),false,false,array());
                while($arBasketItemProps = $basketItemProps->Fetch()) {
                    //���� ����� �������� "���������� ����������", ��������� ��� � ������
                    if ($arBasketItemProps["CODE"] == "GIFT") {
                        $sertificate = $arBasketItemProps;
                    }
                }

                if (is_array($sertificate) && $sertificate["VALUE"]) {
                    //�������� ��� �����������, ����� �������� �� ���� ID �����, ������� ����� ��������� � �����������
                    $arPlanGift = CIBlockElement::GetList(array(), array("ID"=>$arItems["PRODUCT_ID"]),false, false, array("CODE"))->Fetch();
                    $arPlanGift["CODE"] = str_replace("gift_","",$arPlanGift["CODE"]);

                    $arPlan = CIBlockElement::GetList(array(), array("CODE"=>$arPlanGift["CODE"]), false, false, array("ID"))->Fetch();


                    //���������� ��������� �����
                    $COUPON = CatalogGenerateCoupon();

                    $arCouponFields = array(
                        "DISCOUNT_ID" => 6,
                        "ACTIVE" => "Y",
                        "ONE_TIME" => "Y",
                        "COUPON" => $COUPON,
                        "DATE_APPLY" => false
                    );

                    $CID = CCatalogDiscountCoupon::Add($arCouponFields);

                    //��������������� ��������� ���������� � ��������
                    $el = new CIBlockElement;

                    $PROP = array();
                    $PROP["PLAN"] = $arPlan["ID"];  // ����
                    $PROP["ORDER_ID"] = $ID;        // ID ������, � �������� ������������� �����
                    $PROP["COUPON"] = $COUPON;

                    $arLoadProductArray = Array(
                        "IBLOCK_ID"      => 27,
                        "PROPERTY_VALUES"=> $PROP,
                        "NAME"           => $sertificate["VALUE"],
                        "ACTIVE"         => "Y",            // �������
                    );

                    $el->Add($arLoadProductArray);

                }


                //���������. ������������ �� � ������ ����� �� ������. ���� ����, ���������, �� �������� �� � ���� ����������. ���� ���������� ������ - ��������� ���
                if ($arItems["DISCOUNT_COUPON"])  {
                    $arCoupon = CCatalogDiscountCoupon::GetList(array(),array("COUPON"=>$arItems["DISCOUNT_COUPON"]),false,false,array())->Fetch();
                    $arSertificate = CIBLockElement::GetList(array(), array("PROPERTY_COUPON"=>$arItems["DISCOUNT_COUPON"]), false,false, array("ID", "NAME", "PROPERTY_USER"))->Fetch();
                    //��������� ���������� � �����������
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


    //���������� ����� � �������. ���� $PRICE = "N" - �� � ������������ ������ ������ ���� 0 (��� ����� ��� ���������� ������������)
    function addPlanToBasket($ID, $PRICE = false) {
        //������� �������
        CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());

        //��������� ID ��������
        $product = CCatalogProduct::GetList(array(),array("ID"=>$ID /*,"TYPE"=>2*/ /*��� ��� ���������*/));
        $arProduct = $product->Fetch();

        if ($arProduct["ID"] > 0) {

            // arshow($arProduct);

            $element = CIBlockElement::GetList(array(), array("IBLOCK_ID"=> 12,"ID"=>$arProduct["ID"]), false, false, array("PROPERTY_ECONOMY", "PROPERTY_LENGTH", "PROPERTY_CASSETTE", "PROPERTY_RAZOR", "PROPERTY_DELIVERY", "CODE"));
            $arElement = $element->Fetch();

            //arshow($arElement);

            /*$PROPS[] = array("NAME" => "��������, %",
            "CODE" => "ECONOMY",
            "VALUE" => $arElement["PROPERTY_ECONOMY_VALUE"],
            "SORT" => "100"
            );

            $PROPS[] = array("NAME" => "����, �������",
            "CODE" => "LENGTH",
            "VALUE" => $arElement["PROPERTY_LENGTH_VALUE"],
            "SORT" => "100"
            );

            $PROPS[] = array("NAME" => "������� �������",
            "CODE" => "CASSETTE",
            "VALUE" => $arElement["PROPERTY_CASSETTE_VALUE"],
            "SORT" => "100"
            );

            $PROPS[] = array("NAME" => "���������� ������",
            "CODE" => "RAZOR",
            "VALUE" => $arElement["PROPERTY_RAZOR_VALUE"],
            "SORT" => "100"
            );

            $PROPS[] = array("NAME" => "��������",
            "CODE" => "DELIVERY",
            "VALUE" => $arElement["PROPERTY_DELIVERY_VALUE"],
            "SORT" => "100"
            );    */

            //���� ������ ����������, �� ��������� �������������� ��������
            if (substr_count($arElement["CODE"], "gift") > 0) {
                $PROPS[] = array("NAME" => "���������� ����������",
                    "CODE" => "GIFT",
                    "VALUE" => generateSertificate(),
                    "SORT" => "100"
                );
            }

            if ($PRICE != "N") {
                //�������� ���� ������
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

            //�������� ������� ��� ��������
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


    //��������� � ������ � ������ �������������� ����������
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
                //����� ���� � �����
                $order = CSaleOrder::GetById($arFields["ORDER_ID"]);

                //������ ��������
                //$delivery = CSaleDelivery::GetById($order["DELIVERY_ID"]);
                if ($order["DELIVERY_ID"]=='pickpoint:postamat') {
                    $order["DELIVERY_ID"]=37;
                    $delivery = Services\Manager::getById($order["DELIVERY_ID"]);

                } else {
                    $delivery = CSaleDelivery::GetById($order["DELIVERY_ID"]);
                }


                //��������� �������
                $paysystem = CSalePaysystem::GetById($order["PAY_SYSTEM_ID"]);
                //������� ������ ������� � ����
                $pattern = "/(\D)/";
                $price = preg_replace($pattern,'',$arFields["PRICE"]);

                /*if ($order["PAY_SYSTEM_ID"]==17) {
                $arFields["PAYSYSTEM"] = $paysystem['NAME'].' +50 ���';
                $price = $price + 50;
                } else {

                }
                */
                $arFields["PAYSYSTEM"] = $paysystem['NAME'];

                //��������� ��������� ��������
                /*if(!$_SESSION['startBundle']){
                $price = $price - $arFields['DELIVERY_PRICE'];
                $arFields["DELIVERY_PRICE"] -= 200;
                if($arFields["DELIVERY_PRICE"]<0){
                $arFields["DELIVERY_PRICE"] = 0;
                }
                $price = $price + $arFields["DELIVERY_PRICE"];
                } */

                $arFields["PRICE"] = $price;

                //�������� ������
                $orderProps = array();
                $db_props = CSaleOrderPropsValue::GetList(array(),array("ORDER_ID" => $order["ID"]));
                while($orderProp = $db_props->Fetch()) {
                    $orderProps[$orderProp["CODE"]] = $orderProp["VALUE"];
                }
                //��������������
                $location = CSaleLocation::GetByID($orderProps["LOCATION"]);

                //������ ������
                $basket =  CSaleBasket::GetList(array(), array("ORDER_ID"=>$order["ID"]));
                $basketItem = $basket->Fetch();
                $arIblockItem = CIBlockElement::GetList(array(), array("ID"=>$basketItem["PRODUCT_ID"]))->Fetch();
                $arSection =  CIBlockSection::GetList(array(), array("ID"=>$arIblockItem["IBLOCK_SECTION_ID"]))->Fetch();

                //arshow($paysystem);
                //            die;

                //�������� ���������� ������
                //            $arFields["PRICE"] = $arFields["PRICE"] + 50;
                $arFields["DELIVERY_TYPE"] = $delivery["NAME"];
                $arFields["PHONE"] = $orderProps["PHONE"];
                $arFields["ZIP"] = $orderProps["ZIP"];
                // $arFields["ADDRESS"] = $location["COUNTRY_NAME"].", ".$location["REGION_NAME"].", ".$location["CITY_NAME"].", ".$orderProps["ADDRESS"];
                $arFields["ADDRESS"] = $location["COUNTRY_NAME"].", ".$location["CITY_NAME"].", ".$orderProps["ADDRESS"];
                //                $arFields["ORDER_LIST"] = /*"������ ".$arSection["NAME"]; , ".*/$arFields["ORDER_LIST"];
                $arFields["ORDER_LIST"] = str_replace(".00 ��.", " ��.", $arFields["ORDER_LIST"]);
            }

        }
    }

    //��������� � ������ � ������ �������������� ����������
    /*AddEventHandler('main', 'OnBeforeEventSend', Array("newOrder", "orderDataChange"));

    class newOrder
    {
    function orderDataChange(&$arFields, &$arTemplate)
    {

    $data = serialize($arFields)."\n\n".serialize($arTemplate);
    file_put_contents($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/s1/test.txt",$data);

    if ($arFields["ORDER_ID"] > 0) {
    //����� ���� � �����
    $order = CSaleOrder::GetById($arFields["ORDER_ID"]);
    //������ ��������
    //$delivery = CSaleDelivery::GetById($order["DELIVERY_ID"]);
    if ($order["DELIVERY_ID"]=='pickpoint:postamat') {
    $order["DELIVERY_ID"]=37;
    $delivery = Services\Manager::getById($order["DELIVERY_ID"]);

    } else {
    $delivery = CSaleDelivery::GetById($order["DELIVERY_ID"]);
    }


    //��������� �������
    $paysystem = CSalePaysystem::GetById($order["PAY_SYSTEM_ID"]);
    //������� ������ ������� � ����
    $pattern = "/(\D)/";
    $price = preg_replace($pattern,'',$arFields["PRICE"]);

    /*if ($order["PAY_SYSTEM_ID"]==17) {
    $arFields["PAYSYSTEM"] = $paysystem['NAME'].' +50 ���';
    $price = $price + 50;
    } else {

    }
    */
    /*$arFields["PAYSYSTEM"] = $paysystem['NAME'];

    //��������� ��������� ��������
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

    //�������� ������
    $orderProps = array();
    $db_props = CSaleOrderPropsValue::GetList(array(),array("ORDER_ID" => $order["ID"]));
    while($orderProp = $db_props->Fetch()) {
    $orderProps[$orderProp["CODE"]] = $orderProp["VALUE"];
    }
    //��������������
    $location = CSaleLocation::GetByID($orderProps["LOCATION"]);

    //������ ������
    $basket =  CSaleBasket::GetList(array(), array("ORDER_ID"=>$order["ID"]));
    $basketItem = $basket->Fetch();
    $arIblockItem = CIBlockElement::GetList(array(), array("ID"=>$basketItem["PRODUCT_ID"]))->Fetch();
    $arSection =  CIBlockSection::GetList(array(), array("ID"=>$arIblockItem["IBLOCK_SECTION_ID"]))->Fetch();

    //arshow($paysystem);
    //            die;

    //�������� ���������� ������
    //            $arFields["PRICE"] = $arFields["PRICE"] + 50;
    $arFields["DELIVERY_TYPE"] = $delivery["NAME"];
    $arFields["PHONE"] = $orderProps["PHONE"];
    $arFields["ZIP"] = $orderProps["ZIP"];
    // $arFields["ADDRESS"] = $location["COUNTRY_NAME"].", ".$location["REGION_NAME"].", ".$location["CITY_NAME"].", ".$orderProps["ADDRESS"];
    $arFields["ADDRESS"] = $location["COUNTRY_NAME"].", ".$location["CITY_NAME"].", ".$orderProps["ADDRESS"];
    //                $arFields["ORDER_LIST"] = /*"������ ".$arSection["NAME"]; , ".*//*$arFields["ORDER_LIST"];
    $arFields["ORDER_LIST"] = str_replace(".00 ��.", " ��.", $arFields["ORDER_LIST"]);;
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