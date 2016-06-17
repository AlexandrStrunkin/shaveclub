<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<script src="<?=$templateFolder.'/script.js'?>" type="text/javascript"></script>
<? 
    include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/functions.php");
    $arUrls = Array("delete" => $APPLICATION->GetCurPage() . "?action=delete&id=#ID#",
        "delay" => $APPLICATION->GetCurPage() . "?action=delay&id=#ID#",
        "add" => $APPLICATION->GetCurPage() . "?action=add&id=#ID#");

    if (strlen($arResult["ERROR_MESSAGE"]) <= 0) {
        if (is_array($arResult["WARNING_MESSAGE"]) 
            && !empty($arResult["WARNING_MESSAGE"])) { 
                foreach ($arResult["WARNING_MESSAGE"] as $v) { 
                    echo ShowError($v); 
                } 
        }

        $normalCount = count($arResult["ITEMS"]["AnDelCanBuy"]);
        $delayCount = count($arResult["ITEMS"]["DelDelCanBuy"]);
        $subscribeCount = count($arResult["ITEMS"]["ProdSubscribe"]);
        $naCount = count($arResult["ITEMS"]["nAnCanBuy"]);

        $arMenu = array(
            array(
                "ID"=>"AnDelCanBuy", 
                "TITLE"=>GetMessage("SALE_BASKET_ITEMS"), 
                "COUNT"=>$normalCount, 
                "FILE"=>"/basket_items.php"
            )
        );
        if ($delayCount) { 
            $arMenu[] = array(
                "ID"=>"DelDelCanBuy", 
                "TITLE"=>GetMessage("SALE_BASKET_ITEMS_DELAYED"), 
                "COUNT"=>$delayCount, 
                "FILE"=>"/basket_items_delayed.php"
            ); 
        }
        if ($subscribeCount) { 
            $arMenu[] = array(
                "ID"=>"ProdSubscribe", 
                "TITLE"=>GetMessage("SALE_BASKET_ITEMS_SUBSCRIBED"), 
                "COUNT"=>$subscribeCount, 
                "FILE"=>"/basket_items_subscribed.php"
            ); 
        }
        if ($naCount) { 
            $arMenu[] = array(
                "ID"=>"nAnCanBuy", 
                "TITLE"=>GetMessage("SALE_BASKET_ITEMS_NOT_AVAILABLE"), 
                "COUNT"=>$naCount, 
                "FILE"=>"/basket_items_not_available.php"
            ); 
        }
        if ($_COOKIES["KSHOP_BASKET_OPEN_TAB"]) {
            foreach($arMenu as $key => $arElement) { 
                if ($_COOKIES["KSHOP_BASKET_OPEN_TAB"] == $arElement["ID"]) { 
                    $arMenu[$key]["SELECTED"]=true; 
                } 
            }
        }

        if($_REQUEST["section"] == "delay") { 
            foreach($arMenu as $key => $arElement) { 
                if ($arElement["ID"] == "DelDelCanBuy") {
                    $arMenu[$key]["SELECTED"] = true;  
                } else {
                    $arMenu[$key]["SELECTED"] = false; 
                }
            } 
        }

    ?>    

    <form method="post" action="<?= POST_FORM_ACTION_URI ?>" name="basket_form" id="basket_form" class="basket_wrapp">
        <div id="basket_sort" class="basket_sort">
            <ul class="tabs">
                <?foreach($arMenu as $key => $arElement) {?>
                    <li<?= ($arElement["SELECTED"] ? ' class="cur"' : ''); ?> item-section="<?= $arElement["ID"] ?>">
                        <span><?= $arElement["TITLE"] ?></span>
                        <span class="quantity">&nbsp;(<span class="count"><?= $arElement["COUNT"] ?></span>)</span>
                        <i class="triangle"></i>
                    </li>
                    <?}?>
            </ul>
        </div>
        <ul class="tabs_content basket">
            <?foreach($arMenu as $key => $arElement){?>
                <li <?= ($arElement["SELECTED"] ? ' class="cur"' : ''); ?> item-section="<?= $arElement["ID"] ?>">
                    <?include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . $arElement["FILE"]);?>
                </li>
            <?}?>
        </ul>
    </form>

    <script>
        $("#basket_form").ready(function() {
            if (!$(".tabs > li.cur").length) {
                $.cookie("KSHOP_BASKET_OPEN_TAB",  $(".tabs_content > li").first().attr("item-section"));
                $(".tabs > li").first().addClass("cur");
                $(".tabs_content > li").first().addClass("cur");
            }
        });

        $(window).load(function() {
            if ($(window).outerWidth()>600) {
                $("#basket_form .tabs_content.basket li.cur td").each(function() { $(this).width($(this).width()); });

                $("#basket_form .tabs_content.basket li.cur tfoot .delay-cell").width($("#basket_form .tabs_content.basket li.cur tbody td.delay-cell").first().width());
                $("#basket_form .tabs_content.basket li.cur tfoot .row_values").width($("#basket_form .tabs_content.basket li.cur tbody td.summ-cell").first().width());
            }    
        });

        $(".tabs > li").live("click", function() {
            if (!$(this).is(".cur")) {
                $.cookie("KSHOP_BASKET_OPEN_TAB",  $(this).attr("item-section"));
                $(this).siblings().removeClass("cur");
                $(this).addClass("cur");
                $(".tabs_content > li").removeClass("cur");
                $(".tabs_content > li:eq("+$(this).index()+")").addClass("cur");
                $(".tabs_content > li:eq("+$(this).index()+") td").each(function() { 
                    $(this).width($(this).width()); 
                });
            }
        });

        <?if ($arParams["AJAX_MODE_CUSTOM"]=="Y") {?>
            var animateRow = function(row) {
                $(row).find("td.thumb-cell img").css({"maxHeight": "inherit", "maxWidth": "inherit"}).fadeTo(50, 0);
                var columns = $(row).find("td");
                $(columns).wrapInner('<div class="slide"></div>');
                $(row).find(".summ-cell").wrapInner('<div class="slide"></div>');
                setTimeout(function(){$(columns).animate({"paddingTop": 0, "paddingBottom": 0}, 50)}, 0);
                $(columns).find(".slide").slideUp(333);
            }

            $("#basket_form").ready(function() {
                $('form[name^=basket_form] a.apply-button').click(function() {
                    $('form[name^=basket_form]').prepend('<input type="hidden" name="BasketRefresh" value="Y" />');
                    $.post( arKShopOptions['SITE_DIR']+'basket/', $("form[name^=basket_form]").serialize(), $.proxy(
                        function( data) {                    
                            $('form[name^=basket_form] input[name=BasketRefresh]').remove();
                            animateBasketLine();
                            postAnimateResult(data, 333, 'update');
                        }
                    ));
                });


                $('form[name^=basket_form] .counter_block input[type=text]').change( function(e) {
                    e.preventDefault();
                    updateQuantity($(this).attr("id"), $(this).attr("data-id"), $(this).attr("step"));
                    preAnimateResult($(this).attr("id"), $(this).attr("step"), "set", 200)
                });

                $('form[name^=basket_form] .remove').live("click", function(e) {
                    e.preventDefault();
                    animateRow($(this).parents("tr"));
                    deleteProduct($(this).parents("tr[data-id]").attr('data-id'), $(this).parents("li").attr("item-section"));
                });

                $('form[name^=basket_form] .delay .wish_item').live("click", function(e) {
                    e.preventDefault();
                    animateRow($(this).parents("tr"));
                    delayProduct($(this).parents("tr[data-id]").attr('data-id'), $(this).parents("li").attr("item-section"));
                })

                $('form[name^=basket_form] .add .wish_item').live("click", function(e) {
                    e.preventDefault();
                    animateRow($(this).parents("tr"));
                    var basketId = $(this).parents("tr[data-id]").attr('data-id');
                    var controlId =  "QUANTITY_INPUT_"+basketId;
                    var ratio =  $(this).parents("tr[data-id]").find("#"+controlId).attr("step");
                    var quantity =  $(this).parents("tr[data-id]").find("#"+controlId).attr("value");
                    updateQuantity(controlId, basketId, ratio, false);
                    addProduct(basketId, $(this).parents("li").attr("item-section"));
                })
            });
        <?}?>
    </script>

    <?} else {?>
        <div id="basket_form">
            <?include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/basket_items.php");?>
        </div>
    <?}?>
