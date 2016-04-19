<?
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
    $APPLICATION->SetTitle("Заказы");
    /*if (!$USER->IsAuthorized()) {
    header("location: /");
    }*/
?><?
    $arDeliv = CSaleDelivery::GetList(array(), array("ID" => 37));

    $arDeliv = $arDeliv->Fetch();
    arshow($arDeliv);


    if ($arDeliv)
    {
        arshow($arDeliv);
    }
    echo 123;
    $order = CSaleOrder::GetById(1638);
    arshow($order);

    use Bitrix\Sale\Delivery\Services;
    $test = Services\Manager::getById(56);
    arshow ($test);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>