function reviewFormSubmit(object_id, object_name, comments_iblock_id, url, path, captcha, pre_moder, props) {
	var wait = BX.showWait("review");
	BX.ajax.post(
		path + "/script.php",
		{							
			NAME			: BX("reviewName") ? BX("reviewName").value : "",
			MESSAGE			: BX("reviewMessage").value,
			CAPTCHA_WORD	: BX("reviewCaptchaWord") ? BX("reviewCaptchaWord").value : "",
            CAPTCHA_SID		: BX("reviewCaptchaSid") ? BX("reviewCaptchaSid").value : "",
			FORM_NAME		: "REVIEW",			
            OID             : object_id,
			ONAME           : object_name,
            CID             : comments_iblock_id,
			URL             : url,
            PATH            : path,            
            CAPTCHA         : captcha,
            PRE_MODER       : pre_moder,
            PROPS           : props
		},
		BX.delegate(function(result) {
			BX.adjust(BX("echoReviewForm"), {html: result});			
			BX.closeWait("review", wait);
		}, this)
	);
}