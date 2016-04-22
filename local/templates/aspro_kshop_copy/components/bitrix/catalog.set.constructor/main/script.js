function catalogSetConstructDefault(arSetIds, ajax_path, price_currency, lid, element_id, detail_img, items_ratio)
{
	this.arSetIDs = arSetIds;
	this.ajax_path = ajax_path;
	this.price_currency = price_currency;
	this.lid = lid;
	this.element_id = element_id;
	this.detail_img = detail_img;
	this.items_ratio = items_ratio;
}

catalogSetConstructDefault.prototype.Add2Basket = function()
{
	var detail_img = this.detail_img;
	var element_id = this.element_id;
	BX.ajax.post(
		this.ajax_path,
		{
			sessid: BX.bitrix_sessid(),
			action: 'catalogSetAdd2Basket',
			set_ids: this.arSetIDs,
			lid: this.lid,
			iblockId: BX.message('setIblockId'),
			setOffersCartProps: BX.message('setOffersCartProps'),
			itemsRatio: this.items_ratio
		},
		function(result)
		{
			showCatalogSetAdd2BasketPopup(detail_img , element_id);
		}
	);
}

catalogSetConstructDefault.prototype.DeleteItem = function(element, item_id)
{
	var el = this;
	$(element).fadeTo(200, 0, function()
	{

		var wrapObj = element.parentNode;
		BX.remove(element);
		
		for(var i = 0, l = el.arSetIDs.length; i < l; i++)
		{
			if (el.arSetIDs[i] == item_id)
			{
				el.arSetIDs.splice(i,1);
			}
		}

		var sumPrice = +BX.firstChild(wrapObj).getAttribute("data-price");
		var sumOldPrice = +BX.firstChild(wrapObj).getAttribute("data-old-price");
		var sumDiffDiscountPrice = +BX.firstChild(wrapObj).getAttribute("data-discount-diff-price");

		
		var setItems = BX.findChildren(wrapObj, {className: "bx_default_set_items"}, true);
		
		//console.log(setItems.length);

		if (!setItems.length)
		{
			BX.removeClass(BX.firstChild(wrapObj), "plus");
			BX.addClass(BX.firstChild(wrapObj), "equally");
			BX.remove(BX.findChild(BX.firstChild(wrapObj), {className:"item_plus"}, false, false));
		}
		else
		{
			for (var i=0; i<setItems.length; i++)
			{
				if (i == setItems.length-1)
				{
					BX.removeClass(setItems[i], "plus");
					BX.addClass(setItems[i], "equally");
					BX.remove(BX.findChild(setItems[i], {className:"item_plus"}, false, false));	
				}
				sumPrice += +setItems[i].getAttribute("data-price");
				sumOldPrice += +setItems[i].getAttribute("data-old-price");
				sumDiffDiscountPrice += +setItems[i].getAttribute("data-discount-diff-price");
			}
		}
	
	
	
		BX.ajax.post(
			el.ajax_path,
			{
				sessid : BX.bitrix_sessid(),
				action : "ajax_recount_prices",
				sumPrice : sumPrice,
				sumOldPrice : sumOldPrice,
				sumDiffDiscountPrice : sumDiffDiscountPrice,
				currency : el.price_currency
			},
			function(result)
			{		
				var json = JSON.parse(result);
				
				if (json.formatSum)
				{
					//BX.findChild(wrapObj.parentNode, {className:"bx_item_set_current_price"}, true, false).innerHTML = json.formatSum;
					$(".total_wrapp.result .bx_item_set_current_price").animateNumbers(parseInt(delSpaces(json.formatSum).replace(/,/g, "")), 200, true, sumPrice);
				}
				if (json.formatOldSum)
				{
					//BX.findChild(wrapObj.parentNode, {className:"bx_item_set_old_price"}, true, false).innerHTML = json.formatOldSum;
					$(".total_wrapp.result .bx_item_set_old_price").animateNumbers(parseInt(delSpaces(json.formatOldSum).replace(/,/g, "")), 200, true, sumOldPrice);
				}
				else
				{
					BX.findChild(wrapObj.parentNode, {className:"bx_item_set_old_price"}, true, false).style.display = "none";
				}
				if (json.formatDiscDiffSum)
				{
					//BX.findChild(wrapObj.parentNode, {className:"bx_set_discount_diff_price"}, true, false).innerHTML = json.formatDiscDiffSum;
					$(".total_wrapp.result .bx_set_discount_diff_price").animateNumbers(parseInt(delSpaces(json.formatDiscDiffSum).replace(/,/g, "")), 200, true, sumDiffDiscountPrice);
				}
				else
				{
					BX.findChild(wrapObj.parentNode, {className:"bx_item_set_economy_price"}, true, false).style.display = "none";
				}
			}
		);
	
	//});
		});
}

function catalogSetConstructPopup(ItemsCount, ItemsWidth, Currency, DefaultItemPrice, DefaultItemDiscountPrice, DefaultItemDiscountDiffPrice, ajaxPath, setIds, lid, element_id, items_ratio, detail_img)
{
	this.catalogSetItemsCount = ItemsCount;
	this.catalogSetItemsWidth = ItemsWidth;
	this.catalogCurrency = Currency;
	this.catalogDefaultItemPrice = DefaultItemPrice;
	this.catalogDefaultItemDiscountPrice = DefaultItemDiscountPrice;
	this.catalogDefaultItemDiscountDiffPrice = DefaultItemDiscountDiffPrice;
	this.ajaxPath = ajaxPath;
	this.catalogSetIds = setIds;
	this.lid = lid;
	this.element_id = element_id;
	this.items_ratio = items_ratio;
	this.detail_img = detail_img;
}

catalogSetConstructPopup.prototype.scrollItems = function(direction)
{	
	var curLeftPercent = BX("bx_catalog_set_construct_slider_"+this.element_id).getAttribute('data-style-left');
	if (direction == 'left')
	{
		
		if (curLeftPercent >= 0)
		{
			$(".bx_kit_item_slider_arrow_left").addClass("disabled");
			return;
		}
		else
		{
			$(".bx_kit_item_slider_arrow_left").removeClass("disabled");	
			var leftPercent = +(curLeftPercent)+20;
		}
	}
	else
	{
		if (-curLeftPercent >= (this.catalogSetItemsCount - 5)*this.catalogSetItemsWidth)
		{
			$(".bx_kit_item_slider_arrow_right").addClass("disabled");
			return;
		}
		else
		{
			$(".bx_kit_item_slider_arrow_right").removeClass("disabled");	
			var leftPercent = +(curLeftPercent)-20;
		}
		
	}
	BX("bx_catalog_set_construct_slider_"+this.element_id).setAttribute('data-style-left', leftPercent);
	
	if (typeof leftPercent!="indefined")
	{
		$("#bx_catalog_set_construct_slider_"+this.element_id).animate({"left": leftPercent+"%"}, 200, "easeOutQuad", function()
		{
			if (-leftPercent >= (this.catalogSetItemsCount - 5)*this.catalogSetItemsWidth) { $(".bx_kit_item_slider_arrow_right").addClass("disabled"); }
				else { $(".bx_kit_item_slider_arrow_right").removeClass("disabled"); }
			if (leftPercent >= 0) { $(".bx_kit_item_slider_arrow_left").addClass("disabled"); }
				else { $(".bx_kit_item_slider_arrow_left").removeClass("disabled"); }
		});
	}
	
	
	
	
	
	//BX("bx_catalog_set_construct_slider_"+this.element_id).style.left = leftPercent+'%';
}

catalogSetConstructPopup.prototype.recountSlider = function(action)
{
	if (action == 'add')
	{
		this.catalogSetItemsCount -= 1;
	}
	else if (action == 'delete')
	{
		this.catalogSetItemsCount += 1;
	}
	this.catalogSetItemsWidth = (this.catalogSetItemsCount <=5) ? 20 : 100/this.catalogSetItemsCount;
	var dragObj = BX.findChildren(BX("bx_catalog_set_construct_popup_"+this.element_id), {className:"bx_drag_obj"}, true);
	for (var i=0; i<dragObj.length; i++)
	{
		dragObj[i].style.width = this.catalogSetItemsWidth+"%";
	}
	BX("bx_catalog_set_construct_slider_"+this.element_id).style.width = this.catalogSetItemsCount <=5 ? "100%" : (100+(this.catalogSetItemsCount-5)*20)+"%";
	if (this.catalogSetItemsCount > 5)
	{
		BX("bx_catalog_set_construct_slider_left_"+this.element_id).style.display = "block";
		BX("bx_catalog_set_construct_slider_right_"+this.element_id).style.display = "block";
	}
	else
	{
		BX("bx_catalog_set_construct_slider_left_"+this.element_id).style.display = "none";
		BX("bx_catalog_set_construct_slider_right_"+this.element_id).style.display = "none";
		BX("bx_catalog_set_construct_slider_"+this.element_id).style.left = "0%";
		BX("bx_catalog_set_construct_slider_"+this.element_id).setAttribute("data-style-left", 0);
	}

}

catalogSetConstructPopup.prototype.recountPrices = function()
{
	var sumPrice = +this.catalogDefaultItemDiscountPrice;
	var sumOldPrice = +this.catalogDefaultItemPrice;
	var sumDiffDiscountPrice = +this.catalogDefaultItemDiscountDiffPrice;

	var setObj = BX.findChildren(BX("bx_catalog_set_construct_popup_"+this.element_id), {className:"bx_drag_dest"}, true);
	for (var i=0; i<setObj.length; i++)
	{
		if (!BX.hasClass(setObj[i], "bx_kit_item_empty"))
		{
			var priceObj = BX.findChild(setObj[i], {className:"bx_kit_item_price"}, true, false);
			var price = priceObj.getAttribute("data-discount-price");
			if (price)
				sumPrice += +price;
			var oldPrice = priceObj.getAttribute("data-price");
			if (oldPrice)
				sumOldPrice += +oldPrice;
			var discDiffprice = priceObj.getAttribute("data-discount-diff-price");
			if (discDiffprice)
				sumDiffDiscountPrice += +discDiffprice;
		}
	}

	var element_id = this.element_id;

	BX.ajax.post(
		this.ajaxPath,
		{
			sessid : BX.bitrix_sessid(),
			action : "ajax_recount_prices",
			sumPrice : sumPrice,
			sumOldPrice : sumOldPrice,
			sumDiffDiscountPrice : sumDiffDiscountPrice,
			currency : this.catalogCurrency
		},
		function(result)
		{
			var json = JSON.parse(result);

			if (json.formatSum)
			{
				//BX("bx_catalog_set_construct_sum_price_"+element_id).innerHTML = json.formatSum;
				$("#bx_catalog_set_construct_sum_price_"+element_id).animateNumbers(parseInt(delSpaces(json.formatSum).replace(/,/g, "")), 200, true);
				
			}
			if (json.formatOldSum)
			{
				//BX("bx_catalog_set_construct_sum_old_price_"+element_id).innerHTML = json.formatOldSum;
				$("#bx_catalog_set_construct_sum_old_price_"+element_id).animateNumbers(parseInt(delSpaces(json.formatOldSum).replace(/,/g, "")), 200, true);
				BX("bx_catalog_set_construct_sum_old_price_"+element_id).parentNode.style.display = "block";
			}
			else
			{
				BX("bx_catalog_set_construct_sum_old_price_"+element_id).parentNode.style.display = "none";
			}
			if (json.formatDiscDiffSum)
			{
				//BX("bx_catalog_set_construct_sum_diff_price_"+element_id).innerHTML = json.formatDiscDiffSum;
				$("#bx_catalog_set_construct_sum_diff_price_"+element_id).animateNumbers(parseInt(delSpaces(json.formatDiscDiffSum).replace(/,/g, "")), 200, true);
				BX("bx_catalog_set_construct_sum_diff_price_"+element_id).parentNode.style.display = "block";
			}
			else
			{
				BX("bx_catalog_set_construct_sum_diff_price_"+element_id).parentNode.style.display = "none";
			}
			if (!json.formatOldSum && !json.formatDiscDiffSum)
			{
				BX.addClass(BX("bx_catalog_set_construct_price_block_"+element_id), "not_sale");
			}
			else
			{
				BX.removeClass(BX("bx_catalog_set_construct_price_block_"+element_id), "not_sale");
			}
		}
	);
}

catalogSetConstructPopup.prototype.catalogSetAdd = function(element, emptyObj)
{
	if (!emptyObj)
		emptyObj = BX.findChild(BX("bx_catalog_set_construct_popup_"+this.element_id), {className:"bx_kit_item_empty"}, true, false);
	if (emptyObj)
	{
		var add_obj = element.parentNode;

		var objImg = BX.findChild(element, {className:"bx_kit_img_container"}, true, false);
		var objName = BX.findChild(element, {className:"bx_kit_item_title"}, true, false);
		var itemID = objName.getAttribute("data-item-id");
		var objPrice = BX.findChild(element, {className:"bx_kit_item_price"}, true, false);
		var objPriceDIscount = BX.findChild(element, {className:"bx_kit_item_discount"}, true, false);
		var _this = this;
		var objDeleteIcon =  BX.create('DIV', {
			props: {className: "bx_kit_item_del"},
			events: {click: function() {_this.catalogSetDelete(this.parentNode);}}
		});

		var newSetItemInfo = BX.create('DIV', {
			props: {className: "item_info"},
			children: [objName, objPrice, objPriceDIscount]
		});
		var newSetItem = BX.create('DIV', {
			props: {className: "bx_kit_item_children bx_kit_item_border"},
			children: [objImg, newSetItemInfo, objDeleteIcon]
		});

		$(newSetItem).fadeOut(0);
		emptyObj.appendChild(newSetItem);
		$(newSetItem).fadeTo(333, 1);
				
		BX.removeClass(emptyObj, "bx_kit_item_empty bx_kit_item_border");
		/*if (BX.hasClass(element, "discount"))
		{
			BX.addClass(emptyObj, "discount");
			var objDiscount = BX.findChild(add_obj, {className:"bx_kit_item_discount"}, true, false);
			emptyObj.appendChild(objDiscount);
		}*/

		BX.remove(add_obj);

		this.recountSlider("add");
		this.recountPrices();
		this.catalogSetIds.push(itemID);
	}
	return false;
}

catalogSetConstructPopup.prototype.catalogSetDelete = function(element)
{
	var empty_obj = element.parentNode;

	var objImg = BX.findChild(element, {className:"bx_kit_img_container"}, true, false);
	var objName = BX.findChild(element, {className:"bx_kit_item_title"}, true, false);
	var itemID = objName.getAttribute("data-item-id");
	var objPrice = BX.findChild(element, {className:"bx_kit_item_price"}, true, false);
	var objPriceDIscount = BX.findChild(element, {className:"bx_kit_item_discount"}, true, false);

	var _this = this;
	var objAddIcon =  BX.create('DIV', {
		props: {className: "bx_kit_item_add"},
		events: {click: function() {_this.catalogSetAdd(this.parentNode);}}
	});

	var discountClass = BX.hasClass(empty_obj, "discount") ? " discount" : "";

	
	var newSetItemInfo = BX.create('DIV', {
			props: {className: "item_info"},
			children: [objName, objPrice, objPriceDIscount]
		});
	
	var newSetItem = BX.create('DIV', {
		props: {className: "bx_kit_item bx_kit_item_border"+discountClass},
		children: [objImg, newSetItemInfo, objAddIcon]
	});

	var divChilds = [];
	divChilds.push(newSetItem);
	if (discountClass)
	{
		var objDiscount = BX.findChild(empty_obj, {className:"bx_kit_item_discount"}, true, false);
		divChilds.push(objDiscount);
	}

	var objDiv = BX.create('DIV', {
		props: {className: "bx_kit_item_slider bx_drag_obj"},
		children: divChilds
	});
	objDiv.setAttribute("data-main-element-id", this.element_id);
	objDiv.onbxdragstart = catalogSetConstructDragStart;
	objDiv.onbxdrag = catalogSetConstructDragMove;
	objDiv.onbxdraghover = catalogSetConstructDragHover;
	objDiv.onbxdraghout = catalogSetConstructDragOut;
	objDiv.onbxdragrelease = catalogSetConstructDragRelease;   //node was thrown outside of dest
	jsDD.registerObject(objDiv);

	BX("bx_catalog_set_construct_slider_"+this.element_id).appendChild(objDiv);

	$(empty_obj).children().fadeTo(200, 0, function(){$(empty_obj).html("");});
	
	BX.addClass(empty_obj, "bx_kit_item_empty bx_kit_item_border");
	BX.removeClass(empty_obj, "discount");

	this.recountSlider("delete");
	this.recountPrices();
	for(var i = 0, l = this.catalogSetIds.length; i < l; i++)
	{
		if (this.catalogSetIds[i] == itemID)
		{
			this.catalogSetIds.splice(i,1);
		}
	}
	
	$('.bx_kit_two_item_slider').equalize({children: '.item_info', reset: true, equalize: 'outerHeight'}); 
	$('.bx_kit_two_item_slider').equalize({children: '.bx_kit_item', reset: true, equalize: 'outerHeight'}); 
	
	$('.bx_kit_two_item_slider').equalize({children: '.cost', reset: true}); 
	$('.bx_kit_two_item_slider .item_wrapp').equalize({children: '.item-title', reset: true, equalize: 'outerHeight'}); 
	$('.bx_kit_two_item_slider .item_wrapp').equalize({children: '.bx_kit_item_children', reset: true, equalize: 'outerHeight'});
	$('.bx_kit_two_item_slider .item_wrapp').equalize({children: '.bx_kit_item ', reset: true, equalize: 'outerHeight'}); 
	$('.bx_kit_two_item_slider .bx_kit_two_section .slider_wrapp').equalize({reset: true, children: ".bx_kit_item_slider", equalize: 'outerHeight'}); 
	$('.bx_kit_item').hover(
			function() { 
							$(this).find(".bx_kit_item_add").fadeIn(100); 
							$(this).find(".bx_kit_item_del").fadeIn(100); 
							
						},
			function() { 
							$(this).find(".bx_kit_item_add").stop().fadeOut(333); 
							$(this).find(".bx_kit_item_del").stop().fadeOut(333); 
						}
		);
	return false;
}

catalogSetConstructPopup.prototype.Add2Basket = function()
{
	var detail_img = this.detail_img;
	var element_id = this.element_id;
	BX.ajax.post(
		this.ajaxPath,
		{
			sessid: BX.bitrix_sessid(),
			action: 'catalogSetAdd2Basket',
			set_ids: this.catalogSetIds,
			itemsRatio: this.items_ratio,
			lid: this.lid,
			iblockId: BX.message('setIblockId'),
			setOffersCartProps: BX.message('setOffersCartProps')
		},
		function(result)
		{
			BX.CatalogSetConstructor.popup.close();
			
			$('.card_popup_frame').addClass("animate");
			$("#header .cart-call").click();
			postAnimateBasketLine(200);
			
			//showCatalogSetAdd2BasketPopup(detail_img, element_id);
		}
	);
}

function showCatalogSetAdd2BasketPopup(setMainPictureUrl, element_id)
{
	element_id = element_id || "";
	var popup = BX.PopupWindowManager.create("CatalogSetAdd2Basket"+element_id, null, {
		autoHide: true,
		//	zIndex: 0,
		offsetLeft: 0,
		offsetTop: 0,
		overlay : true,
		draggable: {restrict:true},
		closeByEsc: true,
		closeIcon: { right : "12px", top : "10px"},
		content: '' +
			'<div class="bx_modal_container" style="width:300px;height:280px;text-align: center;padding-top:20px">' +
			'<img src="'+setMainPictureUrl+'"/>'+
			'<p>'+BX.message("setItemAdded2Basket")+'</p>'+
			'<a class="bx_bt_blue bx_medium" href="'+BX.message("setButtonBuyUrl")+'"><span class="bx_icon_cart"></span><span>'+BX.message("setButtonBuyName")+'</span></a>'+
			'</div>'
	});

	popup.show();	
}

//drag&drop
function catalogSetConstructDragStart()
{
	var objWidth = this.offsetWidth + "px";
	var objHeight = this.offsetHeight + "px";
	this.style.width = "100%";
	BX.firstChild(this).style.height = objHeight;
	BX.addClass(BX.firstChild(this), "bx_kit_item_slider_drag");

	BX.removeClass(BX.firstChild(this), "bx_kit_item");

	window.bxcatalogset = document.body.appendChild(BX.create('DIV', {             //div to move
		style: {
			position: 'absolute',
			zIndex: '2000',
			height: objHeight,
			width: objWidth
		},
		children: [this]
	}));
}

function catalogSetConstructDragMove(x, y)
{
	window.bxcatalogset.style.left = x-(this.clientWidth/2) + 'px';
	window.bxcatalogset.style.top = y-(this.clientHeight/2) + 'px';
}

function catalogSetConstructDragHover(dest, x, y)
{
	if (BX.hasClass(dest, "bx_kit_item_empty"))
		dest.style.border = "1px solid grey";
}

function catalogSetConstructDragOut(dest, x, y)
{
	if (BX.hasClass(dest, "bx_kit_item_empty"))
		dest.style.border = "";
}

function catalogSetConstructDragRelease()
{
	this.style.width = catalogSetPopupObj.catalogSetItemsWidth+"%";
	var element_id = this.getAttribute("data-main-element-id");
	BX.addClass(BX.firstChild(this), "bx_kit_item");
	BX("bx_catalog_set_construct_slider_"+element_id).appendChild(this);
	BX.remove(window.bxcatalogset);
	window.bxcatalogset = null;
	$(".bx_kit_two_item_slider .bx_kit_item_border").removeClass("bx_kit_item_slider_drag");
	return false;
}

function catalogSetConstructDestFinish(curNode, x, y)    //node was thrown inside of dest
{	
console.log("f");
	if (BX.hasClass(this, "bx_kit_item_empty"))
	{
		this.style.border = "";

		catalogSetPopupObj.catalogSetAdd(BX.firstChild(curNode), this);

		BX.remove(curNode);
		BX.removeClass(this, "bx_kit_item_empty");
		BX.remove(window.bxcatalogset);
		window.bxcatalogset = null;

		jsDD.refreshDestArea();

		return true;
	}
	else
		return false;
}