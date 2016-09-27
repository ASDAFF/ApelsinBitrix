<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->ShowAjaxHead();
$APPLICATION->AddHeadScript("/bitrix/js/main/dd.js");

if(SITE_CHARSET != "utf-8")
	$_REQUEST["arParams"] = $APPLICATION->ConvertCharsetArray($_REQUEST["arParams"], "utf-8", SITE_CHARSET);

$sComponentFolder = $_REQUEST["arParams"]["COMPONENT_PATH"];
$form_action = $_REQUEST["arParams"]["FORM_ACTION"];
$arParams = $_REQUEST["arParams"]["PARAMS"];
$element_id = $_REQUEST["arParams"]["ELEMENT_ID"];
$captcha_code = $_REQUEST["arParams"]["CAPTCHA_CODE"];
$required = $_REQUEST["arParams"]["REQUIRED"];
$name = $_REQUEST["arParams"]["NAME"];
$email = $_REQUEST["arParams"]["EMAIL"];
$arMessage = $_REQUEST["arParams"]["MESS"];?>

<form action="<?=$form_action?>" id="bocForm-<?=$element_id?>" class="boc-form">	
	<span id="echoBocForm-<?=$element_id?>" class="echo-boc-form"></span>
	<div class="row">
		<div class="span1">
			<?=$arMessage["MFT_NAME"]?><?if(empty($arParams["REQUIRED_ORDER_FIELDS"]) || in_array("NAME", $arParams["REQUIRED_ORDER_FIELDS"])):?><span class="mf-req">*</span><?endif?>
		</div>
		<div class="span2">
			<input type="text" class="input-text" id="bocName-<?=$element_id?>" name="boc-name" value="<?=$name?>" />
		</div>
		<div class="clr"></div>
	</div>
	<div class="row">
		<div class="span1">
			<?=$arMessage["MFT_TEL"]?><?if(empty($arParams["REQUIRED_ORDER_FIELDS"]) || in_array("TEL", $arParams["REQUIRED_ORDER_FIELDS"])):?><span class="mf-req">*</span><?endif?>
		</div>
		<div class="span2">
			<input type="text" class="input-text" id="bocTel-<?=$element_id?>" name="boc-tel" value="" />
		</div>
		<div class="clr"></div>
	</div>
	<div class="row">
		<div class="span1">
			<?=$arMessage["MFT_EMAIL"]?><?if(empty($arParams["REQUIRED_ORDER_FIELDS"]) || in_array("EMAIL", $arParams["REQUIRED_ORDER_FIELDS"])):?><span class="mf-req">*</span><?endif?>
		</div>
		<div class="span2">
			<input type="text" class="input-text" id="bocEmail-<?=$element_id?>" name="boc-email" value="<?=$email?>" />
		</div>
		<div class="clr"></div>
	</div>
	<div class="row">
		<div class="span1">
			<?=$arMessage["MFT_MESSAGE"]?><?if(empty($arParams["REQUIRED_ORDER_FIELDS"]) || in_array("MESSAGE", $arParams["REQUIRED_ORDER_FIELDS"])):?><span class="mf-req">*</span><?endif?>
		</div>
		<div class="span2">
			<textarea id="bocMessage-<?=$element_id?>" name="boc-message" rows="3" cols="30"></textarea>
		</div>
		<div class="clr"></div>
	</div>
	<?if(!$USER->IsAuthorized()):?>
		<div class="row">
			<div class="span1">
				<?=$arMessage["MFT_CAPTCHA"];?><span class="mf-req">*</span>
			</div>
			<div class="span2">
				<input type="text" id="bocCaptchaWord-<?=$element_id?>" name="boc-captcha-word" maxlength="50" value="" />
				<img id="bocCImg-<?=$element_id?>" src="/bitrix/tools/captcha.php?captcha_sid=<?=$captcha_code?>" width="127" height="30" alt="CAPTCHA" />
				<input type="hidden" id="bocCaptchaSid-<?=$element_id?>" name="boc-captcha-sid" value="<?=$captcha_code?>" />
			</div>
			<div class="clr"></div>
		</div>
	<?endif;?>
	<input type="hidden" id="bocPersonTypeId-<?=$element_id?>" name="boc-person-type-id" value="<?=$arParams['DEFAULT_PERSON_TYPE']?>" />
	<input type="hidden" id="bocPropNameId-<?=$element_id?>" name="boc-prop-name-id" value="<?=$arParams['DEFAULT_ORDER_PROP_NAME']?>" />
	<input type="hidden" id="bocPropTelId-<?=$element_id?>" name="boc-prop-tel-id" value="<?=$arParams['DEFAULT_ORDER_PROP_TEL']?>" />
	<input type="hidden" id="bocPropEmailId-<?=$element_id?>" name="boc-prop-email-id" value="<?=$arParams['DEFAULT_ORDER_PROP_EMAIL']?>" />
	<input type="hidden" id="bocDeliveryId-<?=$element_id?>" name="boc-delivery-id" value="<?=$arParams['DEFAULT_DELIVERY']?>" />
	<input type="hidden" id="bocPaysystemId-<?=$element_id?>" name="boc-paysystem-id" value="<?=$arParams['DEFAULT_PAYMENT']?>" />
	<input type="hidden" id="bocBuyMode-<?=$element_id?>" name="boc-buy-mode" value="<?=$arParams['BUY_MODE']?>" />
	<input type="hidden" id="bocDubLetter-<?=$element_id?>" name="boc-dub-letter" value="<?=$arParams['DUB']?>" />
	<div class="submit">
		<button type="button" class="btn_buy popdef" id="bocSendButton-<?=$element_id?>" name="send-button" onclick="bocFormSubmit('<?=$sComponentFolder?>', '<?=$required?>', '<?=$element_id?>');"><?=$arMessage["MFT_BUY"];?></button>
	</div>
</form>