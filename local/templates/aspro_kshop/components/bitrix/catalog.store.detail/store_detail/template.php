<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<div class="left_block">
	<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.store.list",
		"stores_list",
		Array(
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"PHONE" => $arParams["PHONE"],
			"SCHEDULE" => $arParams["SCHEDULE"],
			"TITLE" => $arParams["TITLE"],
			"SET_TITLE" => $arParams["SET_TITLE"],
			"PATH_TO_ELEMENT" => $arParams["PATH_TO_ELEMENT"],
			"PATH_TO_LISTSTORES" => $arParams["PATH_TO_LISTSTORES"],
			"MAP_TYPE" => $arParams["MAP_TYPE"],
			"CURRENT_STORE" => $arResult["ID"],
		),	$component
	);?>
</div>
<div class="right_block clearfix store_map">
	<?
		if(($arResult["GPS_S"]!=0) && ($arResult["GPS_N"]!=0))
		{
			$gpsN=substr(doubleval($arResult["GPS_N"]),0,15);
			$gpsS=substr(doubleval($arResult["GPS_S"]),0,15);
			$arPlacemarks[]=array("LON"=>$gpsS,"LAT"=>$gpsN,"TEXT"=>$arResult["TITLE"]);
		}
		if($arResult["MAP"]==0)
		{
			$APPLICATION->IncludeComponent("bitrix:map.yandex.view", "map", array(
				"INIT_MAP_TYPE" => "MAP",
				"MAP_DATA" => serialize(array("yandex_lat"=>$gpsN,"yandex_lon"=>$gpsS,"yandex_scale"=>16,"PLACEMARKS" => $arPlacemarks)),
				"MAP_WIDTH" => "100%",
				"MAP_HEIGHT" => "310",
				"CONTROLS" => array(0 => "ZOOM",),
				"OPTIONS" => array(0 => "ENABLE_SCROLL_ZOOM", 1 => "ENABLE_DBLCLICK_ZOOM", 2 => "ENABLE_DRAGGING",	),
				"MAP_ID" => ""
			), false );
		} else {
			$APPLICATION->IncludeComponent("bitrix:map.google.view", "map", array(
				"INIT_MAP_TYPE" => "MAP",
				"MAP_DATA" => serialize(array("google_lat"=>$gpsN,"google_lon"=>$gpsS,"google_scale"=>16,"PLACEMARKS" => $arPlacemarks)),
				"MAP_WIDTH" => "100%",
				"MAP_HEIGHT" => "310",
				"CONTROLS" => array(0 => "ZOOM",),
				"OPTIONS" => array(0 => "ENABLE_SCROLL_ZOOM", 1 => "ENABLE_DBLCLICK_ZOOM", 2 => "ENABLE_DRAGGING"),
				"MAP_ID" => ""
			), false );
		}
	?>
	<div class="store_description">
		<?if($arResult["DESCRIPTION"]):?>
			<div class="description"><?=$arResult["DESCRIPTION"]?></div>
		<?endif;?>
		<?if($arResult["ADDRESS"]):?>
			<span class="store_property address">
				<div class="title">
					<i></i><?=GetMessage("S_ADDRESS");?>
				</div>
				<div class="value"><?=$arResult["ADDRESS"]?></div>
			</span>
		<?endif;?>
		<?if($arResult["PHONE"]):?>
			<span class="store_property phone">
				<div class="title">
					<i></i><?=GetMessage("S_PHONE");?>
				</div>
				<div class="value"><?=$arResult["PHONE"]?></div>
			</span>
		<?endif;?>
		<? if($arResult["SCHEDULE"]):?>
			<span class="store_property schedule">
				<div class="title">
					<i></i><?=GetMessage("S_SCHEDULE");?>
				</div>
				<div class="value"><?=$arResult["SCHEDULE"]?></div>
			</span>
		<?endif;?>
		<? if($arResult["EMAIL"]):?>
			<span class="store_property email">
				<div class="title">
					<i></i><?=GetMessage("S_EMAIL");?>
				</div>
				<div class="value"><a href="mailto:<?=$arResult["EMAIL"]?>"><?=$arResult["EMAIL"]?></a></div>
			</span>
		<?endif;?>
	</div>
	<?if ($arResult["IMAGE_ID"]>0):?>
		<div class="stores_images">
			<h4><?=GetMessage("S_STORE_PHOTO")?></h4>
			<div class="store_image">
				<?$img_photo_small = CFile::ResizeImageGet( $arResult["IMAGE_ID"], array( "width" => 200, "height" => 200 ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);?>
				<?$img_photo_big = CFile::ResizeImageGet( $arResult["IMAGE_ID"], array( "width" => 800, "height" => 600 ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);?>
				<a class="fancy" href="<?=$img_photo_big["src"]?>">
					<img src="<?=$img_photo_small["src"]?>" alt="<?=$arResult["TITLE"]?>" title="<?=$arResult["TITLE"]?>" />
				</a>
			</div>
		</div>
	<?endif;?>
</div>
<?
$APPLICATION->AddChainItem($arResult["TITLE"], "");
?>