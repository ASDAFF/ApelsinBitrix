function callbackFormSubmit(path, email_to, required) {
	var wait = BX.showWait("callback");
	BX.ajax.post(
		path + "/script.php",
		{							
			NAME			: BX("callbackName").value,
            TEL				: BX("callbackTel").value,
			TIME			: BX("callbackTime").value,
			MESSAGE			: BX("callbackMessage").value,
			CAPTCHA_WORD	: BX("callbackCaptchaWord") ? BX("callbackCaptchaWord").value : "",
            CAPTCHA_SID		: BX("callbackCaptchaSid") ? BX("callbackCaptchaSid").value : "",
			FORM_NAME		: "CALLBACK",
			EMAIL_TO		: email_to,
			REQUIRED		: required
		},
		BX.delegate(function(result) {
			BX.adjust(BX("echoCallbackForm"), {html: result});			
			BX.closeWait("callback", wait);
		}, this)
	);
}