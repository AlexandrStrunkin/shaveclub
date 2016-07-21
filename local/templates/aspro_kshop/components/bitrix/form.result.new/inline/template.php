<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?$frame = $this->createFrame()->begin('')?>
<script>
$(document).ready(function(){
	var emailInpuit = $("form[name=<?=$arResult["arForm"]["VARNAME"];?>] input[type=email]").attr("name");
	if(emailInpuit){
		$("form[name=<?=$arResult["arForm"]["VARNAME"];?>]").validate({
			rules:{ emailInpuit: {email: true} }
		});
	} 
	else{
		$("form[name=<?=$arResult["arForm"]["VARNAME"];?>]").validate();
	}
	<?if(!empty($arResult["FORM_NOTE"]) && ($arResult["isFormErrors"] != "Y")):?>
		$("#content a.faq_icon").toggleClass("opened");
		$("#faq_web_fom").slideDown(333);
	<?endif;?>
	<?if($arResult["isFormErrors"] == "Y"):?>
		$("#content a.ask_question").toggleClass("opened");
		$(".faq_ask_wrapp .module-ans-qw").show();
	<?endif;?>
})
   $(".form-block form").submit(function (e) {
       
       formData = $(this).serialize() + "&web_form_submit=Отправить";
      $.ajax(
         $(this).attr("action")+"?AJAX_REQUEST=Y",
         formData,
         function(response){
            alert(data);
            var message = $(".show-message").html(response);         // Сохраняем загруженные данные на странице в невидимом блоке                       // Если в этих данных есть элементы для показа (ошибка или сообщение)
            $((".show-message")).html('Сообщение отправлено!');

            if(message.find(".form-note").length) {   // Если данные формы приняты сбросим все поля
               $('.form-block textarea').val(" ");
            }
         },
         function (response) {
            alert('Мы бы и рады написать "Спасибо за регистрацию!", но чтото на сервере сломалось'+response);
         }
      );
      e.preventDefault();
      return true;
   });

</script>
<?if($arResult["isFormErrors"] == "Y"){?><?=$arResult["FORM_ERRORS_TEXT"]?><br /><?}?>
<?if(!empty($arResult["FORM_NOTE"]) && ($arResult["isFormErrors"] != "Y")):?><p class="m16"><?=GetMessage("FORM_SENDED");?></p><?endif;?>
<div class="module-ans-qw">
	<div class="drop-question">
		<div class="form-block">
			<?=$arResult["FORM_HEADER"]?>
				<div class="left-data">
					<?foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion){?>
						<?$arQuestion["HTML_CODE"] = str_replace('name=', 'data-sid="'.$FIELD_SID.'" name=', $arQuestion["HTML_CODE"]);?>
						<?if( $arQuestion["STRUCTURE"][0]["FIELD_PARAM"] == 'left' ){?>
							<div class="r">
								<label class="s"><?=$arQuestion["CAPTION"]?>:<?if ($arQuestion["REQUIRED"] == "Y"):?><span class="star">*</span><?endif;?></label>
								<?
									if( is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS']) )
									{
										$html = str_replace('class="', 'class="error ', $arQuestion["HTML_CODE"]);
										$arQuestion["HTML_CODE"] = $html;
									}
									$arQuestion["HTML_CODE"] = str_replace('name="', 'data-sid="'.$FIELD_SID.'" name="', $arQuestion["HTML_CODE"]);
									if( $arQuestion["REQUIRED"] == "Y" )
									{
										$html = str_replace('name=', 'required name=', $arQuestion["HTML_CODE"]);
										$arQuestion["HTML_CODE"] = $html;
									}
									if( $arQuestion["STRUCTURE"][0]["FIELD_TYPE"] == "email" )
									{
										$html = str_replace('type="text"', 'type="email" placeholder="mail@domen.ru"', $arQuestion["HTML_CODE"]);
										$arQuestion["HTML_CODE"] = $html;
									}
								?>
								<?=$arQuestion["HTML_CODE"]?>
							</div>
						<?}?>
					<?}?>
					<?if($arResult["isUseCaptcha"] == "Y"){?>
						<div class="r captcha">
							<input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" />
							<div class="captcha-label">
								<label><?=GetMessage("FORM_CAPRCHE_TITLE")?>:<span class="star">*</span></label><br />
								<input type="text" name="captcha_word" size="30" maxlength="50" value="" class="inputtext" required />
							</div>
							<img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="180" height="40" />
						</div>
					<?}?>
				</div>
				<div class="right-data">
					<?foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion){
						$arQuestion["HTML_CODE"] = str_replace('name=', 'data-sid="'.$FIELD_SID.'" name=', $arQuestion["HTML_CODE"]);
						if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'){echo $arQuestion["HTML_CODE"];}
						elseif( $arQuestion["STRUCTURE"][0]["FIELD_PARAM"] != 'left'){?>
							<div class="r">
								<label class="s"><?=$arQuestion["CAPTION"]?>:<?if ($arQuestion["REQUIRED"] == "Y"):?><span class="star">*</span><?endif;?></label>
								<?
									if( is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS']) )
									{
										$html = str_replace('class="', 'class="error ', $arQuestion["HTML_CODE"]);
										$arQuestion["HTML_CODE"] = $html;
									}
									if( $arQuestion["REQUIRED"] == "Y" )
									{
										$html = str_replace('name=', 'required name=', $arQuestion["HTML_CODE"]);
										$arQuestion["HTML_CODE"] = $html;
									}
									if( $arQuestion["STRUCTURE"][0]["FIELD_TYPE"] == "email" )
									{
										$html = str_replace('type="text"', 'type="email" placeholder="mail@domen.com"', $arQuestion["HTML_CODE"]);
										$arQuestion["HTML_CODE"] = $html;
									}
								?>
								<?=$arQuestion["HTML_CODE"]?>
							</div>
						<?}
					}?>
				</div>
				<div class="but-r">
					<div class="clearboth"></div>
					<div class="but_wr">
						<!--noindex-->
							<button type="submit" name="web_form_submit"  class="button30" value="submit"><span><?=$arResult["arForm"]["BUTTON"]?></span> </button>
						<!--/noindex-->
					</div>
				</div>
			<?=$arResult["FORM_FOOTER"]?>
            <div class="show-message"></div>
		</div>
	</div>
</div>
<?$frame->end()?>