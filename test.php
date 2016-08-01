<?php
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	if(!$USER->IsAdmin())
		exit('ERROR');
	
    CModule::IncludeModule("iblock");
    CModule::IncludeModule("sale");
    CModule::IncludeModule("main");
    CModule::IncludeModule("catalog");
    CModule::IncludeModule("epages.pickpoint");

	
?>