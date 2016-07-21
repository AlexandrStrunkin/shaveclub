var basketTimeout;
var totalSum;
var timerBasketUpdate = false;

function setQuantity(basketId, ratio, direction){
	var curVal = parseFloat(BX("QUANTITY_INPUT_" + basketId).value), newVal,
		isDblQuantity=$('#basket_form tr[data-id='+basketId+'] .counter_block').data('float');	
	ratio = parseFloat(ratio);

	newVal = (direction == 'up') ? curVal + ratio : curVal - ratio;
	if (newVal < 0) newVal = 0;		
	if (newVal > 0) {		
		BX("QUANTITY_INPUT_" + basketId).value = newVal;		
		BX("QUANTITY_INPUT_" + basketId).defaultValue = curVal;
		totalSum=0;
		$('#basket_form tr[data-id='+basketId+']').closest('table').find("tbody tr[data-id]").each(function(i, element) {
			id=$(element).attr("data-id");
			count=BX("QUANTITY_INPUT_" + id).value;
			price = $(document).find("#basket_form input[name=item_price_"+id+"]").val();
			sum = count*price;
			totalSum += sum;
			$(document).find("#basket_form [data-id="+id+"] .summ-cell .price").text(jsPriceFormat(sum));
		});
		
		$("#basket_form .top_total_row span.price").text(jsPriceFormat(totalSum));
		$("#basket_form .top_total_row div.discount").fadeTo( "slow" , 0.2);
		
		
		if(timerBasketUpdate){
			clearTimeout(timerBasketUpdate);
			timerBasketUpdate = false;
		}
		
		timerBasketUpdate = setTimeout(function(){			
			updateQuantity('QUANTITY_INPUT_' + basketId, basketId, ratio);
			timerBasketUpdate=false;
		}, 700);
	}
}

function updateQuantity(controlId, basketId, ratio, animate) {

	var oldVal = BX(controlId).defaultValue, newVal = parseFloat(BX(controlId).value) || 0; bValidChange = false; // if quantity is correct for this ratio
	var nv;
	if (!newVal) {
		bValidChange = false;
		BX(controlId).value = oldVal;
	}
	if (ratio == 0 || ratio == 1) { 
		bValidChange = true; 
	} else  {
		var newValInt = newVal * 10000, ratioInt = ratio * 10000, reminder = newValInt % ratioInt;
		if (reminder == 0) bValidChange = true;
	}
					
	newVal = (ratio == 0 || ratio == 1) ? parseInt(newVal) : parseFloat(newVal).toFixed(2);

    if (isRealValue(BX("QUANTITY_SELECT_" + basketId))) { var option, options = BX("QUANTITY_SELECT_" + basketId).options, i = options.length; }
    while (i--) {
		option = options[i];
		if (parseFloat(option.value).toFixed(2) == parseFloat(newVal).toFixed(2)) option.selected = true;
    }
	
	BX("QUANTITY_" + basketId).value = newVal;			

	if(!$('input[name="COUPON"]').val()){
		$('input[name="COUPON"]').attr('name', 'tmp_COUPON');
	}
	$('form[name^=basket_form]').prepend('<input type="hidden" name="BasketRefresh" value="Y" />');
	$.post( $('#cur_page').val(), $("form[name^=basket_form]").serialize(), $.proxy(function( data){	
		if (timerBasketUpdate==false && newVal==newVal) {
			$("#basket-replace").html(data);
			if($("#basket_line .cart").length){
				reloadTopBasket('top', $('#basket_line'), '', 100);
			}
		}					
	}));
}

function basketAjaxReload() {
	if(!$('input[name="COUPON"]').val()){
		$('input[name="COUPON"]').attr('name', 'tmp_COUPON');
	}

	$.post( $('#cur_page').val(), $("form[name^=basket_form]").serialize(), $.proxy(function( data){	
		$("#basket-replace").html(data);
	}));
}

function delete_all_items(type, item_section, correctSpeed, url){
	$.post( arKShopOptions['SITE_DIR']+"ajax/action_basket.php", "TYPE="+type+"&CLEAR_ALL=Y", $.proxy(function( data ){		
		basketAjaxReload();
	}));
	if($('#basket_line').size()){
		reloadTopBasket('top', $('#basket_line'), '', 200, 'N');
	}
}


function deleteProduct(basketId, itemSection, item){	
	/*if(checkCounters()){
		delFromBasketCounter(item);
		setTimeout(function(){
			$.post( $('#cur_page').val()+'?action=delete&id='+basketId, $.proxy(function( data ){		
				$("#basket-replace").html(data);
			}));
			if($('#basket_line').size()){
				reloadTopBasket('top', $('#basket_line'), '', 200, 'N');
			}
		},100)   
	}else{ */
		$.post( $('#cur_page').val()+'?action=delete&id='+basketId, $.proxy(function( data ){		
			$("#basket-replace").html(data);
		}));
		if($('#basket_line').size()){
			reloadTopBasket('top', $('#basket_line'), '', 200, 'N');
	}
}

function delayProduct(basketId, itemSection){		
	$.post( $('#cur_page').val()+'?action=delay&id='+basketId, function( data ){
		$("#basket-replace").html(data);
	});
	if($('#basket_line').size()){
		reloadTopBasket('top', $('#basket_line'), '', 200, 'N');
	}
}

function addProduct(basketId, itemSection)
{	
	$.post( $('#cur_page').val()+'?action=add&id='+basketId, function( data ) {
		$("#basket-replace").html(data);
	});	
	if($('#basket_line').size()){
		reloadTopBasket('top', $('#basket_line'), '', 200, 'N');
	}
}


function checkOut(event){	
	if (!!BX('COUPON')) BX('COUPON').disabled = true;
	event = event || window.event;
	var th=$(event.target).parent();
	/*if(checkCounters('google')){
		checkoutCounter(1, th.data('text'), th.data('href'));
		setTimeout(function(){
			location.href=th.data('href');
		}, 600);
	}else{
		location.href=th.data('href');
	}
    */
    location.href=th.data('href');
	return true;
}