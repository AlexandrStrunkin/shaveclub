<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	if (!empty($_COOKIE["KSHOP_internal_sections_list_OPENED"])) { 
		if ($_COOKIE["KSHOP_internal_sections_list_OPENED"]=="true") { $_SESSION["KSHOP_internal_sections_list_OPENED"] = true; }
			else { $_SESSION["KSHOP_internal_sections_list_OPENED"] = false;}
		setcookie ($_COOKIE["KSHOP_internal_sections_list_OPENED"], "", time() - 3600);
		unset ($_COOKIE["KSHOP_internal_sections_list_OPENED"]);
	} else { $_SESSION["KSHOP_internal_sections_list_OPENED"] = true;}	
?>


<div class="internal_sections_list">
	<div class="title"><a href="<?=$arResult["LIST_PAGE_URL"]?>"><?=GetMessage("CATALOG_TITLE");?></a><span class="hider<?=($_SESSION["KSHOP_internal_sections_list_OPENED"]? " opened": "")?>"></span></div>
	<ul class="sections_list_wrapp"<?=($_SESSION["KSHOP_internal_sections_list_OPENED"]?'':' style="display: none"')?>>
		<?foreach($arResult["SECTIONS_TREE"] as $arItems){
			$this->AddEditAction($arItems['ID'], $arItems['EDIT_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItems['ID'], $arItems['DELETE_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>		
			<li class="item<?=($arItems["SELECTED"] ? " cur" : "")?>" id="<?=$this->GetEditAreaId($arItems['ID']);?>" data-id="<?=$arItems['ID']?>">
				<a href="<?=$arItems["SECTION_PAGE_URL"]?>"><span><?=$arItems["NAME"]?></span></a>
				<?if (count($arItems["SECTIONS"])):?>
					<? $depth3 = false; foreach ($arItems["SECTIONS"] as $i){if (is_array($i["SECTIONS"])&&!empty($i["SECTIONS"])){$depth3=true; break;}}?>
					<div class="child_container">
						<div class="child_wrapp<?=($depth3?" depth3 clearfix":"")?>">
							<i class="triangle"></i>
							<?if (count($arItems["SECTIONS"])):?>
								<ul class="child">
									<?foreach ($arItems["SECTIONS"] as $key => $arSection):?>
										<?if (count($arSection["SECTIONS"])):?>
											<li class="depth3">
												<a class="menu_title" href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection["NAME"]?></a>
												<?$j = 1;?>
												<?foreach( $arSection["SECTIONS"] as $arSubItem ){?>
													<a class="menu_item<?=($arSubItem["SELECTED"] ? " cur" : "")?>" data-id="<?=$arSubItem['ID']?>" href="<?=$arSubItem["SECTION_PAGE_URL"]?>"><?=$arSubItem["NAME"]?></a>
												<?}?>
											</li>
										<?else:?>
											<li  class="menu_item<?=($arSection["SELECTED"] ? " cur" : "")?>" data-id="<?=$arSection['ID']?>"><a href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection["NAME"]?></a></li>
										<?endif;?>
									<?endforeach;?>
								</ul>
							<?endif;?>
						</div>
					</div>
				<?endif;?>
			</li>
		<?}?>
	</ul>
</div>
<script>
	$(".internal_sections_list").ready(function() 
	{ 
		$(".internal_sections_list .siblings_wrapp li:not(.cur)").click(function() { $(this).addClass("cur").siblings().removeClass("cur"); }); 	
		$(".internal_sections_list .title .hider").click(function() 
		{ 
			$(this).toggleClass("opened")
			$(".internal_sections_list .sections_list_wrapp").slideToggle(200); 
			$.cookie.json = true;			
			$.cookie("KSHOP_internal_sections_list_OPENED", $(this).hasClass("opened"));
		});
	});
</script>