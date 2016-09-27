function updateBasketTable(basketItemId, res) {
	//update product params after recalculation
	if(!!res.BASKET_DATA) {
		for(id in res.BASKET_DATA.GRID.ROWS) {
			if(res.BASKET_DATA.GRID.ROWS.hasOwnProperty(id)) {
				var item = res.BASKET_DATA.GRID.ROWS[id];				

				/***ITEM_PRICE***/
				if(BX("price_" + id))
					BX("price_" + id).innerHTML = item.PRICE_FORMATED;

				/***ITEM_REFERENCE_PRICE***/
				if(BX("reference-price_" + id)) {
					var itemReferenceCont = $(BX("reference-price_" + id)),
						itemReferenceCoef = itemReferenceCont.data("reference-coef"),
						dec_point = itemReferenceCont.data("dec-point"),
						thousands_sep = itemReferenceCont.data("separator"),
						decimals = itemReferenceCont.data("reference-decimal");
					if(itemReferenceCont.data("hide-zero") == "Y") {			
						if(Math.abs(parseFloat(item.PRICE * itemReferenceCoef).toFixed(decimals)) == Math.abs(parseFloat(item.PRICE * itemReferenceCoef).toFixed(0))) {						
							decimals = 0;								
						}
					}
					BX("itemReferenceVal_" + id).innerHTML = number_format(item.PRICE * itemReferenceCoef, decimals, dec_point, thousands_sep);
				}
				
				/***ITEM_OLD_PRICE***/
				if(BX("old-price_" + id))
					BX("old-price_" + id).innerHTML = (item.FULL_PRICE_FORMATED != item.PRICE_FORMATED) ? item.FULL_PRICE_FORMATED : '';				

				/***ITEM_UNIT***/
				if(BX("unit_" + id))
					BX("unit_" + id).innerHTML = item.MEASURE_TEXT;
				
				/***ITEM_SUM***/
				if(BX("cart-item-summa_" + id)) {
					var itemSumCont,
						itemSumOld,
						itemSumCurr,
						decimals;

					itemSumCont = $(BX("cart-item-summa_" + id));

					itemSumOld = itemSumCont.data("itemsum");					
					itemSumCont.data("itemsum", (item.PRICE * item.QUANTITY));
					itemSumCurr = itemSumCont.data("itemsum");

					if(itemSumCurr != itemSumOld) {
						decimals = itemSumCont.data("decimal");
						if(itemSumCont.data("hide-zero") == "Y") {			
							if(Math.abs(parseFloat(itemSumCurr).toFixed(decimals)) == Math.abs(parseFloat(itemSumCurr).toFixed(0))) {						
								decimals = 0;								
							}
						}
						var options = {
							useEasing: false,
							useGrouping: true,
							separator: itemSumCont.data("separator"),
							decimal: itemSumCont.data("dec-point")
						}
						var counter = new countUp("itemSumVal_" + id, itemSumOld, itemSumCurr, decimals, 0.5, options);
						counter.start();
					}

					/***ITEM_REFERENCE_SUM***/
					if(BX("itemReferenceSumVal_" + id)) {
						var itemReferenceSumCoef,
							itemReferenceSumOld,
							itemReferenceSumCurr;					
					
						itemReferenceSumCoef = itemSumCont.data("itemreferencesumcoef");
						itemReferenceSumOld = itemSumCont.data("itemreferencesum");					
						itemSumCont.data("itemreferencesum", (item.PRICE * item.QUANTITY * itemReferenceSumCoef));
						itemReferenceSumCurr = itemSumCont.data("itemreferencesum");

						if(itemReferenceSumCurr != itemReferenceSumOld) {
							decimals = itemSumCont.data("reference-decimal");
							if(itemSumCont.data("hide-zero") == "Y") {			
								if(Math.abs(parseFloat(itemReferenceSumCurr).toFixed(decimals)) == Math.abs(parseFloat(itemReferenceSumCurr).toFixed(0))) {						
									decimals = 0;								
								}
							}
							var options = {
								useEasing: false,
								useGrouping: true,
								separator: itemSumCont.data("separator"),
								decimal: itemSumCont.data("dec-point")
							}
							var counter = new countUp("itemReferenceSumVal_" + id, itemReferenceSumOld, itemReferenceSumCurr, decimals, 0.5, options);
							counter.start();
						}
					}					
				}

				//if the quantity was set by user to 0 or was too much, we need to show corrected quantity value from ajax response
				if(BX("QUANTITY_" + id)) {
					BX("QUANTITY_" + id).value = item.QUANTITY;
				}
			}
		}
	}

	//update coupon info
	if(!!res.BASKET_DATA)
		couponListUpdate(res.BASKET_DATA);

	//update total basket values
	if(!!res.BASKET_DATA) {
		/***ALL_SUM***/
		if(BX("cart-allsum")) {
			var allSumCont,
				allSumOld,
				allSumCurr,
				decimals;

			allSumCont = $(BX("cart-allsum"));
			
			allSumOld = allSumCont.data("allsum");		
			allSumCont.data("allsum", res["BASKET_DATA"]["allSum"]);
			allSumCurr = allSumCont.data("allsum");

			if(allSumCurr != allSumOld) {
				decimals = allSumCont.data("decimal");
				if(allSumCont.data("hide-zero") == "Y") {			
					if(Math.abs(parseFloat(allSumCurr).toFixed(decimals)) == Math.abs(parseFloat(allSumCurr).toFixed(0))) {						
						decimals = 0;										
					}
				}
				var options = {
					useEasing: false,
					useGrouping: true,
					separator: allSumCont.data("separator"),
					decimal: allSumCont.data("dec-point")
				}
				var counter = new countUp("allSumVal", allSumOld, allSumCurr, decimals, 0.5, options);
				counter.start();
			}

			/***ALL_REFERENCE_SUM***/
			if(BX("allReferenceSumVal")) {
				var allReferenceSumCoef,
					allReferenceSumOld,
					allReferenceSumCurr;

				allReferenceSumCoef = allSumCont.data("allreferencesumcoef");
				allReferenceSumOld = allSumCont.data("allreferencesum");		
				allSumCont.data("allreferencesum", (res["BASKET_DATA"]["allSum"] * allReferenceSumCoef));
				allReferenceSumCurr = allSumCont.data("allreferencesum");

				if(allReferenceSumCurr != allReferenceSumOld) {
					decimals = allSumCont.data("reference-decimal");
					if(allSumCont.data("hide-zero") == "Y") {			
						if(Math.abs(parseFloat(allReferenceSumCurr).toFixed(decimals)) == Math.abs(parseFloat(allReferenceSumCurr).toFixed(0))) {						
							decimals = 0;										
						}
					}
					var options = {
						useEasing: false,
						useGrouping: true,
						separator: allSumCont.data("separator"),
						decimal: allSumCont.data("dec-point")
					}
					var counter = new countUp("allReferenceSumVal", allReferenceSumOld, allReferenceSumCurr, decimals, 0.5, options);
					counter.start();
				}
			}
			
			BX.onCustomEvent("OnBasketChange");
		}
	}
}

function number_format(number, decimals, dec_point, thousands_sep) {
	number = (number + "").replace(/[^0-9+\-Ee.]/g, "");
	var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === "undefined") ? "," : thousands_sep,
		dec = (typeof dec_point === "undefined") ? "." : dec_point,
		s = "",
		toFixedFix = function (n, prec) {
			var k = Math.pow(10, prec);
			return "" + Math.round(n * k) / k;
		};
	s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
	if(s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if((s[1] || "").length < prec) {
		s[1] = s[1] || "";
		s[1] += new Array(prec - s[1].length + 1).join("0");
	}
	return s.join(dec);
}

function couponCreate(couponBlock, oneCoupon) {
	var couponClass = 'disabled';

	if(!BX.type.isElementNode(couponBlock))
		return;
	if(oneCoupon.JS_STATUS === 'BAD')
		couponClass = 'bad';
	else if(oneCoupon.JS_STATUS === 'APPLYED')
		couponClass = 'good';

	couponBlock.appendChild(BX.create(
		'div',
		{
			props: {
				className: 'bx_ordercart_coupon'
			},
			children: [				
				BX.create(
					'input',
					{
						props: {							
							type: 'hidden',
							value: oneCoupon.COUPON,
							name: 'OLD_COUPON[]'
						}						
					}
				),
				BX.create(
					'div',
					{
						props: {
							className: 'old_coupon ' + couponClass
						},
						html: oneCoupon.COUPON + ' ' + oneCoupon.JS_CHECK_CODE
					}
				),
				BX.create(
					'span',
					{
						props: {
							className: 'close ' + couponClass
						},
						attrs: {
							'data-coupon': oneCoupon.COUPON
						},
						children: [
							BX.create(
								'i',
								{
									props: {
										className: 'fa fa-times'
									}
								}
							)
						]
					}
				),
				BX.create(
					'div',
					{
						props: {
							className: 'clr'
						}						
					}
				)
			]
		}
	));
}

function couponListUpdate(res) {
	var couponBlock,		
		fieldCoupon,
		couponsCollection,
		couponFound,
		i,
		j,
		key;

	if(!!res && typeof res !== 'object') {
		return;
	}

	couponBlock = BX('cart-coupon');
	if(!!couponBlock) {
		if(!!res.COUPON_LIST && BX.type.isArray(res.COUPON_LIST)) {
			fieldCoupon = BX('coupon');
			if(!!fieldCoupon) {
				fieldCoupon.value = '';
			}
			couponsCollection = BX.findChildren(couponBlock, { tagName: 'input', property: { name: 'OLD_COUPON[]' } }, true);			

			if(!!couponsCollection) {
				if(BX.type.isElementNode(couponsCollection)) {
					couponsCollection = [couponsCollection];
				}
				for(i = 0; i < res.COUPON_LIST.length; i++) {
					couponFound = false;
					key = -1;
					for(j = 0; j < couponsCollection.length; j++) {
						if(couponsCollection[j].value === res.COUPON_LIST[i].COUPON) {
							couponFound = true;
							key = j;
							couponsCollection[j].couponUpdate = true;
							break;
						}
					}					
					if(!couponFound)
						couponCreate(couponBlock, res.COUPON_LIST[i]);
				}
				for(j = 0; j < couponsCollection.length; j++) {
					if(typeof (couponsCollection[j].couponUpdate) === 'undefined' || !couponsCollection[j].couponUpdate) {
						BX.remove(couponsCollection[j].parentNode);
						couponsCollection[j] = null;
					} else {
						couponsCollection[j].couponUpdate = null;
					}
				}
			} else {
				for(i = 0; i < res.COUPON_LIST.length; i++) {
					couponCreate(couponBlock, res.COUPON_LIST[i]);
				}
			}
		}
	}
	couponBlock = null;
}

function checkOut() {
	if(!!BX("coupon"))
		BX("coupon").disabled = true;	
	BX("basket_form").submit();	
	return true;
}

function enterCoupon() {
	var newCoupon = BX("coupon");
	if(!!newCoupon && !!newCoupon.value)
		recalcBasketAjax({"coupon" : newCoupon.value});
}

function updateQuantity(controlId, basketId, ratio, bUseFloatQuantity) {
	var oldVal = BX(controlId).defaultValue,
		newVal = parseFloat(BX(controlId).value) || 0,
		bIsCorrectQuantityForRatio = false;

	if(ratio === 0 || ratio == 1) {
		bIsCorrectQuantityForRatio = true;
	} else {
		var newValInt = newVal * 10000,
			ratioInt = ratio * 10000,
			reminder = newValInt % ratioInt,
			newValRound = parseInt(newVal);

		if(reminder === 0) {
			bIsCorrectQuantityForRatio = true;
		}
	}

	var bIsQuantityFloat = false;

	if(parseInt(newVal) != parseFloat(newVal)) {
		bIsQuantityFloat = true;
	}

	newVal = (bUseFloatQuantity === false && bIsQuantityFloat === false) ? parseInt(newVal) : parseFloat(newVal).toFixed(2);

	if(bIsCorrectQuantityForRatio) {
		BX(controlId).defaultValue = newVal;		
		
		BX("QUANTITY_" + basketId).value = newVal;
		
		recalcBasketAjax({});
	} else {
		newVal = getCorrectRatioQuantity(newVal, ratio, bUseFloatQuantity);
		
		if(newVal != oldVal) {			
			BX("QUANTITY_" + basketId).value = newVal;			
			
			recalcBasketAjax({});
		} else {
			BX(controlId).value = oldVal;
		}
	}
}

//used when quantity is changed by clicking on arrows
function setQuantity(basketId, ratio, sign, bUseFloatQuantity) {
	var curVal = parseFloat(BX("QUANTITY_" + basketId).value),
		newVal;

	newVal = (sign == 'up') ? curVal + ratio : curVal - ratio;

	if(newVal < 0)
		newVal = 0;

	if(bUseFloatQuantity) {
		newVal = newVal.toFixed(2);
	}

	if(ratio > 0 && newVal < ratio) {
		newVal = ratio;
	}

	if(!bUseFloatQuantity && newVal != newVal.toFixed(2)) {
		newVal = newVal.toFixed(2);
	}

	newVal = getCorrectRatioQuantity(newVal, ratio, bUseFloatQuantity);

	BX("QUANTITY_" + basketId).value = newVal;
	BX("QUANTITY_" + basketId).defaultValue = newVal;

	updateQuantity('QUANTITY_' + basketId, basketId, ratio, bUseFloatQuantity);
}

function getCorrectRatioQuantity(quantity, ratio, bUseFloatQuantity) {
	var newValInt = quantity * 10000,
		ratioInt = ratio * 10000,
		reminder = newValInt % ratioInt,
		result = quantity,
		bIsQuantityFloat = false,
		i;
	ratio = parseFloat(ratio);

	if(reminder === 0) {
		return result;
	}

	if(ratio !== 0 && ratio != 1) {
		for(i = ratio, max = parseFloat(quantity) + parseFloat(ratio); i <= max; i = parseFloat(parseFloat(i) + parseFloat(ratio)).toFixed(2)) {
			result = i;
		}
	} else if(ratio === 1) {
		result = quantity | 0;
	}

	if(parseInt(result, 10) != parseFloat(result)) {
		bIsQuantityFloat = true;
	}

	result = (bUseFloatQuantity === false && bIsQuantityFloat === false) ? parseInt(result, 10) : parseFloat(result).toFixed(2);

	return result;
}

function recalcBasketAjax(params) {
	BX.showWait();

	var property_values = {},
		action_var = BX('action_var').value,		
		items = BX.findChildren(BX('cart_equipment'), {className: 'tr'}, true),
		shelveItems = BX.findChildren(BX('shelve_equipment'), {className: 'tr'}, true),
		postData,
		i;	

	postData = {
		'sessid': BX.bitrix_sessid(),
		'site_id': BX.message('SITE_ID'),
		'props': property_values,
		'action_var': action_var,
		'select_props': BX('column_headers').value,
		'offers_props': BX('offers_props').value,
		'quantity_float': BX('quantity_float').value,
		'count_discount_4_all_quantity': BX('count_discount_4_all_quantity').value,
		'price_vat_show_value': BX('price_vat_show_value').value,
		'hide_coupon': BX('hide_coupon').value,
		'use_prepayment': BX('use_prepayment').value
	};
	postData[action_var] = 'recalculate';
	if(!!params && typeof params === 'object') {
		for(i in params) {
			if(params.hasOwnProperty(i))
				postData[i] = params[i];
		}
	}

	if(!!items && items.length > 0) {
		for(i = 0; items.length > i; i++)
			postData['QUANTITY_' + items[i].id] = BX('QUANTITY_' + items[i].id).value;
	}

	if(!!shelveItems && shelveItems.length > 0) {
		for(i = 0; shelveItems.length > i; i++)
			postData['DELAY_' + shelveItems[i].id] = 'Y';
	}

	BX.ajax({
		url: '/bitrix/components/bitrix/sale.basket.basket/ajax.php',
		method: 'POST',
		data: postData,
		dataType: 'json',
		onsuccess: function(result) {
			BX.closeWait();
			updateBasketTable(null, result);
		}
	});
}

function deleteCoupon(e) {
	var target = BX.proxy_context,
		value;

	if(!!target && target.hasAttribute('data-coupon')) {
		value = target.getAttribute('data-coupon');
		if(!!value && value.length > 0) {
			recalcBasketAjax({'delete_coupon' : value});
		}
	}
}

BX.ready(function() {
	var couponBlock = BX('cart-coupon');
	if(!!couponBlock)
		BX.bindDelegate(couponBlock, 'click', { 'attribute': 'data-coupon' }, BX.delegate(function(e){deleteCoupon(e); }, this));
});