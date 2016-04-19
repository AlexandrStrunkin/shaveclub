$(document).ready(function(){
$("#feedback_btn").click(function(){
 var question = $("#feedback").val();
  
 if(question!=="" && question!=="Опишите суть Вашего вопроса"){
 
var id = $("#user_mail").val(); 
 $.post('/ajax/addFeedback.php', {question:question, id:id    
        }, function(data) { 
            
            if (data = "Y"){
             $("#feedMessage").css({"display":"block","color":"green"});
             $("#feedMessage").text("Спасибо, ваша заявка принята"); 
             $("#feedback").val("Опишите суть Вашего вопроса"); 
            }
            else{
             $("#feedMessage").css({"display":"block","color":"red"});
             $("#feedMessage").text("Ошибка отправки сообщения");     
            }

 })    
 }
 else{
    $("#feedMessage").css({"display":"block","color":"red"});
     $("#feedMessage").text("Пожалуйста заполните поле");
 } 
 
    
})

order_cancel();  
planCreatePersonal();  
});


function order_cancel(){ 
    $(".cansel-btn").click(function(){
    if(confirm("Вы действительно хотите отменить заказ?")){
    var id = $(this).attr('rel');
        
    $.post('/ajax/cancelOrder.php', {id:id    
        }, function(data) {  })  
  $(this).parent().html("");
   };
   });
   
}


function planCreatePersonal() {
   $(".repeat_other").click(function(){
    var razor_count = parseInt($(this).siblings(".count_machine").val());
    var cassette_count = parseInt($(this).siblings(".count_caseta").val());
    var razor = $(this).siblings(".el_code").val();
   
    $.post("/ajax/planCreate.php",{razor_count:razor_count,cassette_count:cassette_count,razor:razor},
        function(data){
            //alert(data);
            if (data == "OK") {
                document.location.href="/personal/order/make/";  
            }
    })  
    })
}