<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?  global $arBlockId;
if (!empty($arResult["ORDER"]))
{
	?>
    <? if ($arResult["ORDER"]["PAY_SYSTEM_ID"] == $arBlockId["PAY_SISTEM_ID"]) { ?>

    <? } else { ?>
        <b><?= GetMessage("SOA_TEMPL_ORDER_COMPLETE1", Array("#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"], "#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"]))?></b>
    <? } ?>
    <table class="sale_order_full_table">
        <tr>
            <td>

                <? if ($arResult["ORDER"]["PAY_SYSTEM_ID"] == $arBlockId["PAY_SISTEM_ID"]) {
                    echo GetMessage("SOA_TEMPL_ORDER_SUC", Array("#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"], "#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"]));?>
                    <br /><br />
                    <? echo GetMessage("ORDER_COMPLETE");
                } else { ?>
                    <?= GetMessage("SOA_TEMPL_ORDER") ?>
                    <br />
                    <?= GetMessage("SOA_TEMPL_ORDER_SUC1", Array("#LINK#" => $arParams["PATH_TO_PERSONAL"])) ?>
                <?}?>
            </td>
        </tr>
    </table>
	<?
	if (!empty($arResult["PAY_SYSTEM"]))
	{
		?>
		<br />

		<table class="sale_order_full_table">

			<tr>

			</tr>

			<?
			if (strlen($arResult["PAY_SYSTEM"]["ACTION_FILE"]) > 0)
			{
				?>
				<tr>
					<td>
						<?
						if ($arResult["PAY_SYSTEM"]["NEW_WINDOW"] == "Y")
						{
							?>
							<script language="JavaScript">
								window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))?>');
							</script>
							<?= GetMessage("SOA_TEMPL_PAY_LINK", Array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))))?>
							<?
							if (CSalePdf::isPdfAvailable() && CSalePaySystemsHelper::isPSActionAffordPdf($arResult['PAY_SYSTEM']['ACTION_FILE']))
							{
								?><br />
								<?= GetMessage("SOA_TEMPL_PAY_PDF", Array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))."&pdf=1&DOWNLOAD=Y")) ?>
								<?
							}
						}
						else
						{
							if (strlen($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"])>0)
							{
								include($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"]);
                           //     arshow($arResult["PAY_SYSTEM"]);
							}
						}
						?>
					</td>
				</tr>
				<?
			}
			?>
		</table>
		<?
		if( !$_SESSION["EXISTS_ORDER"][$arResult["ORDER"]["ID"]] ){?>
			<div class="ajax_counter"></div>
			<script>
				purchaseCounter('<?=$arResult["ORDER"]["ID"];?>', '<?=GetMessage("FULL_ORDER");?>');
			</script>
			<?
			$_SESSION["EXISTS_ORDER"][$arResult["ORDER"]["ID"]] = "Y";
		}?>
		<?
	}
}
else
{
	?>
	<b><?=GetMessage("SOA_TEMPL_ERROR_ORDER")?></b><br /><br />

	<table class="sale_order_full_table">
		<tr>
			<td>
				<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST", Array("#ORDER_ID#" => $arResult["ACCOUNT_NUMBER"]))?>
				<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST1")?>
			</td>
		</tr>
	</table>
	<?
}
?>
