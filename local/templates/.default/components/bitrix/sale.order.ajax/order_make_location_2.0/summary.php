<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="order-composition">
    <div>
        <span class="title">������ ������</span>

        <?  $clearedPriceForItems = (int)preg_replace('/\s/','',$arResult['PRICE_WITHOUT_DISCOUNT']) /*- (int)preg_replace('/\s/','',$arResult['DELIVERY_PRICE_FORMATED'])*/;
            //�������� �������� ������
            $prosuct = CIBlockElement::GetList(array(), array("ID"=>$arResult["BASKET_ITEMS"][0]["PRODUCT_ID"]), false, false, array("IBLOCK_SECTION_ID"));
            $arProduct = $prosuct->Fetch();
            $section = CIBlockSection::GetList(array(), array("IBLOCK_ID"=>12,"ID"=>$arProduct["IBLOCK_SECTION_ID"]),false, array("UF_DETAIL_PICTURE"));
            $arSection = $section->Fetch();

            //�������� ������������ ������ ������� ������
            $part = CIBlockSection::GetList(array(), array("IBLOCK_ID"=>12,"ID"=>$arSection["IBLOCK_SECTION_ID"]),false, array());
            $arPart = $part->Fetch();
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
        ?>

        <?
            //���� � ������� ��������
            if (count($arResult["BASKET_ITEMS"]) == 1  && $arResult["BASKET_ITEMS"][0]["CATALOG"]["PROPERTIES"]["PRICE_START"]["VALUE"]=='') {?>

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

                        if (is_array($set)) {
                            foreach ($set as $arSet) {
                                foreach($arSet["ITEMS"] as $setIem){
                                    $setItemsID[] = $setIem["ITEM_ID"];
                                    $setItemsQuantity[$setIem["ITEM_ID"]] = $setIem["QUANTITY"];
                                }
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
                            <td width="150"> ������</td>
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
                            <td><?=intval($item["PRICE"])?> �</td>
                        </tr>

                        <tr>
                            <td>��������</td>
                            <td><?=$arResult["DELIVERY"][$arResult["USER_VALS"]["DELIVERY_ID"]]["NAME"]?></td>
                            <td></td>
                            <td class="deliveryCostSummary">
                                    <?
                                        $arProductTMP = CCatalogProduct::GetByIDEx($item["PRODUCT_ID"]);
                                    ?>
                                <?
                                        echo str_replace("���.","�",$arResult["DELIVERY_PRICE_FORMATED"]);

                                ?>
                                <?
                                    // echo str_replace("���.","�",$arResult["DELIVERY_PRICE_FORMATED"]);
                                ?>
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
                                    $totalPrice = $arResult["ORDER_PRICE"]+$arResult["DELIVERY_PRICE"];
                                ?>
                                <?

                                    echo  $totalPrice." P"
                                    //}?>
                            </td>
                        </tr>
                    </table>
                </div>
                <?}?>

            <?}
            //���� � ������� "����������"
            else if (substr_count($arResult["BASKET_ITEMS"][0]["CATALOG"]["CODE"], "gift") > 0){ ?>
            <?
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

                        ?>
                        <td><?=$arResult["DELIVERY"][$arResult["USER_VALS"]["DELIVERY_ID"]]["NAME"]?></td>
                        <td></td>
                        <?  $clearedPriceForItems = (int)preg_replace('/\s/','',$arResult['PRICE_WITHOUT_DISCOUNT']) /*- (int)preg_replace('/\s/','',$arResult['DELIVERY_PRICE_FORMATED'])*/;

                            if (!bundlePrice::isAStartBundle($arResult['BASKET_ITEMS'],$clearedPriceForItems)) {?>
                            <td class="deliveryCostSummary">0 p</td>
                            <? } else { ?>
                            <td class="deliveryCostSummary"> <?=str_replace("���.","�",$arResult["DELIVERY_PRICE_FORMATED"])?></td>
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
                                $totalPrice = $arResult["ORDER_PRICE"]+$arResult["DELIVERY_PRICE"];

                            ?>
                            <?
                                echo (float)$totalPrice." �";
                                //}?>
                        </td>
                    </tr>
                </table>
                <br>
            </div>
            <?} else { //���� � ������� ���� ����?>
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
                        <!--<td><?=str_replace("���.","�",$arResult["DELIVERY_PRICE_FORMATED"])?></td>-->
                        <td class="deliveryCostSummary">
                            <?
                                if(!bundlePrice::isAStartBundle($arResult['BASKET_ITEMS'],$clearedPriceForItems)){
                                    echo "0 P";
                                } else {
                                    echo str_replace("���.","�",$arResult["DELIVERY_PRICE_FORMATED"]);
                            }?>
                        </td>
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
                                $totalPrice = $arResult["ORDER_PRICE"]+$arResult["DELIVERY_PRICE"];
                            ?>
                            <?
                                echo (float)$totalPrice." �";
                                //}?>
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
    ?>


<div class="payment">
    <?if ($arResult["USER_VALS"]["PAY_SYSTEM_ID"] == 25 || $arResult["USER_VALS"]["PAY_SYSTEM_ID"] == 27 || $arResult["USER_VALS"]["PAY_SYSTEM_ID"] == 48) {?>
        <img class="img" src="/images/inside/payment2.png" alt="" />
        <div class="cash active"><div>
                <span class="title"> ����� <img alt="" src="/images/inside/icon1.png"></span>
                <?//arshow($arResult)?>
                <p class="paySystemText">
                    <? if ($discountValue!='100%') {

                            if($arResult["USER_VALS"]["PAY_SYSTEM_ID"] == 27){
                                echo "������ ������ ��� ��������� � ��������� ��� ��������� ������. ";
                            } else if($arResult["USER_VALS"]["PAY_SYSTEM_ID"] == 25) {
                                echo "������ ��������� ��� ��������� ������. ";
                            } else {
                                echo "������ ������������ ���������� ��������. ";
                            }
                        } else {?>
                        <br><br>
                        <input type="hidden" id="sertGift" name="sertGift" value="Y">
                        <?}?>
                </p>

                <div class="online">
                    <div class="online-detail">
                        <?if ($discountValue!='100%') {?>
                            <div class="line"></div>
                            <div class="sum">�����: <span class="finalSumYellow"><?

                                    $totalPrice = $arResult["ORDER_PRICE"]+$arResult["DELIVERY_PRICE"];

                                    echo $totalPrice." �";
                                ?> </span></div>
                            <?}?>

                        <a href="javascript:void(0)" class="btn make_order">��������</a></div>
                </div>

            </div>

        </div>
        <?} else if ($arResult["USER_VALS"]["PAY_SYSTEM_ID"] == 26 ){?>
        <img class="img" src="/images/inside/payment.png" alt="" />
        <div class="black-bg active"><div>
                <span class="title"> ����� <img alt="" src="/images/inside/icon1.png"></span>
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

                                    $totalPrice = $arResult["ORDER_PRICE"]+$arResult["DELIVERY_PRICE"];
                                    echo $totalPrice." �";
                                ?>
                            </span></div>
                        <a href="javascript:void(0)" onclick="checkFormData();" class="btn make_order">�����</a></div>
                </div>

            </div>

        </div>
        <?}?>

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