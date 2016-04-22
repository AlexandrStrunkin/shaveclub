<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="module-form-block-wr lk-page">
	<?
		if(isset($APPLICATION->arAuthResult)) {
			$arResult['ERROR_MESSAGE'] = $APPLICATION->arAuthResult;
            
            if ( isset($arResult["ERRORS"])){
                $arResult["ERROR_MESSAGE"]["MESSAGE"]= GetMessage("WELL_DONE_CHANGE");
            }   
                   
			ShowMessage($arResult['ERROR_MESSAGE']);
		}   
        arshow($arResult);        
	?>
		
	<? 
	if( isset($_POST["LAST_LOGIN"]) && empty( $_POST["LAST_LOGIN"] ) ){
				$arResult["ERRORS"]["LAST_LOGIN"] = GetMessage("REQUIRED_FIELD");
			}
			if( isset($_POST["USER_PASSWORD"]) && strlen( $_POST["USER_PASSWORD"] ) < 6 ){
				$arResult["ERRORS"]["USER_PASSWORD"] = GetMessage("PASSWORD_MIN_LENGTH_2");
			}
			if( isset($_POST["USER_PASSWORD"]) && empty( $_POST["USER_PASSWORD"] ) ){
				$arResult["ERRORS"]["USER_PASSWORD"] = GetMessage("REQUIRED_FIELD");
			}
			if( isset($_POST["USER_CONFIRM_PASSWORD"]) && strlen( $_POST["USER_CONFIRM_PASSWORD"] ) < 6 ){
				$arResult["ERRORS"]["USER_CONFIRM_PASSWORD"] = GetMessage("PASSWORD_MIN_LENGTH_2");
			}
			if( isset($_POST["USER_CONFIRM_PASSWORD"]) && empty( $_POST["USER_CONFIRM_PASSWORD"] ) ){
				$arResult["ERRORS"]["USER_CONFIRM_PASSWORD"] = GetMessage("REQUIRED_FIELD");
			}
			if( $_POST["USER_PASSWORD"] != $_POST["USER_CONFIRM_PASSWORD"] ){
				$arResult["ERRORS"]["USER_CONFIRM_PASSWORD"] = GetMessage("WRONG_PASSWORD_CONFIRM");
			}
            
            
	if ($arResult['SHOW_ERRORS'] == 'Y' ){
		ShowMessage($arResult['ERROR_MESSAGE']);?>
		<p><font class="errortext"><?=GetMessage("WRONG_LOGIN_OR_PASSWORD")?></font></p>
	<?}?>
	<?if( empty($arResult["ERRORS"]) && !empty( $_POST["change_pwd"] ) ){?>
		<p><?=GetMessage("CHANGE_SUCCESS")?></p>
		<div class="but-r"><a href="/auth/" class="button-30"><span><?=GetMessage("LOGIN")?></span></a></div>
	<?}else{?>
<script>
$(document).ready(function(){
	$(".form-block form").validate({
		rules:{
			USER_CONFIRM_PASSWORD: {equalTo: '#pass'},
			USER_LOGIN: {email: true}
		}, messages:{USER_CONFIRM_PASSWORD: {equalTo: '<?=GetMessage("PASSWORDS_DONT_MATCH")?>'}}	
	});
})
</script>
    <div class="form-block">
        <form method="post" action="/auth/change-password/" name="bform" class="bf">
			<?if (strlen($arResult["BACKURL"]) > 0): ?><input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" /><?endif;?>
			<input type="hidden" name="AUTH_FORM" value="Y">
			<input type="hidden" name="TYPE" value="CHANGE_PWD">	
            <div class="r">
                <label><?=GetMessage("AUTH_LOGIN")?><span class="star">*</span></label>
				<?if($_POST){?>
				<input type="text" name="USER_LOGIN" maxlength="50" required value="<?=$arResult["LAST_LOGIN"]?>" class="bx-auth-input <?=(empty( $_POST["USER_LOGIN"] ))? "error": ''?>" />
				<?}else{?>
                <input type="text" name="USER_LOGIN" maxlength="50" required value="<?=$arResult["LAST_LOGIN"]?>" class="bx-auth-input" />				
				<?}?>
            </div>	
            <div class="r">
                <label><?=GetMessage("AUTH_NEW_PASSWORD_REQ")?><span class="star">*</span></label>
                <input type="password" name="USER_PASSWORD" maxlength="50" id="pass" required value="<?=$arResult["USER_PASSWORD"]?>" class="bx-auth-input <?=(array_key_exists( "USER_PASSWORD", $arResult["ERRORS"] ))? "error": ''?>" />
                <div class="pr"><?=GetMessage("PASSWORD_MIN_LENGTH")?></div>				
            </div>	
            <div class="r">
                <label><?=GetMessage("AUTH_NEW_PASSWORD_CONFIRM")?><span class="star">*</span></label>
				<input type="password" name="USER_CONFIRM_PASSWORD" maxlength="50" required value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" class="bx-auth-input <?=(array_key_exists( "USER_CONFIRM_PASSWORD", $arResult["ERRORS"] ))? "error": ''?>"  />
            </div>				
			<input type="hidden" name="USER_CHECKWORD" maxlength="50" value="<?=$arResult["USER_CHECKWORD"]?>" class="bx-auth-input <?=(array_key_exists( "USER_CONFIRM_PASSWORD", $arResult["ERRORS"] ))? "error": ''?>"  />
            <div class="but-r">
				<button class="button30" type="submit" name="change_pwd" value="<?=GetMessage("AUTH_CHANGE")?>"><span><?=GetMessage("CHANGE_PASSWORD")?></span></button>				
				<div class="prompt"><span class="star">*</span> &mdash;&nbsp; <?=GetMessage("REQUIRED_FIELDS")?></div>		
				<div class="clearboth"></div>
			</div> 		
    	</form> 
    </div>
	<script type="text/javascript">document.bform.USER_LOGIN.focus();</script>
	<?} 
    //arShow($arResult)
    ?>
</div>
