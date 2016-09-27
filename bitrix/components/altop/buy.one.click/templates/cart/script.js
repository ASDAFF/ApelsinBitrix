function bocFormSubmit(path, required, element_id) {
	var wait = BX.showWait("boc-" + element_id);
	BX.ajax.post(
		path + "/script.php",
		{							
			NAME				: BX("bocName-" + element_id).value,
            TEL					: BX("bocTel-" + element_id).value,
			EMAIL				: BX("bocEmail-" + element_id).value,
			MESSAGE				: BX("bocMessage-" + element_id).value,
			CAPTCHA_WORD		: BX("bocCaptchaWord-" + element_id) ? BX("bocCaptchaWord-" + element_id).value : "",
            CAPTCHA_SID			: BX("bocCaptchaSid-" + element_id) ? BX("bocCaptchaSid-" + element_id).value : "",
			FORM_NAME			: "BOC",			
			PERSON_TYPE_ID		: BX("bocPersonTypeId-" + element_id).value,
			PROP_NAME_ID		: BX("bocPropNameId-" + element_id).value,
			PROP_TEL_ID			: BX("bocPropTelId-" + element_id).value,
			PROP_EMAIL_ID		: BX("bocPropEmailId-" + element_id).value,
			DELIVERY_ID			: BX("bocDeliveryId-" + element_id).value,
			PAY_SYSTEM_ID		: BX("bocPaysystemId-" + element_id).value,			
			BUY_MODE			: BX("bocBuyMode-" + element_id).value,			
			DUB_LETTER			: BX("bocDubLetter-" + element_id).value,
			REQUIRED			: required,
			ELEMENT_ID			: element_id
		},
		BX.delegate(function(result) {
			BX.adjust(BX("echoBocForm-" + element_id), {html: result});			
			BX.closeWait("boc-" + element_id, wait);
		}, this)
	);
}