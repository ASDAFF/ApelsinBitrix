<?php
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "DoItAfterUpdateElement");
AddEventHandler("iblock", "OnAfterIBlockElementAdd", "DoItAfterUpdateElement");

define("SHOP_IBLOC_ID_FOR_THIS_SCRIPT", "13");
define("ACTIVE_TRIGGER_PROPERTY_XML_ID", "26e05687-c602-4c36-8b63-debb1b4e0250");
define("ACTIVE_TRIGGER_PROPERTY_VALUE_DEACTIVE", "Нет");
define("ACTIVE_TRIGGER_PROPERTY_VALUE_ACTIVE", "Да");

function DoItAfterUpdateElement (&$arFields) {
	if ($arFields["IBLOCK_ID"] == SHOP_IBLOC_ID_FOR_THIS_SCRIPT && $arFields["RESULT"]) {
		$rs = CIBlockElement::GetList(array(), array("ID"=>$arFields["ID"]), false, array("nTopCount"=>1));
		if($obRes = $rs->GetNextElement())
		{
			$arRes = $obRes->GetFields();
            $el = new CIBlockElement;
			$arRes["PROPERTIES"] = $obRes->GetProperties();
			foreach ($arRes["PROPERTIES"] as $key => $val) {
				if($val["XML_ID"] === ACTIVE_TRIGGER_PROPERTY_XML_ID) {
					if($val["VALUE"] === ACTIVE_TRIGGER_PROPERTY_VALUE_DEACTIVE && $arRes["ACTIVE"] === "Y") {
						$arLoadProductArray = Array("ACTIVE" => "N");
						$el->Update($arFields["ID"], $arLoadProductArray);
					} elseif ($val["VALUE"] === ACTIVE_TRIGGER_PROPERTY_VALUE_ACTIVE && $arRes["ACTIVE"] === "N") {
						$arLoadProductArray = Array("ACTIVE" => "Y");
						$el->Update($arFields["ID"], $arLoadProductArray);
					}
				}
			}
		}
	}
}