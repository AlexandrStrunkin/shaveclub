<?
/********************************************************************************
 * Simple delivery handler.
 * It uses fixed delivery price for any location groups. Needs at least one group of locations to be configured.
 ********************************************************************************/
CModule::IncludeModule("sale");
CModule::IncludeModule("epages.pickpoint");

include(GetLangFileName(dirname(__FILE__).'/', '/delivery_pickpoint.php'));

class CDeliveryPickPoint
{
    function Init()
    {
        return array(
            "SID" => "pickpoint",
            "NAME" => GetMessage("PP_NAME"),
            "DESCRIPTION" => GetMessage("DESCRIPTION"),
            "DESCRIPTION_INNER" => GetMessage("DESCRIPTION_INNER"),
            "BASE_CURRENCY" => COption::GetOptionString("sale", "default_currency", "RUB"),
            "HANDLER" => __FILE__,
            "GETEXTRAINFOPARAMS" => "CDeliveryPickPoint::GetExtraInfoParams",
            "DBGETSETTINGS" => array(
                "CDeliveryPickPoint",
                "GetSettings"
            ),
            "DBSETSETTINGS" => array(
                "CDeliveryPickPoint",
                "SetSettings"
            ),
            "GETCONFIG" => array(
                "CDeliveryPickPoint",
                "GetConfig"
            ),

            "COMPABILITY" => array(
                "CDeliveryPickPoint",
                "Compability"
            ),
            "CALCULATOR" => array(
                "CDeliveryPickPoint",
                "Calculate"
            ),
            "PROFILES" => array(
                "postamat" => array(
                    "TITLE" => GetMessage("PICKPOINT_MAIN"),
                    "DESCRIPTION" => GetMessage("PICKPOINT_SMALL_DESCRIPTION"),

                    "RESTRICTIONS_WEIGHT" => array(0),
                    "RESTRICTIONS_SUM" => array(0),
                ),
            )
        );
    }

    function GetConfig()
    {
        $arConfig = array(
            "CONFIG_GROUPS" => array(
                "postamat" => GetMessage('PICKPOINT_MAIN'),
            ),
            "CONFIG" => array(),
        );

        return $arConfig;
    }

    function GetSettings($strSettings)
    {
        return unserialize($strSettings);
    }

    function SetSettings($arSettings)
    {
        foreach($arSettings as $key => $value)
        {
            if(strlen($value) > 0)
            {
                $arSettings[$key] = doubleval($value);
            }
            else
            {
                unset($arSettings[$key]);
            }
        }

        return serialize($arSettings);
    }

    function __GetLocationPrice($LOCATION_ID, $arConfig)
    {
        if(!CheckPickpointLicense(COption::GetOptionString("epages.pickpoint", "pp_ikn_number", "")))
        {
            return false;
        }

        $obCity = CPickpoint::SelectCityByBXID($LOCATION_ID);
        if($arCity = $obCity->Fetch())
        {
            if($arCity["ACTIVE"] == "Y")
            {
                return FloatVal($arCity["PRICE"]);
            }
        }

        return false;
    }

    function __GetPrice($arOrder)
    {
        return CPickpoint::Calculate($arOrder);
    }

    function Calculate($profile, $arConfig, $arOrder, $STEP, $TEMP = false)
    {
        return array(
            "RESULT" => "OK",
            "VALUE" => CDeliveryPickPoint::__GetPrice($arOrder)
        );
    }

    function Compability($arOrder, $arConfig)
    {
        return array('postamat');
    }
}

AddEventHandler(
    "sale",
    "onSaleDeliveryHandlersBuildList",
    array(
        'CDeliveryPickPoint',
        'Init'
    )
);
