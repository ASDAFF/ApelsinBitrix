<?php
class APLS_CatalogItemDetailsAction {

	private $property = array();
	private $html = "";
	private $yesValue = array("true", "Y", "Да");
	private $noValue = array("false", "N", "Нет");

	const STOCK = "PROMO"; // внешний код иконки "Акция"
	const PROMO_TEXT = "PROMO_TEXT"; // внешние код текста акций

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
		if (isset($this->property["PROMO_TEXT"]["VALUE"]) && $this->property["PROMO_TEXT"]["VALUE"] != "" &&
			!(in_array($this->property[self::STOCK]["VALUE"], $this->noValue))) {
			$html = "<div class='catalog-detail-preview-text'>" . $this->property["PROMO_TEXT"]["VALUE"] . "</div>";
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