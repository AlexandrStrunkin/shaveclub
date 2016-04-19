<?
    $soc = CIBlockElement::GetList(array(),array("IBLOCK_CODE"=>"social","ACTIVE"=>"Y"),false,false,array("NAME","PREVIEW_PICTURE"));
    while($arSoc = $soc->Fetch()) {
    ?>      
    <a href="<?=$arSoc["NAME"]?>" target="_blank" style="background: url(<?=CFile::GetPath($arSoc["PREVIEW_PICTURE"])?>) no-repeat ;"></a>    
    <?}?>