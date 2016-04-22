<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?if( !empty($arResult["ITEMS"]) ):?>
	<div class="question-list">
		<?
		foreach( $arResult["ITEMS"] as $key => $arItem ):
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<div class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<div class="q<?if ($key==0):?> op<?endif;?>"><a href=""><i class="ic"><b></b></i><span><?=$arItem["NAME"]?></span></a></div>
				<div class="ans"<?if ($key==0):?> style="display: block;"<?endif;?>>
					<i class="arr"></i>
					<?=$arItem["DETAIL_TEXT"]?>
				</div>						
			</div>
		<?endforeach;?>	
	</div>
	<?if( $arParams["DISPLAY_BOTTOM_PAGER"] ){?>
		<?=$arResult["NAV_STRING"]?>
	<?}?>
<?endif;?>

<script>
	$('.question-list .q a').live('click', function(e)
	{
		e.preventDefault();
		$(this).parents(".q").toggleClass('op').next(".ans").slideToggle(200);
	});
</script>
