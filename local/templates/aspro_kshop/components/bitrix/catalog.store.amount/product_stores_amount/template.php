<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(strlen($arResult["ERROR_MESSAGE"]) > 0){
	ShowError($arResult["ERROR_MESSAGE"]);
}
?>
<?if(count($arResult["STORES"]) > 0):?>
	<div class="stores_block_wrap">
		<?$empty_count=0;
		$count_stores=count($arResult["STORES"]);?>
		<?foreach($arResult["STORES"] as $pid => $arProperty):?>
			<?if(($arParams['SHOW_EMPTY_STORE'] == 'N' && $arProperty['NUM_AMOUNT'] > 0) || $arParams['SHOW_EMPTY_STORE'] == 'Y'){?>
				<div class="stores_block">
					<span class="stores_text_wrapp">
						<a href="<?=$arProperty["URL"]?>"><?=$arProperty["TITLE"]?></a>
						<?if(isset($arProperty["PHONE"])):?><span class="store_phone">,&nbsp;<?=GetMessage('S_PHONE')?> <?=$arProperty["PHONE"]?></span><?endif;?>
						<?if(isset($arProperty["SCHEDULE"])):?><span>,&nbsp;<?=GetMessage('S_SCHEDULE')?>&nbsp;<?=$arProperty["SCHEDULE"]?></span><?endif;?>
						<?if ($arParams['SHOW_GENERAL_STORE_INFORMATION'] == "Y"){?>
							<?=GetMessage('BALANCE')?>
						<?}?>
					</span>
					<?
					$totalCount = CKShop::CheckTypeCount($arProperty["NUM_AMOUNT"]);
					$arQuantityData = CKShop::GetQuantityArray($totalCount, array('quantity-wrapp', 'quantity-indicators'));
					?>
					<?if(strlen($arQuantityData["TEXT"])):?>
						<?=$arQuantityData["HTML"]?>
					<?endif;?>
				</div>
			<?}?>
		<?endforeach;?>
	</div>
<?endif;?>