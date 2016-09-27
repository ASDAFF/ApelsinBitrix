<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->ShowAjaxHead();
$APPLICATION->AddHeadScript("/bitrix/js/main/dd.js");

if(SITE_CHARSET != "utf-8")
	$_REQUEST["arParams"] = $APPLICATION->ConvertCharsetArray($_REQUEST["arParams"], "utf-8", SITE_CHARSET);

$sComponentFolder = $_REQUEST["arParams"]["COMPONENT_PATH"];
$form_action = $_REQUEST["arParams"]["FORM_ACTION"];
$arParams = $_REQUEST["arParams"]["PARAMS"];
$captcha_code = $_REQUEST["arParams"]["CAPTCHA_CODE"];
$email_to = $_REQUEST["arParams"]["EMAIL_TO"];
$required = $_REQUEST["arParams"]["REQUIRED"];
$name = $_REQUEST["arParams"]["NAME"];
$arMessage = $_REQUEST["arParams"]["MESS"];?>

<form action="<?=$form_action?>" id="callbackForm" class="callback-form">		
	<span id="echoCallbackForm"></span>
	<div class="row">
		<div class="span1">
			<?=$arMessage["MFT_NAME"]?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("NAME", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
		</div>
		<div class="span2">
			<input type="text" class="input-text" id="callbackName" name="callback-name" value="<?=$name?>" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="row">
		<div class="span1">
			<?=$arMessage["MFT_TEL"]?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("TEL", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
		</div>
		<div class="span2">
			<input type="text" class="input-text" id="callbackTel" name="callback-tel" value="" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="row">
		<div class="span1">
			<?=$arMessage["MFT_TIME"]?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("TIME", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
		</div>
		<div class="span2">
			<input type="text" class="input-text" id="callbackTime" name="callback-time" value="" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="row">
		<div class="span1">
			<?=$arMessage["MFT_MESSAGE"]?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("MESSAGE", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
		</div>
		<div class="span2">
			<textarea id="callbackMessage" name="callback-message" rows="3" cols="30"></textarea>
		</div>
		<div class="clear"></div>
	</div>
	<?if(!$USER->IsAuthorized()):?>			
		<div class="row">
			<div class="span1">
				<?=$arMessage["MFT_CAPTCHA"];?><span class="mf-req">*</span>
			</div>
			<div class="span2">					
				<input type="text" id="callbackCaptchaWord" name="callback-captcha-word" maxlength="50" value="" />			
				<img id="callbackCImg" src="/bitrix/tools/captcha.php?captcha_sid=<?=$captcha_code?>" width="127" height="30" alt="CAPTCHA" />
				<input type="hidden" id="callbackCaptchaSid" name="callback-captcha-sid" value="<?=$captcha_code?>" />					
			</div>
			<div class="clear"></div>
		</div>			
	<?endif;?>
	<div class="submit">
		<button type="button" class="btn_buy popdef" id="callbackSendButton" name="send-button" onclick="callbackFormSubmit('<?=$sComponentFolder?>', '<?=$email_to?>', '<?=$required?>');"><?=$arMessage["MFT_ORDER"];?></button>
	</div>
</form>