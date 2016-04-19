<?require_once ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");?>
<?
    if ($_POST["code"]) {
        $CODE = trim(strtoupper($_POST["code"]));
        $arSert = CIBlockElement::GetList(array(), array("IBLOCK_ID"=> 27,"NAME"=>$CODE,"PROPERTY_ACTIVE"=>false), false, false, array("PROPERTY_PLAN","PROPERTY_ACTIVE","PROPERTY_USER","PROPERTY_ORDER_ID","PROPERTY_COUPON", "ID"))->Fetch();

        //если сертификат найден
        if ($arSert["ID"] > 0) {                     
            //добавляем в корзину план из сертификата   
            //arshow($arSert); 
            addPlanToBasket($arSert["PROPERTY_PLAN_VALUE"], "N"); 
            //активируем купон, привязанный к сертификату
            $couponStatus = CCatalogDiscountCoupon::SetCoupon($arSert['PROPERTY_COUPON_VALUE']);

        }
        else {
            //проверяем был ли ранее использован данный купон
            $arUseSert = CIBlockElement::GetList(array(), array("IBLOCK_ID"=> 27,"NAME"=>$CODE), false, false, array("PROPERTY_PLAN","PROPERTY_ACTIVE","PROPERTY_USER","PROPERTY_ORDER_ID","PROPERTY_COUPON", "ID"))->Fetch();
            if ($arUseSert["ID"] > 0){   
                echo "used";  
            } else {
                echo "error";
            }
        }
    }
?>