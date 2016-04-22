<?
$resProperty = CIBlockPropertyEnum::GetList(array("SORT" => "ASC"), Array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "CODE" => "HIT"));
while($item = $resProperty->Fetch()){
	$arHitPropertyValues[$item["ID"]] = $item;
}

$arResult["SPECIALS_BLOCK"] = array();
$arResult["SPECIALS_BLOCK"]["HTML"] = "";
$arResult["SPECIALS_BLOCK"]["OPENED"] = null;
foreach($arResult["ITEMS"] as $key => $arItem)
{
	if( $arItem["CODE"] == "HIT")
	{
		foreach($arItem["VALUES"] as $k => $ar){
			$arResult["SPECIALS_BLOCK"]["HTML"] .= 
				'<div class="specials_'.strtolower($arHitPropertyValues[$k]["XML_ID"]).($ar["DISABLED"] ? ' disabled': '').'">
					<input type="checkbox" value="'.$ar["HTML_VALUE"].'" name="'.$ar["CONTROL_NAME"].'" 
				id="'.$ar["CONTROL_ID"].'"'.($ar["CHECKED"]?'checked="checked"':'').' onclick="smartFilter.click(this)"'.($ar["DISABLED"]?' disabled':'').' />
					<label for="'.$ar["CONTROL_ID"].'"><i class="icon"></i><span>'.$ar["VALUE"].'</span></label>
				</div>';
		}
	}
}
?>