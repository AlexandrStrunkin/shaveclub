<?
    if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
    $this->setFrameMode(false);    ?>
<span class="title">Задaйте нам вопрос</span>

<div class="form_text">
    <?

        if (!empty($arResult["ERRORS"])){?>
        <?ShowError(implode("<br />", $arResult["ERRORS"]))?>
        <?}
        if (strlen($arResult["MESSAGE"]) > 0){?>
        <?ShowNote($arResult["MESSAGE"])?>
        <?}?>
</div>


<form name="iblock_add" action="<?=POST_FORM_ACTION_URI?>" method="post" enctype="multipart/form-data">
    <?=bitrix_sessid_post()?>

    <div class="input-container">

        <div class="input-cols">
            <div>
                <div>
                    <label><input name="PROPERTY[NAME][0]" type="text" class="input empty" id="name"  value="" placeholder="Ваше имя"/></label></div>
            </div>
            <div>
                <div><label><input name="PROPERTY[168][0]" type="text" class="input empty" id="email" value="" placeholder="E-mail"/></label></div>
            </div>
        </div>
        <label><textarea name="PROPERTY[PREVIEW_TEXT][0]" class="textarea empty" id="mes" placeholder="Сообщение"></textarea></label>
    </div>
    <input type="submit" name="iblock_submit" value="Отправить" class="btn feedback_button"/>


</form>