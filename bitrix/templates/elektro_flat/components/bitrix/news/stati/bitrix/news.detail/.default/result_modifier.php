<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arSort = array(
	$arParams["SORT_BY1"] => $arParams["SORT_ORDER1"],
	$arParams["SORT_BY2"] => $arParams["SORT_ORDER2"],
);
if(!array_key_exists("ID", $arSort))
	$arSort["ID"] = "DESC";

$arSelect = array(
	"ID",
	"IBLOCK_ID",
	"NAME",
	"DETAIL_PAGE_URL",
	"PREVIEW_PICTURE",
);

$arFilter = array (	
	"ACTIVE" => "Y",
	"CHECK_PERMISSIONS" => "Y",
	"IBLOCK_ID" => $arResult["IBLOCK_ID"],
	"SECTION_ID" => $arResult["IBLOCK_SECTION_ID"],
	"INCLUDE_SUBSECTIONS" => "Y"
);

if($arParams["CHECK_DATES"] == "Y") {
	$arFilter["ACTIVE_DATE"] = "Y";
}

$arItems = array();
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
				"PREVIEW_PICTURE" => CFile::ResizeImageGet($arItems[$key - 1]["PREVIEW_PICTURE"], array("width" => 208, "height" => 140), BX_RESIZE_IMAGE_PROPORTIONAL, true)
			);
		}
		if($arItems[$key + 1]) {
			$arResult["NEXT_ITEM"] = array(
				"NAME" => $arItems[$key + 1]["NAME"],
				"URL" => $arItems[$key + 1]["DETAIL_PAGE_URL"],
				"PREVIEW_PICTURE" => CFile::ResizeImageGet($arItems[$key + 1]["PREVIEW_PICTURE"], array("width" => 208, "height" => 140), BX_RESIZE_IMAGE_PROPORTIONAL, true)
			);
		}
		return;
	}
}?>