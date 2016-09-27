<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

foreach($arResult["SECTIONS"] as $key => $arSection) {
	if(is_array($arSection["PICTURE"])) {
		$arFileTmp = CFile::ResizeImageGet(
			$arSection["PICTURE"],
			array("width" => "50", "height" => "50"),
			BX_RESIZE_IMAGE_PROPORTIONAL,
			true
		);

		$arResult["SECTIONS"][$key]["PICTURE_PREVIEW"] = array(
			"SRC" => $arFileTmp["src"],
			"WIDTH" => $arFileTmp["width"],
			"HEIGHT" => $arFileTmp["height"],
		);
	}
}?>