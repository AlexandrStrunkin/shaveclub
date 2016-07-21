<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
    /** @var array $arParams */
    /** @var array $arResult */
    /** @global CMain $APPLICATION */
    /** @global CUser $USER */
    /** @global CDatabase $DB */
    /** @var CBitrixComponentTemplate $this */
    /** @var string $templateName */
    /** @var string $templateFile */
    /** @var string $templateFolder */
    /** @var string $componentPath */
    /** @var CBitrixComponent $component */
    $this->setFrameMode(true);
?>
<script>
    $(function(){ 

        $("#auth_form_link, #reg_form_link").fancybox({

        });     

    })
</script>

<ul class="menu-nav1">

    <?foreach($arResult["ITEMS"] as $arItem):?>
        <? //arshow($arItem);
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <li id="<?=$this->GetEditAreaId($arItem['ID']);?>"><a href="<?=$arItem["CODE"]?>" style="background: url(<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>);"><?=$arItem["NAME"]?></a></li>

        <?endforeach;?>

    <?if (!$USER->IsAuthorized()) {?>
        <li>
            <a id="auth_form_link" href="#auth_form" style="background: url(/images/personal.png) no-repeat;">Вход</a> 
            &nbsp;<a id="reg_form_link" href="#reg_form">[Регистрация]</a>    
        </li> 
        <?} else {?>
        <li><a href="/personal/" style="background: url(/images/personal.png) no-repeat;">Личный кабинет</a></li>       
        <li><a href="?logout=yes">Выйти</a></li>
        <?}?>
</ul>

<?if ($USER->IsAuthorized()) {?>
    <div class="authorized_user">Вы вошли как: <span><a href="/personal/"><?=$USER->GetLogin()?></a></span></div>
    <?}?>
