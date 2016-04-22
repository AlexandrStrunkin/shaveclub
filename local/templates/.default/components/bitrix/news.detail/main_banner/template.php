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
<header class="header" style="background: url(<?=($arResult["DETAIL_PICTURE"]["SRC"])?>) 50% 0 no-repeat ;">
    <h1 class="h1 wow fadeIn" style="color: <?=$arResult["PROPERTIES"]["NAME_COLOR"]["VALUE"]?>"><?=$arResult["NAME"]?></h1>      
    <h2 class="h2 wow fadeInLeft" data-wow-delay="500ms" style="color: <?=$arResult["PROPERTIES"]["DESC_COLOR"]["VALUE"]?>"><?=$arResult["PROPERTIES"]["DESC"]["VALUE"]?></h2>
    <ul class="header-ul wow fadeInUp" data-wow-delay="1500ms">
        <?for ($i=1; $i<=3; $i++){?> 
            <li style="background: url(<?=CFile::GetPath($arResult["PROPERTIES"]["IMG_".$i]["VALUE"])?>) no-repeat; color: <?=$arResult["PROPERTIES"]["DESC_COLOR"]["VALUE"]?>"><?=$arResult["PROPERTIES"]["PROP_".$i]["VALUE"]?></li>
            <?}?>

    </ul>
</header>