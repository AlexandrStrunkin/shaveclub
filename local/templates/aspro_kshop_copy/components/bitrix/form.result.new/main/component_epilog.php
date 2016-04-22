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
			$('.popup input[data-sid=CLIENT_NAME], .popup inout[data-sid=FIO], .popup inout[data-sid=NAME]').val('<?=$USER->GetFullName()?>');
			$('.popup input[data-sid=PHONE]').val('<?=$arUser['PERSONAL_PHONE']?>');
			$('.popup input[data-sid=EMAIL]').val('<?=$USER->GetEmail()?>');
		}
		catch(e){
		}
	});
	</script>
<?endif;?>
<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("inlineform-block".$arParams["WEB_FORM_ID"], "");?>