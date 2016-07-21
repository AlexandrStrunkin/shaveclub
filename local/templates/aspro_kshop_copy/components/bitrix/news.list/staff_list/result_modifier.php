<?
$arSectionsID = $arSections = array();

foreach($arResult["ITEMS"] as $arItem){
	$arSectionsID[] = $arItem["IBLOCK_SECTION_ID"];
}
$arSectionsID = array_unique($arSectionsID);

if($arSectionsID){
	$res = CIBlockSection::GetList(array("SORT" => "ASC"), array("ID" => $arSectionsID, "ACTIVE" => "Y"), true, array("NAME", "ID"), false);
	while($arSection = $res->Fetch()){
		$arSections[] = $arSection;
	}

	foreach($arSections as $sectionKey => $arSection){
		foreach($arResult["ITEMS"] as $arItem){
			if($arItem["IBLOCK_SECTION_ID"] == $arSection["ID"]){
				$arSections[$sectionKey]["ITEMS"][] = $arItem;
			}
		}
	}
}
unset($arResult["ITEMS"]);
$arResult["SECTIONS"] = $arSections;
?>