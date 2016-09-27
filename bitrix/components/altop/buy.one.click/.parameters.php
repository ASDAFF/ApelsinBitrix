<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("sale") || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("currency"))
	return;


/***IBLOCK_TYPE***/
$arIBlockType = CIBlockParameters::GetIBlockTypes();


/***IBLOCK_ID***/
$arIBlock = array();
$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE"=>"Y"));
while($arr = $rsIBlock->Fetch()) {
	$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
}


/***PERSON_TYPES***/
$arPersonTypes = array();
$db_person_types = CSalePersonType::GetList(
	array("ID" => "ASC"),
	array("ACTIVE" => "Y"),
	false,
	false,
	array("ID", "NAME")
);
while($arPersonType = $db_person_types->Fetch()) {
	$arPersonTypes[$arPersonType["ID"]] = "[".$arPersonType["ID"]."] ".$arPersonType["NAME"];
}


/***ORDER_PROPS***/
$arOrderProps = array();
$db_order_props = CSaleOrderProps::GetList(
	array("SORT" => "ASC"),
	array("PERSON_TYPE_ID" => $arCurrentValues["DEFAULT_PERSON_TYPE"], "ACTIVE" => "Y"),
	false,
	false,
	array("ID", "NAME")
);
while($arOrderProp = $db_order_props->Fetch()) {
	$arOrderProps[$arOrderProp["ID"]] = "[".$arOrderProp["ID"]."] ".$arOrderProp["NAME"];
}


/***DELIVERY***/
$arDeliveries = array(
	"0" => GetMessage("1CB_PARAMETER_DEFAULT_AUTOMATICALLY")
);
$db_deliveries = CSaleDelivery::GetList(
	array("SORT" => "ASC"),
	array("ACTIVE" => "Y"),
	false,
	false,
	array("ID", "NAME")
);
while($arDelivery = $db_deliveries->Fetch()) {	
	$arDeliveries[$arDelivery["ID"]] = "[".$arDelivery["ID"]."] ".$arDelivery["NAME"];
}


/***PAYMENTS***/
$arPayments = array(
	"0" => GetMessage("1CB_PARAMETER_DEFAULT_AUTOMATICALLY")
);
$db_payments = CSalePaySystem::GetList(
	array("SORT" => "ASC"),
	array("ACTIVE" => "Y"),
	false,
	false,
	array("ID", "NAME")
);
while($arPayment = $db_payments->Fetch()) {
	$arPayments[$arPayment["ID"]] = "[".$arPayment["ID"]."] ".$arPayment["NAME"];
}


/***ORDER_FIELDS***/
$arOrderFields = array(
	"NAME" => GetMessage("1CB_PARAMETER_REQUIRED_ORDER_FIELDS_NAME"),
    "TEL" => GetMessage("1CB_PARAMETER_REQUIRED_ORDER_FIELDS_TEL"),
    "EMAIL" => GetMessage("1CB_PARAMETER_REQUIRED_ORDER_FIELDS_EMAIL"),
	"MESSAGE" => GetMessage("1CB_PARAMETER_REQUIRED_ORDER_FIELDS_MESSAGE"),
);


/***BUY_MODES***/
$arBuyModes = array(
	"ONE" => GetMessage("1CB_PARAMETER_BUY_MODE_ONE"),
	"ALL" => GetMessage("1CB_PARAMETER_BUY_MODE_ALL"),
);


/***DUPLICATE_EMAILS***/
$arDubEmails = array();

$rsSites = CSite::GetList($by = "sort", $order = "desc", Array("ACTIVE" => "Y"));
while($arSite = $rsSites->Fetch()) {	
	if(!empty($arSite["EMAIL"]))
		$arDubEmails["default_".$arSite["LID"]] = GetMessage("1CB_PARAMETER_DUPLICATE_LETTER_TO_DEFAULT").$arSite["EMAIL"];
}

$admin_email = COption::GetOptionString("main", "email_from", "");
if(!empty($admin_email))
	$arDubEmails["admin"] = GetMessage("1CB_PARAMETER_DUPLICATE_LETTER_TO_ADMIN").$admin_email;

$sales_email = COption::GetOptionString("sale", "order_email", "");
if(!empty($sales_email))
	$arDubEmails["sales"] = GetMessage("1CB_PARAMETER_DUPLICATE_LETTER_TO_SALES").$sales_email;

$dub_email = COption::GetOptionString("main", "all_bcc", "");
if(!empty($dub_email))
	$arDubEmails["dub"] = GetMessage("1CB_PARAMETER_DUPLICATE_LETTER_TO_DUB").$dub_email;


$arComponentParameters = array(	
	"PARAMETERS" => array(
		"IBLOCK_TYPE" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("1CB_PARAMETER_IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlockType,
			"ADDITIONAL_VALUES" => "N",
			"REFRESH" => "Y",
			"MULTIPLE" => "N",
		),
		"IBLOCK_ID" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("1CB_PARAMETER_IBLOCK_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlock,
			"ADDITIONAL_VALUES" => "Y",
			"REFRESH" => "Y",
			"MULTIPLE" => "N",			
		),
		"ELEMENT_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("1CB_PARAMETER_ELEMENT_ID"),
			"TYPE" => "STRING",
		),
		"ELEMENT_CODE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("1CB_PARAMETER_ELEMENT_CODE"),
			"TYPE" => "STRING",
		),
		"ELEMENT_PROPS" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("1CB_PARAMETER_ELEMENT_PROPS"),
			"TYPE" => "STRING",
		),
		"SELECT_PROP_DIV" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("1CB_PARAMETER_SELECT_PROP_DIV"),
			"TYPE" => "STRING",
		),		
		"REQUIRED_ORDER_FIELDS" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("1CB_PARAMETER_REQUIRED_ORDER_FIELDS"),
			"TYPE" => "LIST",
			"VALUES" => $arOrderFields,
			"DEFAULT" => array('NAME', 'TEL'),
			"ADDITIONAL_VALUES" => "N",
			"REFRESH" => "N",
			"MULTIPLE" => "Y",
		),
		"DEFAULT_PERSON_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("1CB_PARAMETER_DEFAULT_PERSON_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arPersonTypes,
			"ADDITIONAL_VALUES" => "N",
			"REFRESH" => "Y",
			"MULTIPLE" => "N",
		),
		"DEFAULT_ORDER_PROP_NAME" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("1CB_PARAMETER_DEFAULT_ORDER_PROP_NAME"),
			"TYPE" => "LIST",
			"VALUES" => $arOrderProps,
			"ADDITIONAL_VALUES" => "N",
			"REFRESH" => "Y",
			"MULTIPLE" => "N",
		),
		"DEFAULT_ORDER_PROP_TEL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("1CB_PARAMETER_DEFAULT_ORDER_PROP_TEL"),
			"TYPE" => "LIST",
			"VALUES" => $arOrderProps,
			"ADDITIONAL_VALUES" => "N",
			"REFRESH" => "Y",
			"MULTIPLE" => "N",
		),
		"DEFAULT_ORDER_PROP_EMAIL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("1CB_PARAMETER_DEFAULT_ORDER_PROP_EMAIL"),
			"TYPE" => "LIST",
			"VALUES" => $arOrderProps,
			"ADDITIONAL_VALUES" => "N",
			"REFRESH" => "Y",
			"MULTIPLE" => "N",
		),
		"DEFAULT_DELIVERY" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("1CB_PARAMETER_DEFAULT_DELIVERY"),
			"TYPE" => "LIST",
			"VALUES" => $arDeliveries,
			"ADDITIONAL_VALUES" => "N",
			"REFRESH" => "N",
			"MULTIPLE" => "N",
		),
		"DEFAULT_PAYMENT" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("1CB_PARAMETER_DEFAULT_PAYMENT"),
			"TYPE" => "LIST",
			"VALUES" => $arPayments,
			"ADDITIONAL_VALUES" => "N",
			"REFRESH" => "N",
			"MULTIPLE" => "N",
		),		
		"BUY_MODE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("1CB_PARAMETER_BUY_MODE"),
			"TYPE" => "LIST",
			"VALUES" => $arBuyModes,
			"ADDITIONAL_VALUES" => "N",
			"DEFAULT" => "ONE",
			"REFRESH" => "N",
			"MULTIPLE" => "N",
		),		
		"DUPLICATE_LETTER_TO_EMAILS" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("1CB_PARAMETER_DUPLICATE_LETTER_TO_EMAILS"),
			"TYPE" => "LIST",
			"VALUES" => $arDubEmails,
			"ADDITIONAL_VALUES" => "N",
			"REFRESH" => "N",
			"MULTIPLE" => "Y",
		)
	)
);?>