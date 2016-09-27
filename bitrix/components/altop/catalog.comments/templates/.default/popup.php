<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->ShowAjaxHead();
$APPLICATION->AddHeadScript("/bitrix/js/main/dd.js");

if(SITE_CHARSET != "utf-8")
	$_REQUEST["arParams"] = $APPLICATION->ConvertCharsetArray($_REQUEST["arParams"], "utf-8", SITE_CHARSET);

$sComponentFolder = $_REQUEST["arParams"]["COMPONENT_PATH"];
$form_action = $_REQUEST["arParams"]["FORM_ACTION"];
$arParams = $_REQUEST["arParams"]["PARAMS"];
$arResult = $_REQUEST["arParams"]["RESULT"];
$arMessage = $_REQUEST["arParams"]["MESS"];?>
		
<div class="container">
	<div class="info">
		<div class="image">
			<?if(is_array($arResult["PREVIEW_IMG"])):?>
				<img src="<?=$arResult['PREVIEW_IMG']['SRC']?>" width="<?=$arResult['PREVIEW_IMG']['WIDTH']?>" height="<?=$arResult['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arResult['NAME']?>" />
			<?else:?>
				<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="150" height="150" alt="<?=$arResult['NAME']?>" />
			<?endif?>
		</div>
		<div class="name"><?=$arResult["ELEMENT_NAME"]?></div>
	</div>
	<?if($arResult["NON_AUTHORIZED_USER_CAN_COMMENT"] == "Y"):?>		
		<form action="<?=$form_action?>" id="reviewForm" class="review-form">
			<span id="echoReviewForm"></span>			
			<?if(!$USER->IsAuthorized()) {?>
				<div class="row">
					<div class="span1">
						<?=$arMessage["MFT_NAME"]?><span class="mf-req">*</span>
					</div>
					<div class="span2">
						<input type="text" id="reviewName" name="review-name" value="" />
					</div>
					<div class="clr"></div>
				</div>
			<?}?>
			<div class="row">
				<div class="span1">
					<?=$arMessage["MFT_REVIEW"]?><span class="mf-req">*</span>
				</div>
				<div class="span2">
					<textarea id="reviewMessage" name="review-message" rows="3" cols="90"></textarea>
				</div>
				<div class="clr"></div>
			</div>
			<?if($arResult["USE_CAPTCHA"] == "Y"):?>
				<div class="row">
					<div class="span1">
						<?=$arMessage["MFT_CAPTCHA"]?><span class="mf-req">*</span>
					</div>
					<div class="span2">
						<input type="text" id="reviewCaptchaWord" name="review-captcha-word" maxlength="50" value="" />			
						<img id="reviewCImg" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CAPTCHA_CODE']?>" width="127" height="30" alt="CAPTCHA" />
						<input type="hidden" id="reviewCaptchaSid" name="review-captcha-sid" value="<?=$arResult['CAPTCHA_CODE']?>" />
					</div>
					<div class="clr"></div>
				</div>
			<?endif;?>			
			<div class="submit">
				<button type="button" class="btn_buy popdef" id="reviewSendButton" name="send-button" onclick="reviewFormSubmit(<?=$arParams['OBJECT_ID']?>, '<?=$arParams['OBJECT_NAME']?>', <?=$arParams['COMMENTS_IBLOCK_ID']?>, '<?=$arResult['URL'];?>', '<?=$sComponentFolder?>', '<?=$arResult["USE_CAPTCHA"]?>', '<?=$arParams["PRE_MODERATION"]?>', '<?=$arResult["PROPS"]?>');"><?=$arMessage["MFT_SEND"];?></button>
			</div>
		</form>
	<?else:?>
		<span class="must_auth"><?=$arMessage["MFT_NON_AUTH"]?></span>
	<?endif;?>
</div>