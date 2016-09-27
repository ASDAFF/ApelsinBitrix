<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/***ELEMENT_PREVIEW_IMG***/
if($arResult["ELEMENT"]["DETAIL_PICTURE"]) {
	$arFileTmp = CFile::ResizeImageGet(
		$arResult["ELEMENT"]["DETAIL_PICTURE"],
		array("width" => 160, "height" => 160),
		BX_RESIZE_IMAGE_PROPORTIONAL,
		true
	);	

	$arResult["ELEMENT"]["PREVIEW_IMG"] = array(
		"SRC" => $arFileTmp["src"],
		"WIDTH" => $arFileTmp["width"],
		"HEIGHT" => $arFileTmp["height"],
	);
}

/***SET_ITEMS_PREVIEW_IMG***/
foreach(array("DEFAULT", "OTHER") as $type) {
	foreach($arResult["SET_ITEMS"][$type] as $key => $arItem) {		
		if($arItem["DETAIL_PICTURE"]) {
			$arFileTmp = CFile::ResizeImageGet(
				$arItem["DETAIL_PICTURE"],
				array("width" => 160, "height" => 160),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true
			);

			$arItem["PREVIEW_IMG"] = array(
				"SRC" => $arFileTmp["src"],
				"WIDTH" => $arFileTmp["width"],
				"HEIGHT" => $arFileTmp["height"],
			);			
		}
		$arResult["SET_ITEMS"][$type][$key] = $arItem;		
	}
}

/***SET_ITEMS_DEFAULT_NO_PRICE***/
foreach($arResult["SET_ITEMS"]["DEFAULT"] as $key => $arItem) {
	if($arItem["PRICE_DISCOUNT_VALUE"] <= 0) {
		unset($arResult["SET_ITEMS"]["DEFAULT"][$key]);
		$arResult["SET_ITEMS"]["DEFAULT"][] = $arResult["SET_ITEMS"]["OTHER"][0];
		unset($arResult["SET_ITEMS"]["OTHER"][0]);
	}
}

/***SET_ITEMS_PROPERTIES_SECTIONS***/
if($arResult["ELEMENT"]["PRICE_DISCOUNT_VALUE"] > 0) {
	$arDefaultSetIDs[] = array(
		"ID" => $arResult["ELEMENT"]["ID"],
		"IBLOCK_ID" => $arResult["ELEMENT"]["IBLOCK_ID"]
	);
} else {
	$arDefaultSetIDs = array();
}
$arSetItems = array();

foreach(array("DEFAULT", "OTHER") as $type) {
	foreach($arResult["SET_ITEMS"][$type] as $key => $arItem) {
		if($type == "DEFAULT") {
			$arDefaultSetIDs[] = array(
				"ID" => $arItem["ID"],
				"IBLOCK_ID" => $arItem["IBLOCK_ID"]
			);
		}
		$arSetItemsIds[] = $arItem["ID"];
		
		$mxResult = CCatalogSku::GetProductInfo($arItem["ID"]);
		if(is_array($mxResult)) {
			$res = CIBlockElement::GetByID($mxResult["ID"]);
			if($ar_res = $res->GetNext()) {
				$arItem["IBLOCK_SECTION_ID"] = $ar_res["IBLOCK_SECTION_ID"];
				$arSetItems[$arItem["IBLOCK_SECTION_ID"]]["ITEMS"][$arItem["ID"]] = $arItem;
				$arResult["SET_ITEMS"][$type][$key]["IBLOCK_SECTION_ID"] = $arItem["IBLOCK_SECTION_ID"];
			}
		} else {
			$arSetItems[$arItem["IBLOCK_SECTION_ID"]]["ITEMS"][$arItem["ID"]] = $arItem;
		}		
	}
}

$arResult["DEFAULT_SET_IDS"] = $arDefaultSetIDs;

/***SET_ITEMS_PROPERTIES***/
if(count($arSetItemsIds) > 0) {
	$rsElements = CIBlockElement::GetList(
		array(),
		array("=ID" => $arSetItemsIds),
		false,
		false,
		array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID")
	);	
	while($obElement = $rsElements->GetNextElement()) {	
		$arItem = $obElement->GetFields();			

		$arItem["PROPERTIES"] = $obElement->GetProperties();		

		$mxResult = CCatalogSku::GetProductInfo($arItem["ID"]);
		if(is_array($mxResult)) {
			foreach($arParams["OFFERS_CART_PROPERTIES"] as $pid) {
				if(!isset($arItem["PROPERTIES"][$pid]))
					continue;
				$prop = &$arItem["PROPERTIES"][$pid];
				$boolArr = is_array($prop["VALUE"]);
				if(($boolArr && !empty($prop["VALUE"])) || (!$boolArr && strlen($prop["VALUE"]) > 0)) {
					$arItem["DISPLAY_PROPERTIES"][$pid] = CIBlockFormatProperties::GetDisplayValue($arItem, $prop, "catalog_out");
				}
			}

			$res = CIBlockElement::GetByID($mxResult["ID"]);
			if($ar_res = $res->GetNext()) {
				$arSetItems[$ar_res["IBLOCK_SECTION_ID"]]["ITEMS"][$arItem["ID"]]["PROPERTIES"] = $arItem["PROPERTIES"];
				$arSetItems[$ar_res["IBLOCK_SECTION_ID"]]["ITEMS"][$arItem["ID"]]["DISPLAY_PROPERTIES"] = $arItem["DISPLAY_PROPERTIES"];
			}
		} else {
			$arSetItems[$arItem["IBLOCK_SECTION_ID"]]["ITEMS"][$arItem["ID"]]["PROPERTIES"] = $arItem["PROPERTIES"];
		}
	}
}

/***SET_ITEMS_SECTIONS***/
$arSetSectIds = array_keys($arSetItems);
if(count($arSetSectIds) > 0) {
	$rsSections = CIBlockSection::GetList(
		array(),
		array(
			"=ID" => $arSetSectIds
		),
		false,
		array("ID", "IBLOCK_ID", "NAME")
	);
	while($arSection = $rsSections->GetNext()) {		
		if($arSetItems[$arSection["ID"]]) {
			$arSetItems[$arSection["ID"]]["ID"] = $arSection["ID"];
			$arSetItems[$arSection["ID"]]["NAME"] = $arSection["NAME"];
		}
	}
}

foreach($arResult["SET_ITEMS"]["DEFAULT"] as $key => $arItem) {				
	$arSetItem = $arSetItems[$arItem["IBLOCK_SECTION_ID"]]["ITEMS"][$arItem["ID"]];
	if($arSetItem) {
		if($arSetItem["PROPERTIES"])
			$arResult["SET_ITEMS"]["DEFAULT"][$key]["PROPERTIES"] = $arSetItem["PROPERTIES"];
		if($arSetItem["DISPLAY_PROPERTIES"])
			$arResult["SET_ITEMS"]["DEFAULT"][$key]["DISPLAY_PROPERTIES"] = $arSetItem["DISPLAY_PROPERTIES"];
		unset($arSetItems[$arItem["IBLOCK_SECTION_ID"]]["ITEMS"][$arItem["ID"]]);
	}
}

$arResult["SET_ITEMS"]["SECTIONS"] = $arSetItems;

/***SET_ITEMS_PRICE***/
$arResult["SET_ITEMS"]["PRICE_VALUE"] = 0;
$arResult["SET_ITEMS"]["OLD_PRICE_VALUE"] = 0;

foreach($arResult["SET_ITEMS"]["DEFAULT"] as $key => $arItem) {
	$arResult["SET_ITEMS"]["PRICE_VALUE"] += $arItem["PRICE_DISCOUNT_VALUE"] * $arItem["BASKET_QUANTITY"];
	$arResult["SET_ITEMS"]["OLD_PRICE_VALUE"] += $arItem["PRICE_VALUE"] * $arItem["BASKET_QUANTITY"];	
}

$arResult["SET_ITEMS"]["PRICE_VALUE"] = $arResult["ELEMENT"]["PRICE_DISCOUNT_VALUE"] + $arResult["SET_ITEMS"]["PRICE_VALUE"];
$arResult["SET_ITEMS"]["OLD_PRICE_VALUE"] = $arResult["ELEMENT"]["PRICE_VALUE"] + $arResult["SET_ITEMS"]["OLD_PRICE_VALUE"];
$arResult["SET_ITEMS"]["PRICE_CURRENCY"] = $arResult["ELEMENT"]["PRICE_CURRENCY"];?>