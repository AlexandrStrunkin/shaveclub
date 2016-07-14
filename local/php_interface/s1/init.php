<?
    use Bitrix\Sale\Delivery\Services;

    define("DELIVERY_ID_PICKPOINT", 41);
    define("PAY_SYSTEM_ELECTRONIC_PAYMENT", 48);


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
    function generateSertificate(){

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
            $_SESSION['startBundle'] = "";
            if(substr_count($items['CODE'],'_start') > 0){
                $_SESSION['startBundle'] = "T";
                return true;
            }
        }

    }

    //������ ����� ������������ �� email ��� �������� ������������
    AddEventHandler("main", "OnBeforeUserAdd","setUserFields");
    function setUserFields(&$arFields) {
        $arFields["LOGIN"] = $arFields["EMAIL"];
        $arFields["FIO"] = "";
    }


    //��������� ����������� ����������� ��� ��� �������
    AddEventHandler("sale", "OnOrderAdd", Array("AfterOrderAdd", "addSertificate"));
    class AfterOrderAdd{
        function addSertificate($ID, $arFields){
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
        $product = CCatalogProduct::GetList(array(),array("ID"=>$ID));
        $arProduct = $product->Fetch();

        if ($arProduct["ID"] > 0) {

            $element = CIBlockElement::GetList(array(), array("IBLOCK_ID"=> 12,"ID"=>$arProduct["ID"]), false, false, array("PROPERTY_ECONOMY", "PROPERTY_LENGTH", "PROPERTY_CASSETTE", "PROPERTY_RAZOR", "PROPERTY_DELIVERY", "CODE"));
            $arElement = $element->Fetch();

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

    class newOrder{
        function orderDataChange(&$arFields, &$arTemplate){

            if ($arFields["ORDER_ID"] > 0) {
                //����� ���� � �����
                $order = CSaleOrder::GetById($arFields["ORDER_ID"]);

                //������ ��������
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


                $arFields["PAYSYSTEM"] = $paysystem['NAME'];


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

                //�������� ���������� ������
                $arFields["DELIVERY_TYPE"] = $delivery["NAME"];
                $arFields["PHONE"] = $orderProps["PHONE"];
                $arFields["ZIP"] = $orderProps["ZIP"];
                $arFields["ADDRESS"] = $location["COUNTRY_NAME"].", ".$location["CITY_NAME"].", ".$orderProps["ADDRESS"];
                $arFields["ORDER_LIST"] = str_replace(".00 ��.", " ��.", $arFields["ORDER_LIST"]);
            }

        }
    }

    //��������� � ������ � ������ �������������� ����������
    AddEventHandler("main", "OnAfterUserAdd", "OnAfterUserAddHandler");
    function OnAfterUserAddHandler(&$arFields){
        $user = new CUser;
        $fields = Array(
            "UF_SITE" => 22,
        );
        $user->Update($arFields["ID"], $fields);
    }

    /***
    * ������� ������� ����� ����������� (����� ���������� ������������ ���������)
    *
    * @var array $items_IDs - ������ ID ������� ������� �������� ������������
    *
    ***/

    AddEventHandler("main", "OnAfterUserLogin", "cleaningBasket");
    function cleaningBasket($arUser){
        if ($arUser["USER_ID"] > 0) {
            $i = 0;
            $basket_items_list = CSaleBasket::GetList(
                array(
                    "ID" => "DESC"
                ),
                array(
                    "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                    "LID" => SITE_ID,
                    "ORDER_ID" => "NULL"
                ),
                false,
                false,
                array()
            );
            // ���������� ID ���������� ������������ ��������� � ��� ������������ (������� � ������)
            // �� ������� ID ��������� �������
            while ($basket_items = $basket_items_list -> Fetch()) {
                if ($i > 2) {
                    CSaleBasket::Delete($basket_items["ID"]);
                }
                $i++;
            }
        }
    }
     //Handlers for PickPoint improvements
    AddEventHandler("sale", "OnOrderSave", Array("CustomPickPoint", "RewriteOrderDescription"));
    AddEventHandler("sale", "OnOrderAdd", Array("CustomPickPoint", "AddToSessionOrder"));
    //Class for PickPoint improvements
    class CustomPickPoint {
        //Rewriting user description in ordres with PickPoint delivery
        function RewriteOrderDescription($id, $arFields) {
            GLOBAL $arParams;
            if($arFields["DELIVERY_ID"] == $arParams["PICKPOINT"]["DELIVERY_ID"]) {
                if(COption::GetOptionString($arParams["PICKPOINT"]["MODULE_ID"], $arParams["PICKPOINT"]["ADD_INFO_NAME"], "")) {
                    $arPropFields = array("ORDER_ID" => $id, "NAME" => $arParams["PICKPOINT"]["ADDRESS_TITLE_PROP"], "VALUE" => $_SESSION["PICKPOINT_ADDRESS"]);
                    if($arFields["PERSON_TYPE_ID"] == $arParams["PICKPOINT"]["LEGAL_PERSON_ID"]) {
                        $arPropFields["ORDER_PROPS_ID"] = $arParams["PICKPOINT"]["LEGAL_ADDRESS_ID"];
                        $arPropFields["CODE"] = $arParams["PICKPOINT"]["LEGAL_ADDRESS_CODE"];
                    } else if($arFields["PERSON_TYPE_ID"] == $arParams["PICKPOINT"]["NATURAL_PERSON_ID"]) {
                        $arPropFields["ORDER_PROPS_ID"] = $arParams["PICKPOINT"]["NATURAL_ADDRESS_ID"];
                        $arPropFields["CODE"] = $arParams["PICKPOINT"]["NATURAL_ADDRESS_CODE"];
                    }
                $db_order = CSaleOrderPropsValue::GetList(
                    array("DATE_UPDATE" => "DESC"),
                    array("ORDER_PROPS_ID" => $arPropFields["ORDER_PROPS_ID"], "ORDER_ID" => $arPropFields["ORDER_ID"])
                );
                if ($arOrder = $db_order->Fetch()) {
                    CSaleOrderPropsValue::Update($arOrder["ID"], $arPropFields);
                }
                    unset($_SESSION["PICKPOINT_ADDRESS"]);
                }
            }
        }
        // �������� ������ �� ������ picpoint � ������ �� � ������
        function addPickpointDataToSession($orderId, $arFields) {
        GLOBAL $arParams;    // ����� ������ �� config.php
        if($arFields["DELIVERY_ID"] == $arParams["PICKPOINT"]["DELIVERY_ID"]) {
            if(!empty($_POST["PP_ADDRESS"]) && !empty($_POST["PP_ID"]) && !empty($_POST["PP_SMS_PHONE"])){
                $arToAdd = array(
                    "ORDER_ID" => $orderId,
                    "POSTAMAT_ID" => $_POST["PP_ID"],
                    "ADDRESS" => $_POST["PP_ADDRESS"],
                    "SMS_PHONE" => $_POST["PP_SMS_PHONE"]
                );
                CPickpoint::AddOrderPostamat($arToAdd);
                if(COption::GetOptionString($arParams["PICKPOINT"]["MODULE_ID"], $arParams["PICKPOINT"]["ADD_INFO_NAME"], "")) {
                    // ���������� ��� � ������
                    $_SESSION["PICKPOINT_ADDRESS"] = "{$_POST["PP_ID"]}\n{$_POST["PP_ADDRESS"]}\n{$_POST["PP_SMS_PHONE"]}";
                }
            }
            return false;
        }
    }
    }
?>