<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="module-form-block-wr lk-page">

<?ShowError($arResult["strProfileError"]);?>
<?if( $arResult['DATA_SAVED'] == 'Y' ) {?><?ShowNote(GetMessage('PROFILE_DATA_SAVED'))?><br /><?; }?>
<script>
	$(document).ready(function()
	{
		$(".form-block form").validate({rules:{ EMAIL: { email: true }}	});
	})
</script>

<div class="form-block-title"><?=GetMessage("PERSONAL_DATA")?></div>
	<div class="form-block">
		<form method="post" name="form1" class="main" action="<?=$arResult["FORM_TARGET"]?>?" enctype="multipart/form-data">
			<?=$arResult["BX_SESSION_CHECK"]?>
			<input type="hidden" name="LOGIN" maxlength="50" value="<? echo $arResult["arUser"]["LOGIN"]?>" />
			<input type="hidden" name="lang" value="<?=LANG?>" />
			<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
			
			<div class="r">
				<label><?=GetMessage("PERSONAL_NAME")?>:<span class="star">*</span></label>
				<?
				$arName = array();
				if($arResult["arUser"]["LAST_NAME"]){
					$arName[] = $arResult["arUser"]["LAST_NAME"];
				}
				if($arResult["arUser"]["NAME"]){
					$arName[] = $arResult["arUser"]["NAME"];
				}
				if($arResult["arUser"]["SECOND_NAME"]){
					$arName[] = $arResult["arUser"]["SECOND_NAME"];
				}
				?>
				<input required type="text" name="NAME" maxlength="50" value="<?=implode(' ', $arName);?>" />
			</div>
			
			<div class="r">
				<label><?=GetMessage("PERSONAL_EMAIL")?>:<span class="star">*</span></label>
				<input required type="text" name="EMAIL" maxlength="50" placeholder="name@company.ru" value="<? echo $arResult["arUser"]["EMAIL"]?>" />
				<div class="pr em"><?=GetMessage("PERSONAL_EMAIL_DESCRIPTION")?></div>
			</div>
			
			<div class="r">
				<label><?=GetMessage("PERSONAL_PHONE")?>:<span class="star">*</span></label>
				<input required type="text" name="PERSONAL_PHONE" class="phone-input" maxlength="255" value="<?=$arResult["arUser"]["PERSONAL_PHONE"]?>" />
			</div>
			<div class="but-r">
				<button class="button30" type="submit" name="save" value="<?=(($arResult["ID"]>0) ? GetMessage("MAIN_SAVE") : GetMessage("MAIN_ADD"))?>"><span><?=(($arResult["ID"]>0) ? GetMessage("MAIN_SAVE") : GetMessage("MAIN_ADD"))?></span></button>
				<div class="prompt">
					<span class="star">*</span> &nbsp;&mdash;&nbsp; <?=GetMessage("REQUIRED_FIELDS")?>
				</div>
				<div class="clearboth"></div>
				<?/*<a class="cancel"><?=GetMessage('MAIN_RESET');?></a>*/?>
			</div>
			
		</form>
		<? /*if($arResult["SOCSERV_ENABLED"]){ $APPLICATION->IncludeComponent(
	"bitrix:socserv.auth.split", 
	"main", 
	array(
		"SUFFIX" => "form",
		"SHOW_PROFILES" => "Y",
		"ALLOW_DELETE" => "Y",
		"COMPONENT_TEMPLATE" => "main"
	),
	false
);}*/?>
	</div>
</div>