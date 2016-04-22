<?
    /**
    * @global CMain $APPLICATION
    * @param array $arParams
    * @param array $arResult
    */
    if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
        die();
//        arshow($arResult);
//        arshow($_POST);
?>

<div class="bx-auth-profile">


    <script type="text/javascript">
        <!--
        var opened_sections = [<?
            $arResult["opened"] = $_COOKIE[$arResult["COOKIE_PREFIX"]."_user_profile_open"];
            $arResult["opened"] = preg_replace("/[^a-z0-9_,]/i", "", $arResult["opened"]);
            if (strlen($arResult["opened"]) > 0)
            {
                echo "'".implode("', '", explode(",", $arResult["opened"]))."'";
            }
            else
            {
                $arResult["opened"] = "reg";
                echo "'reg'";
            }
        ?>];
        //-->

        var cookie_prefix = '<?=$arResult["COOKIE_PREFIX"]?>';
        
        
        $(function(){
            //����� �� ����� ��������
            $("#personal_phone").inputmask("+7(999) 999-99-99");
            
            //��������� email � ���� �����
            $("#personal_login").val($("#personal_email").val()); 
            
            $("#personal_email").keyup(function(){
               $("#personal_login").val($(this).val()); 
            })     
        })
    </script>


    <?/*
        <form action="#" method="post" id="kabinet_form">
        <div class="contacts-block1">
        <h1 class="h1">������ �������</h1>
        <span class="title">������ ������</span>

        <div class="input-container">
        <label><input type="text" name="fio" class="input" value="�������� ���� �������������"/></label>
        <label><input type="text" name="email" class="input gray" value="victorovoleg@gmail.com"/></label>
        <label><input type="text" name="password" id="password" class="input empty" onfocus="clearField('password', '����� ������')" onblur="blurField('password', '����� ������')" value="����� ������"/></label>
        <label><input type="text" name="confirm_password" id="confirm_password" onfocus="clearField('confirm_password', '������������� ������')" onblur="blurField('confirm_password', '������������� ������')" class="input empty" value="������������� ������"/></label>
        <label><input type="text" name="phone" class="input" value=""/><span class="error">�� �� ������� ���������� �������<span>!</span></span></label>
        </div>
        </div>

        <div class="delivery-address1">
        <div>

        <a href="#" class="btn">���������</a>
        <p class="delivery-address1-text"><span><span>��������� ��� ���������� ���� ��� ����������</span></span></p>

        </div>

        </div>

        </form>
    */?>


    <form method="post" name="form1" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data" id="kabinet_form">

        <div class="contacts-block1">
            <h1 class="h1">������ �������</h1>

            <?ShowError($arResult["strProfileError"]);?>
            <?
                if ($arResult['DATA_SAVED'] == 'Y') {?>
                <div class="data_saved">
                   <? ShowNote(GetMessage('PROFILE_DATA_SAVED'));?>
                </div>
                <?}?>

            <span class="title">������ ������</span>

            <div class="input-container">

                <?=$arResult["BX_SESSION_CHECK"]?>
                <input type="hidden" name="lang" value="<?=LANG?>" />
                <input type="hidden" name="ID" value=<?=$arResult["ID"]?> />

                <label><input type="text" name="NAME" maxlength="255" value="<?=$arResult["arUser"]["LAST_NAME"].' '.$arResult["arUser"]["NAME"].' '.$arResult["arUser"]["SECOND_NAME"]?>" class="input" placeholder="���"/></label>   

                <label><input type="text" name="EMAIL" maxlength="255" value="<? echo $arResult["arUser"]["EMAIL"]?>" class="input" placeholder="Email" id="personal_email"/></label>

                <input type="hidden" name="LOGIN" maxlength="255" value="<? echo $arResult["arUser"]["LOGIN"]?>" class="input" placeholder="�����" id="personal_login"/>

                <label><input type="text" name="PERSONAL_PHONE" maxlength="255" value="<?=$arResult["arUser"]["PERSONAL_PHONE"]?>" id="personal_phone" class="input" placeholder="�������"/></label>

                <?if($arResult["arUser"]["EXTERNAL_AUTH_ID"] == ''):?>

                    <label><input type="password" name="NEW_PASSWORD" maxlength="50" value="" autocomplete="off" class="input" placeholder="����� ������"/></label>

                    <label><input type="password" name="NEW_PASSWORD_CONFIRM" maxlength="50" value="" autocomplete="off" class="input" placeholder="����� ������ ��� ���"/></label>

                    <?endif?>



            </div>
        </div>

        <div class="delivery-address1">
            <div>

                <input type="submit" class="btn" name="save" value="<?=(($arResult["ID"]>0) ? GetMessage("MAIN_SAVE") : GetMessage("MAIN_ADD"))?>">
                <p class="delivery-address1-text"><span><span>��������� ��� ���������� ���� ��� ����������</span></span></p>

            </div>

        </div>    
    </form>

</div>