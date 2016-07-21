<?
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	/** @var array $arCurrentValues */
	/** @global CUserTypeManager $USER_FIELD_MANAGER */
	global $USER_FIELD_MANAGER;
	CModule::IncludeModule('iblock');

	$arSort = CIBlockParameters::GetElementSortFields(
		array('SHOWS', 'SORT', 'TIMESTAMP_X', 'NAME', 'ID', 'ACTIVE_FROM', 'ACTIVE_TO'),
		array('KEY_LOWERCASE' => 'Y')
	);

	$arIBlocks=Array();
	$db_iblock = CIBlock::GetList(Array("SORT"=>"ASC"), Array("SITE_ID"=>$_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_BANNERS_TYPE"]!="-"?$arCurrentValues["IBLOCK_BANNERS_TYPE"]:"")));
	while($arRes = $db_iblock->Fetch()) $arIBlocks[$arRes["ID"]] = $arRes["NAME"];
	
	$arTypes = array();
	if ($arCurrentValues["IBLOCK_BANNERS_TYPE_ID"])
	{
		$rsTypes=CIBlockElement::GetList(array(), array("IBLOCK_ID"=>$arCurrentValues["IBLOCK_BANNERS_TYPE_ID"], "ACTIVE" =>"Y"), false, false, array("ID", "IBLOCK_ID", "NAME", "CODE"));
		while($arr=$rsTypes->Fetch()) $arTypes[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
	}
	$arTypesEx = CIBlockParameters::GetIBlockTypes(Array("-"=>" "));
	
	
	$arPrice = array();
	if (CModule::IncludeModule("catalog"))
	{
		$arSort = array_merge($arSort, CCatalogIBlockParameters::GetCatalogSortFields());
		$rsPrice=CCatalogGroup::GetList($v1="sort", $v2="asc");
		while($arr=$rsPrice->Fetch()) $arPrice[$arr["NAME"]] = "[".$arr["NAME"]."] ".$arr["NAME_LANG"];
	} else {$arPrice = $arProperty_N;}
	$arPrice  = array_merge(array("MINIMUM_PRICE"=>GetMessage("SORT_PRICES_MINIMUM_PRICE"), "MAXIMUM_PRICE"=>GetMessage("SORT_PRICES_MAXIMUM_PRICE")), $arPrice);


	$arUserFields_S = array();
	$arUserFields_E = array();
	$arUserFields = $USER_FIELD_MANAGER->GetUserFields("IBLOCK_".$arCurrentValues["IBLOCK_ID"]."_SECTION");
	foreach($arUserFields as $FIELD_NAME=>$arUserField) {
		if($arUserField["USER_TYPE"]["BASE_TYPE"]=="enum")
			{ $arUserFields_E[$FIELD_NAME] = $arUserField["LIST_COLUMN_LABEL"]? $arUserField["LIST_COLUMN_LABEL"]: $FIELD_NAME; }
		if($arUserField["USER_TYPE"]["BASE_TYPE"]=="string")
			{ $arUserFields_S[$FIELD_NAME] = $arUserField["LIST_COLUMN_LABEL"]? $arUserField["LIST_COLUMN_LABEL"]: $FIELD_NAME; }
	}	

	$arTemplateParametersParts = array();

	$arTemplateParametersParts[] = array(
		"IBLOCK_STOCK_ID" => Array(
			"NAME" => GetMessage("IBLOCK_STOCK_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),	
		"SHOW_MEASURE" => Array(
				"NAME" => GetMessage("SHOW_MEASURE"),
				"TYPE" => "CHECKBOX",
				"DEFAULT" => "N",
		),
		"SORT_BUTTONS" => Array(
			"SORT" => 100,
			"NAME" => GetMessage("SORT_BUTTONS"),
			"TYPE" => "LIST",
			"VALUES" => array("POPULARITY"=>GetMessage("SORT_BUTTONS_POPULARITY"), "NAME"=>GetMessage("SORT_BUTTONS_NAME"), "PRICE"=>GetMessage("SORT_BUTTONS_PRICE"), "QUANTITY"=>GetMessage("SORT_BUTTONS_QUANTITY")),
			"DEFAULT" => array("POPULARITY", "NAME", "PRICE"),
			"PARENT" => "LIST_SETTINGS",
			"TYPE" => "LIST",
			"REFRESH" => "Y",
			"MULTIPLE" => "Y",
		),
		"IBLOCK_BANNERS_TYPE" => Array(
			"SORT" => 100,
			"NAME" => GetMessage("IBLOCK_BANNERS_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arTypesEx,
			"DEFAULT" => "aspro_kshop_adv",
			"TYPE" => "LIST",
			"REFRESH" => "Y",
			"MULTIPLE" => "N",
		),
		"IBLOCK_BANNERS_ID" => Array(
			"SORT" => 100,
			"NAME" => GetMessage("IBLOCK_BANNERS_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"DEFAULT" => '',
			"TYPE" => "LIST",
			"REFRESH" => "Y",
			"MULTIPLE" => "N",
		),
		"IBLOCK_BANNERS_TYPE_ID" => Array(
			"SORT" => 100,
			"NAME" => GetMessage("IBLOCK_BANNERS_TYPE_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"DEFAULT" => '',
			"TYPE" => "LIST",
			"REFRESH" => "Y",
			"MULTIPLE" => "N",
		),
		"IBLOCK_SMALL_BANNERS_TYPE_ID" => Array(
			"SORT" => 100,
			"NAME" => GetMessage("IBLOCK_SMALL_BANNERS_TYPE_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arTypes,
		),
	);
		
		
	if (in_array("PRICE", $arCurrentValues["SORT_BUTTONS"])){
		$arTemplateParametersParts[]["SORT_PRICES"] = Array(
			"SORT"=>200,
			"NAME" => GetMessage("SORT_PRICES"),
			"TYPE" => "LIST",
			"VALUES" => $arPrice,
			"DEFAULT" => array("MINIMUM_PRICE"),
			"PARENT" => "LIST_SETTINGS",
			"MULTIPLE" => "N",
		);
	}	

	$arTemplateParametersParts[] = array(	
		"DEFAULT_LIST_TEMPLATE" => Array(
				"NAME" => GetMessage("DEFAULT_LIST_TEMPLATE"),
				"TYPE" => "LIST",
				"VALUES" => array("block"=>GetMessage("DEFAULT_LIST_TEMPLATE_BLOCK"), "list"=>GetMessage("DEFAULT_LIST_TEMPLATE_LIST"), "table"=>GetMessage("DEFAULT_LIST_TEMPLATE_TABLE")),
				"DEFAULT" => "list",
				"PARENT" => "LIST_SETTINGS",
		),
		"SECTION_DISPLAY_PROPERTY" => Array(
				"NAME" => GetMessage("SECTION_DISPLAY_PROPERTY"),
				"TYPE" => "LIST",
				"VALUES" => $arUserFields_E,
				"DEFAULT" => "list",
				"MULTIPLE" => "N",
				"PARENT" => "LIST_SETTINGS",
		),
		"USE_RATING" => array(
				"NAME" => GetMessage("USE_RATING"),
				"TYPE" => "CHECKBOX",
				"DEFAULT" => "Y",
		),
		"LIST_DISPLAY_POPUP_IMAGE" => array(
			"NAME" => GetMessage("LIST_DISPLAY_POPUP_IMAGE"),
			"PARENT" => "LIST_SETTINGS",
			"TYPE" => "CHECKBOX",
			"MULTIPLE" => "N",
			"ADDITIONAL_VALUES" => "N",
			"DEFAULT" => "Y",
		),
		"DISPLAY_WISH_BUTTONS" => array(
			"NAME" => GetMessage("DISPLAY_WISH_BUTTONS"),
			"TYPE" => "CHECKBOX",
			"MULTIPLE" => "N",
			"ADDITIONAL_VALUES" => "N",
			"DEFAULT" => "Y",
		),
		"DEFAULT_COUNT" => array(
			"NAME" => GetMessage("DEFAULT_COUNT"),
			"TYPE" => "STRING",
			"DEFAULT" => "1",
		),
		"PROPERTIES_DISPLAY_LOCATION" => Array(
			"NAME" => GetMessage("PROPERTIES_DISPLAY_LOCATION"),
			"TYPE" => "LIST",
			"VALUES" => array("DESCRIPTION"=>GetMessage("PROPERTIES_DISPLAY_LOCATION_DESCRIPTION"), "TAB"=>GetMessage("PROPERTIES_DISPLAY_LOCATION_TAB")),
			"DEFAULT" => "DESCRIPTION",
			"PARENT" => "DETAIL_SETTINGS",
		),
		"SHOW_BRAND_PICTURE" => Array(
				"NAME" => GetMessage("SHOW_BRAND_PICTURE"),
				"TYPE" => "CHECKBOX",
				"DEFAULT" => "Y",
				"PARENT" => "DETAIL_SETTINGS",
		),
		"SHOW_ASK_BLOCK" => Array(
				"NAME" => GetMessage("SHOW_ASK_BLOCK"),
				"TYPE" => "CHECKBOX",
				"DEFAULT" => "Y",
				"PARENT" => "DETAIL_SETTINGS",
		),
		"ASK_FORM_ID" => Array(
				"NAME" => GetMessage("ASK_FORM_ID"),
				"TYPE" => "STRING",
				"DEFAULT" => "",
				"PARENT" => "DETAIL_SETTINGS",
		),
		"SHOW_ADDITIONAL_TAB" => Array(
			"NAME" => GetMessage("SHOW_ADDITIONAL_TAB"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"PARENT" => "DETAIL_SETTINGS",
		),
		"SHOW_HINTS" => Array(
			"NAME" => GetMessage("SHOW_HINTS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		"PROPERTIES_DISPLAY_TYPE" => Array(
			"NAME" => GetMessage("PROPERTIES_DISPLAY_TYPE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => array("BLOCK"=>GetMessage("PROPERTIES_DISPLAY_TYPE_BLOCK"), "TABLE"=>GetMessage("PROPERTIES_DISPLAY_TYPE_TABLE")),
			"DEFAULT" => "BLOCK",
			"PARENT" => "DETAIL_SETTINGS",
		),	

	);

	$arTemplateParametersParts[]["SECTIONS_LIST_PREVIEW_PROPERTY"] = Array(
		"NAME" => GetMessage("SHOW_SECTION_PREVIEW_PROPERTY"),
		"VALUES" => array_merge(array("DESCRIPTION"=>GetMessage("SHOW_SECTION_PREVIEW_PROPERTY_DESCRIPTION")), $arUserFields_S),
		"TYPE" => "LIST",
		"MULTIPLE" => "N",
		"DEFAULT" => "DESCRIPTION",
		"PARENT" => "SECTIONS_SETTINGS",
	);	

	$arTemplateParametersParts[]["SECTION_PREVIEW_PROPERTY"] = Array(
		"NAME" => GetMessage("SHOW_SECTION_PREVIEW_PROPERTY"),
		"VALUES" => array_merge(array("DESCRIPTION"=>GetMessage("SHOW_SECTION_PREVIEW_PROPERTY_DESCRIPTION")), $arUserFields_S),
		"TYPE" => "LIST",
		"MULTIPLE" => "N",
		"DEFAULT" => "DESCRIPTION",
		"PARENT" => "LIST_SETTINGS");
	
	
	$arTemplateParametersParts[] = Array(
		"SHOW_SECTION_LIST_PICTURES" => Array(
			"NAME" => GetMessage("SHOW_SECTION_PICTURES"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"REFRESH" => "Y",
			"PARENT" => "SECTIONS_SETTINGS",
		),
		"SHOW_SECTION_PICTURES" => Array(
			"NAME" => GetMessage("SHOW_SECTION_PICTURES"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"REFRESH" => "Y",
			"PARENT" => "LIST_SETTINGS",
		),
		"SHOW_SECTION_SIBLINGS" => Array(
			"NAME" => GetMessage("SHOW_SECTION_SIBLINGS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "N",
			"PARENT" => "LIST_SETTINGS",
		),
		"SHOW_KIT_PARTS" => Array(
			"NAME" => GetMessage("SHOW_KIT_PARTS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "N",
			"PARENT" => "DETAIL_SETTINGS",
		),
		"SHOW_KIT_PARTS_PRICES" => Array(
			"NAME" => GetMessage("SHOW_KIT_PARTS_PRICES"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "N",
			"PARENT" => "DETAIL_SETTINGS",
		),
	);
		

	//merge parameters to one array 
	$arTemplateParameters = array();
	foreach($arTemplateParametersParts as $i => $part) { $arTemplateParameters = array_merge($arTemplateParameters, $part); }
?>
