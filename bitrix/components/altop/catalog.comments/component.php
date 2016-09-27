<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

$arResult = array();

$arResult["OBJECT_NAME"] = $arParams["OBJECT_NAME"];	
$arResult["PROPS"] = implode("/", array($arParams["PROPERTY_OBJECT_ID"], $arParams["PROPERTY_USER_ID"], $arParams["PROPERTY_IP_COMMENTOR"], $arParams["PROPERTY_URL"]));	
$arResult["NON_AUTHORIZED_USER_CAN_COMMENT"] = $arParams["NON_AUTHORIZED_USER_CAN_COMMENT"];	
$arResult["PRE_MODERATION"] = $arParams["PRE_MODERATION"];
$arResult["USE_CAPTCHA"] = $arParams["USE_CAPTCHA"];

//Проверка на заполненность обязательных параметров компонента
if(empty($arParams["OBJECT_ID"])) {
	echo GetMessage("NO_OBJECT_ID");
	return;
}

if(empty($arParams["OBJECT_NAME"])) {
	echo GetMessage("NO_OBJECT_NAME");
	return;
}

if(empty($arParams["COMMENTS_IBLOCK_ID"])) {
	echo GetMessage("NO_COMMENTS_IBLOCK_ID");
	return;
}

if(empty($arParams["PROPERTY_OBJECT_ID"])) {
	echo GetMessage("NO_PROPERTY_OBJECT_ID");
	return;
}

if(empty($arParams["PROPERTY_USER_ID"])) {
	echo GetMessage("NO_PROPERTY_USER_ID");
	return;
}

$arElement = CIBlockElement::GetList(
	array(), 
	array("ID" => $arParams["OBJECT_ID"]), 
	false, 
	false, 
	array("ID", "IBLOCK_ID", "NAME", "DETAIL_PICTURE")
)->Fetch();	

$arResult["ELEMENT_NAME"] = $arElement["NAME"];

if($arElement["DETAIL_PICTURE"] > 0) {
	$arFileTmp = CFile::ResizeImageGet(
		$arElement["DETAIL_PICTURE"],
		array("width" => 178, "height" => 178),
		BX_RESIZE_IMAGE_PROPORTIONAL,
		true
	);		
	$arResult["PREVIEW_IMG"] = array(
		"SRC" => $arFileTmp["src"],
		"WIDTH" => $arFileTmp["width"],
		"HEIGHT" => $arFileTmp["height"],
	);	
}

if(!$USER->IsAuthorized() && $arResult["USE_CAPTCHA"] == "Y" && $arResult["NON_AUTHORIZED_USER_CAN_COMMENT"] == "Y") {
	$arResult["CAPTCHA_CODE"] = $APPLICATION->CaptchaGetCode();
} elseif($USER->IsAdmin()) {
	$arResult["USE_CAPTCHA"] = "N";
} elseif($USER->IsAuthorized() && !$USER->IsAdmin()) {
	$arResult["USE_CAPTCHA"] = "N";
} else {
	$arResult["USE_CAPTCHA"] = "N";
}	

$comments = array();
	
$arSelect = array("ID", "IBLOCK_ID", "DETAIL_TEXT", "PROPERTY_".$arParams["PROPERTY_USER_ID"], "ACTIVE", "DATE_CREATE", "DATE_ACTIVE_FROM", "CREATED_BY");
	
$arFilter = array("IBLOCK_ID" => $arParams["COMMENTS_IBLOCK_ID"], "ACTIVE" => "Y", "PROPERTY_".$arParams["PROPERTY_OBJECT_ID"] => $arParams["OBJECT_ID"]);
		
$res = CIBlockElement::GetList(
	array(
		"sort" => "asc",
		"active_from" => "desc",
		"created" => "desc"
	),
	$arFilter,
	false,
	array("nPageSize" => 5),
	$arSelect
);
		
while($ob = $res->GetNextElement()) {

	$arFields = $ob->GetFields();
					
	$user["NAME"] = $arFields["PROPERTY_".$arParams["PROPERTY_USER_ID"]."_VALUE"];
		
	$user["PICT"] = array();
	$rsUser = CUser::GetByID($arFields["CREATED_BY"]);
	if($arUser = $rsUser->Fetch()):
		if(!empty($arUser["PERSONAL_PHOTO"])):
			$arFileTmp = CFile::ResizeImageGet(
				$arUser["PERSONAL_PHOTO"],
				array("width" => 57, "height" => 57),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true
			);

			$user["PICT"] = array(
				"SRC" => $arFileTmp["src"],
				"WIDTH" => $arFileTmp["width"],
				"HEIGHT" => $arFileTmp["height"],
			);
		endif;
	endif;

	$comments[] = array(
		"ID" => $arFields["ID"],
		"ACTIVE" => $arFields["ACTIVE"],
		"DATE" => $arFields["DATE_ACTIVE_FROM"] ? $arFields["DATE_ACTIVE_FROM"] : $arFields["DATE_CREATE"],
		"USER" => $user,
		"TEXT" => $arFields["DETAIL_TEXT"],
	);
}

$navStr = $res->GetPageNavStringEx($navComponentObject, "", "reviews");
	
$arResult["COMMENTS"] = $comments;
$arResult["URL"] = $APPLICATION->GetCurPage();
$arResult["COMMENTS_COUNT"] = sizeof($comments);
			
$this->IncludeComponentTemplate();

echo $navStr;?>