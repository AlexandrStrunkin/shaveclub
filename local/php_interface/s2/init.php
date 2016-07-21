<?
    define("PVZ_MSK_DELIVERY_FOR_CHEAP_ORDERS", 48);
    define("PVZ_MSK_DELIVERY_FOR_EXPENSIVE_ORDERS", 47);
    define("PVZ_SPB_DELIVERY_FOR_CHEAP_ORDERS", 50);
    define("PVZ_SPB_DELIVERY_FOR_EXPENSIVE_ORDERS", 51);

    CModule::IncludeModule("forum");
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
    


    
    AddEventHandler('sale', 'OnSaleStatusOrder', 'UpdatingDeducting');
    
    function UpdatingDeducting ($ID, $val) {
        if ($val == "R") {
            $arOrderNewFields = array (
                "ALLOW_DELIVERY" => "Y"    
            );
            CSaleOrder::Update($ID, $arOrderNewFields);    
        }
    }
    
    AddEventHandler('sale', 'OnSaleStatusOrder', 'UpdatingPaymentProp');
    
    function UpdatingPaymentProp ($ID, $arFields) {
        if ($arFields["STATUS_ID"] == "F") {
            $order = CSaleOrder::GetById($ID);
            //если флаг оплаты не стоит - ставим
            if ($order["PAYED"] != "Y") {
                CSaleOrder::PayOrder($ID, "Y", false, false, 0);
            }    
        }
    }
    
    //обработка статусов заказа при получении оплаты
    AddEventHandler('sale', 'OnSalePayOrder', "UpdOrderStatus");
    function UpdOrderStatus ($ID, $val) {
        //при получении оплаты
        if ($val == "Y") {
            $order = CSaleOrder::GetById($ID);
            //если текущий статус закана - не "Выполнен", ставим статус "новый, оплачен"
            if ($order["STATUS_ID"] != "F") {
                CSaleOrder::StatusOrder($ID, "P");
            }
        }
    }
    
    AddEventHandler('sale', 'OnSaleStatusOrder', 'ReturnedItem');
    
    function ReturnedItem ($ID, $val) {
        if ($val == "V") {
            $arOrderNewFields = array (
                "ALLOW_DELIVERY" => "Y"    
            );
            CSaleOrder::Update($ID, $arOrderNewFields);    
        }
    }
    
    AddEventHandler('sale', 'OnSaleStatusOrder', 'DeductingItem');
    
    function DeductingItem ($ID, $val) {
        if ($val == "R") {
            CSaleOrder::DeductOrder($ID, "Y");    
        }
    }
?>