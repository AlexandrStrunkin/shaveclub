<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	__IncludeLang($_SERVER["DOCUMENT_ROOT"].$templateFolder."/lang/".LANGUAGE_ID."/template.php");
?>
<?if($arResult["ID"]):?>
	<?if($arParams["USE_REVIEW"] == "Y" && IsModuleInstalled("forum")):?>
		<div id="reviews_content">
			<?$APPLICATION->IncludeComponent("bitrix:forum.topic.reviews", "template1", Array(
	"CACHE_TYPE" => $arParams["CACHE_TYPE"],	// ��� �����������
		"CACHE_TIME" => $arParams["CACHE_TIME"],	// ����� ����������� (���.)
		"MESSAGES_PER_PAGE" => $arParams["MESSAGES_PER_PAGE"],	// ���������� ��������� �� ����� ��������
		"USE_CAPTCHA" => $arParams["USE_CAPTCHA"],	// ������������ CAPTCHA
		"FORUM_ID" => $arParams["FORUM_ID"],	// ID ������ ��� �������
		"ELEMENT_ID" => $arResult["ID"],	// ID ��������
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],	// ��� ��������������� �����
		"AJAX_POST" => $arParams["REVIEW_AJAX_POST"],	// ������������ AJAX � ��������
		"SHOW_RATING" => "N",	// �������� �������
		"SHOW_MINIMIZED" => "Y",	// ����������� ����� ���������� ������
		"SECTION_REVIEW" => "Y",
		"POST_FIRST_MESSAGE" => "Y",
		"MINIMIZED_MINIMIZE_TEXT" => GetMessage("HIDE_FORM"),
		"MINIMIZED_EXPAND_TEXT" => GetMessage("ADD_REVIEW"),
		"SHOW_AVATAR" => "N",	// ���������� ������� �������������
		"SHOW_LINK_TO_FORUM" => "N",	// �������� ������ �� �����
		"PATH_TO_SMILE" => "/bitrix/images/forum/smile/"
	),
	false
);?>
		</div>
	<?endif;?>
	<?if($arParams["SHOW_COMPARE"]):?>
		<div class="compare" id="compare">
			<?$APPLICATION->IncludeComponent(
				"bitrix:catalog.compare.list",
				"preview",
				Array(
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"AJAX_MODE" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
					"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
					"COMPARE_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["compare"],
					"NAME" => "CATALOG_COMPARE_LIST",
					"AJAX_OPTION_ADDITIONAL" => ""
				)
			);?>
		</div>
	<?endif;?>
	<?if(($arParams["SHOW_ASK_BLOCK"] == "Y") && (intVal($arParams["ASK_FORM_ID"]))):?>
		<div id="ask_block_content">
			<?/*$APPLICATION->IncludeComponent(
				"bitrix:form.result.new",
				"inline",
				Array(
					"WEB_FORM_ID" => $arParams["ASK_FORM_ID"],
					"IGNORE_CUSTOM_TEMPLATE" => "N",
					"USE_EXTENDED_ERRORS" => "N",
					"SEF_MODE" => "N",
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "3600",
					"LIST_URL" => "",
					"EDIT_URL" => "",
					"SUCCESS_URL" => "?send=ok",
					"CHAIN_ITEM_TEXT" => "",
					"CHAIN_ITEM_LINK" => "",
					"VARIABLE_ALIASES" => Array("WEB_FORM_ID" => "WEB_FORM_ID", "RESULT_ID" => "RESULT_ID")
				)
			);*/?>
		</div>
	<?endif;?>
	<script type="text/javascript">
		if($(".specials_tabs_section.specials_slider_wrapp").length && $("#reviews_content").length){
			$("#reviews_content").after($(".specials_tabs_section.specials_slider_wrapp"));
		}
		if($("#ask_block_content").length && $("#ask_block").length){
			$("#ask_block").html($("#ask_block_content").html());
			$("#ask_block_content").remove();
		}
		if($("#reviews_content").length && !$(".tabs_section ul.tabs_content li.cur").length){
			$(".shadow.common").hide();
			$("#reviews_content").show();
		}
		// if($(".tabs_content .stores_tab").length){
			if(!$(".tabs_content .stores_tab .stores_block_wrap .stores_block").length){
				$('.tabs .stores_tab').remove();
				$('.tabs_content .stores_tab').remove();
				$('.extended_info .availability-row .value').addClass('no_store');
			}
		// }
		if($(".tabs_content .prices_tab").length){
			$('.offers_table tr').each(function(){
				if($(this).hasClass("offer_stores")){
					if(!$(this).find('.stores_block_wrap .stores_block').length){
						$(this).prev().find("td").removeClass('opener').find('.opener_icon').remove();
					}
				}
			})
		}
	</script>
<?endif;?>