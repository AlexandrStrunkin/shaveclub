<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if(strlen($arResult["ERROR_MESSAGE"])>0) ShowError($arResult["ERROR_MESSAGE"]);?>	
<?if(is_array($arResult["STORES"]) && !empty($arResult["STORES"])):?>
	<ul class="left_menu stores_list">
		<?foreach($arResult["STORES"] as $pid=>$arProperty):?>
			<li <?if ((intval($arParams["CURRENT_STORE"])==$arProperty["ID"])||(!$arParams["CURRENT_STORE"]&&($pid==0))):?>class="current"<?endif;?>>				
				<a <?if ((!$arParams["CURRENT_STORE"])||($arParams["CURRENT_STORE"]!=$arProperty["ID"])):?>href="<?=$arProperty["URL"]?>"<?endif;?>><span><?=$arProperty["TITLE"]?></span></a>
			</li>
		<?endforeach;?>
	</ul>
<?endif;?>
<script>
	$(document).ready(function()
	{
		$("ul.stores_list a").click(function()
		{
			if (!$(this).parents("li").is(".current"))
			{
				$(this).parents(".stores_list").find("li").removeClass("current");
				$(this).parents("li").addClass("current");
			}
		});
	});
</script>