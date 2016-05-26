<?

foreach($arResult["ORDERS"] as $arOrder){
if (IntVal($arOrder["ORDER"]["PAY_SYSTEM_ID"]) > 0)
    {
        $arPaySys = CSalePaySystem::GetByID($arOrder["ORDER"]["PAY_SYSTEM_ID"], $arOrder["ORDER"]["PERSON_TYPE_ID"]);
        $arResult["PAY_SYSTEM"] = $arPaySys;
        $arResult["PAY_SYSTEM"]["NAME"] = htmlspecialcharsEx($arResult["PAY_SYSTEM"]["NAME"]);
    }

if ($arOrder["ORDER"]["PAY_SYSTEM_ID"] > 0)
        {
            $dbPaySysAction = CSalePaySystemAction::GetList(
                    array(),
                    array(
                            "PAY_SYSTEM_ID" => $arOrder["ORDER"]["PAY_SYSTEM_ID"],
                            "PERSON_TYPE_ID" => $arOrder["ORDER"]["PERSON_TYPE_ID"]
                        ),
                    false,
                    false,
                    array("NAME", "ACTION_FILE", "NEW_WINDOW", "PARAMS", "ENCODING")
                );
            if ($arPaySysAction = $dbPaySysAction->Fetch())
            {
                if (strlen($arPaySysAction["ACTION_FILE"]) > 0)
                {
                    $arResult["CAN_REPAY"] = "Y";
                    if ($arPaySysAction["NEW_WINDOW"] == "Y")
                    {
                        $arResult["PAY_SYSTEM"]["PSA_ACTION_FILE"] = htmlspecialcharsbx($arParams["PATH_TO_PAYMENT"]).'?ORDER_ID='.urlencode(urlencode($arOrder["ACCOUNT_NUMBER"]));
                    }
                    else
                    {
                        CSalePaySystemAction::InitParamArrays($arOrder["ORDER"], $arOrder["ORDER"]["ID"], $arPaySysAction["PARAMS"]);

                        $pathToAction = $_SERVER["DOCUMENT_ROOT"].$arPaySysAction["ACTION_FILE"];
                        $pathToAction = str_replace("\\", "/", $pathToAction);
                        while (substr($pathToAction, strlen($pathToAction) - 1, 1) == "/")
                            $pathToAction = substr($pathToAction, 0, strlen($pathToAction) - 1);
                        if (file_exists($pathToAction))
                        {
                            if (is_dir($pathToAction) && file_exists($pathToAction."/payment.php"))
                                $pathToAction .= "/payment.php";
                            $arResult["PAY_SYSTEM"]["PSA_ACTION_FILE"] = $pathToAction;
                        }
                        if(strlen($arPaySysAction["ENCODING"]) > 0)
                        {
                            define("BX_SALE_ENCODING", $arPaySysAction["ENCODING"]);
                            AddEventHandler("main", "OnEndBufferContent", "ChangeEncoding");
                            function ChangeEncoding($content)
                            {
                                global $APPLICATION;
                                header("Content-Type: text/html; charset=".BX_SALE_ENCODING);
                                $content = $APPLICATION->ConvertCharset($content, SITE_CHARSET, BX_SALE_ENCODING);
                                $content = str_replace("charset=".SITE_CHARSET, "charset=".BX_SALE_ENCODING, $content);
                            }
                        }

                    }
                }
            }
        }
}
?>