<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
    $arUrls = Array(
        "delete" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=delete&id=#ID#",
        "delay" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=delay&id=#ID#",
        "add" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=add&id=#ID#",
    );

    $arBasketJSParams = array(
        'SALE_DELETE' => GetMessage("SALE_DELETE"),
        'SALE_DELAY' => GetMessage("SALE_DELAY"),
        'SALE_TYPE' => GetMessage("SALE_TYPE"),
        'TEMPLATE_FOLDER' => $templateFolder,
        'DELETE_URL' => $arUrls["delete"],
        'DELAY_URL' => $arUrls["delay"],
        'ADD_URL' => $arUrls["add"]
    );
?>
<script type="text/javascript">
    var basketJSParams = <?=CUtil::PhpToJSObject($arBasketJSParams);?>
</script>
<?
    $APPLICATION->AddHeadScript($templateFolder."/script.js");

    include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/functions.php");    

?>
<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form" id="basket_form">
    <div id="basket_form_container">
        <div class="bx_ordercart"> 					
            <?
                include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");
                include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_delayed.php");
                include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_subscribed.php");
                include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_not_available.php");
            ?>
        </div>
    </div>
    <input type="hidden" name="BasketOrder" value="BasketOrder" />
    <!-- <input type="hidden" name="ajax_post" id="ajax_post" value="Y"> -->
</form>