function orderFormSubmit(path, email_to, required, element_id) {
	var wait = BX.showWait("order-" + element_id);
	BX.ajax.post(
		path + "/script.php",
		{							
			NAME			: BX("orderName-" + element_id).value,
            TEL				: BX("orderTel-" + element_id).value,
			TIME			: BX("orderTime-" + element_id).value,
			MESSAGE			: BX("orderMessage-" + element_id).value,
			CAPTCHA_WORD	: BX("orderCaptchaWord-" + element_id) ? BX("orderCaptchaWord-" + element_id).value : "",
            CAPTCHA_SID		: BX("orderCaptchaSid-" + element_id) ? BX("orderCaptchaSid-" + element_id).value : "",
			FORM_NAME		: "ORDER",
			EMAIL_TO		: email_to,
			REQUIRED		: required,
			ELEMENT_ID		: element_id
		},
		BX.delegate(function(result) {
			BX.adjust(BX("echoOrderForm-" + element_id), {html: result});			
			BX.closeWait("order-" + element_id, wait);
		}, this)
	);
}