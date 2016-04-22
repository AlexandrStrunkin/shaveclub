<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
 
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