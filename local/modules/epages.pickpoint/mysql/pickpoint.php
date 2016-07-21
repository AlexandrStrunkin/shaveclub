<?
	class CAllPickpoint
	{
		function SelectCityByPPID($iPPID)
		{
			global $DB;
			return $DB->Query("SELECT * FROM `b_pp_city` WHERE PP_ID={$iPPID}");
		}
		function SelectCityByBXID($iBXID)
		{
			global $DB;
			return $DB->Query("SELECT * FROM `b_pp_city` WHERE BX_ID={$iBXID}");
		}
		function SelectZoneByID($zoneID)
		{
			global $DB;
			return $DB->Query("SELECT * FROM `b_pp_zone` WHERE ZONE_ID={$zoneID}");
		}
        function SelectZones()
		{
			global $DB;
			return $DB->Query("SELECT * FROM `b_pp_zone`");
		}
		function SetPPCity($iPPID, $arFields)
		{
			global $DB;
			if(!empty($arFields) && (intval($iPPID)>0))
			{
				//printr(CAddon::GetOrderBills($iOrderID));

				$obCity = CPickpoint::SelectCityByPPID($iPPID);

				//
				if(!$obCity->SelectedRowsCount())
				{
					$sQuery = "INSERT INTO `b_pp_city` (".implode(",",array_keys($arFields)).")
					VALUES('".implode("','",$arFields)."')";
				}
				else
				{
					$arSet = Array();
					unset($arFields["PP_ID"]);
					foreach($arFields as $sKey=>$sValue) $arSet[] = $sKey." = '{$sValue}'";
					$sQuery = "UPDATE `b_pp_city` SET ".implode(",",$arSet)." WHERE PP_ID = {$iPPID}";
				}
				$DB->Query($sQuery);
			}
		}

		function SetPPZone($zoneID, $arFields)
		{
			global $DB;
			if(!empty($arFields) && (intval($zoneID)>0))
			{
				//printr(CAddon::GetOrderBills($iOrderID));

				$obZone = CPickpoint::SelectZoneByID($zoneID);

				//
				if(!$obZone->SelectedRowsCount())
				{
				    $arFields["ZONE_ID"] = $zoneID;
					$sQuery = "INSERT INTO `b_pp_zone` (".implode(",",array_keys($arFields)).")
					VALUES('".implode("','",$arFields)."')";
				}
				else
				{
					$arSet = Array();
					unset($arFields["PP_ID"]);
					foreach($arFields as $sKey=>$sValue) $arSet[] = $sKey." = '{$sValue}'";
					$sQuery = "UPDATE `b_pp_zone` SET ".implode(",",$arSet)." WHERE ZONE_ID = {$zoneID}";
				}
				$DB->Query($sQuery);
			}
		}

		function AddOrderPostamat($arFields)
		{
			global $DB;
			if(IntVal($arFields["ORDER_ID"]) && strlen($arFields["POSTAMAT_ID"]))
			{
				$sQuery = "INSERT INTO `b_pp_order_postamat` (".implode(",",array_keys($arFields)).")
				VALUES('".implode("','",$arFields)."')";
				if($DB->Query($sQuery)) return true;
			}
			return false;
		}

		function SelectOrderPostamat($iOrderID = 0)
		{
			global $DB;
			return $DB->Query("SELECT * FROM `b_pp_order_postamat`".((IntVal($iOrderID)>0)?" WHERE ORDER_ID={$iOrderID}":"")." ORDER BY ID DESC");
		}
		function SetOrderInvoice($iOrderID = 0, $invoiceId=0)
		{
			global $DB;
            if(intval($iOrderID)>0 && intval($invoiceId)>0)
			{
                $sQuery = "UPDATE `b_pp_order_postamat` SET PP_INVOICE_ID = {$invoiceId} WHERE ORDER_ID={$iOrderID}";
                $DB->Query($sQuery);
            }

		}
        function SetOrderSettings($iOrderID = 0, $options="")
		{
			global $DB;
            if(intval($iOrderID)>0 && strlen($options)>0)
			{
                $sQuery = "UPDATE `b_pp_order_postamat` SET SETTINGS = '{$options}' WHERE ORDER_ID={$iOrderID}";
                $DB->Query($sQuery);
            }

		}
		function DeleteCities($arIDs = Array())
		{
			global $DB;
			$sIN = implode(",",$arIDs);
			$DB->Query("DELETE FROM `b_pp_city` WHERE PP_ID NOT IN ({$sIN})");
		}
		
		
	}

?>