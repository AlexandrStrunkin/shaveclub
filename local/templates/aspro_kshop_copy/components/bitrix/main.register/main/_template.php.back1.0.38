<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?><? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>

<div class="module-form-block-wr registraion-page">
<?/*if( !empty($arResult["ERRORS"]) ){
	$arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);
	ShowError(implode("<br />", $arResult["ERRORS"]));
}*/
if (count($arResult["ERRORS"][0]) > 0){
	//print_r($arResult["ERRORS"][0]);
	foreach ($arResult["ERRORS"] as $key => $error)
		if (intval($key) == 0 && $key !== 0) 
			$arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);

	ShowError(implode("<br />", $arResult["ERRORS"]));}
?>

<?if( empty($arResult["ERRORS"]) && !empty($_POST["register_submit_button"])){LocalRedirect(SITE_DIR.'personal/');}else{?>

	<div class="form-block-title"><?=GetMessage("REGISTER_PERSONAL_DATA")?></div>
    <div class="form-block">
	
	<script>
		$(document).ready(function(){
			$("form#registraion-page-form").validate
			({
				rules:{ emails: "email"}	
			});
		})
	</script>
	
	<form id="registraion-page-form" method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data" >					
			<?if($arResult["BACKURL"] <> ''):?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
			<?endif;?>
			<?
				/*$tmp = array( "LOGIN", "EMAIL", "PASSWORD", "CONFIRM_PASSWORD" );
				for( $i = 4; $i < count($arResult["SHOW_FIELDS"]); $i++ )
					$tmp[] = $arResult["SHOW_FIELDS"][$i];                    
				$arResult["SHOW_FIELDS"] = $tmp;*/
                $arResult['SHOW_FIELDS'] = array(
                                           'NAME',
                                           'PERSONAL_PHONE',
                                           'EMAIL',                                           
                                           'PASSWORD',
                                           'CONFIRM_PASSWORD',
                                           //'PERSONAL_STREET',
											'LOGIN'
                                        );
			?>
			
			<?foreach ($arResult["SHOW_FIELDS"] as $FIELD):?>

<div class="r">	
<?if( $FIELD != "LOGIN" ):?>				
	<label><?=GetMessage("REGISTER_FIELD_".$FIELD)?>:<?if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"):?><span class="star">*</span><?endif;?></label>
<?endif;?>			
            
			<?//print_r($arResult["ERRORS"]);?>
			<?if( array_key_exists( $FIELD, $arResult["ERRORS"] ) ):?>
				<?$class='class="error"'?>		
			<?endif;?>
			
            <?switch ($FIELD){
					case "PASSWORD":?>
						<input size="30" type="password" name="REGISTER[<?=$FIELD?>]" required value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" class="bx-auth-input <?=(array_key_exists( $FIELD, $arResult["ERRORS"] ))? 'error': ''?>"  />
						
					<?break;
					case "CONFIRM_PASSWORD":?>
						<input size="30" type="password" name="REGISTER[<?=$FIELD?>]" required value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" <?=$class?> />
					
					<?break;
					case "PERSONAL_GENDER":?>
						<select name="REGISTER[<?=$FIELD?>]">
							<option value=""><?=GetMessage("USER_DONT_KNOW")?></option>
							<option value="M"<?=$arResult["VALUES"][$FIELD] == "M" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_MALE")?></option>
							<option value="F"<?=$arResult["VALUES"][$FIELD] == "F" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_FEMALE")?></option>
						</select>
						<?break;
					case "PERSONAL_COUNTRY":
					case "WORK_COUNTRY":?>
						<select name="REGISTER[<?=$FIELD?>]">
							<?foreach ($arResult["COUNTRIES"]["reference_id"] as $key => $value){?>
								<option value="<?=$value?>"<?if ($value == $arResult["VALUES"][$FIELD]):?> selected="selected"<?endif?>><?=$arResult["COUNTRIES"]["reference"][$key]?></option>
							<?}?>
						</select>
						<?break;
					case "PERSONAL_PHOTO":
					case "WORK_LOGO":?>
						<input size="30" type="file" name="REGISTER_FILES_<?=$FIELD?>" />
						<?break;
					case "PERSONAL_NOTES":
					case "WORK_NOTES":?>
						<textarea cols="30" rows="5" name="REGISTER[<?=$FIELD?>]"><?=$arResult["VALUES"][$FIELD]?></textarea>
						
					<?case "PERSONAL_STREET":?>
						<textarea cols="30" rows="5" name="REGISTER[<?=$FIELD?>]"><?=$arResult["VALUES"][$FIELD]?></textarea>
						<?break;?>
					<?case "EMAIL":?>
						<input size="30" type="email" name="REGISTER[<?=$FIELD?>]" required value="<?=$arResult["VALUES"][$FIELD]?>" placeholder="example@mail.ru" <?=$class?> id="emails"/> 
                        <div class="pr em"><?=GetMessage("EMAIL_DESCRIPTION")?></div>      
					<?break;?>
					<?case "NAME":?>
						<input size="30" type="text" name="REGISTER[<?=$FIELD?>]" required value="<?=$arResult["VALUES"][$FIELD]?>" <?=$class?>/>                              
					<?break;?>
					<?case "PERSONAL_PHONE":?>
						<input size="30" type="text" name="REGISTER[<?=$FIELD?>]" class="phone-input <?=(array_key_exists( $FIELD, $arResult["ERRORS"] ))? 'error': ''?>" required value="<?=$arResult["VALUES"][$FIELD]?>" /> 
                        <div class="pr"><?=GetMessage("PHONE_DESRIPTION")?></div>      
					<?break;?>	
					<?break;
					default:?>
						<?// hide login?>
						<input size="30" <?=$FIELD == "LOGIN" ? 'type="hidden" value="1"' : 'type="text"'?> name="REGISTER[<?=$FIELD?>]"  />
						<?break;?>
			
                <?}?>
	
				<?if( $FIELD != "LOGIN" && array_key_exists( $FIELD, $arResult["ERRORS"] ) ):?>
					<label class="error"><?=GetMessage("REGISTER_FILL_IT")?></label>
				<?endif;?>
				
            
    </div>
	
	<?//endif?>
<?endforeach?>
			<?if ($arResult["USE_CAPTCHA"] == "Y"){?>
				<div class="r register-captcha">
					<label><?=GetMessage("REGISTER_CAPTCHA_PROMT")?>:<span class="star">*</span></label>
					<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
					<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" style="display:block;" />
					<input <?=!empty( $error ) ? 'class="error"' : ''?> required type="text" name="captcha_word" maxlength="50" value="" />
				</div>
			<?}?>
			<div class="but-r">
				<button class="button30" type="submit" name="register_submit_button" value="<?=GetMessage("AUTH_REGISTER")?>">
					<span><?=GetMessage("REGISTER_REGISTER")?></span>
				</button>
				<div class="prompt"><span class="star">*</span> &nbsp;&mdash;&nbsp; <?=GetMessage("REQUIRED_FIELDS")?></div>
				<div class="clearboth"></div>				
			</div>				
		</form>
        
			
	</div>
<?//}?>
</div><?}?>