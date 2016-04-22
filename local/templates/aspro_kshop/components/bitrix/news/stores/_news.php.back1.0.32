<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if( CModule::IncludeModule("iblock") ){
	$rsStore = CIBlockElement::GetList( array(), array("IBLOCK_ID" => $arParams["IBLOCK_ID"]));
	$rsStore->SetUrlTemplates($arParams["SEF_FOLDER"].$arParams["SEF_URL_TEMPLATES"]["detail"]);
	$arStore = $rsStore->GetNext();	
	LocalRedirect($arStore["DETAIL_PAGE_URL"]);
}?>