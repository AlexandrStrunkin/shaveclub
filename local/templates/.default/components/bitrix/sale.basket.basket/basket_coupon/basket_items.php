<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

    <div id="basket_items_list">

        <input type="hidden" id="column_headers" value="<?=CUtil::JSEscape(implode($arHeaders, ","))?>" />
        <input type="hidden" id="offers_props" value="<?=CUtil::JSEscape(implode($arParams["OFFERS_PROPS"], ","))?>" />
        <input type="hidden" id="action_var" value="<?=CUtil::JSEscape($arParams["ACTION_VARIABLE"])?>" />
        <input type="hidden" id="quantity_float" value="<?=$arParams["QUANTITY_FLOAT"]?>" />
        <input type="hidden" id="count_discount_4_all_quantity" value="<?=($arParams["COUNT_DISCOUNT_4_ALL_QUANTITY"] == "Y") ? "Y" : "N"?>" />
        <input type="hidden" id="price_vat_show_value" value="<?=($arParams["PRICE_VAT_SHOW_VALUE"] == "Y") ? "Y" : "N"?>" />
        <input type="hidden" id="hide_coupon" value="<?=($arParams["HIDE_COUPON"] == "Y") ? "Y" : "N"?>" />
        <input type="hidden" id="coupon_approved" value="N" />
        <input type="hidden" id="use_prepayment" value="<?=($arParams["USE_PREPAYMENT"] == "Y") ? "Y" : "N"?>" />


        <div class="coupon-block"><span class="arr"></span>
            <span class="title">купон на скидку</span>
            <span class="line"></span>

            <p>
                У Вас есть купон на скидку? <br/>
                Введите его в поле и получите скидку к итоговой сумме заказа!
            </p>
            <?
                if ($arParams["HIDE_COUPON"] != "Y"):

                    $couponClass = "";
                    if (array_key_exists('COUPON_VALID', $arResult))
                    {
                        $couponClass = ($arResult["COUPON_VALID"] == "Y") ? "good" : "bad";
                    }elseif (array_key_exists('COUPON', $arResult) && strlen($arResult["COUPON"]) > 0)
                    {
                        $couponClass = "good";
                    }

                ?>
                <input type="text" id="coupon" placeholder="введите код купона" class="number" name="COUPON" value="<?=$arResult["COUPON"]?>" onchange="enterCoupon();" size="21" >
                <?else:?>
                <?endif;?>
            <a href="javascript:void(0)" onclick="checkOut();" class="btn">применить</a>  
        </div>         
    </div>        