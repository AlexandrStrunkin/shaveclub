<?require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");?>
<?

    //  arshow($_POST);
    if (intval($_POST["razor_count"]) > 0 || intval($_POST["cassette_count"]) > 0) {
        //??????? ??????? ????????????
        CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());

        //???????? ?????? ??? ?????????? ? ?????? ? ???????
        $britvaPartsData = array("cassette_".$_POST["razor"] => array("QUANTITY"=>$_POST["cassette_count"]),"razor_".$_POST["razor"] => array("QUANTITY"=>$_POST["razor_count"]));

        //???????? ???? ? ??????? ? ??????
        $britvaParts = array("cassette_".$_POST["razor"],"razor_".$_POST["razor"]);
        $britva = CIBlockElement::GetList(array(), array("=CODE"=>$britvaParts),false, false, array("ID","CODE"));
        while($arBritva = $britva->Fetch()) {
            $britvaPartsData[$arBritva["CODE"]]["ID"] = $arBritva["ID"];
            //???????? ???? ??????/???????
            $partPrice = CPrice::GetList(array(), array("PRODUCT_ID"=>$arBritva["ID"],">PRICE"=>0),false, false, array());
            $arPartPrice = $partPrice->Fetch();
            $britvaPartsData[$arBritva["CODE"]]["PRICE"] = $arPartPrice["PRICE"];
            $britvaPartsData[$arBritva["CODE"]]["PRICE_ID"] = $arPartPrice["ID"];
        }

        //????????? ID ????????

        $basketID = array();
        foreach ($britvaPartsData as $partCode=>$razorPart) {

            //???????? ???????? ??????
            $product = CCatalogProduct::GetList(array(),array("ID"=>$razorPart["ID"],"TYPE"=>1 /*??? ??? ?????????*/));
            $arProduct = $product->Fetch();
            //arshow($razorPart);
            if ($arProduct["ID"] > 0) {

                // arshow($arProduct);

                $element = CIBlockElement::GetList(array(), array("IBLOCK_ID"=> 12,"ID"=>$arProduct["ID"]), false, false, array("PROPERTY_ECONOMY", "PROPERTY_LENGTH", "PROPERTY_CASSETTE", "PROPERTY_RAZOR", "PROPERTY_DELIVERY"));
                $arElement = $element->Fetch();

                //???????? ??????? ??? ????????
                $iblock = CIBlock::GetById($arProduct["ELEMENT_IBLOCK_ID"]);
                $arIblock = $iblock->Fetch();

                $arFields = array(
                    "PRODUCT_ID" => $arProduct["ID"],
                    "PRODUCT_XML_ID" => $arProduct["ELEMENT_XML_ID"],
                    "CATALOG_XML_ID" => $arIblock["XML_ID"],
                    "PRICE" => $razorPart["PRICE"],
                    "CURRENCY" => "RUB",
                    "QUANTITY" => $razorPart["QUANTITY"],
                    "LID" => "s1",
                    "NAME" => $arProduct["ELEMENT_NAME"],
                    "WEIGHT" => $arProduct["WEIGHT"],
                    "WIDTH" => $arProduct["WIDTH"],
                    "HEIGHT" => $arProduct["HEIGHT"],
                    "LENGTH" => $arProduct["LENGTH"],
                    "PROPS" => $PROPS,
                    "PRODUCT_PROVIDER_CLASS"=> "CCatalogProductProvider",
                    "MODULE"=> "catalog",
                );

                //arshow($arFields);
                if ($razorPart["QUANTITY"]>0){
                $basketID[] = CSaleBasket::Add($arFields);
                }

            }

        }

        if (is_array($basketID) && count($basketID) > 0) {
            echo "OK";
        }

    }
?>