<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N"){
    include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/auth.php");
}elseif( !$_REQUEST["ORDER_ID"] ){
    $price=0;
    foreach($arResult["BASKET_ITEMS"] as $arItem){
        if($arItem["CAN_BUY"]=="Y" && $arItem["DELAY"]=="N"){
            $price += ( $arItem["PRICE"]*$arItem["QUANTITY"] );
            $currency = $arItem["CURRENCY"];
        }

    }
    $arError = CKshop::checkAllowDelivery($price,$currency);

    if($arError["ERROR"]){
        LocalRedirect($arParams["PATH_TO_BASKET"]);
    }
}
?>
<?
if($USER->IsAuthorized() || $arParams["ALLOW_AUTO_REGISTER"] == "Y")
{
    if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")
    {
        if(strlen($arResult["REDIRECT_URL"]) > 0)
        {
            $APPLICATION->RestartBuffer();
            ?>
            <script type="text/javascript">
                window.top.location.href='<?=CUtil::JSEscape($arResult["REDIRECT_URL"])?>';
            </script>
            <?
            die();
        }

    }
}

$APPLICATION->SetAdditionalCSS($templateFolder."/style_cart.css");
$APPLICATION->SetAdditionalCSS($templateFolder."/style.css");

CJSCore::Init(array('fx', 'popup', 'window', 'ajax'));
?>
<a name="order_form"></a>

<div id="order_form_div" class="order-checkout">
<NOSCRIPT>
    <div class="errortext"><?=GetMessage("SOA_NO_JS")?></div>
</NOSCRIPT>

<?
if (!function_exists("getColumnName"))
{
    function getColumnName($arHeader)
    {
        return (strlen($arHeader["name"]) > 0) ? $arHeader["name"] : GetMessage("SALE_".$arHeader["id"]);
    }
}

if (!function_exists("cmpBySort"))
{
    function cmpBySort($array1, $array2)
    {
        if (!isset($array1["SORT"]) || !isset($array2["SORT"]))
            return -1;

        if ($array1["SORT"] > $array2["SORT"])
            return 1;

        if ($array1["SORT"] < $array2["SORT"])
            return -1;

        if ($array1["SORT"] == $array2["SORT"])
            return 0;
    }
}
?>

<div class="bx_order_make">
    <?
    if(!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N")
    {
        if(!empty($arResult["ERROR"]))
        {
            foreach($arResult["ERROR"] as $v)
                echo ShowError($v);
        }
        elseif(!empty($arResult["OK_MESSAGE"]))
        {
            foreach($arResult["OK_MESSAGE"] as $v)
                echo ShowNote($v);
        }

        include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/auth.php");
    }
    else
    {
        if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")
        {
            if(strlen($arResult["REDIRECT_URL"]) == 0)
            {
                include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/confirm.php");
            }
        }
        else
        {
            ?>
            <script type="text/javascript">
            try{
                $(document).ready(function(){
                    <?if(trim(COption::GetOptionString("aspro.kshop", "PHONE_MASK", "+9 (999) 999-99-99", SITE_ID))){?>
                        $('input[code="PHONE"]').mask('<?=trim(COption::GetOptionString("aspro.kshop", "PHONE_MASK", "+9 (999) 999-99-99", SITE_ID));?>');
                    <?}?>
                });
            }
            catch(e){}
            <?if(CSaleLocation::isLocationProEnabled()):?>
                <?
                // spike: for children of cities we place this prompt
                $city = \Bitrix\Sale\Location\TypeTable::getList(array('filter' => array('=CODE' => 'CITY'), 'select' => array('ID')))->fetch();
                ?>

                BX.saleOrderAjax.init(<?=CUtil::PhpToJSObject(array(
                    'source' => $this->__component->getPath().'/get.php',
                    'cityTypeId' => intval($city['ID']),
                    'messages' => array(
                        'otherLocation' => '--- '.GetMessage('SOA_OTHER_LOCATION'),
                        'moreInfoLocation' => '--- '.GetMessage('SOA_NOT_SELECTED_ALT'), // spike: for children of cities we place this prompt
                        'notFoundPrompt' => '<div class="-bx-popup-special-prompt">'.GetMessage('SOA_LOCATION_NOT_FOUND').'.<br />'.GetMessage('SOA_LOCATION_NOT_FOUND_PROMPT', array(
                            '#ANCHOR#' => '<a href="javascript:void(0)" class="-bx-popup-set-mode-add-loc">',
                            '#ANCHOR_END#' => '</a>'
                        )).'</div>'
                    )
                ))?>);

            <?endif;?>

            var BXFormPosting = false;
            function submitForm(val)
            {
                $('#ORDER_PROP_82').val($('.commission_delivery_price').val());
                if (BXFormPosting === true)
                    return true;

                BXFormPosting = true;
                if(val != 'Y')
                    BX('confirmorder').value = 'N';

                var orderForm = BX('ORDER_FORM');
                BX.showWait();

                <?if(CSaleLocation::isLocationProEnabled()):?>
                    BX.saleOrderAjax.cleanUp();
                <?endif?>

                BX.ajax.submit(orderForm, ajaxResult);

                return true;
            }

            function ajaxResult(res)
            {
                var orderForm = BX('ORDER_FORM');
                try
                {
                    // if json came, it obviously a successfull order submit

                    var json = JSON.parse(res);
                    BX.closeWait();

                    if (json.error)
                    {
                        BXFormPosting = false;
                        return;
                    }
                    else if (json.redirect)
                    {
                        window.top.location.href = json.redirect;
                    }
                }
                catch (e)
                {
                    // json parse failed, so it is a simple chunk of html

                    BXFormPosting = false;
                    BX('order_form_content').innerHTML = res;

                    <?if(CSaleLocation::isLocationProEnabled()):?>
                        BX.saleOrderAjax.initDeferredControl();
                    <?endif?>
                }

                BX.closeWait();
                BX.onCustomEvent(orderForm, 'onAjaxSuccess');
                $(function(){
                    $('.bx-core-adm-dialog-buttons #crmOk').attr('value', '<?=GetMessage('CHOOSE')?>');
                })
            }

            function SetContact(profileId)
            {
                BX("profile_change").value = "Y";
                submitForm();
            }

            BX.addCustomEvent('onAjaxSuccess', function(){
               try{
                    $(document).ready(function(){
                        <?if(trim(COption::GetOptionString("aspro.kshop", "PHONE_MASK", "+9 (999) 999-99-99", SITE_ID))){?>
                            $('input[code="PHONE"]').mask('<?=trim(COption::GetOptionString("aspro.kshop", "PHONE_MASK", "+9 (999) 999-99-99", SITE_ID));?>');
                        <?}?>
                    });
                }
                catch(e){}
            });
            </script>
            <script type="text/javascript">
                $(document).ready(function() {
                    $.ajax({
                        url: arKShopOptions['SITE_DIR'] + 'ajax/basket_fly_order.php',
                        type: 'post',
                        success: function(html){
                            $('#basket_line').append(html);
                        }
                    });
                });
            </script>
            <?if($_POST["is_ajax_post"] != "Y")
            {
                ?><form action="<?=$APPLICATION->GetCurPage();?>" method="POST" name="ORDER_FORM" id="ORDER_FORM" enctype="multipart/form-data" class="validatable">
                <?=bitrix_sessid_post()?>
                <div id="order_form_content">
                <?
            }
            else
            {
                $APPLICATION->RestartBuffer();
            }

            if($_REQUEST['PERMANENT_MODE_STEPS'] == 1)
            {
                ?>
                <input type="hidden" name="PERMANENT_MODE_STEPS" value="1" />
                <?
            }

            if(!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y")
            {
                foreach($arResult["ERROR"] as $v)
                    echo ShowError($v);
                ?>
                <script type="text/javascript">
                    top.BX.scrollToNode(top.BX('ORDER_FORM'));
                </script>
                <?
            }

            // field personal phone
            if($USER->IsAuthorized()){
                foreach($arResult["ORDER_PROP"]["USER_PROPS_Y"] as $i => $arProp){
                    if($arProp["CODE"] == "PHONE" && !strlen($arProp["VALUE"])){
                        $dbRes = CUser::GetList(($by = "id"), ($order = "asc"), array("ID" => $USER->GetID()), array("FIELDS" => array("ID", "PERSONAL_PHONE")));
                        $arUser = $dbRes->Fetch();
                        $arResult["ORDER_PROP"]["USER_PROPS_Y"][$i]["VALUE"] = $arResult["ORDER_PROP"]["USER_PROPS_Y"][$i]["VALUE_FORMATED"] = $arResult["ORDER_PROP"]["PRINT"][$i]["VALUE"] = $arUser["PERSONAL_PHONE"];
                    }
                }
            }

            include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/person_type.php");
            include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props.php");
            if ($arParams["DELIVERY_TO_PAYSYSTEM"] == "p2d")
            {
                include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");
                include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");
            }
            else
            {
                include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");
                include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");
            }

            include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/summary.php");
            if(strlen($arResult["PREPAY_ADIT_FIELDS"]) > 0)
                echo $arResult["PREPAY_ADIT_FIELDS"];
            ?>

            <?if($_POST["is_ajax_post"] != "Y")
            {
                ?>
                    </div>
                    <input type="hidden" name="confirmorder" id="confirmorder" value="Y">
                    <input type="hidden" name="profile_change" id="profile_change" value="N">
                    <input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
                    <input type="hidden" name="json" value="Y">
                    <button class="button30 checkout" type="button" id="ORDER_CONFIRM_BUTTON" name="submitbutton" onClick="submitForm('Y');" value="<?=GetMessage("SOA_TEMPL_BUTTON")?>"><span><?=GetMessage("SOA_TEMPL_BUTTON")?></span></button>
                </form>
                <?
                if($arParams["DELIVERY_NO_AJAX"] == "N")
                {
                    ?>
                    <div style="display:none;"><?$APPLICATION->IncludeComponent("bitrix:sale.ajax.delivery.calculator", "", array(), null, array('HIDE_ICONS' => 'Y')); ?></div>
                    <?
                }
            }
            else
            {
                ?>
                <script type="text/javascript">
                    top.BX('confirmorder').value = 'Y';
                    top.BX('profile_change').value = 'N';
                </script>
                <?
                die();
            }
        }
    }
    ?>
    </div>
</div>
<?if(CSaleLocation::isLocationProEnabled()):?>

    <div style="display: none">
        <?// we need to have all styles for sale.location.selector.steps, but RestartBuffer() cuts off document head with styles in it?>
        <?
        $APPLICATION->IncludeComponent(
    "bitrix:sale.location.selector.steps",
    "location_selector_kshop",
    array(
        "COMPONENT_TEMPLATE" => "location_selector_kshop",
        "ID" => "",
        "CODE" => "",
        "INPUT_NAME" => "LOCATION",
        "PROVIDE_LINK_BY" => "id",
        "JS_CONTROL_GLOBAL_ID" => "",
        "JS_CALLBACK" => "",
        "PRESELECT_TREE_TRUNK" => "N",
        "PRECACHE_LAST_LEVEL" => "N",
        "FILTER_BY_SITE" => "N",
        "SHOW_DEFAULT_LOCATIONS" => "Y",
        "FILTER_SITE_ID" => "s2",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "SUPPRESS_ERRORS" => "N",
        "DISABLE_KEYBOARD_INPUT" => "N",
        "INITIALIZE_BY_GLOBAL_EVENT" => ""
    ),
    false
);?>
        <?$APPLICATION->IncludeComponent(
    "bitrix:sale.location.selector.search",
    "new_location",
    array(
        "COMPONENT_TEMPLATE" => "new_location",
        "ID" => "",
        "CODE" => "",
        "INPUT_NAME" => "LOCATION",
        "PROVIDE_LINK_BY" => "id",
        "FILTER_BY_SITE" => "N",
        "SHOW_DEFAULT_LOCATIONS" => "Y",
        "FILTER_SITE_ID" => "s2",
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "36000000",
        "JS_CONTROL_GLOBAL_ID" => "",
        "JS_CALLBACK" => "",
        "SUPPRESS_ERRORS" => "N",
        "INITIALIZE_BY_GLOBAL_EVENT" => ""
    ),
    false
);?>
    </div>

<?endif?>