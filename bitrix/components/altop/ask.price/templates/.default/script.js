function askPriceFormSubmit(path, email_to, required, element_id) {
	var wait = BX.showWait("askPrice-" + element_id);
	BX.ajax.post(
		path + "/script.php",
		{							
			NAME			: BX("askPriceName-" + element_id).value,
            TEL				: BX("askPriceTel-" + element_id).value,
			TIME			: BX("askPriceTime-" + element_id).value,
			MESSAGE			: BX("askPriceMessage-" + element_id).value,
			CAPTCHA_WORD	: BX("askPriceCaptchaWord-" + element_id) ? BX("askPriceCaptchaWord-" + element_id).value : "",
            CAPTCHA_SID		: BX("askPriceCaptchaSid-" + element_id) ? BX("askPriceCaptchaSid-" + element_id).value : "",
			FORM_NAME		: "ASKPRICE",
			EMAIL_TO		: email_to,
			REQUIRED		: required,
			ELEMENT_ID		: element_id
		},
		BX.delegate(function(result) {
			BX.adjust(BX("echoAskPriceForm-" + element_id), {html: result});			
			BX.closeWait("askPrice-" + element_id, wait);
		}, this)
	);
}