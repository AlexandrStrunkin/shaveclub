<?
	$arElements = array();
	$arElementsID = array();
	foreach($arResult as $key => $val) { $arElementsID[] = $val["ID"]; }
	$db_res = CIBlockElement::GetList(Array("SORT"=>"ASC"),  Array("ID"=>$arElementsID), false, false, Array("ID", "IBLOCK_ID", "DETAIL_PAGE_URL"));
	while($arElement = $db_res->GetNext()) { foreach($arResult as $key => $val) { if ($arElement["ID"]==$val["ID"]) { $arResult[$key]["DETAIL_PAGE_URL"]=$arElement["DETAIL_PAGE_URL"]; } } }
?>