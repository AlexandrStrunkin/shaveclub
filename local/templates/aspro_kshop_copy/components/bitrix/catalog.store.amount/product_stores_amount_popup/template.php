<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<a href="#" class="close jqmClose"></a>
<div class="popup-intro">
	<div class="pop-up-title">
		<?if($arParams["MAIN_TITLE"]):?>
			<?=$APPLICATION->ConvertCharset($arParams["MAIN_TITLE"], "utf-8", SITE_CHARSET);?>
		<?else:?>
			<?=GetMessage("POPUP_TITLE")?>
		<?endif;?>
	</div>
	<div class="after-title">
		<span class="description-wrapp">
			<?=GetMessage("AMOUNT_DESCRIPTION")?>
		</span>
	</div>
</div>

<?if(strlen($arResult["ERROR_MESSAGE"]) > 0) ShowError($arResult["ERROR_MESSAGE"]);?>
<?if(count($arResult["STORES"]) > 0):?>
	<div class="form-wr">
		<div class="stores_block_wrap">
			<?foreach($arResult["STORES"] as $pid => $arProperty):?>
				<div class="stores_block">
					<span class="stores_text_wrapp">
					<a href="<?=$arProperty["URL"]?>"><?=$arProperty["TITLE"]?></a>
					<?if(isset($arProperty["PHONE"])):?><span class="store_phone">,&nbsp;<?=GetMessage('S_PHONE')?> <?=$arProperty["PHONE"]?></span><?endif;?>
					<?if(isset($arProperty["SCHEDULE"])):?><span>,&nbsp;<?=GetMessage('S_SCHEDULE')?>&nbsp;<?=$arProperty["SCHEDULE"]?></span><?endif;?>
				</span>
				<?
				$totalCount = CKShop::CheckTypeCount($arProperty["NUM_AMOUNT"]);
				$arQuantityData = CKShop::GetQuantityArray($totalCount, array('quantity-wrapp', 'quantity-indicators'));
				?>
				<?if(strlen($arQuantityData["TEXT"])):?>
					<?=$arQuantityData["HTML"]?>
				<?endif;?>
				</div>
			<?endforeach;?>
		</div>
	</div>
<?endif;?>