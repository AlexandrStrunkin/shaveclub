<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="delivery-type payment-select">
    <span class="title">способ оплаты</span>

    <div class="section">
        <script type="text/javascript">
            function changePaySystem(param)
            {
                if (BX("account_only") && BX("account_only").value == 'Y') // PAY_CURRENT_ACCOUNT checkbox should act as radio
                {
                    if (param == 'account')
                    {
                        if (BX("PAY_CURRENT_ACCOUNT"))
                        {
                            BX("PAY_CURRENT_ACCOUNT").checked = true;
                            BX("PAY_CURRENT_ACCOUNT").setAttribute("checked", "checked");
                            BX.addClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');

                            // deselect all other
                            var el = document.getElementsByName("PAY_SYSTEM_ID");
                            for(var i=0; i<el.length; i++)
                                el[i].checked = false;
                        }
                    }
                    else
                    {
                        BX("PAY_CURRENT_ACCOUNT").checked = false;
                        BX("PAY_CURRENT_ACCOUNT").removeAttribute("checked");
                        BX.removeClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
                    }
                }
                else if (BX("account_only") && BX("account_only").value == 'N')
                {
                    if (param == 'account')
                    {
                        if (BX("PAY_CURRENT_ACCOUNT"))
                        {
                            BX("PAY_CURRENT_ACCOUNT").checked = !BX("PAY_CURRENT_ACCOUNT").checked;

                            if (BX("PAY_CURRENT_ACCOUNT").checked)
                            {
                                BX("PAY_CURRENT_ACCOUNT").setAttribute("checked", "checked");
                                BX.addClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
                            }
                            else
                            {
                                BX("PAY_CURRENT_ACCOUNT").removeAttribute("checked");
                                BX.removeClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
                            }
                        }
                    }
                }



                submitForm();

            }



            /*document.addEventListener('DOMContentLoaded', function(){

            function changeBGAndStatus(text,bgColor){
            $('.black-bg').css('background-color',bgColor);
            $('.paySystemText').html(text);
            }

            BX.addCustomEvent('onAjaxSuccess', function() {
            console.log('rverveerv');
            });
            $('body').on('click','.item',function(e){
            var paySystem = $(e.currentTarget).parent().attr('for');
            switch(paySystem){
            case "ID_PAY_SYSTEM_ID_18":
            changeBGAndStatus('Оплата через Uniteller','#000');
            break;
            case "ID_PAY_SYSTEM_ID_17":
            changeBGAndStatus('При оплате наличными взимается комиссия в размере 50 рублей','#040');
            break;
            }  
            })
            })*/
        </script>

        <div class="bx_section">
            <?
                /*
            ?>
            <div class="bx_element" >
                <input type="radio" id="ID_PAY_SYSTEM_ID" name="PAY_SYSTEM_ID">
                <label class="pay" for="ID_PAY_SYSTEM_ID">
                    <div class="item none_cursor">
                        <span class="item-title">Электронный платёж</span>
                        <p>В данный момент оплата заказа через электронные платежи недоступна</p>
                    </div>
                </label>
                <div class="clear"></div>
            </div>
            <?
                */
            ?>
            <?
                if ($arResult["PAY_FROM_ACCOUNT"] == "Y")
                {
                    $accountOnly = ($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y") ? "Y" : "N";
                ?>
                <input type="hidden" id="account_only" value="<?=$accountOnly?>" />
                <div class="bx_element">
                    <input type="hidden" name="PAY_CURRENT_ACCOUNT" value="N">
                    <label for="PAY_CURRENT_ACCOUNT" id="PAY_CURRENT_ACCOUNT_LABEL" onclick="changePaySystem('account');" class="<?if($arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y") echo "selected"?>">
                        <input type="checkbox" name="PAY_CURRENT_ACCOUNT" id="PAY_CURRENT_ACCOUNT" value="Y"<?if($arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y") echo " checked=\"checked\"";?>>
                        <div class="bx_logotype">
                            <span style="background-image:url(<?=$templateFolder?>/images/logo-default-ps.gif);"></span>
                        </div>
                        <div class="bx_description">
                            <strong><?=GetMessage("SOA_TEMPL_PAY_ACCOUNT")?></strong>
                            <p>
                                <div><?=GetMessage("SOA_TEMPL_PAY_ACCOUNT1")." <b>".$arResult["CURRENT_BUDGET_FORMATED"]?></b></div>
                                <? if ($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y"):?>
                                    <div><?=GetMessage("SOA_TEMPL_PAY_ACCOUNT3")?></div>
                                    <? else:?>
                                    <div><?=GetMessage("SOA_TEMPL_PAY_ACCOUNT2")?></div>
                                    <? endif;?>
                            </p>
                        </div>
                    </label>
                    <div class="clear"></div>
                </div>
                <?
                }

                uasort($arResult["PAY_SYSTEM"], "cmpBySort"); // resort arrays according to SORT value

                foreach($arResult["PAY_SYSTEM"] as $arPaySystem)
                {
                    if (strlen(trim(str_replace("<br />", "", $arPaySystem["DESCRIPTION"]))) > 0 || intval($arPaySystem["PRICE"]) > 0)
                    {
                        if (count($arResult["PAY_SYSTEM"]) == 1)
                        {
                        ?>
                        <div class="bx_element">
                            <input type="hidden" name="PAY_SYSTEM_ID" value="<?=$arPaySystem["ID"]?>">

                            <input type="radio"
                                id="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>"
                                name="PAY_SYSTEM_ID"
                                value="<?=$arPaySystem["ID"]?>"
                                <?if ($arPaySystem["CHECKED"]=="Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y")) echo " checked=\"checked\"";?>
                                onclick="changePaySystem();"
                                />
                            <label class="<?=$arPaySystem["ID"]?>" for="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>" onclick="BX('ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>').checked=true;changePaySystem();">

                                <div class="item <?if($arPaySystem["CHECKED"] == "Y"){?>active<?}?>">
                                    <div class="price"><?//=$arPaySystem["PRICE"]?></div>
                                    <span class="item-title"><?=$arPaySystem["NAME"]?></span>

                                    <p> 
                                        <?if (strlen($arPaySystem["DESCRIPTION"]) > 0):?>
                                            <?=nl2br($arPaySystem["DESCRIPTION"])?>
                                            <?else:?>
                                            <?=nl2br($arPaySystem["DESCRIPTION"])?>
                                            <?endif;?>
                                    </p>
                                </div>      




                            </label>
                            <div class="clear"></div>
                        </div>
                        <?
                        }
                        else // more than one
                        {
                        ?>
                        <div class="bx_element">
                            <input type="radio"
                                id="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>"
                                name="PAY_SYSTEM_ID"
                                value="<?=$arPaySystem["ID"]?>"
                                <?if ($arPaySystem["CHECKED"]=="Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y")) echo " checked=\"checked\"";?>
                                onclick="changePaySystem();" />
                            <label class="pay_<?=$arPaySystem["ID"]?>" for="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>" onclick="BX('ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>').checked=true;changePaySystem();">


                                <div class="item <?if($arPaySystem["CHECKED"] == "Y"){?>active<?}?>">
                                    <div class="price"><?//=$arPaySystem["PRICE"]?></div>
                                    <span class="item-title"><?=$arPaySystem["NAME"]?></span>

                                    <p> 
                                        <?if (strlen($arPaySystem["DESCRIPTION"]) > 0):?>
                                            <?=nl2br($arPaySystem["DESCRIPTION"])?>
                                            <?else:?>
                                            <?=nl2br($arPaySystem["DESCRIPTION"])?>
                                            <?endif;?>
                                    </p>
                                </div>



                            </label>
                            <div class="clear"></div>
                        </div>
                        <?
                        }
                    }

                    if (strlen(trim(str_replace("<br />", "", $arPaySystem["DESCRIPTION"]))) == 0 && intval($arPaySystem["PRICE"]) == 0)
                    {
                        if (count($arResult["PAY_SYSTEM"]) == 1)
                        {
                        ?>
                        <div class="bx_block horizontal">
                            <div class="bx_element">
                                <input type="hidden" name="PAY_SYSTEM_ID" value="<?=$arPaySystem["ID"]?>">
                                <input type="radio"
                                    id="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>"
                                    name="PAY_SYSTEM_ID"
                                    value="<?=$arPaySystem["ID"]?>"
                                    <?if ($arPaySystem["CHECKED"]=="Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y")) echo " checked=\"checked\"";?>
                                    onclick="changePaySystem();"
                                    />
                                <label for="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>" onclick="BX('ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>').checked=true;changePaySystem();">

                                <div class="item <?if($arPaySystem["CHECKED"] == "Y"){?>active<?}?>">
                                    <div class="price"><?//=$arPaySystem["PRICE"]?></div>
                                    <span class="item-title"><?=$arPaySystem["NAME"]?></span>

                                    <p> 
                                        <?if (strlen($arPaySystem["DESCRIPTION"]) > 0):?>
                                            <?=nl2br($arPaySystem["DESCRIPTION"])?>
                                            <?else:?>
                                            <?=nl2br($arPaySystem["DESCRIPTION"])?>
                                            <?endif;?>
                                    </p>
                                </div>


                            </div>
                        </div>
                        <?
                        }
                        else // more than one
                        {
                        ?>
                        <div class="bx_element">

                            <input type="radio"
                                id="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>"
                                name="PAY_SYSTEM_ID"
                                value="<?=$arPaySystem["ID"]?>"
                                <?if ($arPaySystem["CHECKED"]=="Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y")) echo " checked=\"checked\"";?>
                                onclick="changePaySystem();" />

                            <label for="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>" onclick="BX('ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>').checked=true;changePaySystem();">

                                <div class="item <?if($arPaySystem["CHECKED"] == "Y"){?>active<?}?>">
                                    <div class="price"><?//=$arPaySystem["PRICE"]?></div>
                                    <span class="item-title"><?=$arPaySystem["NAME"]?></span>

                                    <p> 
                                        <?if (strlen($arPaySystem["DESCRIPTION"]) > 0):?>
                                            <?=nl2br($arPaySystem["DESCRIPTION"])?>
                                            <?else:?>
                                            <?=nl2br($arPaySystem["DESCRIPTION"])?>
                                            <?endif;?>
                                    </p>
                                </div>      

                            </label>
                        </div>
                        <?
                        }
                    }
                }
            ?>
            <div style="clear: both;"></div>
        </div>
    </div>

</div>
