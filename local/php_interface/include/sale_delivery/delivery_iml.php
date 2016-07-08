<?
$module_id="ipol.iml";
CModule::IncludeModule($module_id);

// установим метод CDeliveryIML::Init в качестве обработчика события
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$module_id.'/classes/general/imldelivery.php')){
	AddEventHandler("sale", "onSaleDeliveryHandlersBuildList", array('CDeliveryIML', 'Init')); 
}
?>