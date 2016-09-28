<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог товаров");?>

<?
define('BX_DISABLE_INDEX_PAGE', true);
include_once $_SERVER["DOCUMENT_ROOT"]."/apls_lib/catalog/APLS_CatalogItemLabels.php";
?>

<?$APPLICATION->IncludeComponent("bitrix:catalog", ".default",
	array(
		"PATH_TO_SHIPPING" => "/delivery/",
		"DISPLAY_IMG_WIDTH" => "178",
		"DISPLAY_IMG_HEIGHT" => "178",
		"DISPLAY_DETAIL_IMG_WIDTH" => "390",
		"DISPLAY_DETAIL_IMG_HEIGHT" => "390",
		"DISPLAY_MORE_PHOTO_WIDTH" => "86",
		"DISPLAY_MORE_PHOTO_HEIGHT" => "86",
		"SHARPEN" => "30",
		"AJAX_MODE" => "N",
		"SEF_MODE" => "Y",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "13",
		"USE_FILTER" => "Y",
		"USE_REVIEW" => "N",
		"USE_COMPARE" => "Y",
		"SHOW_TOP_ELEMENTS" => "N",
		"SECTION_COUNT_ELEMENTS" => "N",
		"SECTION_TOP_DEPTH" => "2",
		"PAGE_ELEMENT_COUNT" => "12",
		"LINE_ELEMENT_COUNT" => "1",
		"ELEMENT_SORT_FIELD" => "CATALOG_AVAILABLE",
		"ELEMENT_SORT_ORDER" => "desc",
		"ELEMENT_SORT_FIELD2" => "",
		"ELEMENT_SORT_ORDER2" => "",
		"LIST_PROPERTY_CODE" => array(
			0 => "CHASTOTA_H_H",
			1 => "MAX_KR_MOM",
			2 => "NAPRAJ_AKKUM",
			3 => "VES_S_AKKUM"
		),
		"INCLUDE_SUBSECTIONS" => "Y",
		"USE_MAIN_ELEMENT_SECTION" => "Y",
		"LIST_META_KEYWORDS" => "UF_KEYWORDS",
		"LIST_META_DESCRIPTION" => "UF_META_DESCRIPTION",
		"LIST_BROWSER_TITLE" => "UF_BROWSER_TITLE",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "ARTNUMBER",
			1 => "MANUFACTURER",
			2 => "COUNTRY",
			3 => "CHASTOTA_H_H",
			4 => "MAX_KR_MOM",
			5 => "NAPRAJ_AKKUM",
			6 => "VES_S_AKKUM"
		),
		"DETAIL_META_KEYWORDS" => "KEYWORDS",
		"DETAIL_META_DESCRIPTION" => "DESCRIPTION",
		"DETAIL_BROWSER_TITLE" => "TITLE",
		"DETAIL_SET_CANONICAL_URL" => "N",
		"BASKET_URL" => "/personal/cart/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "86400",
		"CACHE_NOTES" => "",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "Y",
		"SET_STATUS_404" => "Y",
		"PRICE_CODE" => array(
			0 => "BASE"
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"PRODUCT_PROPERTIES" => array(),
		"USE_PRODUCT_QUANTITY" => "Y",
		"LINK_IBLOCK_TYPE" => "",
		"LINK_IBLOCK_ID" => "",
		"LINK_PROPERTY_SID" => "",
		"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
		"USE_ALSO_BUY" => "N",
		"USE_STORE" => "Y",
		"USE_ELEMENT_COUNTER" => "Y",
		"PAGER_TEMPLATE" => "arrows",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"COMPARE_FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_TEXT",
			2 => "DETAIL_PICTURE"
		),
		"COMPARE_PROPERTY_CODE" => array(
			0 => "NEWPRODUCT",
			1 => "SALELEADER",
			2 => "DISCOUNT",
			3 => "ARTNUMBER",
			4 => "MANUFACTURER",
			5 => "COUNTRY",
			6 => "CHASTOTA_H_H",
			7 => "MAX_KR_MOM",
			8 => "NAPRAJ_AKKUM",
			9 => "VES_S_AKKUM"
		),
		"COMPARE_OFFERS_FIELD_CODE" => array(),
		"COMPARE_OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR",
			1 => "PROP2"
		),
		"COMPARE_ELEMENT_SORT_FIELD" => "sort",
		"COMPARE_ELEMENT_SORT_ORDER" => "asc",
		"DISPLAY_ELEMENT_SELECT_BOX" => "N",
		"LIST_OFFERS_FIELD_CODE" => array(),
		"LIST_OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR",
			1 => "PROP2"
		),
		"LIST_OFFERS_LIMIT" => "",
		"DETAIL_OFFERS_FIELD_CODE" => array(),
		"DETAIL_OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR",
			1 => "PROP2"
		),
		"FILTER_NAME" => "arrFilter",
		"FILTER_FIELD_CODE" => array(),
		"FILTER_PROPERTY_CODE" => array(),
		"FILTER_PRICE_CODE" => array(
			0 => "BASE"
		),
		"FILTER_OFFERS_FIELD_CODE" => array(),
		"FILTER_OFFERS_PROPERTY_CODE" => array(),
		"USE_STORE_PHONE" => "Y",
		"USE_STORE_SCHEDULE" => "Y",
		"USE_MIN_AMOUNT" => "N",
		"STORE_PATH" => "/store/#store_id#",
		"MAIN_TITLE" => "Наличие на складах",
		"HIDE_NOT_AVAILABLE" => "N",
		"CONVERT_CURRENCY" => "N",
		"CURRENCY_ID" => "",
		"OFFERS_CART_PROPERTIES" => array(
			0 => "COLOR",
			1 => "PROP2"
		),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "asc",
		"IBLOCK_TYPE_REVIEWS" => "catalog",
		"IBLOCK_ID_REVIEWS" => "15",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"SEF_FOLDER" => "/catalog/",
		"USE_BIG_DATA" => "Y",
		"BIG_DATA_RCM_TYPE" => "any",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ADD_ELEMENT_CHAIN" => "Y",
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
		"STORES" => array(),
		"USER_FIELDS" => array(),
		"FIELDS" => array(
			0 => "TITLE",
			1 => "ADDRESS",
			2 => "DESCRIPTION",
			3 => "PHONE",
			4 => "SCHEDULE",
			5 => "EMAIL",
			6 => "IMAGE_ID",
			7 => "COORDINATES"
		),
		"SHOW_EMPTY_STORE" => "Y",
		"SHOW_GENERAL_STORE_INFORMATION" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"PROPERTY_CODE_MOD" => array(
			0 => "GUARANTEE"
		),
		"BUTTON_PAYMENTS_HREF" => "/payments/",
		"BUTTON_CREDIT_HREF" => "/credit/",
		"BUTTON_DELIVERY_HREF" => "/delivery/",
		"SHOW_DEACTIVATED" => "N",
		"COMPONENT_TEMPLATE" => ".default",
		"SET_LAST_MODIFIED" => "N",		
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "Y",
		"FILE_404" => "",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DETAIL_SET_VIEWED_IN_COMPONENT" => "N",
		"MIN_AMOUNT" => "10",
		"SEF_URL_TEMPLATES" => array(
			"sections" => "",
			"section" => "#SECTION_CODE#/",
			"element" => "#SECTION_CODE#/#ELEMENT_CODE#/",
			"compare" => "compare/",
			"smart_filter" => "#SECTION_CODE#/filter/#SMART_FILTER_PATH#/apply/"
		)
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>