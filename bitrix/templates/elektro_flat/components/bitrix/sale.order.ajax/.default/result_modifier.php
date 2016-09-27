<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("catalog"))
	return;

/***PICTURE***/
foreach($arResult["BASKET_ITEMS"] as $key => $arBasketItems) {
	$ar = CIBlockElement::GetList(
		array(), 
		array("ID" => $arBasketItems["PRODUCT_ID"]), 
		false, 
		false, 
		array("ID", "IBLOCK_ID", "DETAIL_PICTURE")
	)->Fetch();		
	if($ar["DETAIL_PICTURE"] > 0) {
		$arResult["BASKET_ITEMS"][$key]["DETAIL_PICTURE"] = CFile::ResizeImageGet($ar["DETAIL_PICTURE"], array("width" => 30, "height" => 30), BX_RESIZE_IMAGE_PROPORTIONAL, true);
	} else {
		$mxResult = CCatalogSku::GetProductInfo($ar["ID"]);
		if(is_array($mxResult)) {
			$ar = CIBlockElement::GetList(
				array(), 
				array("ID" => $mxResult["ID"]), 
				false, 
				false, 
				array("ID", "IBLOCK_ID", "DETAIL_PICTURE")
			)->Fetch();
			if($ar["DETAIL_PICTURE"] > 0) {
				$arResult["BASKET_ITEMS"][$key]["DETAIL_PICTURE"] = CFile::ResizeImageGet($ar["DETAIL_PICTURE"], array("width" => 30, "height" => 30), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			}
		}
	}
}

/***AUTH_SERVICES***/
$arResult["AUTH_SERVICES"] = false;
if(!$USER->IsAuthorized() && CModule::IncludeModule("socialservices")) {
	$oAuthManager = new CSocServAuthManager();
	$arServices = $oAuthManager->GetActiveAuthServices($arResult);

	if(!empty($arServices)) {
		$arResult["AUTH_SERVICES"] = $arServices;
	}
}?>