<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?if (!empty($arResult)){?>
	<ul class="left_menu">
		<?foreach($arResult as $arItem){ if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) continue;?>
			<?if($arItem["SELECTED"]){?><li class="current<?if ($arItem["PARAMS"]["class"]):?> <?=$arItem["PARAMS"]["class"]?><?endif;?>"><a href="<?=$arItem["LINK"]?>"><span><?=$arItem["TEXT"]?></span></a></li>
			<?}else{?> <li <?if ($arItem["PARAMS"]["class"]):?>class="<?=$arItem["PARAMS"]["class"]?>"<?endif;?>><a href="<?=$arItem["LINK"]?>"><span><?=$arItem["TEXT"]?></span><?if ($arItem["PARAMS"]["class"]=="exit"):?><i></i><?endif;?></a></li><?}?>
		<?}?>
	</ul>
<?}?>
<script>
	$("ul.left_menu li:not(.current)").click(function()
	{
		$(this).siblings().removeClass("current"); $(this).addClass("current");
	});
</script>


