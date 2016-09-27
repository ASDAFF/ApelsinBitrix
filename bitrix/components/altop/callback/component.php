<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	
$arResult = array();

if(!$USER->IsAuthorized()) {
	$arResult["CAPTCHA_CODE"] = $APPLICATION->CaptchaGetCode();
}
	
$arResult["EMAIL_TO"] = trim($arParams["EMAIL_TO"]);
if(strlen($arParams["EMAIL_TO"]) <= 0)
	$arResult["EMAIL_TO"] = COption::GetOptionString("main", "email_from");

$arResult["REQUIRED"] = implode("/", $arParams["REQUIRED_FIELDS"]);

if($USER->IsAuthorized())
	$arResult["NAME"] = $USER->GetFullName();
	
$this->IncludeComponentTemplate();?>