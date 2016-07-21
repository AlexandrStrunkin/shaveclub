<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<div class="jobs_wrapp">
	<?foreach($arResult["ITEMS"] as $key => $arItem){
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
		<div class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<div class="name">
				<table cellspacing="0" cellpadding="0" border="0" width="100%"><tr>
					<td class="icon"><a<?=($key==0)?' class="opened"':""?>><span class="icon"><i></i></span></a></td><td class="title"><a<?=($key==0)?' class="opened"':""?>><span class="pseudo"><span class="item_title"><?=$arItem['NAME']?></span></span></a></td>
				<?if ($arItem["DISPLAY_PROPERTIES"]["SALARY"]):?>
					<td class="salary_wrapp"><div class="salary"><?=GetMessage("SALARY");?> <?=number_format($arItem["DISPLAY_PROPERTIES"]["SALARY"]["VALUE"], 0, "", " ");?></div></td>
				<?endif;?>
				</tr></table>
			</div>
			<div class="description_wrapp" <?if ($key==0):?>style="display: block;"<?endif;?>>
				<?if ($arItem['PREVIEW_TEXT']):?>
					<div class="description"><?=$arItem['PREVIEW_TEXT']?></div>
				<?elseif ($arItem['DETAIL_TEXT']): ?>
					<div class="description"><?=$arItem['DETAIL_TEXT']?></div>
				<?endif;?>
				<a class="button30 resume_send" jobs="<?=$arItem['NAME']?>">
					<span><?=GetMessage('SEND_RESUME')?></span>
				</a>
			</div>
		</div>
	<?}?>
	<?if($arParams["DISPLAY_BOTTOM_PAGER"]){?>
		<?=$arResult["NAV_STRING"]?>
	<?}?>
</div>
<script> $(document).ready(function() { $(".jobs_wrapp .name").click(function(){ $(this).find("a").toggleClass('opened').parents(".name").next().slideToggle(333); }); });</script>