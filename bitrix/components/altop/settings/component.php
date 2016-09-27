<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$moduleClass = "CElektroinstrument";
$moduleID = "altop.elektroinstrument";

if(!CModule::IncludeModule($moduleID))
	return;

if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["CHANGE_THEME"] == "Y" && check_bitrix_sessid()) {
	foreach($moduleClass::$arParametrsList as $blockCode => $arBlock){
		foreach($arBlock["OPTIONS"] as $optionCode => $arOption) {
			if($arOption["IN_SETTINGS_PANEL"] == "Y" && $_POST["THEME"] == "default") {
				$newVal = $arOption["DEFAULT"];
			} else {
				if(isset($_POST[$optionCode])) {
					if($optionCode == "COLOR_SCHEME_CUSTOM"){
						$_POST[$optionCode] = $moduleClass::CheckColor($_POST[$optionCode]);
					}
					$newVal = $_POST[$optionCode];
					if($arOption["TYPE"] == "multiselectbox") {
						if(!is_array($newVal))
							$newVal = array();
					}
				}
			}			
			$arTab["OPTIONS"][$optionCode] = $newVal;
		}
	}		
	COption::SetOptionString($moduleID, "OPTIONS", serialize((array)$arTab["OPTIONS"]), "", SITE_ID);

	if($moduleClass::IsCompositeEnabled()){
		$obCache = new CPHPCache();
		$obCache->CleanDir("", "html_pages");
		$moduleClass::EnableComposite();
	}

	BXClearCache(true, "/".SITE_ID."/altop/catalog.top/");
	BXClearCache(true, "/".SITE_ID."/bitrix/catalog.bigdata.products/");
	BXClearCache(true, "/".SITE_ID."/bitrix/catalog.element/");
	BXClearCache(true, "/".SITE_ID."/bitrix/catalog.section/");
	BXClearCache(true, "/".SITE_ID."/bitrix/catalog.set.constructor/");
	BXClearCache(true, "/".SITE_ID."/bitrix/news.list/");
}

global $USER;
$arResult = array();

$arFrontParametrs = $moduleClass::GetFrontParametrsValues(SITE_ID);
foreach($moduleClass::$arParametrsList as $blockCode => $arBlock){
	foreach($arBlock["OPTIONS"] as $optionCode => $arOption){
		$arResult[$optionCode] = $arOption;
		$arResult[$optionCode]["VALUE"] = $arFrontParametrs[$optionCode];
		//CURRENT for compatibility with old versions
		if($arResult[$optionCode]["LIST"]){
			foreach($arResult[$optionCode]["LIST"] as $variantCode => $variantTitle){
				if(!is_array($variantTitle)){
					$arResult[$optionCode]["LIST"][$variantCode] = array("TITLE" => $variantTitle);
				}
				if($arResult[$optionCode]["TYPE"] == "selectbox"){
					if($arResult[$optionCode]["VALUE"] == $variantCode){
						$arResult[$optionCode]["LIST"][$variantCode]["CURRENT"] = "Y";
					}
				} elseif($arResult[$optionCode]["TYPE"] == "multiselectbox"){
					if(in_array($variantCode, $arResult[$optionCode]["VALUE"])){
						$arResult[$optionCode]["LIST"][$variantCode]["CURRENT"] = "Y";
					}
				}
			}
		}
	}
}

if($arResult["COLOR_SCHEME"]["VALUE"] != "YELLOW") {	
	require_once $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$moduleID."/less/lessc.inc.php";	
	$less = new lessc;
	try {	
		if($arResult["COLOR_SCHEME"]["VALUE"] == "CUSTOM") {
			$baseColorCustom = str_replace("#", "", $arResult["COLOR_SCHEME_CUSTOM"]["VALUE"]);			
			$less->setVariables(array("bcolor" => (strlen($baseColorCustom) ? "#".$baseColorCustom : $arResult["COLOR_SCHEME"]["LIST"][$arResult["COLOR_SCHEME"]["DEFAULT"]]["COLOR"])));
		} else {
			$less->setVariables(array("bcolor" => $arResult["COLOR_SCHEME"]["LIST"][$arResult["COLOR_SCHEME"]["VALUE"]]["COLOR"]));
		}
		if(defined("SITE_TEMPLATE_PATH")) {
			$schemeDirPath = $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/schemes/".$arResult["COLOR_SCHEME"]["VALUE"]."/";
			if(!is_dir($schemeDirPath))
				mkdir($schemeDirPath, 0755, true);

			$inputFile = $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$moduleID."/less/colors.less";
			$outputFile = $schemeDirPath."colors.css";			

			$cache = $less->cachedCompile($inputFile);
			$newCache = file_get_contents($outputFile);								

			if(md5($newCache) != md5($cache["compiled"])) {
				$output = $less->compileFile($inputFile, $outputFile);
			} else {
				$output = $less->checkedCompile($inputFile, $outputFile);
			}			
		}
	} catch(exception $e) {
		echo "Fatal error: ".$e->getMessage();
		die();
	}	
	$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/schemes/".$arResult["COLOR_SCHEME"]["VALUE"]."/colors.css", true);
}
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/custom.css", true);

if(in_array("FALLING_SNOW", $arResult["GENERAL_SETTINGS"]["VALUE"])) {
	$moduleClass::StartFallingSnow(SITE_TEMPLATE_PATH);
}

if($arResult["SHOW_SETTINGS_PANEL"]["VALUE"] == "Y") {
	if($USER->IsAdmin()) {
		$this->IncludeComponentTemplate();
	}
}

return $arResult;?>