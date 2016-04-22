<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props_format.php");?>  
<?     
$bHideProps = false;
    ?>

<div class="contacts-block">

    <h1 class="h1">оформление заказа</h1>
    <span class="title">контактные данные</span>
    <div class="input-container">

        <?  
//            $bHideProps = false;   
         
    
        ?> 
           
        <div id="sale_order_props" >
            <?
                PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_N"], $arParams["TEMPLATE_LOCATION"]);  
                PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"], $arParams["TEMPLATE_LOCATION"]);
            ?>
        </div>


        <script type="text/javascript">
            function fGetBuyerProps(el)
            {
                var show = '<?=GetMessageJS('SOA_TEMPL_BUYER_SHOW')?>';
                var hide = '<?=GetMessageJS('SOA_TEMPL_BUYER_HIDE')?>';
                var status = BX('sale_order_props').style.display;
                var startVal = 0;
                var startHeight = 0;
                var endVal = 0;
                var endHeight = 0;
                var pFormCont = BX('sale_order_props');
                pFormCont.style.display = "block";
                pFormCont.style.overflow = "hidden";
                pFormCont.style.height = 0;
                var display = "";

                if (status == 'none')
                {
                    el.text = '<?=GetMessageJS('SOA_TEMPL_BUYER_HIDE');?>';

                    startVal = 0;
                    startHeight = 0;
                    endVal = 100;
                    endHeight = pFormCont.scrollHeight;
                    display = 'block';
                    BX('showProps').value = "Y";
                    el.innerHTML = hide;
                }
                else
                {
                    el.text = '<?=GetMessageJS('SOA_TEMPL_BUYER_SHOW');?>';

                    startVal = 100;
                    startHeight = pFormCont.scrollHeight;
                    endVal = 0;
                    endHeight = 0;
                    display = 'none';
                    BX('showProps').value = "N";
                    pFormCont.style.height = startHeight+'px';
                    el.innerHTML = show;
                }

                (new BX.easing({
                    duration : 700,
                    start : { opacity : startVal, height : startHeight},
                    finish : { opacity: endVal, height : endHeight},
                    transition : BX.easing.makeEaseOut(BX.easing.transitions.quart),
                    step : function(state){
                        pFormCont.style.height = state.height + "px";
                        pFormCont.style.opacity = state.opacity / 100;
                    },
                    complete : function(){
                        BX('sale_order_props').style.display = display;
                        BX('sale_order_props').style.height = '';
                    }
                })).animate();
            }
        </script>


    </div>
</div>       
<div style="clear: both;"></div>