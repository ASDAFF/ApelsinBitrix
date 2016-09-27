<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->ShowAjaxHead();
$APPLICATION->AddHeadScript("/bitrix/js/main/dd.js");

if(SITE_CHARSET != "utf-8")
	$_REQUEST["arParams"] = $APPLICATION->ConvertCharsetArray($_REQUEST["arParams"], "utf-8", SITE_CHARSET);

$arResult = $_REQUEST["arParams"]["RESULT"];
$arMessage = $_REQUEST["arParams"]["MESS"];?>

<div class="login-form" id="loginForm">
	<div class="fields">
		<form name="form_auth" method="post" target="_top" action="<?=SITE_DIR?>personal/">
			<input type="hidden" name="AUTH_FORM" value="Y"/>
			<input type="hidden" name="TYPE" value="AUTH"/>
			<?if(strlen($arResult["BACKURL"]) > 0):?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>"/>
			<?endif?>
			<?foreach($arResult["POST"] as $key => $value){?>
				<input type="hidden" name="<?=$key?>" value="<?=$value?>"/>
			<?}?>
			<div class="field">
				<input type="text" name="USER_LOGIN" maxlength="50" placeholder="<?=$arMessage['AUTH_LOGIN']?>" value="" class="input-field"/>
			</div>	
			<div class="field">
				<input type="password" name="USER_PASSWORD" maxlength="50" placeholder="<?=$arMessage['AUTH_PASSWORD']?>" value="" class="input-field"/>
			</div>
			<div class="field field-button">
				<button type="submit" name="Login" class="btn_buy popdef" value="<?=$arMessage['LOGIN']?>"><?=$arMessage["LOGIN"]?></button>
			</div>
			<div class="field" style="margin:0px;">
				<a class="btn_buy apuo forgot" href="<?=SITE_DIR?>personal/profile/?forgot_password=yes" rel="nofollow"><?=$arMessage["AUTH_FORGOT_PASSWORD"]?></a>
			</div>
		</form>
		<script type="text/javascript">
			<?if(strlen($arResult["LAST_LOGIN"]) > 0) {?>
				try {
					document.form_auth.USER_PASSWORD.focus();
				} catch(e) {}
			<?} else {?>
				try {
					document.form_auth.USER_LOGIN.focus();
				} catch(e) {}
			<?}?>
		</script>
	</div>					
	<?if($arResult["AUTH_SERVICES"]):?>
		<p class="login_as"><?=$arMessage["LOGIN_AS_USER"]?></p>
		<?$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "icons", 
			array(
				"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
				"SUFFIX" => "form", 
			), 
			$component, 
			array("HIDE_ICONS"=>"Y")
		);?>
		<?$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "",
			array(
				"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
				"AUTH_URL" => $arResult["AUTH_URL"],
				"POST" => $arResult["POST"],
				"POPUP" => "Y",
				"SUFFIX" => "form",
			),
			$component,
			array("HIDE_ICONS"=>"Y")
		);?>
	<?endif?>					
</div>