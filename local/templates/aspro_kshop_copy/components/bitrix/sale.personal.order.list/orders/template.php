<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
	if (!function_exists("formatPrice")) {
		function formatPrice ($price, $currencyCode) {
			if (!$price&&$currencyCode) {
				$currency = CCurrencyLang::GetCurrencyFormat($currencyCode, LANGUAGE_ID);
				return substr($currency["FORMAT_STRING"], strrpos($currency["FORMAT_STRING"], ' '));
			}
			elseif ($price&&!$currencyCode) { return number_format( $price, 0, '', ' '); }
			elseif ($price&&$currencyCode){
				$currency = CCurrencyLang::GetCurrencyFormat($currencyCode, LANGUAGE_ID);
				return number_format( $price, 0, '', ' ' ).substr($currency["FORMAT_STRING"], strrpos($currency["FORMAT_STRING"], ' '));
			}
		}
	}
?>
<small><?//var_dump($arResult["ORDERS"]);?></small>
<?if (count($arResult["ORDERS"])):?>
	<table class="module-orders-list colored">
		<thead>
			<tr>
				<td class="item-name-th"><?=GetMessage("STPOL_ORDER_NUMBER")?></td>
				<td class="date-th"><?=GetMessage("STPOL_ORDER_DATE")?></td>
				<td class="count-th"><?=GetMessage("STPOL_ORDER_QUANTITY")?></td>
				<td class="price-th"><?=GetMessage("STPOL_ORDER_SUMM")?></td>
				<td class="pay-status-th"><?=GetMessage("STPOL_ORDER_PAY")?></td>
				<td class="order-status-th"><?=GetMessage("SPOL_T_STATUS")?></td>
			</tr>
		</thead>
		<tbody>
		<?foreach( $arResult["ORDERS"] as $val ) {?>
			<?$summ = 0;
			foreach( $val["BASKET_ITEMS"] as $vval ) {
				$summ += $vval["PRICE"] * $vval["QUANTITY"];
			}?>

				<tr class="tr-d">
					<td class="item-name-cell">
						<a class="item_name"><span class="icon"><i></i></span><span class="name"><?=GetMessage("STPOL_ORDER")?><?=$val["ORDER"]["ACCOUNT_NUMBER"]?></span></a>

						<span class="order-extra-properties">
							, <span class="item">
								<?=GetMessage("STPOL_ORDER_DATE")?>:<?$date = explode( ' ', $val["ORDER"]["DATE_INSERT_FORMAT"] );?> <?=$date[0]?>,
							</span>
							<span class="item">
								<?=GetMessage("STPOL_ORDER_QUANTITY_2")?>:&nbsp;<?=count( $val["BASKET_ITEMS"] )?>,
							</span>
							<span class="item">
								<?=GetMessage("STPOL_ORDER_SUMM")?>:&nbsp;
								<?=$val["ORDER"]["FORMATED_PRICE"]?>
							</span>
							<span class="item"><?=GetMessage("STPOL_ORDER_PAY")?>:&nbsp;
								<span class="pay-status-cell<?=$val["ORDER"]["PAYED"] == 'Y' ? ' payed' : ' not_payed'?>"><?=$val["ORDER"]["PAYED"] == 'Y' ? GetMessage("SPOL_T_PAYED") : GetMessage("SPOL_T_NOT_PAYED")?></span>,
							</span>
							<span class="item">
								<?=GetMessage("SPOL_T_STATUS")?>:&nbsp;
								<?if( $val["ORDER"]["CANCELED"] == "Y" ){?><?=GetMessage("SPOL_T_CANCELED");?>
								<?}elseif( $val["ORDER"]["STATUS_ID"]){?><?=$arResult["INFO"]["STATUS"][$val["ORDER"]["STATUS_ID"]]["NAME"]?><?}?>
							</span>
						</span>
					</td>
					<td class="date-cell"><?$date = explode( ' ', $val["ORDER"]["DATE_INSERT_FORMAT"] );?> <?=$date[0]?></td>
					<td class="count-cell"><?=count( $val["BASKET_ITEMS"] )?></td>
					<td class="price-cell"><?=$val["ORDER"]["FORMATED_PRICE"]?></td>
					<td class="pay-status-cell<?=$val["ORDER"]["PAYED"] == 'Y' ? ' payed' : ' not_payed'?>">
						<?=$val["ORDER"]["PAYED"] == 'Y' ? GetMessage("SPOL_T_PAYED") : GetMessage("SPOL_T_NOT_PAYED")?>
					</td>
					<td class="order-status-cell">
						<?if( $val["ORDER"]["CANCELED"] == "Y" ){?><span class="status canceled"><?=GetMessage("SPOL_T_CANCELED");?></span>
						<?}elseif( $val["ORDER"]["STATUS_ID"] == 'F' ){?><span class="status delivered"><?=$arResult["INFO"]["STATUS"][$val["ORDER"]["STATUS_ID"]]["NAME"]?></span>
						<?}else{?><span class="status in-process"><?=$arResult["INFO"]["STATUS"][$val["ORDER"]["STATUS_ID"]]["NAME"]?></span><?}?>
					</td>
				</tr>
				<tr class="drop">
					<td colspan="6" class="drop-cell wrap-all">
						<div class="drop-container">
							<?if($val["ORDER"]["PAYED"] != 'Y' && $val["ORDER"]["CANCELED"] != 'Y' && intval( $val["ORDER"]["PAY_SYSTEM_ID"] ) > 0 ):?>
								<?$arPaySysAction = CSalePaySystemAction::GetList( array(), array( "PAY_SYSTEM_ID" => $val["ORDER"]["PAY_SYSTEM_ID"] ), false, false, array("NAME", "ACTION_FILE", "NEW_WINDOW", "PARAMS", "ENCODING") )->GetNext();?>
								<?if(strlen($arPaySysAction["ACTION_FILE"] && $arPaySysAction["NEW_WINDOW"] == "Y")):?>
									<div class="not-payed">
										<!--noindex-->
											<?=GetMessage("STPOL_ORDER_NOT_PAYD")?>
											<a href="<?=htmlspecialcharsbx( $arParams["PATH_TO_PAYMENT"] ).'?ORDER_ID='.$val["ORDER"]["ID"];?>" target="_blank" class="button22" style="float: right;"><span><?=GetMessage("SPOL_T_ORDER_PAY");?></span></a>
										<!--/noindex-->
										<div style="clear:both"></div>
									</div>
								<?endif;?>
							<?endif;?>
							<div class="t"><?=GetMessage("STPOL_IN_ORDER")?>:</div>
							<table class="item-shell">
								<thead>
									<tr>
										<td class="name-th"><?=GetMessage("SPOL_T_PRODUCT")?></td>
										<td class="price-th"><?=GetMessage("SPOL_T_PRICE")?></td>
										<td class="count-th"><?=GetMessage("STPOL_ORDER_QUANTITY")?></td>
										<td class="summ-th"><?=GetMessage("STPOL_ORDER_SUMM")?></td>
									</tr>
								</thead>
								<tbody>
									<?foreach( $val["BASKET_ITEMS"] as $vval ){?>
										<tr>
											<td  class="name-cell">
												<a href="<?=$vval["DETAIL_PAGE_URL"]?>"><?=$vval["NAME"]?></a>
												<div class="item-extra-properties">
													<?=formatPrice($vval["PRICE"], $vval["CURRENCY"]);?> &times; <?=$vval["QUANTITY"]?>
												</div>
											</td>
											<td class="price-cell">
												<?=formatPrice($vval["PRICE"], $vval["CURRENCY"]);?>
											</td>
											<td class="count-cell"><?=$vval["QUANTITY"]?></td>
											<td class="summ-cell">
												<?=formatPrice($vval["PRICE"]*$vval["QUANTITY"], $vval["CURRENCY"]);?>
											</td>
										</tr>
									<?};?>
								</tbody>
							</table>
							<div class="result-row">
								<div class="result">

									<table cellspacing="0" cellpadding="0" border="0">
										<?if( intval( $val["ORDER"]["PRICE_DELIVERY"] ) > 0 ){?>
											<tr class="order_property">
												<td class="name"><?=GetMessage("SPOL_T_DELIVERY")?>:</td>
												<td class="r"><?=formatPrice($val["ORDER"]["PRICE_DELIVERY"], $vval["CURRENCY"]);?></td>
											</tr>
										<?}?>
										<tr class="order_property price">
											<td class="name"><?=GetMessage("STPOL_ORDER_TO_AMOUNT")?>:</td>
											<td class="r"><?=$val["ORDER"]["FORMATED_PRICE"]?></td>
										</tr>
									</table>


								</div>
								<!--noindex-->
									<a href="<?=$val["ORDER"]["URL_TO_DETAIL"]?>" class="button22"><span><?=GetMessage("SPOL_T_DETAIL")?></span></a>
									<a href="<?=$val["ORDER"]["URL_TO_COPY"]?>" class="button22"><span><?=GetMessage("SPOL_T_COPY_ORDER_DESCR")?></span></a>
                                    <?         // подключение оплаты яндекс кассы
                                    CSalePaySystemAction::InitParamArrays($val["ORDER"], $val["ORDER"]["ID"], $arResult["PAY_SYSTEM"]["PARAMS"]);

                                    $pathToAction = $_SERVER["DOCUMENT_ROOT"].$arPaySysAction["ACTION_FILE"];
                                    $pathToAction = str_replace("\\", "/", $pathToAction);
                                    while (substr($pathToAction, strlen($pathToAction) - 1, 1) == "/"){
                                        $pathToAction = substr($pathToAction, 0, strlen($pathToAction) - 1);
                                    }
                                    if (file_exists($pathToAction)){
                                        if (is_dir($pathToAction) && file_exists($pathToAction."/payment.php")){
                                            $pathToAction .= "/payment.php";
                                        }
                                        $val["PAY_SYSTEM"]["PSA_ACTION_FILE"] = $pathToAction;

                                    }
                                    try{
                                        include($val["PAY_SYSTEM"]["PSA_ACTION_FILE"]);
                                    }
                                    catch(\Bitrix\Main\SystemException $e){
                                        if($e->getCode() == CSalePaySystemAction::GET_PARAM_VALUE){
                                            $message = GetMessage("SOA_TEMPL_ORDER_PS_ERROR");
                                        }else{
                                            $message = $e->getMessage();
                                        }
                                        ShowError($message);
                                    }   // подключение оплаты яндекс кассы
                                    ?>
									<?if( $val["ORDER"]["CAN_CANCEL"] == "Y"  && ($val["ORDER"]["STATUS_ID"] == "N" || $val["ORDER"]["STATUS_ID"] == "P")){?>
										<a href="<?=$val["ORDER"]["URL_TO_CANCEL"]?>" class="button22 grey"><span><?=GetMessage("SPOL_T_DELETE_DESCR")?></span></a>
									<?}?>
								<!--/noindex-->
							</div>
						</div>
					</td>
				</tr>
		<?}?>
		</tbody>
	</table>

	<?if( strlen($arResult["NAV_STRING"]) > 0 ):?>
		<?=$arResult["NAV_STRING"]?>
	<?endif;?>

	<script>
		$('.tr-d td').on('click', function(e){
			e.preventDefault();
			$(this).parents("tr").toggleClass("opened").next("tr.drop").find(".drop-cell").slideToggle(200).find(".drop-container").slideToggle(200);
		});
	</script>
<?else:?>
	<script>$(".module-order-history .tabs").hide();</script>
	<div class="empty_history">
		<p><?=GetMessage("NO_ORDERS")?></p><br />
		<form action="<?=SITE_DIR?>catalog/">
			<button class="button30"><?=GetMessage("TO_CATALOG")?></button>
		</form>
	</div>
<?endif;?>