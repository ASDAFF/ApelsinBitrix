<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("sale") || !CModule::IncludeModule("catalog"))
	return;

$rsBasket = CSaleBasket::GetList(
	array(), 
	array(
		"FUSER_ID" => CSaleBasket::GetBasketUserID(),
		"LID" => SITE_ID,
		"ORDER_ID" => "NULL",
		"DELAY" => "N",
		"CAN_BUY" => "Y",
	), 
	false, 
	false, 
	array("ID", "NAME", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "DELAY", "CAN_BUY", "PRICE", "WEIGHT", "DETAIL_PAGE_URL", "NOTES", "CURRENCY", "VAT_RATE", "CATALOG_XML_ID", "PRODUCT_XML_ID", "SUBSCRIBE", "DISCOUNT_PRICE", "PRODUCT_PROVIDER_CLASS", "TYPE", "SET_PARENT_ID")
);

$arBasketItems = array();

while($arItem = $rsBasket->Fetch()) {
	if(CSaleBasketHelper::isSetItem($arItem))
		continue;
	$arBasketItems[] = $arItem;
}

$totalQnt = 0;
$totalPrice = 0;
$arResult = array();

if($arBasketItems) {
	foreach($arBasketItems as $arItem) {		
		$totalQnt += $arItem["QUANTITY"];
		$totalPrice += $arItem["PRICE"] * $arItem["QUANTITY"];			
	}

	$arOrder = array(
		"SITE_ID" => SITE_ID,
		"USER_ID" => CSaleBasket::GetBasketUserID(),
		"ORDER_PRICE" => $totalPrice,
		"ORDER_WEIGHT" => array(),
		"BASKET_ITEMS" => $arBasketItems
	);	

	$arOptions = array(
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => $arParams["COUNT_DISCOUNT_4_ALL_QUANTITY"],
	);
	
	$arErrors = array();
	
	CSaleDiscount::DoProcessOrder($arOrder, $arOptions, $arErrors);
	
	$totalPrice = $arOrder["ORDER_PRICE"];
}

$arResult["QUANTITY"] = $totalQnt;

$currency = CCurrencyLang::GetCurrencyFormat(CSaleLang::GetLangCurrency(SITE_ID), "ru");
if(empty($currency["THOUSANDS_SEP"])):
	$currency["THOUSANDS_SEP"] = " ";
endif;

$arResult["DECIMALS"] = $currency["DECIMALS"];
if($currency["HIDE_ZERO"] == "Y"):
	if(round($totalPrice, $currency["DECIMALS"]) == round($totalPrice, 0)):
		$arResult["DECIMALS"] = 0;
	endif;
endif;
$arResult["DEC_POINT"] = $currency["DEC_POINT"];
$arResult["THOUSANDS_SEP"] = $currency["THOUSANDS_SEP"];

$arResult["SUM"] = $totalPrice;
$arResult["SUM_FORMATED"] = number_format($totalPrice, $arResult["DECIMALS"], $arResult["DEC_POINT"], $arResult["THOUSANDS_SEP"]);

$arResult["CURRENCY"] = str_replace("# ", " ", $currency["FORMAT_STRING"]);?>