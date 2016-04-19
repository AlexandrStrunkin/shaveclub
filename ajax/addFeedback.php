<?require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");?>

<?//arshow($_POST);
if($_POST["id"] and $_POST["question"]):
$block_id=CIBlock::GetList(
        array(""),
        array("CODE"=>"feedback")                 
    );
   $block = $block_id->Fetch();
   $_POST["question"]=iconv('UTF-8','CP1251',$_POST["question"]);
   $userId =  intval($_POST["id"]);
  $resUser = CUser::GetByID($userId); 
 
$obUser = $resUser -> Fetch();
//arshow($obUser);
if(empty($obUser["NAME"])):
$obUser["NAME"] = $obUser["LOGIN"];
endif;

$data = array(168 => $obUser["EMAIL"]);
 $el = new CIBlockElement;
        $arLoadProductArray = Array(
            //"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
            "IBLOCK_ID"      => $block["ID"],
            "PROPERTY_VALUES" => $data, 
            "NAME"    =>     $obUser["NAME"], 
            "PREVIEW_TEXT" => $_POST["question"],  
        );  
  if($PRODUCT_ID = $el->Add($arLoadProductArray)){
   echo "Y";   
  }
  else{
   echo "N";   
  }
        


else:
echo "N";
endif;

?>