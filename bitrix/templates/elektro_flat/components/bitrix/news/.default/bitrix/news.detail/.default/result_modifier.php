<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arSort = array(
	$arParams["SORT_BY1"]=>$arParams["SORT_ORDER1"],
	$arParams["SORT_BY2"]=>$arParams["SORT_ORDER2"],
);
if(!array_key_exists("ID", $arSort))
	$arSort["ID"] = "DESC";

$arSelect = array(
	"ID",
	"NAME",
	"DETAIL_PAGE_URL",
	"DATE_ACTIVE_FROM",
);

$arFilter = array (	
	"ACTIVE" => "Y",
	"CHECK_PERMISSIONS" => "Y",
	"IBLOCK_ID" => $arResult["IBLOCK_ID"],
);

if($arParams["CHECK_DATES"] == "Y") {
	$arFilter["ACTIVE_DATE"] = "Y";
}

$arItems = Array();
$rsElement = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
while($arElement = $rsElement->GetNext()) {	
	$arItems[] = $arElement;
}

foreach($arItems as $key => $arItem) {	
	if($arItem["ID"] == $arResult["ID"]) {
		if($arItems[$key - 1]) {
			$arResult["PREV_ITEM"] = array(
				"NAME" => $arItems[$key - 1]["NAME"],
				"URL" => $arItems[$key - 1]["DETAIL_PAGE_URL"],
				"DISPLAY_ACTIVE_FROM" => CIBlockFormatProperties::DateFormat($arParams["ACTIVE_DATE_FORMAT"], MakeTimeStamp($arItems[$key - 1]["DATE_ACTIVE_FROM"], CSite::GetDateFormat()))
			);
		}
		if($arItems[$key + 1]) {
			$arResult["NEXT_ITEM"] = array(
				"NAME" => $arItems[$key + 1]["NAME"],
				"URL" => $arItems[$key + 1]["DETAIL_PAGE_URL"],
				"DISPLAY_ACTIVE_FROM" => CIBlockFormatProperties::DateFormat($arParams["ACTIVE_DATE_FORMAT"], MakeTimeStamp($arItems[$key + 1]["DATE_ACTIVE_FROM"], CSite::GetDateFormat()))
			);
		}
		return;
	}
}?>