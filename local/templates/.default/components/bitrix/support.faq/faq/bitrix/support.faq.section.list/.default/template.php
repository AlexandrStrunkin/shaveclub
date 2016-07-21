<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
    /** @var array $arParams */
    /** @var array $arResult */
    /** @global CMain $APPLICATION */
    /** @global CUser $USER */
    /** @global CDatabase $DB */
    /** @var CBitrixComponentTemplate $this */
    /** @var string $templateName */
    /** @var string $templateFile */
    /** @var string $templateFolder */
    /** @var string $componentPath */
    /** @var CBitrixComponent $component */
    $this->setFrameMode(true); 
?>
<?//display sections?>
<table cellspacing="0" cellpadding="0" class="data-table" width="100%"> 	
    <tr> 		
        <td class="border-gray-body">
            <?foreach ($arResult['SECTIONS'] as $val):?>
                <?if($arParams["SECTION_ID"]==$val["ID"]) $SELECTED_ITEM = $val?>
                <nobr>
                    <div style="padding: 2px 2px 2px <?=17*$val['REAL_DEPTH'].'px'?>;">
                        <div class="<?=($arParams["SECTION_ID"]==$val["ID"])?'':'un'?>selected-arrow-faq"></div>
                        <?='<a href="'.$val['SECTION_PAGE_URL'].'" class="'.($arParams["SECTION_ID"]==$val["ID"]?'':'un').'selected-faq-item">'.$val['NAME'].'</a> ('.$val['ELEMENT_CNT'].')'?>
                        <br clear="all">
                    </div>
                </nobr>

                <?  
                    arshow($arParams["ID"]);
                    $arFilter = Array("IBLOCK_SECTION_ID"=>$arParams["ID"], "ACTIVE"=>"Y");
                    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), array());
                    while($ob = $res->GetNextElement())
                    {
                        $arFields = $ob->GetFields();
                    ?> 
                    <!--<div class="delivery-page">
                        <span class="title1"><span class="arr"></span><?=$arFields["NAME"]?></span>
                        <div class="delivery-desc" style="display: none;">
                            <span class="delivery-title">сумма заказа от 1000Р и выше</span>
                            <p>Мы готовы предложить Вам бесплатную доставку заказа курьером в пределах МКАД, при сумме заказа от 1000 руб.
                                и
                                выше. Доставка за МКАД (в пределах 10 км) потребует доплаты в 100 руб.
                            </p>  <span class="delivery-title">сумма заказа до 1000р</span>
                            <p>
                                При заказе на сумму до 1000 руб., стоимость доставки составляет 300 руб. в пределах МКАД. Стоимость доставки
                                за МКАД (в пределах 10 км) составляет 400 руб.
                            </p>
                            <p>
                                Доставка на расстояние, превышающее 10 км от МКАД, рассчитывается <span>индивидуально.</span>
                            </p>
                            <p><span>Заказ доставляется в течение 2 рабочих дней</span> после дня заказа. В день доставки курьер
                                с утра свяжется с Вами для уточнения времени доставки.</p>
                        </div>
                    </div>-->
                    <?

                       //arshow($arFields);
                    }
                ?>

                <?endforeach;?>
        </td>
    </tr>
</table>
<?if(isset($SELECTED_ITEM)):?>
    <h2><?=$SELECTED_ITEM["NAME"]?></h2>
    <?endif;?>