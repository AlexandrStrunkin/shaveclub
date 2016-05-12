 $(".slide_news").flexslider({
    animation: "slide",
    selector: ".news_slider > li",
    slideshow: false,
    slideshowSpeed: 6000,
    animationSpeed: 600,
    directionNav: true,
    animationLoop: true,
    itemWidth: 360,
    controlNav: true,
    pauseOnHover: true,
    controlsContainer: ".news_navigation_news",
    manualControls: ".news_block.news .flex-control-nav.flex-control-paging li a"
});
 $(".slide_sale").flexslider({
    animation: "slide",
    selector: ".news_slider > li",
    slideshow: false,
    slideshowSpeed: 6000,
    animationSpeed: 600,
    directionNav: true,
    animationLoop: true,
    itemWidth: 360,
    controlNav: true,
    pauseOnHover: true,
    controlsContainer: ".news_navigation_sale",
    manualControls: ".news_block.sale .flex-control-nav.flex-control-paging li a"
});
 $(function(){
  $('body').on('click', '.slide_1', function(){
       $('#slide_1').show();
       $('#slide_2').hide();
       $(this).addClass('active');
       $(this).next().removeClass('active');
  })
  $('body').on('click', '.slide_2', function(){
       $('#slide_2').show();
       $('#slide_1').hide();
       $(this).addClass('active');
       $(this).prev().removeClass('active');
  })
})