<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
    use Bitrix\Main\Type\Collection;
    $arResult['ALL_FIELDS'] = array();
    $existShow = !empty($arResult['SHOW_FIELDS']);
    $existDelete = !empty($arResult['DELETED_FIELDS']);
    if ($existShow || $existDelete)
    {
        if ($existShow)
        {
            foreach ($arResult['SHOW_FIELDS'] as $propCode)
            {
                $arResult['SHOW_FIELDS'][$propCode] = array(
                    'CODE' => $propCode,
                    'IS_DELETED' => 'N',
                    'ACTION_LINK' => str_replace('#CODE#', $propCode, $arResult['~DELETE_FEATURE_FIELD_TEMPLATE']),
                    'SORT' => $arResult['FIELDS_SORT'][$propCode]
                );
            }
            unset($propCode);
            $arResult['ALL_FIELDS'] = $arResult['SHOW_FIELDS'];
            //if($arResult['ALL_FIELDS']["PREVIEW_PICTURE"] || $arResult['ALL_FIELDS']["DETAIL_PICTURE"])
            //unset($arResult['ALL_FIELDS']["PREVIEW_PICTURE"],$arResult['ALL_FIELDS']["DETAIL_PICTURE"]);
        }
        if ($existDelete)
        {
            foreach ($arResult['DELETED_FIELDS'] as $propCode)
            {
                $arResult['ALL_FIELDS'][$propCode] = array(
                    'CODE' => $propCode,
                    'IS_DELETED' => 'Y',
                    'ACTION_LINK' => str_replace('#CODE#', $propCode, $arResult['~ADD_FEATURE_FIELD_TEMPLATE']),
                    'SORT' => $arResult['FIELDS_SORT'][$propCode]
                );
            }
            unset($propCode, $arResult['DELETED_FIELDS']);
        }
        Collection::sortByColumn($arResult['ALL_FIELDS'], array('SORT' => SORT_ASC));
    }

    $arResult['ALL_PROPERTIES'] = array();
    $existShow = !empty($arResult['SHOW_PROPERTIES']);
    $existDelete = !empty($arResult['DELETED_PROPERTIES']);
    if ($existShow || $existDelete)
    {
        if ($existShow)
        {
            foreach ($arResult['SHOW_PROPERTIES'] as $propCode => $arProp)
            {
                $arResult['SHOW_PROPERTIES'][$propCode]['IS_DELETED'] = 'N';
                $arResult['SHOW_PROPERTIES'][$propCode]['ACTION_LINK'] = str_replace('#CODE#', $propCode, $arResult['~DELETE_FEATURE_PROPERTY_TEMPLATE']);
            }
            $arResult['ALL_PROPERTIES'] = $arResult['SHOW_PROPERTIES'];
        }
        unset($arProp, $propCode);
        if ($existDelete)
        {
            foreach ($arResult['DELETED_PROPERTIES'] as $propCode => $arProp)
            {
                $arResult['DELETED_PROPERTIES'][$propCode]['IS_DELETED'] = 'Y';
                $arResult['DELETED_PROPERTIES'][$propCode]['ACTION_LINK'] = str_replace('#CODE#', $propCode, $arResult['~ADD_FEATURE_PROPERTY_TEMPLATE']);
                $arResult['ALL_PROPERTIES'][$propCode] = $arResult['DELETED_PROPERTIES'][$propCode];
            }
            unset($arProp, $propCode, $arResult['DELETED_PROPERTIES']);
        }
        Collection::sortByColumn($arResult["ALL_PROPERTIES"], array('SORT' => SORT_ASC, 'ID' => SORT_ASC));
    }

    $arResult["ALL_OFFER_FIELDS"] = array();
    $existShow = !empty($arResult["SHOW_OFFER_FIELDS"]);
    $existDelete = !empty($arResult["DELETED_OFFER_FIELDS"]);
    if ($existShow || $existDelete)
    {
        if ($existShow)
        {
            foreach ($arResult["SHOW_OFFER_FIELDS"] as $propCode)
            {
                if($propCode=="PREVIEW_PICTURE" || $propCode=="DETAIL_PICTURE"){
                    unset($arResult["SHOW_OFFER_FIELDS"][$propCode]);
                }else{
                    $arResult["SHOW_OFFER_FIELDS"][$propCode] = array(
                        "CODE" => $propCode,
                        "IS_DELETED" => "N",
                        "ACTION_LINK" => str_replace('#CODE#', $propCode, $arResult['~DELETE_FEATURE_OF_FIELD_TEMPLATE']),
                        'SORT' => $arResult['FIELDS_SORT'][$propCode]
                    );
                }
            }
            unset($propCode);
            $arResult['ALL_OFFER_FIELDS'] = $arResult['SHOW_OFFER_FIELDS'];
        }
        if ($existDelete)
        {
            foreach ($arResult['DELETED_OFFER_FIELDS'] as $propCode)
            {
                $arResult['ALL_OFFER_FIELDS'][$propCode] = array(
                    "CODE" => $propCode,
                    "IS_DELETED" => "Y",
                    "ACTION_LINK" => str_replace('#CODE#', $propCode, $arResult['~ADD_FEATURE_OF_FIELD_TEMPLATE']),
                    'SORT' => $arResult['FIELDS_SORT'][$propCode]
                );
            }
            unset($propCode, $arResult['DELETED_OFFER_FIELDS']);
        }
        Collection::sortByColumn($arResult['ALL_OFFER_FIELDS'], array('SORT' => SORT_ASC));
    }

    $arResult['ALL_OFFER_PROPERTIES'] = array();
    $existShow = !empty($arResult["SHOW_OFFER_PROPERTIES"]);
    $existDelete = !empty($arResult["DELETED_OFFER_PROPERTIES"]);
    if ($existShow || $existDelete)
    {
        if ($existShow)
        {
            foreach ($arResult['SHOW_OFFER_PROPERTIES'] as $propCode => $arProp)
            {
                $arResult["SHOW_OFFER_PROPERTIES"][$propCode]["IS_DELETED"] = "N";
                $arResult["SHOW_OFFER_PROPERTIES"][$propCode]["ACTION_LINK"] = str_replace('#CODE#', $propCode, $arResult['~DELETE_FEATURE_OF_PROPERTY_TEMPLATE']);
            }
            unset($arProp, $propCode);
            $arResult['ALL_OFFER_PROPERTIES'] = $arResult['SHOW_OFFER_PROPERTIES'];
        }
        if ($existDelete)
        {
            foreach ($arResult['DELETED_OFFER_PROPERTIES'] as $propCode => $arProp)
            {
                $arResult["DELETED_OFFER_PROPERTIES"][$propCode]["IS_DELETED"] = "Y";
                $arResult["DELETED_OFFER_PROPERTIES"][$propCode]["ACTION_LINK"] = str_replace('#CODE#', $propCode, $arResult['~ADD_FEATURE_OF_PROPERTY_TEMPLATE']);
                $arResult['ALL_OFFER_PROPERTIES'][$propCode] = $arResult["DELETED_OFFER_PROPERTIES"][$propCode];
            }
            unset($arProp, $propCode, $arResult['DELETED_OFFER_PROPERTIES']);
        }
        Collection::sortByColumn($arResult['ALL_OFFER_PROPERTIES'], array('SORT' => SORT_ASC, 'ID' => SORT_ASC));
    }

    $arInfo = CCatalogSKU::GetInfoByProductIBlock($arParams["IBLOCK_ID"]);

    if ($arInfo){
        $arSelect = array( "ID", "IBLOCK_ID" );
        if (!$arParams["USE_PRICE_COUNT"]){
            foreach($arResult["PRICES"] as &$value){
                if (!$value['CAN_VIEW'] && !$value['CAN_BUY'])
                    continue;
                $arSelect[] = $value["SELECT"];
                $arFilter["CATALOG_SHOP_QUANTITY_".$value["ID"]] = $arParams["SHOW_PRICE_COUNT"];
            }
            if (isset($value))
                unset($value);
        }

        foreach( $arResult["ITEMS"] as $key => $arItem ):
            $rsOffers = CIBlockElement::GetList(array(),array("IBLOCK_ID" => $arInfo["IBLOCK_ID"], "PROPERTY_".$arInfo["SKU_PROPERTY_ID"] => $arItem["ID"]), false, false, $arSelect);
            if($rsOffers->SelectedRowsCount()){
                while ($arOffer = $rsOffers->GetNext()){
                    $arOffer["PRICES"] = CIBlockPriceTools::GetItemPrices(
                        $arOffer["IBLOCK_ID"],
                        $arResult["PRICES"],
                        $arOffer,
                        $arParams["PRICE_VAT_INCLUDE"],
                        $arResult['CONVERT_CURRENCY']
                    );
                    $arResult["ITEMS"][$key]["OFFERS"][] = $arOffer;
                }

            }
            endforeach;
    }
    $arResult["START_POSITION"] = 1;
    $arResult["END_POSITION"] = 3;
    //arshow($arResult);
    $filter=Array();
    foreach($arResult['ITEMS'] as $item)
    {
        $filter['ID'][]=$item['ID'];
    }
    $iblocks = CIBlockElement::GetList(array(),$filter, false, array(), array('PREVIEW_PICTURE','ID'));
    $i=0;
    while($arIBlock = $iblocks->GetNext())
    {       
        $pictures[$arIBlock['ID']]=  CFile::ResizeImageGet($arIBlock['PREVIEW_PICTURE'], Array("width"=>160, "height"=>160), BX_RESIZE_IMAGE_PROPORTIONAL, true);

    }
    foreach($arResult['ITEMS'] as &$item)
    {
        $item['PREVIEW_PICTURE']=$pictures[$item['ID']];
    }
?>