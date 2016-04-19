<?require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");?>
<?
    if (intval($_POST["ID"]) > 0) {
        //очищаем корзину пользователя               
        
        addPlanToBasket($_POST["ID"]);
       

    }
?>