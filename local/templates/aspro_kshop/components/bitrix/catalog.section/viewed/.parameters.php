<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arTemplateParametersParts = Array(
	"PARAMETERS" => Array(
		"VIEWED_COUNT" => Array(
			"NAME" => GetMessage("VIEWED_COUNT"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "5",
			"COLS" => 5,
			"PARENT" => "BASE",
		),
		"VIEWED_NAME" => Array(
			"NAME" => GetMessage("VIEWED_NAME"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" =>"Y",
			"PARENT" => "BASE",
		),
		"VIEWED_IMAGE" => Array(
			"NAME" => GetMessage("VIEWED_IMAGE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" =>"Y",
			"PARENT" => "BASE",
		),
		"VIEWED_PRICE" => Array(
			"NAME" => GetMessage("VIEWED_PRICE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" =>"Y",
			"PARENT" => "BASE",
		),
		"VIEWED_CANBUY" => Array(
			"NAME" => GetMessage("VIEWED_CANBUY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" =>"N",
			"PARENT" => "BASE",
		),
	)
);
?>