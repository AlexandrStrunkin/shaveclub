<?
	$arServiceTypes = Array(
		0=>"STD",
		1=>"STDCOD",
		2=>"PRIO",
		3=>"PRIOCOD"
	);
    $arServiceTypesCodes = Array(
        0=>10001,
        1=>10003,
        2=>10002,
        3=>10004
    );
	$arEnclosingTypes = Array(
		0=>"CUR",
		1=>"WIN",
		2=>"APTCON",
		3=>"APT"
	);
    $arEnclosingTypesCodes = Array(
        0=>101, //"CUR",
        1=>102,//"WIN",
        2=>103,//"APTCON",
        3=>104//"APT"
    );

	$arPayedServiceTypes = Array(1,3); // Доступные типы при оплате через PickPoint
	
	$arSizes = Array(
		"S"=>Array("NAME"=>"S","SIZE_X"=>15,"SIZE_Y"=>36,"SIZE_Z"=>60),
		"M"=>Array("NAME"=>"M","SIZE_X"=>20,"SIZE_Y"=>36,"SIZE_Z"=>60),
		"L"=>Array("NAME"=>"L","SIZE_X"=>36,"SIZE_Y"=>36,"SIZE_Z"=>60)
	
	);

	
	$arOptionDefaults = Array(
		"FIO"=>Array(
			"TYPE"=>"USER",
			"VALUE"=>"USER_FIO"
		),
		"ADDITIONAL_PHONES"=>Array(
			"TYPE"=>"USER",
			"VALUE"=>"PERSONAL_MOBILE"
		),
		"NUMBER_P"=>Array(
			"TYPE"=>"ORDER",
			"VALUE"=>"ID"
		),
		"EMAIL"=>Array(
			"TYPE"=>"USER",
			"VALUE"=>"EMAIL"
		)

	);	
?>