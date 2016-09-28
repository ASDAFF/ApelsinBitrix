<?php
class APLS_CatalogItemDetailsAction {

	private $property = array();
	private $html = "";
	private $yesValue = array("true", "Y", "Да");
	private $noValue = array("false", "N", "Нет");

	const STOCK = "STOCK"; // внешний код иконки "Акция"

	// внешние коды созданных акций
	const AKTION_5_AND_1 = "AKTION_5_AND_1";

	private $allAction = [
		self::AKTION_5_AND_1
	];

	public function __construct(array $property) {
		$this->property = $property;
	}

	public function getAction() {
		if (CModule::IncludeModule('iblock')) {
			$this->getIssetAction();
			$this->get();
		}

	}

	private function getIssetAction() {
		if (isset($this->property[self::STOCK]["VALUE"]) && $this->property[self::STOCK]["VALUE"] != "" &&
			in_array($this->property[self::STOCK]["VALUE"], $this->yesValue)) {
			$this->html = $this->getTextAction();
		}
	}

	private function getTextAction() {
		$html = "";
		foreach ($this->allAction as $action) {
			if (isset($this->property[$action]["VALUE"]) && $this->property[$action]["VALUE"] != "" &&
				!(in_array($this->property[self::STOCK]["VALUE"], $this->noValue))) {
				$html = "<div class='catalog-detail-preview-text'>" . $this->property[$action]["VALUE"] . "</div>";
			}
		}
		return $html;
	}

	private function get() {
		echo $this->html;
	}

	private function getHtml() {
		return $this->html;
	}
}