<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
	CJSCore::Init(array("fx"));
		
	if (!empty($_COOKIE["KSHOP_FILTER_CLOSED"]))
	{
		$arCookies =  json_decode($_COOKIE["KSHOP_FILTER_CLOSED"]);	
		array_unique ($arCookies);		
		unset ($_COOKIE["KSHOP_FILTER_CLOSED"]);		
		setcookie ($_COOKIE["KSHOP_FILTER_CLOSED"], null, -1);			
		foreach($arCookies as $key => $value)
		{ foreach($arResult["ITEMS"] as $key=>$property) { if ($property["ID"]==$value) { $arResult["ITEMS"][$key]["OPENED"]="N"; } } }	
		if (in_array("specials", $arCookies)){ $arResult["SPECIALS_BLOCK"]["OPENED"]="N"; }
	}
	if (!empty($_COOKIE["KSHOP_FILTER_OPENED"]))
	{
		$arCookies =  json_decode($_COOKIE["KSHOP_FILTER_OPENED"]);	
		array_unique ($arCookies);		
		unset ($_COOKIE["KSHOP_FILTER_OPENED"]);		
		setcookie ($_COOKIE["KSHOP_FILTER_OPENED"], null, -1);		
		foreach($arCookies as $key => $value)
		{ foreach($arResult["ITEMS"] as $key=>$property) { if ($property["ID"]==$value) { $arResult["ITEMS"][$key]["OPENED"]="Y"; } } }	
		if (in_array("specials", $arCookies)){ $arResult["SPECIALS_BLOCK"]["OPENED"]="Y"; }		
	}
	$propsCount = 0;
	foreach($arResult["ITEMS"] as $key=>$arItem) 
	{
		if((isset($arItem["PRICE"]) && !(!$arItem["VALUES"]["MIN"]["VALUE"] || !$arItem["VALUES"]["MAX"]["VALUE"] || $arItem["VALUES"]["MIN"]["VALUE"] == $arItem["VALUES"]["MAX"]["VALUE"])) || (!isset($arItem["PRICE"])&&( $arItem["PROPERTY_TYPE"] == "N" && !empty($arItem["VALUES"]["MIN"]["VALUE"]) || $arItem["PROPERTY_TYPE"] != "N" && !empty($arItem["VALUES"]) )&& (!($arItem["PROPERTY_TYPE"]=="N"&&($arItem["VALUES"]["MIN"]["VALUE"]==$arItem["VALUES"]["MAX"]["VALUE"]))))) $propsCount++;
	}
?>
<?if ($propsCount){?>
	<div class="bx_filter_vertical">
		<div class="bx_filter_section m4">
			<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter">
				<?foreach($arResult["HIDDEN"] as $arItem):?><input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" /><?endforeach;?>
				
				<?$iOpened=0;$firstTitle=true;?>
				<?if (strlen($arResult["SPECIALS_BLOCK"]["HTML"])):?>
					<?if ($firstTitle){$firstTitle = false;} $iOpened++;?>
					<div class="bx_filter_container<?if($arResult["SPECIALS_BLOCK"]["OPENED"]=="Y" || ($iOpened<=3 && $arResult["SPECIALS_BLOCK"]["OPENED"]!="N")):?> active<?endif;?>" property_id="specials">						
						<div class="bx_filter_container_title no_border"><span class="name"><span><?=GetMessage("SPECIAL_BLOCK_TITLE")?></span></span><i class="arr"></i></div>
						<div class="bx_filter_block<?if($arResult["SPECIALS_BLOCK"]["OPENED"]=="Y" || ($iOpened<=3 && $arResult["SPECIALS_BLOCK"]["OPENED"]!="N")):?> active<?endif;?>"><?=$arResult["SPECIALS_BLOCK"]["HTML"]?></div>
						<span class="bx_filter_container_modef"></span>
					</div>
				<?endif;?>			
				
				<?foreach($arResult["ITEMS"] as $key=>$arItem):?>
					<?if(isset($arItem["PRICE"])){?>
						<?
							if (!$arItem["VALUES"]["MIN"]["VALUE"] || !$arItem["VALUES"]["MAX"]["VALUE"] || $arItem["VALUES"]["MIN"]["VALUE"] == $arItem["VALUES"]["MAX"]["VALUE"]) continue;
							$cur_min = !empty($arItem["VALUES"]["MIN"]["HTML_VALUE"]) ? floor($arItem["VALUES"]["MIN"]["HTML_VALUE"]) : floor($arItem["VALUES"]["MIN"]["VALUE"]);
							$cur_max = !empty($arItem["VALUES"]["MAX"]["HTML_VALUE"]) ? ceil($arItem["VALUES"]["MAX"]["HTML_VALUE"]) : ceil($arItem["VALUES"]["MAX"]["VALUE"]);
							if (!$cur_min) $cur_min = 0;
							if (!$cur_max) $cur_max = 50000;
							$iOpened++;
						?>
						<div class="bx_filter_container price<?if($arItem["OPENED"]=="Y" || ($iOpened<=3 && $arItem["OPENED"]!="N")):?> active<?endif;?>" property_id="<?=$arItem["ID"]?>">	
							<div class="bx_filter_container_title<?if($firstTitle):?> no_border<?endif;?>"><span class="name"><span><?=$arItem["NAME"]?></span></span><i class="arr"></i></div>
							<?if ($firstTitle){$firstTitle = false;}?>
							<div class="bx_filter_block<?if($arItem["OPENED"]=="Y" || ($iOpened<=3 && $arItem["OPENED"]!="N")):?> active<?endif;?>">
								<div class="bx_ui_slider_track_values">
									<div class="<?=$arItem["CODE"]?>_abs_min" style="display: none;"><?=floor($arItem["VALUES"]["MIN"]["VALUE"])?></div>
									<div class="<?=$arItem["CODE"]?>_abs_max" style="display: none;"><?=ceil($arItem["VALUES"]["MAX"]["VALUE"])?></div>
									<?=GetMessage('CT_BCSF_FILTER_FROM')?>&nbsp;<input
										class="min-price"
										type="text"
										name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
										id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>" 
										value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
										placeholder="<?=floor($cur_min);?>"
										size="5"
										onkeyup="smartFilter.keyup(this)"
									/>
									&nbsp;<?=GetMessage('CT_BCSF_FILTER_TO')?>&nbsp;
									<input
										class="max-price"
										type="text"
										name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
										id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
										value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
										placeholder="<?=ceil($cur_max);?>"
										size="5"
										onkeyup="smartFilter.keyup(this)"
									/>
								</div>
								<div class="bx_ui_slider_track" id="drag_track_<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>">
									<div class="bx_ui_slider_range" style="left: 0; right: 0%;"  id="drag_tracker_<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"></div>
									<a class="bx_ui_slider_handle left"  href="javascript:void(0)" style="left:0;" id="left_slider_<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"></a>
									<a class="bx_ui_slider_handle right" href="javascript:void(0)" style="right:0%;" id="right_slider_<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"></a>
								</div>
							</div>
							<span class="bx_filter_container_modef"></span>
						</div>

						<script type="text/javascript">
							var DoubleTrackBar<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?> = new cDoubleTrackBar('drag_track_<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>', 'drag_tracker_<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>', 'left_slider_<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>', 'right_slider_<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>', {
								Min: parseFloat(<?=floor($arItem["VALUES"]["MIN"]["VALUE"]);?>),
								Max: parseFloat(<?=ceil($arItem["VALUES"]["MAX"]["VALUE"]);?>),
								MinInputId : BX('<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>'),
								MaxInputId : BX('<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>'),
								FingerOffset: 3,
								MinSpace: 1,
								RoundTo: 1
							});
						</script>
					<?}?>
				<?endforeach?>
				
				<?$i=0;?>		
				<?foreach($arResult["ITEMS"] as $arItem){
					if(!isset($arItem["PRICE"])&&( $arItem["PROPERTY_TYPE"] == "N" && !empty($arItem["VALUES"]["MIN"]["VALUE"]) || $arItem["PROPERTY_TYPE"] != "N" && !empty($arItem["VALUES"]) )&&
					(!($arItem["PROPERTY_TYPE"]=="N"&&($arItem["VALUES"]["MIN"]["VALUE"]==$arItem["VALUES"]["MAX"]["VALUE"])))){?>

						<?if(($arItem["CODE"]=="HIT")&&strlen($arResult["SPECIALS_BLOCK"]["HTML"])){
							continue;
						}
						elseif( $arItem["PROPERTY_TYPE"] == "N" ){
								$cur_min = !empty($arItem["VALUES"]["MIN"]["HTML_VALUE"]) ? floor($arItem["VALUES"]["MIN"]["HTML_VALUE"]) : floor($arItem["VALUES"]["MIN"]["VALUE"]);
								$cur_max = !empty($arItem["VALUES"]["MAX"]["HTML_VALUE"]) ? ceil($arItem["VALUES"]["MAX"]["HTML_VALUE"]) : ceil($arItem["VALUES"]["MAX"]["VALUE"]);
								if($arParams["SHOW_HINTS"]=="Y") { $prop = CIBlockProperty::GetByID($arItem["ID"], $arParams["IBLOCK_ID"])->GetNext(); }?>
								<?$iOpened++;?>
									
									<div class="bx_filter_container<?if($arItem["OPENED"]=="Y" || ($iOpened<=3 && $arItem["OPENED"]!="N")):?> active<?endif;?>" property_id="<?=$arItem["ID"]?>">
										<div class="bx_filter_container_title"><span class="name"><span><?=$arItem["NAME"]?></span></span><?if ($prop["HINT"]):?><span class="hint"><span class="hint_icon"><i>?</i></span><b class="triangle"></b><div class="tooltip"><a class="tooltip_close">&times;</a><?=$prop["HINT"]?></div></span><?endif;?><i class="arr"></i></div>
										<div class="bx_filter_block<?if($arItem["OPENED"]=="Y" || ($iOpened<=3 && $arItem["OPENED"]!="N")):?> active<?endif;?>">
											<div class="bx_ui_slider_track_values">
												<div class="<?=$arItem["CODE"]?>_abs_min" style="display: none;"><?=floor($arItem["VALUES"]["MIN"]["VALUE"])?></div>
												<div class="<?=$arItem["CODE"]?>_abs_max" style="display: none;"><?=ceil($arItem["VALUES"]["MAX"]["VALUE"])?></div>
												<?=GetMessage('CT_BCSF_FILTER_FROM')?>&nbsp;
												<input
													class="min-input"
													type="text"
													name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
													id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>" 
													value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
													placeholder="<?=floor($cur_min);?>"
													size="5"
													onkeyup="smartFilter.keyup(this)"
												/>
												&nbsp;<?=GetMessage('CT_BCSF_FILTER_TO')?>&nbsp;
												<input
													class="max-input"
													type="text"
													name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
													id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>" 
													value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
													placeholder="<?=ceil($cur_max);?>"
													size="5"
													onkeyup="smartFilter.keyup(this)"
												/>
											</div>
											<div class="bx_ui_slider_track" id="drag_track_<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>">
												<div class="bx_ui_slider_range" style="left: 0; right: 0%;"  id="drag_tracker_<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"></div>
												<a class="bx_ui_slider_handle left"  href="javascript:void(0)" style="left:0;" id="left_slider_<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"></a>
												<a class="bx_ui_slider_handle right" href="javascript:void(0)" style="right:0%;" id="right_slider_<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"></a>
											</div>
										</div>
										<span class="bx_filter_container_modef"></span>
									</div>
									
									<script type="text/javascript">
										var DoubleTrackBar<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?> = new cDoubleTrackBar('drag_track_<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>', 'drag_tracker_<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>', 'left_slider_<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>', 'right_slider_<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>', {
											Min: parseFloat(<?=floor($arItem["VALUES"]["MIN"]["VALUE"]);?>),
											Max: parseFloat(<?=ceil($arItem["VALUES"]["MAX"]["VALUE"]);?>),
											MinInputId : BX('<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>'),
											MaxInputId : BX('<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>'),
											FingerOffset: 4,
											MinSpace: 1,
											RoundTo: 1
										});
									</script>
							
								<?} elseif(!empty($arItem["VALUES"]) && !isset($arItem["PRICE"])){?>
									<?if($arItem["CODE"] == "HIT"){continue;}?>
									<? if($arParams["SHOW_HINTS"]=="Y") { $prop = CIBlockProperty::GetByID($arItem["ID"], $arParams["IBLOCK_ID"])->GetNext(); } ?>
									
									<?foreach($arItem["VALUES"] as $val => $ar):?>
										<? if ($ar["CHECKED"]) { $arItem["OPENED"]="Y"; } ?>
									<?endforeach;?>
									
									<?$iOpened++;?>
									<div class="bx_filter_container<?if($arItem["OPENED"]=="Y" || ($iOpened<=3 && $arItem["OPENED"]!="N")):?> active<?endif;?>" property_id="<?=$arItem["ID"]?>">
										<div class="bx_filter_container_title"><span class="name"><span><?=$arItem["NAME"]?></span></span><?if ($prop["HINT"]):?><span class="hint"><span class="hint_icon"><i>?</i></span><b class="triangle"></b><div class="tooltip"><a class="tooltip_close">&times;</a><?=$prop["HINT"]?></div></span><?endif;?><i class="arr"></i></div>
										<div class="bx_filter_block<?if($arItem["OPENED"]=="Y" || ($iOpened<=3 && $arItem["OPENED"]!="N")):?> active<?endif;?><?if(count($arItem["VALUES"])>6):?> scrollable<?endif;?>">
											<?foreach($arItem["VALUES"] as $val => $ar):?>
												<div class="<?echo $ar["DISABLED"] ? 'disabled': ''?>">
													<input
														type="checkbox"
														value="<?echo $ar["HTML_VALUE"]?>"
														name="<?echo $ar["CONTROL_NAME"]?>"
														id="<?echo $ar["CONTROL_ID"]?>"
														<?echo $ar["CHECKED"]? 'checked="checked"': ''?>
														onclick="smartFilter.click(this)"
														<?if ($ar["DISABLED"]):?>disabled<?endif?>
													/>
													<label for="<?echo $ar["CONTROL_ID"]?>"><?echo $ar["VALUE"];?></label>
												</div>
											<?endforeach;?>
										</div>
										<span class="bx_filter_container_modef"></span>
									</div>
								<?}?>
					<?}?>
				<?}?>
						
				<div style="clear: both;"></div>
				<div class="bx_filter_control_section">
					<div class="for_button">
					<button class="filter_button show" type="submit" id="set_filter" name="set_filter" value="<?=GetMessage("CT_BCSF_SET_FILTER")?>"><span><?=GetMessage("CT_BCSF_SET_FILTER")?></span></button>
					<button class="filter_button clear_filter" type="submit" id="del_filter" name="del_filter" value="<?=GetMessage("CT_BCSF_DEL_FILTER")?>" /><span><?=GetMessage("CT_BCSF_DEL_FILTER")?></span></button>
					</div>
					<div class="bx_filter_popup_result" id="modef" <?if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"';?> style="display: inline-block;">
						<i class="triangle"></i>
						<?echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
						<a href="<?echo $arResult["FILTER_URL"]?>"><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a>
					</div>
				</div>
			</form>
			<div style="clear: both;"></div>
		</div>
	</div>

	<script>
		var checkClosed = function(item)
		{
			$.cookie.json = true;
			var arClosed = $.cookie("KSHOP_FILTER_CLOSED");
			if (arClosed && typeof arClosed != "undefined")
			{
				if (typeof item != "undefined")
				{
					var propID = item.parents(".bx_filter_container").attr("property_id");
					var delIndex = $.inArray(propID, arClosed);
					if (delIndex >= 0) { arClosed.splice(delIndex,1);} else {arClosed.push(propID);}
				}
			}
			else
			{
				var arClosed = [];
				if (typeof item != "undefined")
				{
					item = $(item);
					var propID = item.parents(".bx_filter_container").attr("property_id");
					if (!item.parents(".bx_filter_container").is(".active")) { if (!$.inArray(propID, arClosed) >= 0) { arClosed.push(propID); } }
						else { if ($.inArray(propID, arClosed) >= 0) { arClosed.splice(delIndex,1); } } 
				}
			}
			$.cookie("KSHOP_FILTER_CLOSED", arClosed);
			return true;
		}

		var checkOpened = function(item)
		{
			
			$.cookie.json = true;
			var arOpened = $.cookie("KSHOP_FILTER_OPENED");
			if (arOpened && typeof arOpened != "undefined")
			{
				if (typeof item != "undefined")
				{
					var propID = item.parents(".bx_filter_container").attr("property_id");
					var delIndex = $.inArray(propID, arOpened);
					if (delIndex >= 0) { arOpened.splice(delIndex,1); checkClosed(item); } 
						else { arOpened.push(propID); checkClosed(item); }
				}
				else
				{
					$(".bx_filter_container").each(function() 
					{ 
						var propID = $(this).attr("property_id");	
						if ($(this).is(".active")) { if ($.inArray(propID, arOpened) < 0) { arOpened.push(propID); checkClosed(item); } } 
					});
				}	
			}
			else
			{
				var arOpened = [];
				if (typeof item != "undefined")
				{
					item = $(item);
					var propID = item.parents(".bx_filter_container").attr("property_id");
					if (item.parents(".bx_filter_container").is(".active")) { if (!$.inArray(propID, arOpened) >= 0) { arOpened.push(propID); checkClosed(item); }  }
						else { if ($.inArray(propID, arOpened) >= 0) { arOpened.splice(delIndex,1); checkClosed(item); } } 	
				}
				else
				{
					$(".bx_filter_container").each(function() 
					{ 
						var propID = $(this).attr("property_id");
						if ($(this).is(".active")) { if ($.inArray(propID, arOpened) < 0) { arOpened.push(propID); checkClosed(item); } } 
					});
				}
			}
			$.cookie("KSHOP_FILTER_OPENED", arOpened);
			return true;
		}
		checkOpened();
		
		var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>');
			
		$(".bx_filter_container_title").click( function()
		{
			if ($(this).parents(".bx_filter_container").hasClass("active")) { $(this).next(".bx_filter_block").slideUp(100); }
			else { $(this).next(".bx_filter_block").slideDown(200); }
			$(this).parents(".bx_filter_container").toggleClass("active");
			checkOpened($(this));
		});
		
		
		$(".hint .hint_icon").click(function(e)
		{
			var tooltipWrapp = $(this).parents(".hint");
			tooltipWrapp.click(function(e){e.stopPropagation();})
			if (tooltipWrapp.is(".active"))
			{
				tooltipWrapp.removeClass("active").find(".tooltip").slideUp(200); 
			}
			else
			{
				tooltipWrapp.addClass("active").find(".tooltip").slideDown(200);
				tooltipWrapp.find(".tooltip_close").click(function(e) { e.stopPropagation(); tooltipWrapp.removeClass("active").find(".tooltip").slideUp(100);});	
				$(document).click(function() { tooltipWrapp.removeClass("active").find(".tooltip").slideUp(100);});				
			}
		});	
	</script>
<?}?>