<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

 <footer class="footer">
    <div>
        <div class="footer-soc">
           <?include($_SERVER["DOCUMENT_ROOT"]."/include/footer_soc.php");?>
        </div>

        <?$APPLICATION->IncludeComponent(
            "bitrix:menu",
            "bottom_menu_overall",
            array(
                "ALLOW_MULTI_SELECT" => "N",
                "CHILD_MENU_TYPE" => "bottom",
                "DELAY" => "N",
                "MAX_LEVEL" => "1",
                "MENU_CACHE_GET_VARS" => array(
                ),
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "ROOT_MENU_TYPE" => "bottom",
                "USE_EXT" => "N",
                "COMPONENT_TEMPLATE" => "bottom_menu"
            ),
            false
        );?>
        <div class="clear"></div>
        <div class="link-container">
            <a href="#">© <?=date("Y")?> <?=GetMessage("SHAVECLUB");?></a>
            <a href="/about/privacy-policy/"><?=GetMessage("PRIVACY_POLICY");?></a>
            <a href="/about/delivery/"><?=GetMessage("DELIVERY");?></a></div>
    </div>
</footer>

                <!-- .content -->
                <div class="clear"></div>


            </div>


            <!-- .footer -->
        </div>
        <!-- .wrapper -->
 <script>
  var gridOperations = new howGridAnimations();
  document.addEventListener('DOMContentLoaded',function(){
    document.querySelector('#smileBlock').addEventListener('mouseover',function(){gridOperations.smileAnimations('#fca713','#fff')},false);
    document.querySelector('#smileBlock').addEventListener('mouseout',function(){gridOperations.smileAnimations()},false);
  }, false);

  /*----Mobile events-----*/
  document.querySelector('#smileBlock').addEventListener('touchstart',function(){
      gridOperations.smileAnimations('#fca713','#fff');
  },false);

  var stepBlocks = document.querySelectorAll('.steps_block');
  Array.prototype.forEach.call(stepBlocks, function(el, i){
    el.addEventListener('mouseenter',function(event){gridOperations.descriptionBoxAnimations(event,0)},false);
    el.addEventListener('mouseleave',function(event){gridOperations.descriptionBoxAnimations(event,100)},false);
  });
  </script>
</body>
</html>