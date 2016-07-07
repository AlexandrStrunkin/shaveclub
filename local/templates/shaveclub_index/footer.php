<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);?>
</div>
</main>

<footer class="footer">
    <div>
        <div class="footer-soc">
           <?include($_SERVER["DOCUMENT_ROOT"]."/include/footer_soc.php");?>
        </div>

        <?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_menu_overall", Array(
	        "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
		    "CHILD_MENU_TYPE" => "bottom",	// Тип меню для остальных уровней
		    "DELAY" => "N",	// Откладывать выполнение шаблона меню
		    "MAX_LEVEL" => "1",	// Уровень вложенности меню
		    "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
		    "MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
		    "MENU_CACHE_TYPE" => "N",	// Тип кеширования
		    "MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
		    "ROOT_MENU_TYPE" => "bottom",	// Тип меню для первого уровня
		    "USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
		    "COMPONENT_TEMPLATE" => "bottom_menu"
	        ),
	        false
        );?>

        <div class="link-container">
            <a href="#">© <?=date("Y")?> <?=GetMessage('SHAVECLUB')?></a>
            <a href="/about/privacy-policy/"><?=GetMessage('PRIVACY_POLICY')?></a>
            <a href="/about/delivery/"><?=GetMessage('DELIVERY')?></a>
        </div>
    </div>
</footer>
                <!-- .content -->
                <div class="clear"></div>


            </div>


            <!-- .footer -->
        </div>
        <!-- .wrapper -->
        <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter20899105 = new Ya.Metrika({id:20899105,
                        webvisor:true,
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true});
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks");
    </script>
    <noscript><div><img src="//mc.yandex.ru/watch/20899105" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
    <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-39970778-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
    </script>
<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
(function(){ var widget_id = 'f1Iok7TX2M';var d=document;var w=window;function l(){
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();</script>
<!-- {/literal} END JIVOSITE CODE -->
</body>
</html>