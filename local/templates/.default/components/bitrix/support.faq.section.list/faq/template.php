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
<?/*//display sections?>
    <table cellspacing="0" cellpadding="0" class="data-table" width="100%"> 	
    <tr> 		
    <td class="border-gray-body" >
    <?foreach ($arResult['SECTIONS'] as $val):?>
    <?if($arParams["SECTION_ID"]==$val["ID"]) $SELECTED_ITEM = $val?>
    <nobr>
    <!--<div style="padding: 2px 2px 2px <?=17*$val['REAL_DEPTH'].'px'?>;">
    <div class="<?=($arParams["SECTION_ID"]==$val["ID"])?'':'un'?>selected-arrow-faq"></div>
    <?='<span href="'.$val['SECTION_PAGE_URL'].'" class="'.($arParams["SECTION_ID"]==$val["ID"]?'':'un').'selected-faq-item">'.$val['NAME'].'</span> ('.$val['ELEMENT_CNT'].')'?>
    <br clear="all"> -->
    <br>
    <div class="delivery-page" style="">
    <span class="title1"><?=$val['NAME']?><span class="arr"></span></span>
    <div class="delivery-desc" style="display: none;">
    <?  
    //arshow($arParams);
    $arFilter = Array("IBLOCK_ID"=>25, "SECTION_ID"=>$val['SECTION_PAGE_URL'], "ACTIVE"=>"Y");
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), array());
    while($ob = $res->GetNextElement())
    {
    $arFields = $ob->GetFields();
    //arshow($arFields);
    ?> 


    <span class="delivery-title"><?=$arFields['NAME']?></span>
    <p>
    <?=$arFields['DETAIL_TEXT']?>
    </p>  


    <?

    }
    ?>
    </div>
    </div>
    </div>
    </nobr>
    <?endforeach;?>
    </td>
    </tr>
    </table>
    <?if(isset($SELECTED_ITEM)):?>
    <h2><?=$SELECTED_ITEM["NAME"]?></h2>
<?endif;*/?>

<?    $APPLICATION->SetAdditionalCSS($templateFolder."/style_cart.css");
    $APPLICATION->SetAdditionalCSS($templateFolder."/style.css");

    CJSCore::Init(array('fx', 'popup', 'window', 'ajax'));       
?>

<script>     
    $(function(){
        $(".make_order").live("click",function(){
            $(".checkout").click();
        })

        $(".error").on("click",function(){
            $(this).remove();
        })

        $("#sale_order_props label").click(function(){
            $(this).find(".error").remove();
        })

        $(".delivery-title").click(function(){

            // if ($(this).siblings(".faq-text").css("display")=="none"){
            //                $(this).siblings(".faq-text").slideDown(); 
            //            } else {
            //                $(this).siblings(".faq-text").slideUp();
            //            }  

            var el = $(this);
            el.next().slideToggle(300, function () {
                menuHeight();
            });                                         
        })

        //      $(window).resize(function() {
        //            var currentWidth = $(document.body).width();
        //            var differ = 1920 - currentWidth;
        //            if ( currentWidth > '1000') {
        //                $('.нужный div').css('width', '')
        //            }
        //
        //        });


    })    



    //валидаци€ формы заказа
    function checkFormData() {

        var err = "no";

        $("#sale_order_props .input").each(function(){
            //провер€ем валидность email
            if ($(this).attr("placeholder") == "E-Mail" && $(this).val() != "") {
                var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i; 
                if(!pattern.test($(this).val())) {
                    if (!$(this).siblings("span").hasClass("error")) {    
                        $(this).parent().append("<span class='error'>Ќокорректный '" + $(this).attr("placeholder") + "'! <span>!</span></span>");
                    }
                    //если email невалидный, ставим флаг ошибки
                    err = "yes";
                }
            }

            //провер€ем остальные пол€
            else if ($(this).val() == "") {  
                if (!$(this).siblings("span").hasClass("error")) {               
                    $(this).parent().append("<span class='error'>«аполните поле '" + $(this).attr("placeholder") + "'! <span>!</span></span>");
                }
                //если поле не заполнено, ставим флаг ошибки
                err = "yes";   
            }
        })


        if (err == "no"){
            submitForm('Y');
        }
        else {
            return false;
        }

    }



    //отслеживание добавлени€ на страницу выпадающего списка городов и изменеи€ ее ширины
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
                }

                function SetContact(profileId)
                {
                    BX("profile_change").value = "Y";
                    submitForm();
                }
            </script>


            <div class="inside-page-col" > 

                <table cellspacing="0" cellpadding="0" class="data-table" width="100%" style="margin-bottom: 200px;">     
                <tr>         
                <td class="border-gray-body" >
                <?  
                    $i=0;
                ?>

                <?foreach ($arResult['SECTIONS'] as $val):?>
                    <?if ($i<4) {  ?>
                        <?if($arParams["SECTION_ID"]==$val["ID"]) $SELECTED_ITEM = $val?>
                        <nobr>
                        <!--<div style="padding: 2px 2px 2px <?=17*$val['REAL_DEPTH'].'px'?>;">
                        <div class="<?=($arParams["SECTION_ID"]==$val["ID"])?'':'un'?>selected-arrow-faq"></div>
                        <?='<span href="'.$val['SECTION_PAGE_URL'].'" class="'.($arParams["SECTION_ID"]==$val["ID"]?'':'un').'selected-faq-item">'.$val['NAME'].'</span> ('.$val['ELEMENT_CNT'].')'?>
                        <br clear="all"> -->
                        <br>
                        <div class="delivery-page" style="">
                            <span class="title1"><?=$val['NAME']?><span class="arr"></span></span>
                            <div class="delivery-desc" style="display: none;">
                                <?  
                                    
                                    $arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "SECTION_ID"=>$val['SECTION_PAGE_URL'], "ACTIVE"=>"Y");
                                    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), array());
                                    while($ob = $res->GetNextElement())
                                    {
                                        $arFields = $ob->GetFields();
                                        //arshow($arFields);
                                    ?> 

                                    <div class="faq-cont">
                                        <span class="delivery-title"><?=$arFields['NAME']?></span>
                                        <div class="faq-text">
                                            <?=$arFields['DETAIL_TEXT']?>
                                        </div>  
                                    </div>

                                    <?

                                    }
                                    $i++;
                                }
                            ?>
                        </div>
                    </div>
                </div>
                </nobr>
                <?endforeach;?>
            </td>
            </tr>
            </table>


        </div>
        </div> 

        <div><span class="inside-page-col-shadow-faq"></span></div>

        <!--  <span class="inside-page-col-shadow"></span>  -->

        <div class="inside-page-col" >



            <table cellspacing="0" cellpadding="0" class="data-table" width="100%" style="margin-left: 40px; margin-bottom: 200px;">     
            <tr>         
            <td class="border-gray-body" >
            <?
                $i=0;
            ?>

            <?
            
            foreach ($arResult['SECTIONS'] as $val):?>
                <?if ($i>=4) {  ?>
                    <?if($arParams["SECTION_ID"]==$val["ID"]) $SELECTED_ITEM = $val?>
                    <nobr>
                    <!--<div style="padding: 2px 2px 2px <?=17*$val['REAL_DEPTH'].'px'?>;">
                    <div class="<?=($arParams["SECTION_ID"]==$val["ID"])?'':'un'?>selected-arrow-faq"></div>
                    <?='<span href="'.$val['SECTION_PAGE_URL'].'" class="'.($arParams["SECTION_ID"]==$val["ID"]?'':'un').'selected-faq-item">'.$val['NAME'].'</span> ('.$val['ELEMENT_CNT'].')'?>
                    <br clear="all"> -->
                    <br>
                    <div class="delivery-page" style="">
                        <span class="title1"><?=$val['NAME']?><span class="arr"></span></span>
                        <div class="delivery-desc" style="display: none;">
                            <?  
                                //arshow($arParams);
                                $arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "SECTION_ID"=>$val['SECTION_PAGE_URL'], "ACTIVE"=>"Y");
                                $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), array());
                                while($ob = $res->GetNextElement())
                                {
                                    $arFields = $ob->GetFields();
                                    //arshow($arFields);
                                ?> 


                                <div class="faq-cont">
                                    <span class="delivery-title"><?=$arFields['NAME']?></span>
                                    <div class="faq-text">
                                        <?=$arFields['DETAIL_TEXT']?>
                                    </div>  
                                </div> 


                                <?

                                }

                            }  else { $i++;}
                        ?>
                    </div>
                </div>
            </div>
            </nobr>
            <?endforeach;?>
        </td>
        </tr>
        </table>

        </div> 
        </div>

        <?
        }
    }
?>




</div>    
