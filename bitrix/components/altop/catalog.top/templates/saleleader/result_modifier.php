<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Type\Collection;

global $arSetting;

/***FUNCTION_SORT***/
function cmpBySortHit($array1, $array2) {
	if(!isset($array1["SORT"]) || !isset($array2["SORT"]))
		return -1;
	if($array1["SORT"] > $array2["SORT"])
		return 1;
	if($array1["SORT"] < $array2["SORT"])
		return -1;
	if($array1["SORT"] == $array2["SORT"])
		return 0;
}

/***ELEMENTS***/
foreach($arResult["ITEMS"] as $key => $arElement):
	/***CURRENT_DISCOUNT***/
	$arPrice = array();
	$arResult["ITEMS"][$key]["CURRENT_DISCOUNT"] = array();

	if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])) {
		$minId = false;
		$minDiscount = false;
		foreach($arElement["OFFERS"] as $key_off => $arOffer) {
			if($arOffer["MIN_PRICE"]["DISCOUNT_VALUE"] == 0)
				continue;		
			
			if($minDiscount === false || $minDiscount > $arOffer["MIN_PRICE"]["DISCOUNT_VALUE"]) {			
				$minId = $arOffer["ID"];
				$minDiscount = $arOffer["MIN_PRICE"]["DISCOUNT_VALUE"];
			}
		}
		if($minId > 0) {
			$arPrice = CCatalogProduct::GetOptimalPrice($minId, 1, $USER->GetUserGroupArray(), "N");
			$arResult["ITEMS"][$key]["CURRENT_DISCOUNT"] = $arPrice["DISCOUNT"];
		}
	} else {
		$arPrice = CCatalogProduct::GetOptimalPrice($arElement["ID"], 1, $USER->GetUserGroupArray(), "N");
		$arResult["ITEMS"][$key]["CURRENT_DISCOUNT"] = $arPrice["DISCOUNT"];
	}

	/***PREVIEW_IMG***/
	if(is_array($arElement["DETAIL_PICTURE"])) {
		$arFileTmp = CFile::ResizeImageGet(
			$arElement["DETAIL_PICTURE"],
			array("width" => $arParams["DISPLAY_IMG_WIDTH"], "height" => $arParams["DISPLAY_IMG_HEIGHT"]),
			BX_RESIZE_IMAGE_PROPORTIONAL,
			true
		);

		$arResult["ITEMS"][$key]["PREVIEW_IMG"] = array(
			"SRC" => $arFileTmp["src"],
			'WIDTH' => $arFileTmp["width"],
			'HEIGHT' => $arFileTmp["height"],
		);
	}
	
	/***MANUFACTURER***/
	if($arElement["PROPERTIES"]["MANUFACTURER"]["VALUE"]):
		$obElement = CIBlockElement::GetByID($arElement["PROPERTIES"]["MANUFACTURER"]["VALUE"]);
		if($arEl = $obElement->GetNext()):
			$arResult["ITEMS"][$key]["PROPERTIES"]["MANUFACTURER"]["NAME"] = $arEl["NAME"];

			$rsFile = CFile::ResizeImageGet(
				$arEl["PREVIEW_PICTURE"],
				array("width" => 69, "height" => 24),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true
			);
			$arResult["ITEMS"][$key]["PROPERTIES"]["MANUFACTURER"]["PREVIEW_IMG"] = array(
				"SRC" => $rsFile["src"],
				'WIDTH' => $rsFile["width"],
				'HEIGHT' => $rsFile["height"],
			);
		endif;
	endif;

	/***SELECT_PROPS***/
	if(is_array($arParams["PROPERTY_CODE_MOD"]) && !empty($arParams["PROPERTY_CODE_MOD"])) {
		$arResult["ITEMS"][$key]["SELECT_PROPS"] = array();
		foreach($arParams["PROPERTY_CODE_MOD"] as $pid) {
			if(!isset($arElement["PROPERTIES"][$pid]))
				continue;
			$prop = &$arElement["PROPERTIES"][$pid];
			$boolArr = is_array($prop["VALUE"]);
			if($prop["MULTIPLE"] == "Y" && $boolArr && !empty($prop["VALUE"])) {
				$arResult["ITEMS"][$key]["SELECT_PROPS"][$pid] = CIBlockFormatProperties::GetDisplayValue($arElement, $prop, "catalog_out");
				if(!is_array($arResult["ITEMS"][$key]["SELECT_PROPS"][$pid]["DISPLAY_VALUE"]) && !empty($arResult["ITEMS"][$key]["SELECT_PROPS"][$pid]["DISPLAY_VALUE"])) {
					$arTmp = $arResult["ITEMS"][$key]["SELECT_PROPS"][$pid]["DISPLAY_VALUE"];
					unset($arResult["ITEMS"][$key]["SELECT_PROPS"][$pid]["DISPLAY_VALUE"]);
					$arResult["ITEMS"][$key]["SELECT_PROPS"][$pid]["DISPLAY_VALUE"][0] = $arTmp;
				}
			} elseif($prop["MULTIPLE"] == "N" && !$boolArr) {
				if($prop["PROPERTY_TYPE"] == "L") {
					$arResult["ITEMS"][$key]["SELECT_PROPS"][$pid] = $prop;
					$property_enums = CIBlockPropertyEnum::GetList(Array("SORT" => "ASC"), Array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "CODE" => $pid));
					while($enum_fields = $property_enums->GetNext()) {
						$arResult["ITEMS"][$key]["SELECT_PROPS"][$pid]["DISPLAY_VALUE"][] = $enum_fields["VALUE"];
					}
				}
			}
		}
		uasort($arResult["ITEMS"][$key]["SELECT_PROPS"], "cmpBySortHit");
	}

	/***OFFERS***/
	if(isset($arElement['OFFERS']) && !empty($arElement['OFFERS'])):
		/***TOTAL_OFFERS***/	
		$totalQnt = false;
		$totalDiscount = array();
		
		$minId = false;
		$minPrice = false;	
		$minPrintPrice = false;
		$minDiscount = false;
		$minDiscountDiff = false;
		$minDiscountDiffPercent = false;
		$minCurr = false;
		$minMeasureRatio = false;
		$minMeasure = false;
		$minCanByu = false;
		$minQntTrace = false;
		$minProperties = false;
		$minDisplayProperties = false;
		
		$arResult["ITEMS"][$key]["TOTAL_OFFERS"] = array();
		
		foreach($arElement["OFFERS"] as $key_off => $arOffer):			
			$totalQnt += $arOffer["CATALOG_QUANTITY"];
			
			if($arOffer["MIN_PRICE"]["DISCOUNT_VALUE"] == 0)
				continue;

			$totalDiscount[] = $arOffer["MIN_PRICE"]["DISCOUNT_VALUE"];
			
			if($minDiscount === false || $minDiscount > $arOffer["MIN_PRICE"]["DISCOUNT_VALUE"]) {			
				$minId = $arOffer["ID"];
				$minPrice = $arOffer["MIN_PRICE"]["VALUE"];			
				$minPrintPrice = $arOffer["MIN_PRICE"]["PRINT_VALUE"];
				$minDiscount = $arOffer["MIN_PRICE"]["DISCOUNT_VALUE"];
				$minDiscountDiff = $arOffer["MIN_PRICE"]["PRINT_DISCOUNT_DIFF"];
				$minDiscountDiffPercent = $arOffer["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"];
				$minCurr = $arOffer["MIN_PRICE"]["CURRENCY"];			
				$minMeasureRatio = $arOffer["CATALOG_MEASURE_RATIO"];
				$minMeasure = $arOffer["CATALOG_MEASURE_NAME"];
				$minCanByu = $arOffer["MIN_PRICE"]["CAN_BUY"];
				$minQntTrace = $arOffer["CATALOG_QUANTITY_TRACE"];
				$minProperties = $arOffer["PROPERTIES"];
				$minDisplayProperties = $arOffer["DISPLAY_PROPERTIES"];
			}		
		endforeach;
		
		if(count($totalDiscount) > 0):
			$arResult["ITEMS"][$key]["TOTAL_OFFERS"]["MIN_PRICE"] = array(		
				"ID" => $minId,
				"VALUE" => $minPrice,		
				"PRINT_VALUE" => $minPrintPrice,
				"DISCOUNT_VALUE" => $minDiscount,
				"PRINT_DISCOUNT_DIFF" => $minDiscountDiff,
				"DISCOUNT_DIFF_PERCENT" => $minDiscountDiffPercent,
				"CURRENCY" => $minCurr,		
				"CATALOG_MEASURE_RATIO" => $minMeasureRatio,
				"CATALOG_MEASURE_NAME" => $minMeasure,
				"CAN_BUY" => $minCanByu,
				"CATALOG_QUANTITY_TRACE" => $minQntTrace,
				"PROPERTIES" => $minProperties,
				"DISPLAY_PROPERTIES" => $minDisplayProperties
			);
		else:
			$arResult["ITEMS"][$key]["TOTAL_OFFERS"]["MIN_PRICE"] = array(
				"VALUE" => "0",
				"CURRENCY" => $arElement["OFFERS"][0]["MIN_PRICE"]["CURRENCY"],
				"CATALOG_MEASURE_RATIO" => $arElement["OFFERS"][0]["CATALOG_MEASURE_RATIO"],
				"CATALOG_MEASURE_NAME" => $arElement["OFFERS"][0]["CATALOG_MEASURE_NAME"]
			);			
		endif;
		
		$arResult["ITEMS"][$key]["TOTAL_OFFERS"]["QUANTITY"] = $totalQnt;
		
		if(count(array_unique($totalDiscount)) > 1):
			$arResult["ITEMS"][$key]["TOTAL_OFFERS"]["FROM"] = "Y";
		else:
			$arResult["ITEMS"][$key]["TOTAL_OFFERS"]["FROM"] = "N";
		endif;
		/***END_TOTAL_OFFERS***/
		
		/***PREVIEW_IMG***/
		foreach($arElement['OFFERS'] as $key_off => $arOffer):	
			if(isset($arOffer["DETAIL_PICTURE"])) {
				$arFileTmp = CFile::ResizeImageGet(
					$arOffer["DETAIL_PICTURE"],
					array("width" => $arParams["DISPLAY_IMG_WIDTH"], "height" => $arParams["DISPLAY_IMG_HEIGHT"]),
					BX_RESIZE_IMAGE_PROPORTIONAL,
					true
				);

				$arResult["ITEMS"][$key]["OFFERS"][$key_off]["PREVIEW_IMG"] = array(
					"SRC" => $arFileTmp["src"],
					'WIDTH' => $arFileTmp["width"],
					'HEIGHT' => $arFileTmp["height"],
				);
			}
		endforeach;
		/***END_PREVIEW_IMG***/
	endif;
	/***END_OFFERS***/
endforeach;
/***END_ELEMENTS***/


/***PROPERTIES_JS_OFFERS***/
$arParams['OFFER_TREE_PROPS'] = $arParams['OFFERS_PROPERTY_CODE'];
if(!is_array($arParams['OFFER_TREE_PROPS']))
	$arParams['OFFER_TREE_PROPS'] = array($arParams['OFFER_TREE_PROPS']);
foreach($arParams['OFFER_TREE_PROPS'] as $key => $value) {
	$value = (string)$value;
	if('' == $value || '-' == $value)
		unset($arParams['OFFER_TREE_PROPS'][$key]);
}
if(empty($arParams['OFFER_TREE_PROPS']) && isset($arParams['OFFERS_CART_PROPERTIES']) && is_array($arParams['OFFERS_CART_PROPERTIES'])) {
	$arParams['OFFER_TREE_PROPS'] = $arParams['OFFERS_CART_PROPERTIES'];
	foreach($arParams['OFFER_TREE_PROPS'] as $key => $value) {
		$value = (string)$value;
		if('' == $value || '-' == $value)
			unset($arParams['OFFER_TREE_PROPS'][$key]);
	}
}

if(!empty($arResult['ITEMS'])) {
	$arSKUPropList = array();
	$arSKUPropIDs = array();
	$arSKUPropKeys = array();
	$boolSKU = false;
		
	if($arResult['MODULES']['catalog']) {
		$arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
		$boolSKU = !empty($arSKU) && is_array($arSKU);
		if($boolSKU && !empty($arParams['OFFER_TREE_PROPS'])) {
			if($arSetting["OFFERS_VIEW"]["VALUE"] != "LIST") {
				$arSKUPropList = CIBlockPriceTools::getTreeProperties(
					$arSKU,
					$arParams['OFFER_TREE_PROPS'],
					array()
				);
				$arNeedValues = array();
				CIBlockPriceTools::getTreePropertyValues($arSKUPropList, $arNeedValues);
			} else {
				$rsProperties = CIBlock::GetProperties(
					$arSKU["IBLOCK_ID"],
					array(
						"SORT" => "ASC",
						"ID" => "ASC"
					),
					array(
						"=PROPERTY_TYPE" => array("L", "E", "S"),
						"=ACTIVE" => "Y",
						"=MULTIPLE" => "N"
					)
				);
				while($arProp = $rsProperties->fetch()) {
					if($arProp["ID"] == $arSKU["SKU_PROPERTY_ID"])
						continue;
					if(!in_array($arProp["CODE"], $arParams["OFFER_TREE_PROPS"]))
						continue;
					
					if($arProp["PROPERTY_TYPE"] == "E") {
						$showMode = "PICT";
					} else {
						$showMode = "TEXT";
					}
					
					$treeProp = array(
						"ID" => $arProp["ID"],
						"CODE" => $arProp["CODE"],
						"NAME" => $arProp["NAME"],
						"SORT" => $arProp["SORT"],
						"PROPERTY_TYPE" => $arProp["PROPERTY_TYPE"],
						"USER_TYPE" => $arProp["USER_TYPE"],
						"LINK_IBLOCK_ID" => $arProp["LINK_IBLOCK_ID"],
						"USER_TYPE_SETTINGS" => $arProp["USER_TYPE_SETTINGS"],
						"VALUES" => array(),
						"SHOW_MODE" => $showMode,
						"DEFAULT_VALUES" => array(
							"PICT" => false,
							"NAME" => "-"
						)
					);						
					
					$arSKUPropList[$arProp["CODE"]] = $treeProp;
				}
			}
			$arSKUPropIDs = array_keys($arSKUPropList);
			$arSKUPropKeys = array_fill_keys($arSKUPropIDs, false);
		}
	}

	$arNewItemsList = array();
	foreach($arResult['ITEMS'] as $key => $arItem) {
		if($arResult['MODULES']['catalog']) {
			$arItem['CATALOG'] = true;
			if(!isset($arItem['CATALOG_TYPE']))
				$arItem['CATALOG_TYPE'] = CCatalogProduct::TYPE_PRODUCT;
			if((CCatalogProduct::TYPE_PRODUCT == $arItem['CATALOG_TYPE'] || CCatalogProduct::TYPE_SKU == $arItem['CATALOG_TYPE']) && !empty($arItem['OFFERS'])) {
				$arItem['CATALOG_TYPE'] = CCatalogProduct::TYPE_SKU;
			}
			switch($arItem['CATALOG_TYPE']) {
				case CCatalogProduct::TYPE_SET:
					$arItem['OFFERS'] = array();
					break;
				case CCatalogProduct::TYPE_SKU:
					break;
				case CCatalogProduct::TYPE_PRODUCT:
				default:
					break;
			}
		} else {
			$arItem['CATALOG_TYPE'] = 0;
			$arItem['OFFERS'] = array();
		}

		if($arItem['CATALOG'] && isset($arItem['OFFERS']) && !empty($arItem['OFFERS'])) {
			$arMatrixFields = $arSKUPropKeys;
			$arMatrix = array();

			$arNewOffers = array();
			$arItem['OFFERS_PROP'] = false;

			$arDouble = array();
			foreach($arItem['OFFERS'] as $keyOffer => $arOffer) {
				$arOffer['ID'] = intval($arOffer['ID']);
				if(isset($arDouble[$arOffer['ID']]))
					continue;
				$arRow = array();
				foreach($arSKUPropIDs as $propkey => $strOneCode) {
					$arCell = array(
						'VALUE' => 0,
						'SORT' => PHP_INT_MAX,
						'NA' => true
					);
					if(isset($arOffer['DISPLAY_PROPERTIES'][$strOneCode])) {
						$arMatrixFields[$strOneCode] = true;
						$arCell['NA'] = false;
						if('directory' == $arSKUPropList[$strOneCode]['USER_TYPE']) {
							$intValue = $arSKUPropList[$strOneCode]['XML_MAP'][$arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']];
							$arCell['VALUE'] = $intValue;
						} elseif('L' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE']) {
							$arCell['VALUE'] = intval($arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE_ENUM_ID']);
						} elseif('E' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE']) {
							$arCell['VALUE'] = intval($arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']);
						} elseif('S' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE']) {
							$arCell['VALUE'] = intval($arOffer['DISPLAY_PROPERTIES'][$strOneCode]['PROPERTY_VALUE_ID']);					
						}
						$arCell['SORT'] = $arSKUPropList[$strOneCode]['VALUES'][$arCell['VALUE']]['SORT'];
					}
					$arRow[$strOneCode] = $arCell;
				}
				$arMatrix[$keyOffer] = $arRow;

				$arDouble[$arOffer['ID']] = true;
				$arNewOffers[$keyOffer] = $arOffer;
			}
			$arItem['OFFERS'] = $arNewOffers;
			
			$arUsedFields = array();
			$arSortFields = array();

			foreach($arSKUPropIDs as $propkey => $strOneCode) {
				$boolExist = $arMatrixFields[$strOneCode];
				foreach($arMatrix as $keyOffer => $arRow) {
					if($boolExist) {
						if(!isset($arItem['OFFERS'][$keyOffer]['TREE']))
							$arItem['OFFERS'][$keyOffer]['TREE'] = array();
						$arItem['OFFERS'][$keyOffer]['TREE']['PROP_'.$arSKUPropList[$strOneCode]['ID']] = $arMatrix[$keyOffer][$strOneCode]['VALUE'];
						$arItem['OFFERS'][$keyOffer]['SKU_SORT_'.$strOneCode] = $arMatrix[$keyOffer][$strOneCode]['SORT'];
						$arUsedFields[$strOneCode] = true;
						$arSortFields['SKU_SORT_'.$strOneCode] = SORT_NUMERIC;
					} else {
						unset($arMatrix[$keyOffer][$strOneCode]);
					}
				}
			}
			$arItem['OFFERS_PROP'] = $arUsedFields;
							
			Collection::sortByColumn($arItem['OFFERS'], $arSortFields);

			$intSelected = -1;
			$arItem['MIN_PRICE'] = false;
			foreach($arItem['OFFERS'] as $keyOffer => $arOffer) {
				if($arOffer["MIN_PRICE"]["DISCOUNT_VALUE"] == 0)
					continue;
				if($arItem['MIN_PRICE'] === false || $arItem['MIN_PRICE'] > $arOffer['MIN_PRICE']['DISCOUNT_VALUE']) {
					$intSelected = $keyOffer;
					$arItem['MIN_PRICE'] = $arOffer['MIN_PRICE']['DISCOUNT_VALUE'];
				}
			}
			$arMatrix = array();
			foreach($arItem['OFFERS'] as $keyOffer => $arOffer) {				
				$arOneRow = array(
					'ID' => $arOffer['ID'],
					'NAME' => $arOffer['~NAME'],
					'TREE' => $arOffer['TREE']
				);
				$arMatrix[$keyOffer] = $arOneRow;
			}
			if(-1 == $intSelected)
				$intSelected = 0;
			$arItem['JS_OFFERS'] = $arMatrix;
			$arItem['OFFERS_SELECTED'] = $intSelected;
		}

		$arNewItemsList[$key] = $arItem;
	}
	$arResult['ITEMS'] = $arNewItemsList;
	
	$arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_HEX", "PROPERTY_PICT");
	foreach($arSKUPropList as $key => $arSKUProp) {
		if($arSKUProp["SHOW_MODE"] == "PICT") {			
			$arLinkIBlockID = array();
			$arSkuID = array();
			foreach($arSKUProp["VALUES"] as $key2 => $arSKU) {
				$arLinkIBlockID[] = $arSKUProp["LINK_IBLOCK_ID"];
				$arSkuID[] = $arSKU["ID"];
			}
			$arFilter = Array("IBLOCK_ID" => $arLinkIBlockID, "ID" => $arSkuID);
			$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
			while($ob = $res->GetNextElement()) {
				$arFields = $ob->GetFields();				
				$arSKUPropList[$key]["VALUES"][$arFields["ID"]]["HEX"] = !empty($arFields["PROPERTY_HEX_VALUE"]) ? $arFields["PROPERTY_HEX_VALUE"] : array();
				$arSKUPropList[$key]["VALUES"][$arFields["ID"]]["PICT"] = !empty($arFields["PROPERTY_PICT_VALUE"]) ? CFile::ResizeImageGet($arFields["PROPERTY_PICT_VALUE"], array("width" => 24, "height" => 24), BX_RESIZE_IMAGE_PROPORTIONAL, true) : array();
			}			
		}
	}

	$arResult['SKU_PROPS'] = $arSKUPropList;
}?>