<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<style>
    #pp_sms_phone{
        display:block;
    }

    #pp_coeff + p{
        display:none;
    }

    label > div{
        height:165px !important;      
    }



</style>

<?//  arshow($arResult["BASKET_ITEMS"]["DISCOUNT_PRICE_PERCENT"]);
    //    arshow($_POST);
    if ($arResult["BASKET_ITEMS"][0]["DISCOUNT_PRICE_PERCENT"]==100){?>
    <style>
        .inside-page .delivery-type {
            display:none !important;
        }
    </style>
    <?} 
    if ($_POST["DELIVERY_ID"]=='pickpoint:postamat'){?>
    <style>
        .pickpoint .item {
            height:235px !important;      
        }
        .payment-select {
            margin-bottom: 200px;
        }
    </style>
    <?} 
?>


<div class="delivery-type">
    <span class="title">способ доставки</span>

    <script type="text/javascript">
        function fShowStore(id, showImages, formWidth, siteId)
        {
            var strUrl = '<?=$templateFolder?>' + '/map.php';
            var strUrlPost = 'delivery=' + id + '&showImages=' + showImages + '&siteId=' + siteId;

            var storeForm = new BX.CDialog({
                'title': '<?=GetMessage('SOA_ORDER_GIVE')?>',
                head: '',
                'content_url': strUrl,
                'content_post': strUrlPost,
                'width': formWidth,
                'height':450,
                'resizable':false,
                'draggable':false
            });

            var button = [
                {
                    title: '<?=GetMessage('SOA_POPUP_SAVE')?>',
                    id: 'crmOk',
                    'action': function ()
                    {
                        GetBuyerStore();
                        BX.WindowManager.Get().Close();
                    }
                },
                BX.CDialog.btnCancel
            ];
            storeForm.ClearButtons();
            storeForm.SetButtons(button);
            storeForm.Show();
        }

        function GetBuyerStore()
        {
            BX('BUYER_STORE').value = BX('POPUP_STORE_ID').value;
            //BX('ORDER_DESCRIPTION').value = '<?=GetMessage("SOA_ORDER_GIVE_TITLE")?>: '+BX('POPUP_STORE_NAME').value;
            BX('store_desc').innerHTML = BX('POPUP_STORE_NAME').value;
            BX.show(BX('select_store'));
        }

        function showExtraParamsDialog(deliveryId)
        {
            var strUrl = '<?=$templateFolder?>' + '/delivery_extra_params.php';
            var formName = 'extra_params_form';
            var strUrlPost = 'deliveryId=' + deliveryId + '&formName=' + formName;

            if(window.BX.SaleDeliveryExtraParams)
            {
                for(var i in window.BX.SaleDeliveryExtraParams)
                {
                    strUrlPost += '&'+encodeURI(i)+'='+encodeURI(window.BX.SaleDeliveryExtraParams[i]);
                }
            }

            var paramsDialog = new BX.CDialog({
                'title': '<?=GetMessage('SOA_ORDER_DELIVERY_EXTRA_PARAMS')?>',
                head: '',
                'content_url': strUrl,
                'content_post': strUrlPost,
                'width': 500,
                'height':200,
                'resizable':true,
                'draggable':false
            });

            var button = [
                {
                    title: '<?=GetMessage('SOA_POPUP_SAVE')?>',
                    id: 'saleDeliveryExtraParamsOk',
                    'action': function ()
                    {
                        insertParamsToForm(deliveryId, formName);
                        BX.WindowManager.Get().Close();
                    }
                },
                BX.CDialog.btnCancel
            ];

            paramsDialog.ClearButtons();
            paramsDialog.SetButtons(button);
            //paramsDialog.adjustSizeEx();
            paramsDialog.Show();
        }

        function insertParamsToForm(deliveryId, paramsFormName)
        {
            var orderForm = BX("ORDER_FORM"),
            paramsForm = BX(paramsFormName);
            wrapDivId = deliveryId + "_extra_params";

            var wrapDiv = BX(wrapDivId);
            window.BX.SaleDeliveryExtraParams = {};

            if(wrapDiv)
                wrapDiv.parentNode.removeChild(wrapDiv);

            wrapDiv = BX.create('div', {props: { id: wrapDivId}});

            for(var i = paramsForm.elements.length-1; i >= 0; i--)
            {
                var input = BX.create('input', {
                    props: {
                        type: 'hidden',
                        name: 'DELIVERY_EXTRA['+deliveryId+']['+paramsForm.elements[i].name+']',
                        value: paramsForm.elements[i].value
                    }
                    }
                );

                window.BX.SaleDeliveryExtraParams[paramsForm.elements[i].name] = paramsForm.elements[i].value;

                wrapDiv.appendChild(input);
            }

            orderForm.appendChild(wrapDiv);

            BX.onCustomEvent('onSaleDeliveryGetExtraParams',[window.BX.SaleDeliveryExtraParams]);
        }
    </script>

    <input type="hidden" name="BUYER_STORE" id="BUYER_STORE" value="<?=$arResult["BUYER_STORE"]?>" />      


    <div class="bx_section">
        <?
            if(!empty($arResult["DELIVERY"]))
            {
                $width = ($arParams["SHOW_STORES_IMAGES"] == "Y") ? 850 : 700;
            ?>
            

            <?

                foreach ($arResult["DELIVERY"] as $delivery_id => $arDelivery)
                {
                    if ($delivery_id !== 0 && intval($delivery_id) <= 0)
                    { // ----- pickpoint
                        foreach ($arDelivery["PROFILES"] as $profile_id => $arProfile)
                        {  
                        ?>        
                        <div class="bx_element">

                            <input
                                type="radio"
                                id="ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>"
                                name="<?=htmlspecialcharsbx($arProfile["FIELD_NAME"])?>"
                                value="<?=$delivery_id.":".$profile_id;?>"
                                <?=$arProfile["CHECKED"] == "Y" ? "checked=\"checked\"" : "";?>
                                onclick="submitForm();"
                                />

                            <label class="<?=$delivery_id?>" for="ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>">

                            <div class="item <?if($arProfile["CHECKED"] == "Y"){?>active<?}?>">
                                <div class="price">

                                    <?
                                        if($arProfile["CHECKED"] == "Y" && doubleval($arResult["DELIVERY_PRICE"]) > 0):
                                        ?>
                                        <?=$arResult["DELIVERY_PRICE"]?>
                                        <?
                                            if ((isset($arResult["PACKS_COUNT"]) && $arResult["PACKS_COUNT"]) > 1):
                                                echo $arResult["PACKS_COUNT"];
                                                endif;

                                            else:
                                            echo $arResult["DELIVERY_PRICE"];
                                            endif;
                                    ?>



                                    <span class="rouble">i</span></div>
                                <span class="item-title"><?=$arDelivery["TITLE"]?></span>

                                <p> 
                                    <?if (strlen($arProfile["DESCRIPTION"]) > 0):?>
                                        <?=nl2br($arProfile["DESCRIPTION"])?>
                                        <?else:?>
                                        <?=nl2br($arDelivery["DESCRIPTION"])?>
                                        <?endif;?>
                                </p>





                                <p style="display:none" onclick="BX('ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>').checked=true;submitForm();">
                            </div>  

                            </p>



                        </div>




                        <?
                        } // endforeach
                    }
                    else // stores and courier
                    {

                        if (count($arDelivery["STORE"]) > 0)
                            $clickHandler = "onClick = \"fShowStore('".$arDelivery["ID"]."','".$arParams["SHOW_STORES_IMAGES"]."','".$width."','".SITE_ID."')\";";
                        else
                            $clickHandler = "onClick = \"BX('ID_DELIVERY_ID_".$arDelivery["ID"]."').checked=true;submitForm();\"";
                    ?>      
                    <div class="bx_element"> 
                        <?
                            //                           arshow($arDelivery);
                            //                           arshow($_SESSION);
                        ?>  
                        <input type="radio"
                            id="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>"
                            name="<?=htmlspecialcharsbx($arDelivery["FIELD_NAME"])?>"
                            value="<?= $arDelivery["ID"] ?>"<?if ($arDelivery["CHECKED"]=="Y") echo " checked";?>
                            onclick="submitForm();"
                            />                   
                        <label for="ID_DELIVERY_ID_<?=$arDelivery["ID"]?>" <?=$clickHandler?>>  

                            <div class="item <?if($arDelivery["CHECKED"] == "Y"){?>active<?}?>">
                                <?
                                    $clearedPriceForItems = (int)preg_replace('/\s/','',$arResult['PRICE_WITHOUT_DISCOUNT']) /*- (int)preg_replace('/\s/','',$arResult['DELIVERY_PRICE_FORMATED'])*/; 
                                ?>
                                <div class="price">
                                    <?
                                        //                                     arshow($arResult['BASKET_ITEMS'], true);
                                    ?>
                                    <?if(!bundlePrice::isAStartBundle($arResult['BASKET_ITEMS'],$clearedPriceForItems)){
                                            if ($delivery_id==6){?>    
                                            80
                                            <?} else {?>
                                            0 <?} ?>
                                        <?} else {?>
                                        <?echo $arDelivery["PRICE"];
                                    }?> 

                                    <span class="rouble">i</span></div>
                                <span class="item-title"><?=$arDelivery["NAME"]?></span>

                                <p> 
                                    <?if (strlen($arDelivery["DESCRIPTION"]) > 0):?>     
                                        <?=nl2br($arDelivery["DESCRIPTION"])?>
                                        <?endif;?>
                                </p>
                            </div>            
                            <?
                                if (count($arDelivery["LOGOTIP"]) > 0):

                                    $arFileTmp = CFile::ResizeImageGet(
                                        $arDelivery["LOGOTIP"]["ID"],
                                        array("width" => "95", "height" =>"55"),
                                        BX_RESIZE_IMAGE_PROPORTIONAL,
                                        true
                                    );

                                    $deliveryImgURL = $arFileTmp["src"];
                                    else:
                                    $deliveryImgURL = $templateFolder."/images/logo-default-d.gif";
                                    endif;
                            ?>                             

                        </label>

                        <div class="clear"></div>
                    </div>

                    <?
                    }
                }
            }
        ?>
    </div>
</div>
