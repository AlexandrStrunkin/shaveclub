<?
    require_once ($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
?>
<?
    if ($_POST["coupon"] == "") {
        $couponStatus = CCatalogDiscountCoupon::ClearCoupon();
        $couponsCleaning = "Y";
        $strCouponStatus = "Y";
    } else {
        $couponStatus = CCatalogDiscountCoupon::SetCoupon($_POST['coupon']);
        $couponsCleaning = "N";
    }

    if ($couponStatus==true) {
        $strCouponStatus="Y";
    } else {
        $strCouponStatus="N";
    };

?>
<?
    $arID = array();
    $arBasketItems = array();
    $dbBasketItems = CSaleBasket::GetList(array("NAME" => "ASC", "ID" => "ASC"), array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"), false, false, array());
    while ($arItems = $dbBasketItems -> Fetch()) {
        CSaleBasket::UpdatePrice($arItems["ID"]);
        $res = CSaleBasket::GetByID($arItems["ID"]);
        $arBasketItems[] = $res;
    }
    //�������� ����������� � ��������������� ��������� �� ������
    $arFilter = array('COUPON' => $arBasketItems[0]['DISCOUNT_COUPON']);
    $dbCoupon = CCatalogDiscountCoupon::GetList (array(), $arFilter);
    if($arCoupon = $dbCoupon->Fetch()) {

        $couponDescription = $arCoupon['DESCRIPTION'];
    }
    // �������� ������, ���������� ���������� �� ������� ������ �������
    //return json_encode($arBasketItems);

  //  $array = array($arBasketItems[0]['DISCOUNT_VALUE'],$arBasketItems[0]['PRICE'], $couponStatus,  $couponDescription);
//    echo json_encode($array);
    $string = $arBasketItems[0]['DISCOUNT_VALUE']."#".($arBasketItems[2]['PRICE'] * 1)."#".$strCouponStatus."#".$couponDescription."#".$couponsCleaning;
    echo ($string);
?>