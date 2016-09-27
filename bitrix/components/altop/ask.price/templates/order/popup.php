<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->ShowAjaxHead();
$APPLICATION->AddHeadScript("/bitrix/js/main/dd.js");

if(SITE_CHARSET != "utf-8")
	$_REQUEST["arParams"] = $APPLICATION->ConvertCharsetArray($_REQUEST["arParams"], "utf-8", SITE_CHARSET);

$sComponentFolder = $_REQUEST["arParams"]["COMPONENT_PATH"];
$form_action = $_REQUEST["arParams"]["FORM_ACTION"];
$arParams = $_REQUEST["arParams"]["PARAMS"];
$element_id = $_REQUEST["arParams"]["ELEMENT_ID"];
$element_name = $_REQUEST["arParams"]["ELEMENT_NAME"];
$preview_img = $_REQUEST["arParams"]["PREVIEW_IMG"];
$captcha_code = $_REQUEST["arParams"]["CAPTCHA_CODE"];
$email_to = $_REQUEST["arParams"]["EMAIL_TO"];
$required = $_REQUEST["arParams"]["REQUIRED"];
$name = $_REQUEST["arParams"]["NAME"];
$message = $_REQUEST["arParams"]["MESSAGE"];
$arMessage = $_REQUEST["arParams"]["MESS"];

/***JS***/?>
<script type="text/javascript">
	/***SELECT_PROPS***/
	<?if(!empty($arParams["SELECT_PROP_DIV"])) {?>
		var selAskOrderValueArr = [],
			orderMessageArr = [];
		ActiveItems = BX.findChildren(BX("<?=$arParams['SELECT_PROP_DIV']?>"), {tagName: "li", className: "active"}, true);
		if(!!ActiveItems && 0 < ActiveItems.length) {
			for(i = 0; i < ActiveItems.length; i++) {			
				SelectName = BX.findChildren(ActiveItems[i].parentNode.parentNode.parentNode, {className: "h3"}, true);
				SelectValue = BX.findChildren(ActiveItems[i], {tagName: "span"}, true);
				if((!!SelectName && 0 < SelectName.length) && (!!SelectValue && 0 < SelectValue.length)) {					
					selAskOrderValueArr[i] = SelectName[0].innerHTML+': '+SelectValue[0].innerHTML;
				}
			}
		}
		if(0 < selAskOrderValueArr.length) {
			selAskOrderValue = selAskOrderValueArr.join('; ');		
			orderMessage = "<?=$message?>";
			orderMessageArr = orderMessage.split(')');
			orderMessageNew = orderMessageArr[0];		
			BX("orderMessage-<?=$element_id?>").innerHTML = orderMessageNew + "; " + selAskOrderValue + ")";
		}
	<?}?>
</script>

<div class="container">
	<div class="info">
		<div class="image">
			<?if(is_array($preview_img)):?>
				<img src="<?=$preview_img['SRC']?>" width="<?=$preview_img['WIDTH']?>" height="<?=$preview_img['HEIGHT']?>" alt="<?=$element_name?>" />
			<?else:?>
				<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="150" height="150" alt="<?=$element_name?>" />
			<?endif?>
		</div>
		<div class="name"><?=$element_name?></div>
	</div>
	<form action="<?=$form_action?>" id="orderForm-<?=$element_id?>" class="order-form">
		<span id="echoOrderForm-<?=$element_id?>" class="echo-order-form"></span>
		<div class="row">
			<div class="span1">
				<?=$arMessage["MFT_NAME"]?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("NAME", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
			</div>
			<div class="span2">
				<input type="text" class="input-text" id="orderName-<?=$element_id?>" name="order-name" value="<?=$name?>" />
			</div>
			<div class="clear"></div>
		</div>			
		<div class="row">
			<div class="span1">
				<?=$arMessage["MFT_TEL"]?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("TEL", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
			</div>
			<div class="span2">
				<input type="text" class="input-text" id="orderTel-<?=$element_id?>" name="order-tel" value="" />
			</div>
			<div class="clear"></div>
		</div>
		<div class="row">
			<div class="span1">
				<?=$arMessage["MFT_TIME"]?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("TIME", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
			</div>
			<div class="span2">
				<input type="text" class="input-text" id="orderTime-<?=$element_id?>" name="order-time" value="" />
			</div>
			<div class="clear"></div>
		</div>
		<div class="row">
			<div class="span1">
				<?=$arMessage["MFT_MESSAGE"]?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("MESSAGE", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
			</div>
			<div class="span2">
				<textarea id="orderMessage-<?=$element_id?>" name="order-message" rows="3" cols="30"><?=$message?></textarea>
			</div>
			<div class="clear"></div>
		</div>
		<?if(!$USER->IsAuthorized()):?>
			<div class="row">
				<div class="span1">
					<?=$arMessage["MFT_CAPTCHA"];?><span class="mf-req">*</span>
				</div>
				<div class="span2">
					<input type="text" id="orderCaptchaWord-<?=$element_id?>" name="order-captcha-word" maxlength="50" value="" />
					<img id="orderCImg-<?=$element_id?>" src="/bitrix/tools/captcha.php?captcha_sid=<?=$captcha_code?>" width="127" height="30" alt="CAPTCHA" />
					<input type="hidden" id="orderCaptchaSid-<?=$element_id?>" name="order-captcha-sid" value="<?=$captcha_code?>" />
				</div>
				<div class="clear"></div>
			</div>
		<?endif;?>
		<div class="submit">
			<button type="button" class="btn_buy popdef" id="orderSendButton-<?=$element_id?>" name="send-button" onclick="orderFormSubmit('<?=$sComponentFolder?>', '<?=$email_to?>', '<?=$required?>', '<?=$element_id?>');"><?=$arMessage["MFT_ORDER"];?></button>				
		</div>
	</form>
</div>