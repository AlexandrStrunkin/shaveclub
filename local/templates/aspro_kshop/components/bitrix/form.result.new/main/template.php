<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<style id="correct_wait_window_temp">.bx-core-waitwindow{display:none;}</style>
<script>
$(document).ready(function()
{	
	/*if (document.body.addEventListener) { document.body.addEventListener ("DOMNodeInserted", function(event) { if(event.target.className=="bx-core-waitwindow"){ event.target.remove(); } }); }*/
	
	var waitWindowFormID = "#wait_comp_"+$("form[name=<?=$arResult["arForm"]["VARNAME"];?>] input[name=bxajaxid]").attr("value");
			$("#correct_wait_window_temp").remove();
			$("form[name=<?=$arResult["arForm"]["VARNAME"];?>]").append("<style id='correct_wait_window'>"+waitWindowFormID+"{display:none;}</style>");
	
	var emailInpuit = $("form[name=<?=$arResult["arForm"]["VARNAME"];?>] input[type=email]").attr("name");

	if (emailInpuit)
	{
		$("form[name=<?=$arResult["arForm"]["VARNAME"];?>]").validate({
			rules:{
			  emailInpuit: {
				required: true,
				email: true
			  }
			}
		});
	} 
	else 
	{ 
		$("form[name=<?=$arResult["arForm"]["VARNAME"];?>]").validate({}); 
	}
	$('input.phone').mask('<?=trim(COption::GetOptionString("aspro.kshop", "PHONE_MASK", "+9 (999) 999-99-99", SITE_ID));?>');
	$('.popup').jqmAddClose('a.jqmClose');
	$(".bx-core-waitwindow").hide();
});
</script>
<a href="#" class="close jqmClose"><i></i></a>
<?if( !empty( $arResult["FORM_NOTE"] ) ){?>
	<div class="popup-intro">
		<div class="pop-up-title"><?=$arResult["FORM_TITLE"]?></div>
	</div>
	<?/*if( !empty( $arResult["FORM_DESCRIPTION"] ) ){?>
		<div class="form_description">
			<?=$arResult["FORM_DESCRIPTION"]?>
		</div>
	<?}*/?>
	
	<div class="form_txt">
		<p><?=$arResult["FORM_NOTE"]?></p>
	</div>
<?}else{?>
	<div class="popup-intro">
		<div class="pop-up-title"><?=$arResult["FORM_TITLE"]?></div>
		<?if( !empty( $arResult["FORM_DESCRIPTION"] ) ){?>
			<div class="after-title">
				<span class="description-wrapp">
					<?=$arResult["FORM_DESCRIPTION"]?>
				</span>
			</div>
		<?}?>
	</div>
	<div class="form-wr">
		<?if( $arResult["isFormErrors"] == "Y" ){?><?=$arResult["FORM_ERRORS_TEXT"]?><br /><?}?>
		
		<?=$arResult["FORM_HEADER"]?>
		<?=bitrix_sessid_post()?>
		<?foreach( $arResult["QUESTIONS"] as $FIELD_SID => $arQuestion){
			$arQuestion["HTML_CODE"] = str_replace('name=', 'data-sid="'.$FIELD_SID.'" name=', $arQuestion["HTML_CODE"]);
			if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'){
				echo $arQuestion["HTML_CODE"];
			}else{?>
				<div class="r"<?if ($FIELD_SID=="PRODUCT"):?>product_name<?endif;?>>
					<label><?=$arQuestion["CAPTION"]?><?if( $arQuestion["REQUIRED"] == "Y" ){?> <span class="star">*</span><?}?></label>
					<?if( is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS']) ){
						$html = str_replace('class="', 'class="error ', $arQuestion["HTML_CODE"]);
						$arQuestion["HTML_CODE"] = $html;
					}
					$arQuestion["HTML_CODE"] = str_replace('name="', 'data-sid="'.$FIELD_SID.'" name="', $arQuestion["HTML_CODE"]);
					if( $arQuestion["REQUIRED"] == "Y" ){
						$html = str_replace('name=', 'required name=', $arQuestion["HTML_CODE"]);
						$arQuestion["HTML_CODE"] = $html;
					}
					if( $arQuestion["STRUCTURE"][0]["FIELD_TYPE"] == "email" ){
						$html = str_replace('type="text"', 'type="email" placeholder="mail@domen.com"', $arQuestion["HTML_CODE"]);
						$arQuestion["HTML_CODE"] = $html;
					}?>
					<?=$arQuestion["HTML_CODE"]?>
				</div>
			<?}?>
		<?}?>
		<?if($arResult["isUseCaptcha"] == "Y"){?>
			<div class="r captcha_wrapp">
				<label><?=GetMessage("FORM_CAPTCHA_LABEL")?> <span class="star">*</span></label>
				<input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" />
				<img  class="captcha" src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="180" height="40" /><br />
				<input type="text" name="captcha_word" size="30" maxlength="50" value="" required class="inputtext" />
			</div>
		<?}?>
		
			<!--noindex-->
				<div class="but-r clearfix">	
					<button type="submit" name="web_form_submit" class="button30" value="submit"><span><?=GetMessage("FORM_SEND")?></span> </button>
					<div class="prompt"><span class="star_big">*</span> &mdash;&nbsp; <?=GetMessage("FORM_REQUIRED")?></div>
				</div>
			<!--/noindex-->
		</div>
		<?=$arResult["FORM_FOOTER"]?>
	</div>
<?}?>