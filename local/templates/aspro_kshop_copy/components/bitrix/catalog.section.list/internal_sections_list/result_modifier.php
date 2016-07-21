<?
	global $KShopSectionID;
	foreach($arResult["SECTIONS"] as $i => $arSection){
		$arPointers[$arSection['ID']] = &$arResult["SECTIONS"][$i];
	}
	
	foreach($arResult["SECTIONS"] as $i => $arSection){
		$sid = $arSection['ID'];
		if(!$pid = $arSection['IBLOCK_SECTION_ID']){
			$arResult['SECTIONS_TREE'][] = &$arResult["SECTIONS"][$i];
		}
		$arResult["SECTIONS"][$i]['SELECTED'] = $arSection["ID"] == $KShopSectionID;
		$ii = 0;
		while($pid && $ii++ < 10){
			$arPointers[$pid]['SECTIONS'][$sid] = $arPointers[$sid];
			if($arPointers[$sid]['SELECTED']){
				$arPointers[$pid]['SELECTED'] = true;
			}
			$sid = $pid;
			$pid = $arPointers[$pid]['IBLOCK_SECTION_ID'];
		}
	}
?>