var isMobile;
$(document).ready(function () {
    /**************/
    //главная страница
    isMobile = ($(".mobile-top").css("display") !== "none");
    //высота меню
    if (isMobile) {
        $(".menu").css({"height": $(".main-container").height(), "margin-left": -220, display: "none"});
        $(".main-container").css("margin-left", 0).css("width", $(window).width());
    } else {
        $(".menu").css({"height": "100%", "margin-left": 0, display: "block"});
        $(".main-container").css("margin-left", $(".menu").width()).css("width", $(window).width() - $(".menu").width());
    }
    menuHeight();
    setTimeout(menuHeight, 10);


    //слайдеры главная
    $(".scroll-slider").each(scrollSlider);

    //мобильная версия (показать/скрыть меню)
    $(".menu-btn").click(function () {
        var menu = $(".menu"), el = $(".main-container, .mobile-top");
        if (menu.css("display") !== "none") {
            el.animate({"margin-left": 0}, 500);
            menu.animate({"margin-left": -220}, 500, function () {
                menu.css({"display": "none", width: 0});
                //menuHeight();
            });
        } else {
            el.css("position", "absolute");
            el.animate({"margin-left": 220}, 500);
            menu.css({display: "block", width: 220}).animate({"margin-left": 0}, 500, function () {
                el.css("position", "static");
                if ($(window).width() < 1000)
                    $(".menu").css({"height": $(".main-container").height()});
                $(".menu").jScrollPane({showArrows: true, scrollbarMargin: 0});
                //menuHeight();
            });
        }

    });

    $(".overlook").click(function () {
        //  window.location.href = "#";
    });


    //эффекты анимации на главной
    if (!isMobile)
        new WOW().init();

    $(".main-block1-col ul li.desc a").click(function(){
        if (isMobile) $(this).parent().addClass("hover");
        return false;
    }).hover(function(){
        if (!isMobile) $(this).parent().addClass("hover");
        }, function(){
            if (!isMobile) $(this).parent().removeClass("hover");
    });

    //***************
    //inside page (oform.html)

    //стиль инпутов, появление сообщений об ошибках
    $(".input, .textarea").focus(function () {
        $(this).addClass("active");
    }).blur(function () {
        var el = $(this), v = el.val();
        el.removeClass("active");
        if (v.length === 0)
            el.closest("label").find(".error").show();
    });
    //скрыть сообщение об ошибке
    $("span.error").click(function () {
        $(this).closest("label").find("input").focus();
        $(this).hide();
    });

    $('.title').click(function(){
        $(".active").click();
//        alert(123)
    });

    //выбор типа доставки
    /*
    $(".delivery-type .item").click(function () {
    $(".delivery-type .item").removeClass("active");
    $(this).addClass("active");
    });
    $(".courier-service .item").click(function () {
    var el = $(this);
    $(".delivery-type .item").removeClass("active");
    if (el.hasClass("active"))
    el.removeClass("active");
    else el.addClass("active");
    });      */

    $(".online .title, .cash").unbind("click").click(function () {

        $(".online-detail, .cash-detail").slideToggle(300, function () {

        });
        var el = $(".online .title").parent().parent();
        if (el.hasClass("active")) {
            el.removeClass("active");

        } else {
            el.addClass("active");

        }
        var el = $(".cash");
        if (el.hasClass("active")) {
            el.removeClass("active");
            $(".payment .img").show();
            $(".payment .img1").hide();
        } else {
            el.addClass("active");
            setTimeout(function () {
                $(".payment .img1").show();
                $(".payment .img").hide();
                }, 100);

        }

    });
    $(".inside-page-col-close").hover(function(){
        $(this).addClass("active");
        }, function(){
            $(this).removeClass("active");
    }).click(function () {
        //  $(this).removeClass("active");
        //  $(this).fadeOut(500);
        //    $(this).next().fadeOut(500);

    });

    $(".bx-ui-sls-fake").focus(function(){
        $(".dropdown-block").addClass("active-input");
    });

    $(".bx-ui-sls-fake").blur(function(){
        $(".dropdown-block").removeClass("active-input");

    });


    //нестандартный select
    //if ($("select").length > 0)
    //    $("select").selectBox();
    if ($("select").length > 0) {
        cuSel({changedEl: "select", visRows: 3, scrollArrows: true});
        $(".cusel").each(function () {
            var v = $(this).find("input").val();
            if (v != -1) $(this).find(".cuselText").addClass("active");
        });
        $(".cusel-scroll-pane span").click(function () {
            $(this).closest("label").find(".cuselText").addClass("active");
        });

    }


    /**************/
    //страница контакты
    //init();
    /*var div = $(".contacts-map");
    if (div.length > 0)
    div.gMap({
    markers: [
    { latitude: 55.899018,
    longitude: 37.598633}
    ],
    icon: { image: "images/marker.png",
    iconsize: [78, 131]
    },
    latitude: 55.899018,
    longitude: 37.598633,
    zoom: 13 });
    */
    $(".contacts_form .btn, #kabinet_form .btn, .feedback .btn, .opt-order .btn").click(function () {
        // $(this).closest("form").submit();
        // return false;
    });
    $(".opt-order form").submit(function () {
        $(".opt-order-popup").show();
        return false;
    });
    $(".opt-order-popup .btn").click(function () {
        $(".opt-order-popup").fadeOut(500);
        return false;
    });
    /**************/
    //страница доставка
    $(".delivery-page .title1").click(function () {
        var el = $(this);
        if (el.hasClass("active")) {
            el.removeClass("active");
        } else {
            el.addClass("active");
        }
        el.next().slideToggle(300, function () {
            menuHeight();
        });
        curr_offset_pos=window.pageYOffset;
        window.scrollTo(0,curr_offset_pos);
       curr_scroll_pos=$(".col2 .jspPane").css("top");
       $(".col2 .jspPane").css("top")=curr_scroll_pos;

        $(".inside-page-col").jScrollPane({showArrows: true, scrollbarMargin: 0, maintainPosition: false });
        // $('.bx_page').jScrollPane({showArrows: true, scrollbarMargin: 0, maintainPosition: false});
    });
    /**************/
    //страница свой план
    $(".svoy-plan-ul .plus, .svoy-plan-ul .minus").click(function () {

        var el = $(this).parent().find("input"), val = el.val();
        val = parseInt(val, 10);
        if (!isNaN(val)) {
            if ($(this).hasClass("plus"))  val++;
            else val--;
            if (val < 0 ) {
                val = 0;
            }
            if (val >= 0)
                el[0].value = val;
            el.change();
        }
    });

    $("input.count").keyup(function () {
        var val  = $(this).val().replace(/\D+/,'');
        if (val < 0 || val == "") {
            val = 0;
        }
        $(this).val(val);
    });


    var refreshSum = function () {
        var sum = 0, c = 0;
        $(".svoy-plan-ul li").each(function () {
            var el1 = $(this).find("input.count"), el2 = $(this).find("input.price");
            if (el1.length > 0 && el2.length > 0 && !isNaN(el1.val()) && !isNaN(el2.val())) {
                sum += parseFloat(el1.val()) * parseFloat(el2.val());
                c += parseFloat(el1.val());
            }
        });
        sum = splitNums(" ", sum);
        $(".gen-sum").text(sum);

    };
    $(".svoy-plan-ul input.count").change(function () {
        var val = $(this).val(), el2 = $(this).closest("li").find("input.price");
        if (val >= 0 && !isNaN(val)) {
            $(this).closest("li").find(".sum span:first").text(splitNums(" ", val * el2.val()));
            refreshSum();
        }
    });

    $(".col-container .close").click(function () {
        $(this).parent().fadeOut(500);
        $(this).parent().parent().find(".quest").addClass("gray");
    });
    $(".quest").click(function () {
        $(this).removeClass("gray");
        $(this).parent().find(".col-container").show();
        return false;
    });

    $(".img .plus").click(function () {
        $(".img .plus").removeClass("active");
        el = $(this);
        el.addClass("active");
        var id = el.attr("rel");

        $(".razor_full_description").hide();

        $(".preview-product-ul li").hide();
        $(".preview-product-ul li[rel=" + id + "]").fadeIn(200);
        $(".inside-page-col").jScrollPane({showArrows: true, scrollbarMargin: 0});
       //  $('.bx_page').jScrollPane({showArrows: true, scrollbarMargin: 0});

        /*
        var el = $(this), id = el.attr("id"), el1;
        if (!id) return;
        id = id.replace("plus", "");
        el1 = $("#block" + id);

        $(".plus").removeClass("active");
        $(".preview-product-ul .no-active").show();
        $(".preview-product-ul .active").hide();


        el.addClass("active");
        el1.find(".active").show();
        el1.find(".no-active").hide();
        $(".preview-product-ul li:visible").hide();
        el1.next().fadeIn(200);
        el1.next().next().fadeIn(200);
        el1.fadeIn(200);
        $(".preview-product-ul .img1").removeClass("active1");
        el1.find(".img1").addClass("active1");
        */


        /*
        if ($(window).width() <= 1280)
        window.scroll(0, parseFloat(id) * 167 - 167);
        else {
        $(".inside-page-col1").data('jsp').scrollToY(parseFloat(id) * 167 - 167, 500);
        window.scroll(0, 0);
        }
        */
    });

    // $(".preview-product-ul .img1").css("background-image", "url(" + $(".preview-product .img img").attr("src") + ")");

    // $(".preview-product-ul li").click(function () {
    /*var el = $(this), id = el.attr("id"), el1;
    id = id.replace("block", "");*/
    /*el1 = $("#plus" + id);
    el1.click();*/
    //});
    /*$(".preview-product .plus").each(function(){
    var el = $(this), id = el.attr("id"), p = el.position();
    id = id.replace("plus", "");
    $("#block" + id + " .img1").css("background-position", "-"+p.left+"px -"+ p.top +"px");
    });*/
    // $("#plus3").click();

    $(".cuselOpen").click(function () {
        return false;
    });

    $(".jspContainer").hover(function () {
        $(this).find(".jspVerticalBar").fadeIn(500);
        }, function () {
            $(this).find(".jspVerticalBar").fadeOut(500);
    });

    //главная (эффект при наведении)
    $(".overlook, .times").hover(function () {
        $(".overlook, .times").addClass("active");
        }, function () {
            $(".overlook, .times").removeClass("active");
    });
    $(".main-block3-col .text-content, .main-block3-col .triangle").hover(function () {
        $(".main-block3-col .text-content, .main-block3-col .triangle").addClass("active");
        $(".main-block3-col .triangle img").attr("src", "images/bg32_hover.png");
        }, function () {
            $(".main-block3-col .text-content, .main-block3-col .triangle").removeClass("active");
            $(".main-block3-col .triangle img").attr("src", "images/bg32.png");
    });

    $(".inside-page-col").jScrollPane({showArrows: true, scrollbarMargin: 0});
    // $('.bx_page').jScrollPane({showArrows: true, scrollbarMargin: 0});
    $('.aboutus .scroll-pane').jScrollPane();
});
function splitNums(delimiter, str) {
    str = str.toString();
    str = str.replace(/(\d+)(\.\d+)?/g,
        function (c, b, a) {
            return b.replace(/(\d)(?=(\d{3})+$)/g, '$1' + delimiter) + (a ? a : '')
    });

    return str;
};
function menuHeight() {
    var w = $(window).width();

    var newIsMobile = ($(".mobile-top").css("display") !== "none");

    if (newIsMobile && (newIsMobile !== isMobile)) {
        $(".menu").css({"height": $(".main-container").height(), "margin-left": -220, display: "none"});
        $(".main-container, .mobile-top").css("margin-left", 0).css("width", $(window).width());
    }
    isMobile = newIsMobile;

    if (isMobile) {
        $(".menu").css({"height": $(window).height()});
        $(".main-container").css("width", w);
    } else {
        $(".menu").css({"height": "100%", "margin-left": 0, display: "block"});
        $(".main-container").css("margin-left", $(".menu").width()).css("width", w - $(".menu").width());

    }
    $(".mobile-top").css("width", $(".main-container").width());

    $(".menu").jScrollPane({showArrows: true, scrollbarMargin: 0});


    $(".preview-product .img").css("margin-left", $(".preview-product-ul").width());


    if ($(".inside-page-col").length > 0) {
        $(".inside-page-col").removeClass("auto_h");
        var h = $(window).height();
        if (isMobile) {
            h = h - 61;
            //$("body").css("overflow", "auto");
        }
        $(".contacts-map").css({"height": h});
        $(".inside-page-col, .jspContainer").css({"height": h});
        if (w <= 1000) {
            // $("body").css("overflow", "auto");
            $(".inside-page-col").addClass("auto_h");
        }
        if (!isMobile) {
            // var url = document.location.href;
            //    if (url.indexOf("/order/make/") > 0){} else {
            $(".inside-page-col").jScrollPane({showArrows: true, scrollbarMargin: 0});
        //     $('.bx_page').jScrollPane({showArrows: true, scrollbarMargin: 0});
            //  }
        }




        // $(".menu").css({"height": h, overflow: "hidden"});
    }

    //Полоски с планами должны растягиваться в таком случае, а баннер "для себя" должен всегда примыкать к нижней границе

    var els = $(".plans li"), h = $(window).height();
    if (els.length > 0) {
        els.css("min-height", "auto");
        if (!isMobile) {
            $(".plans li").css("min-height", h - 160);
        }
    }

    //заявка на опт заказ (высота блока)
    if (!isMobile) {
        $(".opt-order").css("min-height", $(window).height() - 20);
        $(".plans, .plans li").css("min-height", $(window).height() - $(".plans-create").height());
        $(".svoy-plan").css("min-height", $(window).height() - $(".gen-sum-block").height());

    } else {
        $(".opt-order, .plans, .plans li, .svoy-plan").css("min-height", 1);
    }




}
function scrollSlider(index) {
    var el = $($(".scroll-slider")[index]), l = el.find("li").length, ar = el.parent().parent().find(".arrow-right, .arrow-left"),
    ul = el.find("ul"), count = 1, w = el.width() , el_c = el.parent().parent().find(".count");

    el.find("li").css("width", w);
    el_c.html(count + "/<span>" + l + "</span>");
    ul.css("margin-left", 0);

    var m = 0, animated = false;
    //el.find(".arrow-left").hide();
    ar.unbind("click").click(function () {
        var step = w;
        if (animated) return false;
        if ($(this).hasClass("arrow-left")) {
            m += step;
            count--;
        } else {
            m -= step;
            count++;
        }
        ar.show();
        if (m < -(l - 1) * w) {
            m = -(l - 1) * w;
            return;
            //el.find(".arrow-right").hide();
        }
        if (m > 0) {
            m = 0;
            return;
            //el.find(".arrow-left").hide();
        }
        if (count < 1) count = 1;
        if (count > l) count = l;
        animated = true;

        el_c.html(count + "/<span>" + l + "</span>");

        ul.stop(true, true).animate({"margin-left": m}, 400, function () {
            animated = false;
        });
        return false;
    });


};
$(window).resize(function () {

    menuHeight();
    //$(".main-container").css("margin-left", $(".menu").width());
    $(".scroll-slider").each(scrollSlider);
    $(".inside-page-col").jScrollPane({showArrows: true, scrollbarMargin: 0});
    //  $('.bx_page').jScrollPane({showArrows: true, scrollbarMargin: 0});
    $('.aboutus .scroll-pane').each(function(){
        var api = $(this).data('jsp');
        api.reinitialise();
    });

});
function clearField(id, text) {
    if ($("#" + id).val() === text) {
        $("#" + id).val("").removeClass("empty");
    }
};

function blurField(id, text) {
    if ($("#" + id).val() === "") {
        $("#" + id).val(text).addClass("empty");
    }
};


//добавление в корзину
function addToBasket(id) {
    if (parseInt(id) > 0) {
        $.post("/ajax/addToBasket.php",{ID:id},
            function(data){
                //alert(data);
                if (data == "OK") {
                    document.location.href="/personal/order/make/";
                }
        })
    }
}

//добавление в корзину элементов при создании своего плана
function planCreate() {
    var razor_count = parseInt($("#razor_count").val());
    var cassette_count = parseInt($("#cassette_count").val());
    var razor = $("#razor_type").val();

    $.post("/ajax/planCreate.php",{razor_count:razor_count,cassette_count:cassette_count,razor:razor},
        function(data){
            //alert(data);
            if (data == "OK") {
                document.location.href="/personal/order/make/";
            }
    })
}

//показать/скрыть окно создания собственного плана
function makePlan() {

    if ($('.svoy-plan').css("display") == "none") {
        $(".svoy-plan").siblings("div").fadeOut();
        $('.svoy-plan').fadeIn(500, function(){
            $('.svoy-plan').css("opacity","1");

            if (document.location.href.indexOf("personal") > 0) {

                $(".inside-page-col").each(function(){
                    if ($(this).find(".svoy_plan")) {
                        api = $(this).data('jsp');
                        api.destroy();
                    }
                })

                $(".inside-page-col").jScrollPane({showArrows: true, scrollbarMargin: 0});

            }
        });

        $(".inside-page-col-close").css("display","block");

    }

    else {

        $(".inside-page-col-close").css("display","none");
        $(".svoy-plan").siblings("div").fadeIn();
        $('.svoy-plan').fadeOut(500, function(){
            if (document.location.href.indexOf("personal") > 0) {

                $(".inside-page-col").each(function(){
                    if ($(this).find(".svoy_plan")) {
                        api = $(this).data('jsp');
                        api.destroy();
                    }
                })

                $(".inside-page-col").jScrollPane({showArrows: true, scrollbarMargin: 0});

                $('.svoy-plan').css("display","none");
            }
        });
    }
    //заново инициализируем скролл
    $(".inside-page-col .jspPane").animate({"top": "0"}, 500);

}

function sertSubmit() {
    var code = $(".sertActivate").val();
    if (code) {
        $.post("/ajax/checkSertificate.php",{code: code}, function(data){
            if (data == 'OK') {
                document.location.href="/personal/order/make/";
            }
            else {
                $.fancybox.open({href: '#error_message'});
                //                alert(data);
                if (data == "error") {
                    $("div.form_text").html('Извините, такой сертификат не <font style="color:#D6911C">зарегистрирован </font>в нашей базе. Пожалуйста, обратитесь <br> к администратору для выяснения возникших сложностей.')
                }   else {
                    $("div.form_text").html('Извините, такой сертификат <font style="color:#D6911C">уже был активирован ранее </font>в нашей базе. Если Вы активируете сертификат впервые, пожалуйста, напишите нам на <a href="mailto:zakaz@shaveclub.ru">zakaz@shaveclub.ru</a> о возникших сложностях.')

                }

            }
        })
    }

}






