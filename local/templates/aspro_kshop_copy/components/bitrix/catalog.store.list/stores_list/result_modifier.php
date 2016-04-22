<?
	foreach ($arResult["STORES"] as $key=>$value)
	{
		$arSelect = array("ID", "TITLE", "ADDRESS");
		$dbProps = CCatalogStore::GetList(array('ID' => 'ASC'),array('ID' => $value["ID"], 'ACTIVE' => 'Y'),false,false,array());	
		$res = $dbProps->GetNext();
		
		
		//var_dump($res);
		
		$arResult["STORES"][$key]["TITLE"] = $res["TITLE"];
		$arResult["STORES"][$key]["ADDRESS"] = $res["ADDRESS"];
	}
	
	$arSelect = array("ID", "TITLE", "ADDRESS", "DESCRIPTION", "GPS_N", "GPS_S", "IMAGE_ID", "PHONE", "SCHEDULE", );
	$dbProps = CCatalogStore::GetList(array('ID' => 'ASC'),array('ID' => $arResult["STORES"][0]['ID'], 'ACTIVE' => 'Y'),false,false,$arSelect);	
	$res = $dbProps->GetNext();
	$arResult["CURRENT_STORE"] = $res;
?>