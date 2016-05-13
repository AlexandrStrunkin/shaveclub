<?
    if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
    //echo ShowError($arResult["ERROR_MESSAGE"]);
    $bDelayColumn  = false;
    $bDeleteColumn = false;
    $bWeightColumn = false;
    $bPropsColumn  = false;
    if ($normalCount > 0):
    ?>


    <?//=var_dump($arResult["GRID"]["HEADERS"]);?>

    <div class="module-cart">
        <table class="colored">
            <thead>
                <tr>
                    <?
                        foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader)
                        {
                            if ($arHeader["id"] == "DELETE"){$bDeleteColumn = true;}
                            if ($arHeader["id"] == "TYPE"){$bTypeColumn = true;}
                            if ($arHeader["id"] == "QUANTITY"){$bQuantityColumn = true;}
                        }
                    ?>
                    <?if ($bDeleteColumn):?><td class="remove-cell"></td><?endif;?>
                    <?foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):
                            if (in_array($arHeader["id"], array("TYPE"))) {continue;} // some header columns are shown differently
                            elseif ($arHeader["id"] == "PROPS"){$bPropsColumn = true; continue;}
                            elseif ($arHeader["id"] == "DELAY"){$bDelayColumn = true; continue;}
                            elseif ($arHeader["id"] == "WEIGHT"){ $bWeightColumn = true;}
                            elseif ($arHeader["id"] == "DELETE"){ continue;}
                            if ($arHeader["id"] == "NAME"):?>
                            <td class="thumb-cell"></td><td class="name-th">
                            <?else:?><td class="<?=strToLower($arHeader["id"])?>-th"><?endif;?><?=getColumnName($arHeader)?></td>
                        <?endforeach;?>
                    <?if ($bDelayColumn):?><td class="delay-cell"></td><?endif;?>
                </tr>
            </thead>

            <tbody>
                <?foreach ($arResult["GRID"]["ROWS"] as $k => $arItem):
                    if ($arItem["DELAY"] == "N" && $arItem["CAN_BUY"] == "Y"):?>

                    <tr data-id="<?=$arItem["ID"]?>"  <?if($arItem["QUANTITY"]>$arItem["AVAILABLE_QUANTITY"]):?>data-error="no_amounth"<?endif;?>>
                        <?if ($bDeleteColumn):?>
                            <td class="remove-cell"><a class="remove" href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["delete"])?>" title="<?=GetMessage("SALE_DELETE")?>"><i></i></a></td>
                            <?endif;?>
                        <?foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):
                                if (in_array($arHeader["id"], array("PROPS", "DELAY", "DELETE", "TYPE"))) continue; // some values are not shown in columns in this template
                                if ($arHeader["id"] == "NAME"):
                                ?>
                                <td class="thumb-cell">
                                    <?if( strlen($arItem["PREVIEW_PICTURE"]["SRC"])>0 ){?>
                                        <?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb"><?endif;?>
                                            <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=(is_array($arItem["PREVIEW_PICTURE"]["ALT"])?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=(is_array($arItem["PREVIEW_PICTURE"]["TITLE"])?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
                                        <?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
                                        <?}elseif( strlen($arItem["DETAIL_PICTURE"]["SRC"])>0 ){?>
                                        <?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb"><?endif;?>
                                            <img src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="<?=(is_array($arItem["DETAIL_PICTURE"]["ALT"])?$arItem["DETAIL_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=(is_array($arItem["DETAIL_PICTURE"]["TITLE"])?$arItem["DETAIL_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
                                        <?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
                                        <?}else{?>
                                        <?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb"><?endif;?>
                                            <img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" width="80" height="80" />
                                        <?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
                                        <?}?>
                                    <?if (!empty($arItem["BRAND"])):?><div class="ordercart_brand"><img src="<?=$arItem["BRAND"]?>" /></div><?endif;?>
                                </td>
                                <td class="name-cell">
                                    <?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?endif;?><?=$arItem["NAME"]?><?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?><br />
                                    <?if ($bPropsColumn):?>
                                        <div class="item_props">
                                            <? foreach ($arItem["PROPS"] as $val) {
                                                if (is_array($arItem["SKU_DATA"])) {
                                                    $bSkip = false;
                                                    foreach ($arItem["SKU_DATA"] as $propId => $arProp) { if ($arProp["CODE"] == $val["CODE"]) { $bSkip = true; break; } }
                                                    if ($bSkip) continue;
                                                } echo '<span class="item_prop"><span class="name">'.$val["NAME"].':&nbsp;</span><span class="property_value">'.$val["VALUE"].'</span></span>';
                                            }?>
                                        </div>
                                        <?endif;?>
                                    <?if (is_array($arItem["SKU_DATA"])):
                                        foreach ($arItem["SKU_DATA"] as $propId => $arProp):
                                            $isImgProperty = false; // is image property
                                            foreach ($arProp["VALUES"] as $id => $arVal) { if (isset($arVal["PICT"]) && !empty($arVal["PICT"])) { $isImgProperty = true; break; } }
                                            $full = (count($arProp["VALUES"]) > 5) ? "full" : "";
                                            if ($isImgProperty): // iblock element relation property
                                            ?>
                                            <div class="bx_item_detail_scu_small_noadaptive <?=$full?>">
                                                <label><?=$arProp["NAME"]?>:</span>
                                                <div class="bx_scu_scroller_container">
                                                    <div class="bx_scu">
                                                        <ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>" style="width: 200%;margin-left:0%;">
                                                            <?	foreach ($arProp["VALUES"] as $valueId => $arSkuValue){
                                                                    $selected = "";
                                                                    foreach ($arItem["PROPS"] as $arItemProp) {
                                                                        if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"])
                                                                            { if ($arItemProp["VALUE"] == $arSkuValue["NAME"] || $arItemProp["VALUE"] == $arSkuValue["XML_ID"]) $selected = "class=\"bx_active\""; }
                                                                    };?>
                                                                <li style="width:10%;" <?=$selected?>>
                                                                    <a href="javascript:void(0);"><span style="background-image:url(<?=$arSkuValue["PICT"]["SRC"]?>)"></span></a>
                                                                </li>
                                                                <?}?>
                                                        </ul>
                                                    </div>
                                                    <div class="bx_slide_left" onclick="leftScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
                                                    <div class="bx_slide_right" onclick="rightScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
                                                </div>
                                            </div>
                                            <?else:?>
                                            <div class="bx_item_detail_size_small_noadaptive <?=$full?>">
                                                <span class="bx_item_section_name_gray">
                                                    <?=$arProp["NAME"]?>:
                                                </span>

                                                <div class="bx_size_scroller_container">
                                                    <div class="bx_size">
                                                        <ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>" style="width: 200%; margin-left:0%;">
                                                            <?foreach ($arProp["VALUES"] as $valueId => $arSkuValue) {
                                                                $selected = "";
                                                                foreach ($arItem["PROPS"] as $arItemProp) {
                                                                    if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"])
                                                                        { if ($arItemProp["VALUE"] == $arSkuValue["NAME"]) $selected = "class=\"bx_active\""; }
                                                            }?>
                                                            <li style="width:10%;" <?=$selected?>><a href="javascript:void(0);"><?=$arSkuValue["NAME"]?></a></li>
                                                            <?}?>
                                                    </ul>
                                                </div>
                                                <div class="bx_slide_left" onclick="leftScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
                                                <div class="bx_slide_right" onclick="rightScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
                                            </div>
                                        </div>
                                        <?endif;
                                            endforeach;
                                        endif;
                                    ?>
                                </td>
                                <?elseif ($arHeader["id"] == "QUANTITY"):?>
                                <td class="count-cell">
                                    <?if($arItem["QUANTITY"]>$arItem["AVAILABLE_QUANTITY"]):?><div class="error" style="display:none;"><?=GetMessage("NO_NEED_AMMOUNT")?></div><?endif;?>
                                    <div class="counter_block basket">
                                        <?
                                            $ratio = isset($arItem["MEASURE_RATIO"]) ? $arItem["MEASURE_RATIO"] : 0;
                                            $max = isset($arItem["AVAILABLE_QUANTITY"]) ? "max=\"".$arItem["AVAILABLE_QUANTITY"]."\"" : "";
                                        ?>
                                        <?if (isset($arItem["MEASURE_RATIO"])&& floatval($arItem["MEASURE_RATIO"]) != 0&& !CSaleBasketHelper::isSetParent($arItem)):?><span onclick="setQuantity('<?=$arItem["ID"]?>', '<?=$ratio?>', 'down')" class="minus">-</span><?endif;?>
                                        <input
                                            type="text"
                                            class="text"
                                            id="QUANTITY_INPUT_<?=$arItem["ID"]?>"
                                            name="QUANTITY_INPUT_<?=$arItem["ID"]?>"
                                            size="2"
                                            data-id="<?=$arItem["ID"];?>"
                                            maxlength="18"
                                            min="0"
                                            <?=$max?>
                                            step="<?=$ratio?>"
                                            value="<?=$arItem["QUANTITY"]?>"
                                            onchange="updateQuantity('QUANTITY_INPUT_<?=$arItem["ID"]?>', '<?=$arItem["ID"]?>', '<?=$ratio?>')"
                                            >
                                        <?if (isset($arItem["MEASURE_RATIO"])&& floatval($arItem["MEASURE_RATIO"]) != 0&& !CSaleBasketHelper::isSetParent($arItem)):?><span onclick="setQuantity('<?=$arItem["ID"]?>', '<?=$ratio?>', 'up')" class="plus">+</span><?endif;?>
                                    </div>
                                    <?if (isset($arItem["MEASURE_TEXT"]) && $arParams["SHOW_MEASURE"]=="Y"):?><div class="measure"><?=$arItem["MEASURE_TEXT"];?></div><?endif;?>
                                    <input type="hidden" id="QUANTITY_<?=$arItem['ID']?>" name="QUANTITY_<?=$arItem['ID']?>" value="<?=$arItem["QUANTITY"]?>" />
                                    <?=getQuantitySelectControl("QUANTITY_SELECT_".$arItem["ID"], "QUANTITY_SELECT_".$arItem["ID"], $arItem["QUANTITY"], $arItem["AVAILABLE_QUANTITY"], $arItem["MEASURE_RATIO"], $arItem["MEASURE_TEXT"]); // quantity selector for mobile ?>
                                </td>
                                <?elseif ($arHeader["id"] == "SUMM"):?>
                                <td class="summ-cell"><?=$arItem["SUMM_FORMATED"];?></td>
                                <?elseif ($arHeader["id"] == "PRICE"):?>
                                <td class="cost-cell">
                                    <?if( doubleval($arItem["DISCOUNT_PRICE_PERCENT"]) > 0 ){?>
                                        <div class="price"><?=$arItem["PRICE_FORMATED"]?></div>
                                        <div class="price discount"><strike><?=$arItem["FULL_PRICE_FORMATED"]?></strike></div>
                                        <?}else{?>
                                        <div class="price"><?=$arItem["PRICE_FORMATED"];?></div>
                                        <?}?>
                                    <?if (strlen($arItem["NOTES"]) > 0 && $bTypeColumn):?>
                                        <div class="price_name"><?=$arItem["NOTES"]?></div>
                                        <?endif;?>
                                </td>
                                <?elseif ($arHeader["id"] == "DISCOUNT"):?>
                                <td class="discount-cell"><?=$arItem["DISCOUNT_PRICE_PERCENT_FORMATED"]?></td>
                                <?elseif ($arHeader["id"] == "WEIGHT"):?>
                                <td class="weight-cell"><?=$arItem["WEIGHT_FORMATED"]?></td>
                                <?else:?>
                                <td class="cell"><?=$arItem[$arHeader["id"]]?></td>
                                <?endif;?>
                            <?endforeach;?>

                        <?if ($bDelayColumn ):?>
                            <td class="delay-cell delay">
                                <a class="wish_item" href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["delay"])?>">
                                    <span class="icon"><i></i></span>
                                    <span class="value pseudo"><?=GetMessage("SALE_DELAY")?></span>
                                </a>
                            </td>
                            <?endif;?>
                    </tr>
                    <?
                        endif;
                    endforeach;
                ?>
                <?
                    $arTotal = array();
                    if ($bWeightColumn) { $arTotal["WEIGHT"]["NAME"] = GetMessage("SALE_TOTAL_WEIGHT"); $arTotal["WEIGHT"]["VALUE"] = $arResult["allWeight_FORMATED"];}
                    if ($arParams["PRICE_VAT_SHOW_VALUE"] == "Y")
                    {
                        $arTotal["VAT_EXCLUDED"]["NAME"] = GetMessage("SALE_VAT_EXCLUDED"); $arTotal["VAT_EXCLUDED"]["VALUE"] = $arResult["allSum_wVAT_FORMATED"];
                        $arTotal["VAT_INCLUDED"]["NAME"] = GetMessage("SALE_VAT_INCLUDED"); $arTotal["VAT_INCLUDED"]["VALUE"] = $arResult["allVATSum_FORMATED"];
                    }
                    if (doubleval($arResult["DISCOUNT_PRICE_ALL"]) > 0)
                    {
                        $arTotal["PRICE"]["NAME"] = GetMessage("SALE_TOTAL");
                        $arTotal["PRICE"]["VALUES"]["ALL"] = str_replace(" ", "&nbsp;", $arResult["allSum_FORMATED"]);
                        $arTotal["PRICE"]["VALUES"]["WITHOUT_DISCOUNT"] = $arResult["PRICE_WITHOUT_DISCOUNT"];
                    }
                    else
                    {
                        $arTotal["PRICE"]["NAME"] = GetMessage("SALE_TOTAL");
                        $arTotal["PRICE"]["VALUES"]["ALL"] = $arResult["allSum_FORMATED"];
                    }
                ?>
            </tbody>
        </table>
        <table class="colored">
            <tfoot>
                <tr data-id="total_row">
                    <?foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader){
                            if (in_array($arHeader["id"], array("PROPS", "DELAY", "DELETE", "TYPE", "QUANTITY"))) continue; // some values are not shown in columns in this template
                            if ($arHeader["id"] == "NAME"):
                            ?>
                            <td class="coupon_wrapp">
                                <?if( $arParams["HIDE_COUPON"] != "Y" ){?>
                                    <div class="coupon<?if ($arParams["AJAX_MODE_CUSTOM"]!="Y"):?> b16<?endif;?>">
                                        <span class="coupon-ts"><?=GetMessage("STB_COUPON_LABEL");?>:</span>&nbsp;
                                        <? $class=""; if ($_REQUEST["COUPON"]) { if ($arResult["APPLYED_COUPON"]) $class=' class="good"'; else $class=' class="good"'; } ?>

                                        <input type="text" id="COUPON" size="21" value="<?=$arResult["APPLYED_COUPON"]["COUPON"]?>" name="COUPON"<?=$class?>>
                                        <?if ($arParams["AJAX_MODE_CUSTOM"]=="Y"){?><a  class="button30 gradient apply-button"><?=GetMessage("SALE_APPLY")?></a><?}?>

                                    </div>
                                    <?}?>
                            </td>
                            <?if ($bDelayColumn ):?><td class="extended-cell"></td><?endif;?>
                            <?elseif ($arHeader["id"] == "PRICE"):?>
                            <td class="row_titles">
                                <?foreach($arTotal as $key => $value):?>
                                    <?if ($value["VALUES"] && $value["NAME"]):?><div class="item_title"><?=$value["NAME"]?></div><?endif;?>
                                    <?endforeach;?>
                            </td>
                            <?elseif ($arHeader["id"] == "SUMM"):?>
                            <td class="row_values">
                                <?foreach($arTotal as $key => $value):?>
                                    <?if ($value["VALUES"] && $value["NAME"]):?>
                                        <?if ($key=="PRICE"):?>
                                            <?if ($arResult["DISCOUNT_PRICE_ALL"]):?>
                                                <div data-type="price_discount">
                                                    <span class="price"><?=$value["VALUES"]["ALL"];?></span>
                                                    <div class="price discount"><strike><?=$value["VALUES"]["WITHOUT_DISCOUNT"];?></strike></div>
                                                </div>
                                                <?else:?>
                                                <div  data-type="price_normal"><span class="price"><?=$arResult["allSum_FORMATED"];?></span></div>
                                                <?endif;?>
                                            <?elseif ($value["VALUE"]):?>
                                            <div data-type="<?=strToLower($key)?>"><span class="price"><?=$value["VALUE"]?></span></div>
                                            <?endif;?>
                                        <?endif;?>
                                    <?endforeach;?>
                            </td>
                            <?else:?><td class="<?=strtolower($arHeader["id"])?>-cell"></td><?endif;?>
                        <?}?>
                    <?if ($bDelayColumn ):?><td class="delay-cell"></td><?endif;?>
                </tr>
                <tr data-id="total_buttons">
                    <?foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader){
                            if (in_array($arHeader["id"], array("PROPS", "DELAY", "DELETE", "TYPE", "QUANTITY"))) continue; // some values are not shown in columns in this template
                            if ($arHeader["id"] == "NAME"):
                            ?>
                            <td class="backet_back_wrapp">
                                <div class="basket_back">
                                    <a class="button30 gradient " href="<?=SITE_DIR?>catalog/"><span><?=GetMessage("SALE_BACK")?></span></a>
                                    <div class="description"><?=GetMessage("SALE_BACK_DESCRIPTION");?></div>
                                </div>
                                <?if ($arParams["AJAX_MODE_CUSTOM"]!="Y"):?>
                                    <div class="basket_update clearfix">
                                        <button type="submit"  name="BasketRefresh" class="button30 gradient refresh"><span><?=GetMessage("SALE_REFRESH")?></span></button>
                                        <div class="description"><?=GetMessage("SALE_REFRESH_DESCRIPTION");?></div>
                                    </div>
                                    <?endif;?>
                            </td>
                            <?elseif ($arHeader["id"] == "PRICE"):?>
                            <td class="basket_checkout_wrapp">
                                <?if ($arParams["SHOW_FULL_ORDER_BUTTON"]=="Y"):?>
                                    <div class="basket_checkout clearfix">
                                        <a href="javascript:void(0)" class="button30 fast_order checkout" onclick="checkOut();"><span><?=GetMessage("SALE_ORDER")?></span></a>
                                        <div class="description"><?=GetMessage("SALE_ORDER_DESCRIPTION");?></div>
                                        <input type="hidden" value="BasketOrder" name="BasketOrder">
                                    </div>
                                    <?endif;?>
                            </td>
                            <?elseif ($arHeader["id"] == "SUMM"):?>
                            <td class="basket_fast_order_wrapp" <?if ($bDelayColumn ):?> colspan="2"<?endif;?>>
                                <?/*if ($arParams["SHOW_FAST_ORDER_BUTTON"]=="Y"):?>
                                    <div class="basket_fast_order clearfix">
                                        <a onclick="oneClickBuyBasket()" class="button30 gradient fast_order"><span><?=GetMessage("SALE_FAST_ORDER")?></span></a>
                                        <div class="description"><?=GetMessage("SALE_FAST_ORDER_DESCRIPTION");?></div>
                                    </div>
                                <?endif;*/?>
                            </td>
                            <?else:?><td class="<?=strtolower($arHeader["id"])?>-cell"></td><?endif;?>
                        <?}?>

                </tr>
            </tfoot>
        </table>

        </table>
    </div>
    <?else:?>
    <div class="cart_empty">
        <table cellspacing="0" cellpadding="0" width="100%" border="0"><tr><td class="img_wrapp">
                    <div class="img">
                        <img src="<?=SITE_TEMPLATE_PATH?>/images/empty_cart.png" alt="<?=GetMessage("BASKET_EMPTY")?>" />
                    </div>
                </td><td>
                    <div class="text">
                        <?$APPLICATION->IncludeFile(SITE_DIR."include/empty_cart.php", Array(), Array("MODE"      => "html", "NAME"      => GetMessage("SALE_BASKET_EMPTY"),));?>
                    </div>
                </td></tr></table>
        <div class="clearboth"></div>
    </div>
    <?endif;?>
<div class="one_click_buy_basket_frame"></div>