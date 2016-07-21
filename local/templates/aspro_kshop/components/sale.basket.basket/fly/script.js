var basketTimeout;
var totalSum;
var timerBasketUpdate = false;

function setQuantity(basketId, ratio, direction){
	var curVal = parseFloat(BX("QUANTITY_INPUT_" + basketId).value), newVal;	
	ratio = parseFloat(ratio);
	newVal = (direction == 'up') ? curVal + ratio : curVal - ratio;
	if (newVal < 0) newVal = 0;		
	if (newVal > 0) {		
		BX("QUANTITY_INPUT_" + basketId).value = newVal;		
		BX("QUANTITY_INPUT_" + basketId).defaultValue = curVal;

		totalSum=0;
		$('#basket_line .basket_fly tr[data-id='+basketId+']').closest('table').find("tbody tr[data-id]").each(function(i, element) {
			id=$(element).attr("data-id");
			count=BX("QUANTITY_INPUT_" + id).value;			
			price = $(document).find("#basket_form input[name=item_price_"+id+"]").val();
			sum = count*price;
			totalSum += sum;
			$(document).find("#basket_form [data-id="+id+"] .summ-cell .price").text(jsPriceFormat(sum));
		});

		$("#basket_form .itog span.price").text(jsPriceFormat(totalSum));
		$("#basket_form .itog div.discount").fadeTo( "slow" , 0.2);
		
		
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

	BX("QUANTITY_" + basketId).value = newVal; // set hidden real quantity value (will be used in POST)


	$('form[name^=basket_form]').prepend('<input type="hidden" name="BasketRefresh" value="Y" />');
		$.post( arKShopOptions['SITE_DIR']+'ajax/show_basket.php', $("form[name^=basket_form]").serialize(), $.proxy(function( data){
			if (timerBasketUpdate==false) {
				basketFly('open');
			}
			$('form[name^=basket_form] input[name=BasketRefresh]').remove();
		}));
}

function delete_all_items(type, item_section, correctSpeed){	
	$.post( arKShopOptions['SITE_DIR']+"ajax/show_basket_fly.php", "PARAMS="+$("#basket_form").find("input#fly_basket_params").val()+"&TYPE="+type+"&CLEAR_ALL=Y", $.proxy(function( data ) {
		basketFly('open');
		$('.in-cart').hide();
		$('.in-cart').closest('.button_block').removeClass('wide');
		$('.to-cart').show();
		$('.counter_block').show();
		$('.wish_item').removeClass("added");
		$('.wish_item').find('.value').show();
		$('.wish_item').find('.value.added').hide();
	}));
}

function deleteProduct(basketId, itemSection, item){	
	if(checkCounters()){
		delFromBasketCounter(item);
		setTimeout(function(){
			$.post( arKShopOptions['SITE_DIR']+'ajax/show_basket.php?action=delete&id='+basketId, $.proxy(function( data ){		
				basketFly('open');
			}));
		},100);
	}else{
		$.post( arKShopOptions['SITE_DIR']+'ajax/show_basket.php?action=delete&id='+basketId, $.proxy(function( data ){		
			basketFly('open');
		}));
	}	
}

function delayProduct(basketId, itemSection){		
	$.post( arKShopOptions['SITE_DIR']+'ajax/show_basket.php?action=delay&id='+basketId, $.proxy(function( data ){
		basketFly('open');
	}));
}

function addProduct(basketId, itemSection){		
	$.post( arKShopOptions['SITE_DIR']+'ajax/show_basket.php?action=add&id='+basketId, $.proxy(function( data ) {
		basketFly('open');
	}));				
}

function checkOut(){
	BX("basket_form").submit();
	return true;
}

