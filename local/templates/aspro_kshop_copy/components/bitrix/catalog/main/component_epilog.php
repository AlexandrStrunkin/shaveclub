<?if ($arParams["USE_COMPARE"]=="Y"){?>
	<div id="compare_preview_content">
		<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.compare.list", 
	"compare_preview", 
	array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"NAME" => "CATALOG_COMPARE_LIST",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000",
		"COMPARE_URL" => SITE_DIR."catalog/compare.php",
		"COMPONENT_TEMPLATE" => "compare_preview",
		"DETAIL_URL" => "",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id"
	),
	false
);?>
	</div>
<?}?>
<script> 
	if ($("#compare_preview_content").length && $("#compare_small").length) 
	{ 
		$("#compare_small").html($("#compare_preview_content").html()); 
		$("#compare_preview_content").empty(); 
	}  
</script>

