<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<div class="order-composition">
    <div>
        <span class="title">������ ������</span>    

        <?  $clearedPriceForItems = (int)preg_replace('/\s/','',$arResult['PRICE_WITHOUT_DISCOUNT']) - (int)preg_replace('/\s/','',$arResult['DELIVERY_PRICE_FORMATED']); 
            //�������� �������� ������
            $prosuct = CIBlockElement::GetList(array(), array("ID"=>$arResult["BASKET_ITEMS"][0]["PRODUCT_ID"]), false, false, array("IBLOCK_SECTION_ID"));
            $arProduct = $prosuct->Fetch();   
            $section = CIBlockSection::GetList(array(), array("IBLOCK_ID"=>12,"ID"=>$arProduct["IBLOCK_SECTION_ID"]),false,array("UF_DETAIL_PICTURE"));
            $arSection = $section->Fetch();

            //�������� ������������ ������ ������� ������
            $part = CIBlockSection::GetList(array(), array("IBLOCK_ID"=>12,"ID"=>$arSection["IBLOCK_SECTION_ID"]),false,array());
            $arPart = $part->Fetch();
            //            arshow($arResult["BASKET_ITEMS"]);
        ?>


        <?//���� ��������� ����� �� ����������� �����������, �� �������� ������ �� ��������� ������ 
            if ($arResult["BASKET_ITEMS"][0]["DISCOUNT_PRICE_PERCENT"]!=100) {?>
            <div class="under-title-note">��� ��������� ������, ��������� <a href="<?='/'.$arPart["CODE"].'/'.$arSection["CODE"]?>/">�����</a> � �������� ������ ������ �/��� ���� ������</div>
            <?
            }
        ?>


        <?
            if (count($arResult["BASKET_ITEMS"]) < 1) {
                header("location: /");
            }

            //             arshow($arResult["BASKET_ITEMS"])
        ?>

        <?
            //���� � ������� ��������
            if ($arResult["BASKET_ITEMS"][0]["TYPE"] == 1) {?> 

            <img class="img" src="<?=CFIle::GetPath($arSection["UF_DETAIL_PICTURE"])?>" alt=""/>
            <?foreach ($arResult["BASKET_ITEMS"] as $item){?>
                <div class="table-container">
                    <?  
                        $props = array();
                        foreach ($item["PROPS"] as $prop) {
                            $props[$prop["CODE"]] = $prop["VALUE"]; 
                        }


                        //�������� ���� � ��������� �����
                        $element = CIBLockElement::GetList(array(), array("ID"=>$item["PRODUCT_ID"]),false,false,array("IBLOCK_SECTION_ID","NAME"));
                        $arElement = $element->Fetch();
                        //�������� ������
                        $section = CIBlockSection::GetList(array(),array("ID"=>$arElement["IBLOCK_SECTION_ID"]),false, array("NAME","CODE"));
                        $arSection = $section->Fetch();
                        //�������� ���� � ����
                        $set = CCatalogProductSet::getAllSetsByProduct($item["PRODUCT_ID"],1);  
                        //arshow($set);            
                        $thisSet = array();
                        //�������� �������� ����
                        $setItemsID = array(); //������ ���������������
                        $setItemsQuantity = array(); // ������ � ����������� ������� �������� ����

                        foreach ($set as $arSet) {
                            foreach($arSet["ITEMS"] as $setIem){
                                $setItemsID[] = $setIem["ITEM_ID"];
                                $setItemsQuantity[$setIem["ITEM_ID"]] = $setIem["QUANTITY"];                             
                            }   
                        }            

                        //�������� ���� � ������������ ����
                        $setItems = array();
                        $setItemsInfo = CIBLockElement::GetList(array(), array("ID"=>$setItemsID), false, false, array("ID","NAME","CODE"));
                        while($arSetItemsInfo = $setItemsInfo->Fetch()) {
                            $setItems[$arSetItemsInfo["CODE"]] = array("ID"=>$arSetItemsInfo["ID"]); 
                        }

                    ?>
                    <table>
                        <tr>
                            <td width="150">������</td>
                            <td width="280"><?=$arSection["NAME"]?></td>
                            <td></td>
                            <td width="100"></td>
                        </tr>

                        <tr>
                            <td>���� ������</td>
                            <td>
                                <?=$arElement["NAME"]?> 
                            </td>
                            <td></td>
                            <td><?=$item["PRICE"]?> �</td>
                        </tr>

                        <tr>
                            <td>������������</td>
                            <td>���������� ������<br/>
                                ������� �������
                            </td>
                            <td>�<?=$setItemsQuantity[$setItems["razor_".$arSection["CODE"]]["ID"]]?><br/>
                                �<?=$setItemsQuantity[$setItems["cassette_".$arSection["CODE"]]["ID"]]?>
                            </td>
                            <td>� �������</td>
                        </tr>

                        <tr>
                            <td>��������</td>
                            <td><?=$arResult["DELIVERY"][$arResult["USER_VALS"]["DELIVERY_ID"]]["NAME"]?></td>
                            <td></td>
                            <td class="deliveryCostSummary">                 
                                <?
                                    if(!bundlePrice::isAStartBundle($arResult['BASKET_ITEMS'],$clearedPriceForItems)){
                                        echo "0 P";
                                    } else {
                                        echo str_replace("���.","�",$arResult["DELIVERY_PRICE_FORMATED"]);
                                }?> 
                            </td>
                        </tr>

                        <?if ($props["ECONOMY"] > 0){?>
                            <tr>
                                <td>��������</td>
                                <td></td>
                                <td></td>
                                <td><?=$props["ECONOMY"]?>%</td>
                            </tr>
                            <?}?>

                        <tr>
                            <td>������ �� ������</td>
                            <td></td>
                            <td></td>
                            <?
                                $dbBasketItems = CSaleBasket::GetList(array("NAME" => "ASC", "ID" => "ASC"), array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"), false, false, array());
                                while ($arItems = $dbBasketItems -> Fetch()) {                              
                                    $discountValue = $arItems["DISCOUNT_VALUE"];
                                }
                            ?>
                            <td class="tableDiscountPerc"><?=$discountValue?></td>
                        </tr>

                        <tr>
                            <td>�����</td>
                            <td></td>
                            <td></td>
                            <td class="tableSum">
                                <?
                                    if(!bundlePrice::isAStartBundle($arResult['BASKET_ITEMS'],$clearedPriceForItems)){
                                        $totalPrice = (int)preg_replace('/\s/','',$arResult["ORDER_TOTAL_PRICE_FORMATED"]) - (int)preg_replace('/\s/','',$arResult['DELIVERY_PRICE_FORMATED']);
                                        echo $totalPrice." �";
                                    } else {
                                        echo str_replace("���.","�",$arResult["ORDER_TOTAL_PRICE_FORMATED"]);
                                }?> 
                            </td>
                        </tr>
                    </table>
                </div>
                <?}?> 

            <?} 
            //���� � ������� "����������"
            else if (substr_count($arResult["BASKET_ITEMS"][0]["CATALOG"]["CODE"], "gift") > 0){ ?>
            <?//arshow($arResult["BASKET_ITEMS"][0])
            ?>
            <img class="img label" src="/images/gift_label.png" alt=""/>
            <img class="img" src="<?=CFIle::GetPath($arSection["UF_DETAIL_PICTURE"])?>" alt=""/>
            <div class="table-container">
                <table>
                    <tr>
                        <td width="150">������</td>
                        <td width="280"><?=$arSection["NAME"]?></td>
                        <td>��</td>
                        <td width="100">����</td>
                    </tr>   

                    <?foreach ($arResult["BASKET_ITEMS"] as $item){?>   
                        <?//arshow($item)?>
                        <tr>
                            <td>���� ������</td>
                            <td><?=$item["NAME"]?></td>
                            <td>x <?=$item["QUANTITY"]?></td>
                            <td><?=$item["PRICE"]*$item["QUANTITY"]?> �</td>
                        </tr> 
                        <?}?>  
                    <tr>
                        <td>��������</td>
                        <?
                            //                               arshow($arResult["BASKET_ITEMS"]);
                        ?>
                        <td><?=$arResult["DELIVERY"][$arResult["USER_VALS"]["DELIVERY_ID"]]["NAME"]?></td>
                        <td></td>
                        <?  $clearedPriceForItems = (int)preg_replace('/\s/','',$arResult['PRICE_WITHOUT_DISCOUNT']) - (int)preg_replace('/\s/','',$arResult['DELIVERY_PRICE_FORMATED']); 
//                            arshow($clearedPriceForItems);
                            if (!bundlePrice::isAStartBundle($arResult['BASKET_ITEMS'],$clearedPriceForItems)) {?>
                            <td>0 p</td>
                            <? } else { ?>
                            <td><?=str_replace("���.","�",$arResult["DELIVERY_PRICE_FORMATED"])?></td>
                            <? } ?>
                    </tr> 

                    <?if($arResult["BASKET_ITEMS"][0]['DISCOUNT_PRICE_PERCENT']){?>      
                        <tr>
                            <td>������</td>
                            <td></td>
                            <td></td>
                            <td><?=$arResult["BASKET_ITEMS"][0]['DISCOUNT_PRICE_PERCENT_FORMATED']?></td>
                        </tr>                         
                        <?}?>
                    <tr>
                        <td>������ �� ������</td>
                        <td></td>
                        <td></td>
                        <?
                            $dbBasketItems = CSaleBasket::GetList(array("NAME" => "ASC", "ID" => "ASC"), array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"), false, false, array());
                            while ($arItems = $dbBasketItems -> Fetch()) {                              
                                $discountValue = $arItems["DISCOUNT_VALUE"];
                            }
                        ?>
                        <td class="tableDiscountPerc"><?=$discountValue?></td>
                    </tr>
                    <tr>
                        <td>�����</td>
                        <td></td>
                        <td></td>
                        <td class="tableSum">
                            <?
                                if(!bundlePrice::isAStartBundle($arResult['BASKET_ITEMS'],$clearedPriceForItems)){
                                    $totalPrice = (int)preg_replace('/\s/','',$arResult["ORDER_TOTAL_PRICE_FORMATED"]) - (int)preg_replace('/\s/','',$arResult['DELIVERY_PRICE_FORMATED']);
                                    echo $totalPrice." �";
                                } else {
                                    echo str_replace("���.","�",$arResult["ORDER_TOTAL_PRICE_FORMATED"]);
                            }?> 
                        </td>
                    </tr>
                </table>
                <br>
            </div> 
            <?} else { //���� � ������� ���� ����?>
            <?//arshow($arResult["BASKET_ITEMS"][0][])?>                                                                                                                                                                                  
            <img class="img" src="<?=CFIle::GetPath($arSection["UF_DETAIL_PICTURE"])?>" alt=""/>
            <div class="table-container">

                <table>
                    <tr>
                        <td width="150">������</td>
                        <td width="280"><?=$arSection["NAME"]?></td>
                        <td>��</td>
                        <td width="100">����</td>
                    </tr>

                    <tr>
                        <td>���� ������</td>
                        <td>����</td>
                        <td></td>
                        <td></td>
                    </tr>

                    <?foreach ($arResult["BASKET_ITEMS"] as $item){?>   
                        <?//arshow($item)?>
                        <tr>
                            <td><?=$item["NAME"]?></td>
                            <td><?=$item["PRICE"]?> �</td>
                            <td>x <?=$item["QUANTITY"]?></td>
                            <td><?=$item["PRICE"]*$item["QUANTITY"]?> �</td>
                        </tr> 
                        <?}?>  
                    <tr>
                        <td>��������</td>
                        <td><?=$arResult["DELIVERY"][$arResult["USER_VALS"]["DELIVERY_ID"]]["NAME"]?></td>
                        <td></td>
                        <td><?=str_replace("���.","�",$arResult["DELIVERY_PRICE_FORMATED"])?></td>
                    </tr> 

                    <?if($arResult["BASKET_ITEMS"][0]['DISCOUNT_PRICE_PERCENT']){?>      
                        <tr>
                            <td>������</td>
                            <td></td>
                            <td></td>
                            <td><?=$arResult["BASKET_ITEMS"][0]['DISCOUNT_PRICE_PERCENT_FORMATED']?></td>
                        </tr>                         
                        <?}?>
                    <tr>
                        <td>������ �� ������</td>
                        <td></td>
                        <td></td>
                        <?
                            $dbBasketItems = CSaleBasket::GetList(array("NAME" => "ASC", "ID" => "ASC"), array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"), false, false, array());
                            while ($arItems = $dbBasketItems -> Fetch()) {                              
                                $discountValue = $arItems["DISCOUNT_VALUE"];
                            }
                        ?>
                        <td class="tableDiscountPerc"><?=$discountValue?></td>
                    </tr>
                    <tr>
                        <td>�����</td>
                        <td></td>
                        <td></td>
                        <td class="tableSum">
                            <?
                                if(!bundlePrice::isAStartBundle($arResult['BASKET_ITEMS'],$clearedPriceForItems)){
                                    $totalPrice = (int)preg_replace('/\s/','',$arResult["ORDER_TOTAL_PRICE_FORMATED"]) - (int)preg_replace('/\s/','',$arResult['DELIVERY_PRICE_FORMATED']);
                                    echo $totalPrice." �";
                                } else {
                                    echo str_replace("���.","�",$arResult["ORDER_TOTAL_PRICE_FORMATED"]);
                            }?> 
                        </td>
                    </tr>
                </table>
            </div>
            <?}?>     
    </div>
</div>
<input type="hidden" id="discountVal" value="<?=$discountValue?>">
<?
    //��� ����� ��� ������
    if ($discountValue!='100%') {
    ?>
    <div class="coupon-block"><span class="arr"></span>
        <span class="title">����� �� ������</span>
        <span class="line"></span>

        <p>
            � ��� ���� ����� �� ������? <br/>
            ������� ��� � ���� � �������� ������ � �������� ����� ������!</p>
        <input type="text" class="number couponCode" value="" />
        <a href="#" class="btn submitCoupon">���������</a>
    </div>

    <? }
    /*$APPLICATION->IncludeComponent(
    "bitrix:sale.basket.basket", 
    "basket_coupon", 
    array(
    "COLUMNS_LIST" => array(              
    ),
    "PATH_TO_ORDER" => "/personal/order/make/",
    "HIDE_COUPON" => "N",
    "PRICE_VAT_SHOW_VALUE" => "N",
    "COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
    "USE_PREPAYMENT" => "N",
    "QUANTITY_FLOAT" => "N",
    "SET_TITLE" => "N",
    "ACTION_VARIABLE" => "action",
    "OFFERS_PROPS" => array(
    )
    ),
    false
);*/?>

<script>
    $(document).ready(function(){           

        $('.submitCoupon').unbind('click').click(function(e){
            e.preventDefault();
            var coupon = $(".couponCode")[1].value;
            $.post("/ajax/coupon.php",{coupon:coupon},function(data){
                price = data.split('#') ;
                //alert(data);
                //price = JSON.parse(data);
                //alert(price);
                console.log(price);
                $('.tableDiscountPerc').html(price[0]);
                $('.tableSum').html(price[1]+' �');
                $('.finalSumYellow').html(price[1]+' P');
                if (price[2]=='Y' && price[0]!=0) {
                    $('.form_discount').html(price[3]);
                    $.fancybox.open({href: '#success_form'}); 

                } else {
                    //alert(price[2]);
                    $.fancybox.open({href: '#error_form'})
                }
            })  
        })
    })  
</script>

<?
    /////////////////////////////
?>


<div class="payment">

    <?if ($arResult["USER_VALS"]["PAY_SYSTEM_ID"] == 17 /*��������*/){?>
        <img class="img" src="/images/inside/payment2.png" alt="" />
        <div class="cash active"><div>
                <span class="title"> ����� <img alt="" src="/images/inside/icon1.png"></span>
                <?//arshow($arResult)?>
                <p class="paySystemText">                  
                    <? if ($discountValue!='100%') { ?>          
                        ��� ������ ��������� ��������� �������� � ������� 50 ������ 
                        <?} else {?>
                        <br><br> 
                        <?}?>                        
                </p>

                <div class="online">
                    <div class="online-detail"> 
                        <?if ($discountValue!='100%') {?>   
                            <div class="line"></div>
                            <div class="sum">�����: <span class="finalSumYellow"><?
                                    if(!bundlePrice::isAStartBundle($arResult['BASKET_ITEMS'],$clearedPriceForItems)){
                                        $totalPrice = (int)preg_replace('/\s/','',$arResult["ORDER_TOTAL_PRICE_FORMATED"]) - (int)preg_replace('/\s/','',$arResult['DELIVERY_PRICE_FORMATED']) + 50;
                                        echo $totalPrice." �";
                                    } else {
                                        echo str_replace("���.","�",$arResult["ORDER_TOTAL_PRICE_FORMATED"]);
                                }?> </span></div>
                            <?}?>
                        <input type="hidden" id="sertGift" name="sertGift" value="Y">
                        <a href="javascript:void(0)" class="btn make_order">��������</a></div>
                </div>

            </div>

        </div>
        <?} else if ($arResult["USER_VALS"]["PAY_SYSTEM_ID"] == 18 /*uniteller*/){?>           
        <img class="img" src="/images/inside/payment.png" alt="" />
        <div class="black-bg active"><div>
                <span class="title"> ����� <img alt="" src="/images/inside/icon1.png"></span>
                <?//arshow($arResult) ?>
                <p class="paySystemText">
                    <? if ($discountValue!='100%') { ?>          
                        �������� ������ ����� ��������� ������� Uniteller. ��� ������, ������ �, �������, ���������!            
                        <?} else {?>
                        <br><br> 
                        <?}?>
                </p>       
                <div class="online">
                    <div class="online-detail">    
                        <?if ($discountValue!='100%') {?>
                            <div class="line"></div>
                            <?}?>
                        <div class="sum">�����: <span class="finalSumYellow">
                                <?
                                    if(!bundlePrice::isAStartBundle($arResult['BASKET_ITEMS'],$clearedPriceForItems)){
                                        $totalPrice = (int)preg_replace('/\s/','',$arResult["ORDER_TOTAL_PRICE_FORMATED"]) - (int)preg_replace('/\s/','',$arResult['DELIVERY_PRICE_FORMATED']);
                                        echo $totalPrice." �";
                                    } else {
                                        echo str_replace("���.","�",$arResult["ORDER_TOTAL_PRICE_FORMATED"]);
                                }?> 
                            </span></div>
                        <a href="javascript:void(0)" onclick="submitForm('Y');" class="btn make_order">�����</a></div>
                </div>

            </div>

        </div>            
        <?}?>


    <?
        /*
        $sales = CSaleDiscount::GetList(array(),array(), false, array());

        while ($arSales = $sales->Fetch())
        {
        //                arshow($arSales);
        }
        */
    ?>

</div>


<div id="success_form">

    <div class="form_title">�����</div>
    <div class="form_title_separator"></div>
    <div class="form_text"><div class="form_discount"></div></div>
    <div class="form_error"></div>
    <a onclick="$.fancybox.close();" class="close_button">����</a>
    <!-- <div class="form_line form_line_left"></div>
    <div class="form_line form_line_right"></div>-->

</div>

<div id="error_form">

    <div class="form_title">������</div>
    <div class="form_title_separator"></div>
    <div class="form_text">��� �����:( ��� ����� �� ��������</div>
    <div class="form_error"></div>
    <a onclick="$.fancybox.close();" class="close_button">����</a>
    <!--<div class="form_line form_line_left"></div>
    <div class="form_line form_line_right"></div>-->

</div>