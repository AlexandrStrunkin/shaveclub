<?
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
    $users = CUser::GetList($by="id", $sort = "asc", array(), array("FIELDS"=>array("ID", "LID"),"SELECT"=>array("UF_SITE")));
    $cUser = new CUser;
    $fieldsS1 = array( 
        "UF_SITE" => 22, 
    ); 
    $fieldsS2 = array( 
        "UF_SITE" => 23, 
    ); 
    while ($user = $users->GetNext()){
        if(empty($user["UF_SITE"])){
            arshow($user);
            switch($user["LID"]){
                case "s1":
                    $cUser->Update($user["ID"], $fieldsS1);
                    break;
                case "s2":
                    $cUser->Update($user["ID"], $fieldsS2);
                    break;
                default:
                {
                    $cUser->Update($user["ID"], $fieldsS1);
                }
            }
        }  
    }
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>