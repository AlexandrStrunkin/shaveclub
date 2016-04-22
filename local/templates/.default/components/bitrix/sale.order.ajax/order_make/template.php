<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
    if($USER->IsAuthorized() || $arParams["ALLOW_AUTO_REGISTER"] == "Y")
    {
        if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")
        {
            if(strlen($arResult["REDIRECT_URL"]) > 0)
            {
                $APPLICATION->RestartBuffer();
            ?>
            <script type="text/javascript">
                window.top.location.href='<?=CUtil::JSEscape($arResult["REDIRECT_URL"])?>';
            </script>
            <?
                die();
            }

        }
    }

    $APPLICATION->SetAdditionalCSS($templateFolder."/style_cart.css");
    $APPLICATION->SetAdditionalCSS($templateFolder."/style.css");

    CJSCore::Init(array('fx', 'popup', 'window', 'ajax'));       

?>

<script>
    $(function(){
        $("#ORDER_PROP_3").inputmask("+7(999) 999-99-99");

        $(".make_order").live("click",function(){
            $(".checkout").click();
        })

        $("body").on("click",'.error',function(){
            $(this).remove();
        })

        $("#sale_order_props label").click(function(){
            $(this).find(".error").remove();
        })
    })    

    $(document).ready(function(){
        if ($("#discountVal").val()=='100%') {
            $('#ID_PAY_SYSTEM_ID_17').click();
        }       
    });



    //валидация формы заказа
    function checkFormData() {

        var err = "no";

        $("#sale_order_props .input").each(function(){
            //проверяем валидность email
            if ($(this).attr("placeholder") == "E-Mail" && $(this).val() != "") {
                var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i; 
                if(!pattern.test($(this).val())) {
                    if (!$(this).siblings("span").hasClass("error")) {    
                        $(this).parent().append("<span class='error'>Некорректный '" + $(this).attr("placeholder") + "'! <span>!</span></span>");
                    }
                    //если email невалидный, ставим флаг ошибки
                    err = "yes";
                    $(".inside-page-col:eq(0) .jspPane").animate({top:"0px"}, 500);
                }
            }

            //проверяем остальные поля
            else if ($(this).val() == "") {  
                if (!$(this).siblings("span").hasClass("error")) {               
                    $(this).parent().append("<span class='error'>Заполните поле '" + $(this).attr("placeholder") + "'! <span>!</span></span>");
                }
                //если поле не заполнено, ставим флаг ошибки
                err = "yes"; 
                $(".inside-page-col:eq(0) .jspPane").animate({top:"0px"}, 500);  
            }
        })

        <?if (!$USER->IsAuthorized()){?>

            //если нет ошибок в заполнеии формы, проверяем email на существование в базе
            if (err == "no") {
                err = "yes";
                //проверяем email на существование
                var email = $("#ORDER_PROP_2").val();
                $.post('/ajax/checkEmail',{email:email},function(data){   
                    if (data == "Y") {                                     
                        $("#auth_form_link").click();
                        $("#auth_form .form_error").css("display","block").html("Ваш email уже зарегистрирован в системе. Пожалуйста, авторизуйтесь!");
                        $("#auth_email").val(email);  
                    }
                    else {
                        err = "no"; 
                        submitForm('Y'); 
                    }
                }) 
            }      
            <?}?>



        if (err == "yes"){
            return false;
        }
        else {
            submitForm('Y'); 
        }    

    }



    //отслеживание добавления на страницу выпадающего списка городов и изменеия ее ширины
    var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;
    var list = document.querySelector('body');

    var observer = new MutationObserver(function(mutations) {  
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                var input_width = $(".inside-page .input").outerWidth();
                $(".search-popup").css("width",input_width - 2 + "px");
            }
        });
    });

    observer.observe(list, {
        attributes: true, 
        childList: true, 
        characterData: true 
    });
    /////////////////////////////////////////////////////



</script>




<a name="order_form"></a>

<NOSCRIPT>
    <div class="errortext"><?=GetMessage("SOA_NO_JS")?></div>
</NOSCRIPT>

<?
    if (!function_exists("getColumnName"))
    {
        function getColumnName($arHeader)
        {
            return (strlen($arHeader["name"]) > 0) ? $arHeader["name"] : GetMessage("SALE_".$arHeader["id"]);
        }
    }

    if (!function_exists("cmpBySort"))
    {
        function cmpBySort($array1, $array2)
        {
            if (!isset($array1["SORT"]) || !isset($array2["SORT"]))
                return -1;

            if ($array1["SORT"] > $array2["SORT"])
                return 1;

            if ($array1["SORT"] < $array2["SORT"])
                return -1;

            if ($array1["SORT"] == $array2["SORT"])
                return 0;
        }
    }
?>

<div class="bx_order_make">



    <?


        if(!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N")
        {
            if(!empty($arResult["ERROR"]))
            {
                foreach($arResult["ERROR"] as $v)
                    echo ShowError($v);
            }
            elseif(!empty($arResult["OK_MESSAGE"]))
            {
                foreach($arResult["OK_MESSAGE"] as $v)
                    echo ShowNote($v);
            }

            include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/auth.php");
        }
        else
        {
            if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")
            {
                if(strlen($arResult["REDIRECT_URL"]) == 0)
                {
                    include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/confirm.php");
                }
            }
            else
            {
            ?>
            <script type="text/javascript">

                function checkBundleStatus(){
                    //console.log($('label[for="ID_DELIVERY_pickpoint_postamat"] div.price').text());     
                    data = localStorage.getItem('isAStartBundle')
                    if (data!="T"){
                        finalPrice = parseInt($('label[for="ID_DELIVERY_pickpoint_postamat"] div.price').text()) - 200;
                        if(finalPrice<0){
                            finalPrice = 0;
                        }
                        $('label[for="ID_DELIVERY_pickpoint_postamat"] div.price').html(finalPrice + ' <span class="rouble">i</span>');
                        $('.deliveryCostSummary').html(finalPrice + 'Р');
                        $('.tableSum').html(parseInt($('.tableSum').text())+finalPrice+' Р');
                        $('.finalSumYellow').html(parseInt($('.finalSumYellow').text())+finalPrice+' Р');
                    }
                }

                 

                function submitForm(val)
                {
                    if(val != 'Y')
                        BX('confirmorder').value = 'N';

                    var orderForm = BX('ORDER_FORM');
                    BX.showWait();
                    BX.ajax.submit(orderForm, ajaxResult);



                    return true;
                }

                function ajaxResult(res)
                {
                    try
                    {
                        var json = JSON.parse(res);
                        BX.closeWait();

                        if (json.error)
                        {
                            return;
                        }
                        else if (json.redirect)
                        {
                            window.top.location.href = json.redirect;
                        }
                    }
                    catch (e)
                    {
                        BX('order_form_content').innerHTML = res;

                        BX('summary_no_ajax').innerHTML = BX('summary_ajax').innerHTML;
                    }

                    BX.closeWait();
                    $("#ORDER_PROP_3").inputmask("+7(999) 999-99-99");
                    checkBundleStatus();
                    $(".inside-page-col").jScrollPane({showArrows: true, scrollbarMargin: 0});  

                }

                function SetContact(profileId)
                {
                    BX("profile_change").value = "Y";
                    submitForm();
                }
            </script>

            <div class="inside-page-col" > 
                <div class="div" >

                    <?if($_POST["is_ajax_post"] != "Y")
                        {
                        ?><form action="<?=$APPLICATION->GetCurPage();?>" method="POST" name="ORDER_FORM" id="ORDER_FORM" enctype="multipart/form-data">   
                            <?=bitrix_sessid_post()?>
                            <div id="order_form_content">     
                                <?
                                }
                                else
                                {
                                    $APPLICATION->RestartBuffer();
                                }

                            ?>

                            <?
                                if(!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y")
                                {   

                                    foreach($arResult["ERROR"] as $v) {
                                        //  echo ShowError($v);                                             
                                    }

                                ?>
                                <script type="text/javascript">
                                    top.BX.scrollToNode(top.BX('ORDER_FORM'));
                                </script>
                                <?
                                }

                                include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/person_type.php");
                                include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props.php");
                                if ($arParams["DELIVERY_TO_PAYSYSTEM"] == "p2d")
                                {
                                    include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");
                                    include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");
                                }
                                else
                                {
                                    include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");
                                    include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");
                                }

                            ?>

                            <?
                                echo "<script>localStorage.setItem('isAStartBundle','".$_SESSION['startBundle']."');</script>"
                            ?>


                            <?

                                include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/related_props.php");                                  
                            ?>

                            <div id="summary_ajax" style="display: none !important;">     
                                <?
                                    include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/summary.php");
                                    if(strlen($arResult["PREPAY_ADIT_FIELDS"]) > 0)
                                        echo $arResult["PREPAY_ADIT_FIELDS"];
                                ?>
                            </div>


                            <?if($_POST["is_ajax_post"] != "Y")
                                {
                                ?> 


                            </div>
                            <input type="hidden" name="confirmorder" id="confirmorder" value="Y">
                            <input type="hidden" name="profile_change" id="profile_change" value="N">
                            <input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
                            <input type="hidden" name="json" value="Y">
                            <div class="bx_ordercart_order_pay_center" style="display: none;">
                                <a href="javascript:void();" onclick="checkFormData(); return false;" class="checkout"><?=GetMessage("SOA_TEMPL_BUTTON")?></a>
                            </div>
                        </form>


                        <?
                            if($arParams["DELIVERY_NO_AJAX"] == "N")
                            {
                            ?>
                            <div style="display:none;"><?$APPLICATION->IncludeComponent("bitrix:sale.ajax.delivery.calculator", "", array(), null, array('HIDE_ICONS' => 'Y')); ?></div>
                            <?
                            }
                        }
                        else
                        {
                        ?>
                        <script type="text/javascript">
                            top.BX('confirmorder').value = 'Y';
                            top.BX('profile_change').value = 'N';
                        </script>
                        <?
                            die();
                    } ?>
                </div>
            </div>

            <div class="inside-page-col order_shadow" > 

                <div id="summary_no_ajax">
                    <?
                        include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/summary.php");
                        if(strlen($arResult["PREPAY_ADIT_FIELDS"]) > 0)
                            echo $arResult["PREPAY_ADIT_FIELDS"];
                    ?>
                </div>
            </div> 

            <?
            }
        }
    ?>




</div>