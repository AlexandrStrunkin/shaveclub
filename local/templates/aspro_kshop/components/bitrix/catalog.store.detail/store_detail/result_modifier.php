<?
	$arSelect = array("ID", "EMAIL");
	$dbProps = CCatalogStore::GetList(array('ID' => 'ASC'),array('ID' => $arResult["ID"], 'ACTIVE' => 'Y'),false,false,array());	
	$res = $dbProps->GetNext();		
	if ($res["EMAIL"]) { $arResult["EMAIL"] = $res["EMAIL"]; }
?>