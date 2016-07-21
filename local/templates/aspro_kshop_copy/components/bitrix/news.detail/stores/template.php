<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?if( !empty($arResult["PROPERTIES"]["MAP"]["VALUE"]) ){?>
	<?$coord = explode(",", $arResult["PROPERTIES"]["MAP"]["VALUE"]);
	$map_data = serialize( 
		array( 
			'google_lat' => $coord[0], 
			'google_lon' => $coord[1],
			'google_scale' => 15, 
			'PLACEMARKS' => array( 
				array( 
					//'TEXT' => $arResult["NAME"],
					'LAT' => $coord[0],
					'LON' => $coord[1],
					'MARK' => "/bitrix/templates/aspro_kshop/images/map-marker.png"
				), 
			),
		));?> 
	<?$APPLICATION->IncludeComponent(
		"bitrix:map.google.view",
		"map",
		Array(
			"INIT_MAP_TYPE" => "ROADMAP",
			"MAP_DATA" => $map_data,
			"MAP_WIDTH" => "100%",
			"MAP_HEIGHT" => "290",
			"CONTROLS" => array("SMALL_ZOOM_CONTROL","TYPECONTROL","SCALELINE"),
			"OPTIONS" => array("ENABLE_DBLCLICK_ZOOM","ENABLE_DRAGGING","ENABLE_KEYBOARD"),
			"MAP_ID" => ""
		)
	);?>
<?}?>

<div class="store_description">
	<?if($arResult["PREVIEW_TEXT"]):?>
		<div class="description"><?=$arResult["PREVIEW_TEXT"]?></div>
	<?endif;?>
	<?if($arResult["DISPLAY_PROPERTIES"]["SCHEDULE"]["VALUE"]):?>
		<span class="store_property address">
			<div class="title">
				<i></i><?=GetMessage("ADDRESS");?>
			</div>
			<div class="value"><?=$arResult["DISPLAY_PROPERTIES"]["SCHEDULE"]["VALUE"]?></div>
		</span>
	<?endif;?>
	<?if($arResult["DISPLAY_PROPERTIES"]["SCHEDULE"]["VALUE"]):?>
		<span class="store_property phone">
			<div class="title">
				<i></i><?=GetMessage("SCHEDULE");?>
			</div>
			<div class="value"><?=$arResult["DISPLAY_PROPERTIES"]["SCHEDULE"]["VALUE"]?></div>
		</span>
	<?endif;?>
	<?if($arResult["DISPLAY_PROPERTIES"]["PHONE"]["VALUE"]):?>
		<span class="store_property phone">
			<div class="title">
				<i></i><?=GetMessage("PHONES");?>
			</div>
			<div class="value"><?=$arResult["DISPLAY_PROPERTIES"]["PHONE"]["VALUE"]?></div>
		</span>
	<?endif;?>
	<? if($arResult["DISPLAY_PROPERTIES"]["EMAIL"]["VALUE"]):?>
		<span class="store_property email">
			<div class="title">
				<i></i><?=GetMessage("EMAIL");?>
			</div>
			<div class="value"><a href="mailto:<?=$arResult["DISPLAY_PROPERTIES"]["EMAIL"]["VALUE"]?>"><?=$arResult["DISPLAY_PROPERTIES"]["EMAIL"]["VALUE"]?></a></div>
		</span>
	<?endif;?>
	<?if ($arResult["DETAIL_TEXT"]):?>
		<div style="clear:both;"></div>
		<p><?=$arResult["DETAIL_TEXT"]?></p>
	<?endif;?>
</div>

<?if ($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"]):?>
	<div class="stores_images multiple">
		<h4><?=GetMessage("S_STORE_PHOTO")?></h4>
		<?foreach( $arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE_SMALL"] as $key => $arPhoto ){?>
			<div class="store_image">
				<a class="fancy" href="<?=$arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"][$key]["SRC"]?>">
					<img src="<?=$arPhoto["SRC"]?>" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>" />
				</a>
			</div>
		<?}?>
	</div>
<?endif;?>
