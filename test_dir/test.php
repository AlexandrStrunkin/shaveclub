<? 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//$order = CSaleOrder::GetById('795');
//arshow($order);
$db_props = CSaleOrderPropsValue::GetList(array(),array("ORDER_ID" => '795')); 
while($orderProp = $db_props->Fetch()) {
 //   arshow($orderProp);
} 
$location = CSaleLocation::GetByID(20);
arshow($location);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>