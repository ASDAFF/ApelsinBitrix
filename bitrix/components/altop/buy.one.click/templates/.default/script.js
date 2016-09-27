function bocFormSubmit(path, required, element_code, element_id) {
	var wait = BX.showWait("boc-" + element_code + element_id);
	BX.ajax.post(
		path + "/script.php",
		{							
			NAME				: BX("bocName-" + element_code + element_id).value,
            TEL					: BX("bocTel-" + element_code + element_id).value,
			EMAIL				: BX("bocEmail-" + element_code + element_id).value,
			MESSAGE				: BX("bocMessage-" + element_code + element_id).value,
			CAPTCHA_WORD		: BX("bocCaptchaWord-" + element_code + element_id) ? BX("bocCaptchaWord-" + element_code + element_id).value : "",
            CAPTCHA_SID			: BX("bocCaptchaSid-" + element_code + element_id) ? BX("bocCaptchaSid-" + element_code + element_id).value : "",
			FORM_NAME			: "BOC",
			ELEMENT_PROPS		: BX("bocElementProps-" + element_code + element_id).value,
			ELEMENT_SELECT_PROPS: BX("bocElementSelectProps-" + element_code + element_id).value,
			QUANTITY			: BX("bocQuantity-" + element_code + element_id).value,
			PERSON_TYPE_ID		: BX("bocPersonTypeId-" + element_code + element_id).value,
			PROP_NAME_ID		: BX("bocPropNameId-" + element_code + element_id).value,
			PROP_TEL_ID			: BX("bocPropTelId-" + element_code + element_id).value,
			PROP_EMAIL_ID		: BX("bocPropEmailId-" + element_code + element_id).value,
			DELIVERY_ID			: BX("bocDeliveryId-" + element_code + element_id).value,
			PAY_SYSTEM_ID		: BX("bocPaysystemId-" + element_code + element_id).value,			
			BUY_MODE			: BX("bocBuyMode-" + element_code + element_id).value,			
			DUB_LETTER			: BX("bocDubLetter-" + element_code + element_id).value,
			REQUIRED			: required,
			ELEMENT_ID			: element_id
		},
		BX.delegate(function(result) {
			BX.adjust(BX("echoBocForm-" + element_code + element_id), {html: result});			
			BX.closeWait("boc-" + element_code + element_id, wait);
		}, this)
	);
}