<?global $USER;?>
<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("inlineform-block".$arParams["WEB_FORM_ID"]);?>
<?if($USER->IsAuthorized()):?>
	<?
	$dbRes = CUser::GetList(($by = "id"), ($order = "asc"), array("ID" => $USER->GetID()), array("FIELDS" => array("ID", "PERSONAL_PHONE")));
	$arUser = $dbRes->Fetch();
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		try{
			$('.form-block input[data-sid=CLIENT_NAME], .popup inout[data-sid=FIO], .popup inout[data-sid=NAME]').val('<?=$USER->GetFullName()?>');
			$('.form-block input[data-sid=PHONE]').val('<?=$arUser['PERSONAL_PHONE']?>');
			$('.form-block input[data-sid=EMAIL]').val('<?=$USER->GetEmail()?>');
			$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=PRODUCT_NAME]').val($('h1').text());
		}
		catch(e){
		}
	});
	</script>
<?endif;?>
<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("inlineform-block".$arParams["WEB_FORM_ID"], "");?>
<script type="text/javascript">
	$(document).ready(function() {
		$('.form-block input[data-sid=PRODUCT_NAME_QUESTION]').val($('h1').text());
	})
</script>