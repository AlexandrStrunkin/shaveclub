
 $(function(){
  $('body').on('click', '.slide_1', function(){
       $('#slide_1').show();
       $('#slide_2').hide();
       $(this).addClass('active');
       $(this).next().removeClass('active');
  })
  $('body').on('click', '.slide_2', function(){
       $('#slide_2').css({'display': 'block','opacity': 1 });
       $('#slide_1').hide();
       $(this).addClass('active');
       $(this).prev().removeClass('active');
  })

})