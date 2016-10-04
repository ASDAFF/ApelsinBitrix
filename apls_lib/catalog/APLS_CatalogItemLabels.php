<?php

class APLS_CatalogItemLabels
{
	private $html = "";
	private $properties = array();
	private $offers = array();
	private $totalOffers = array();
	private $curentDiscount = array();
	private $discountText = array();
	private $yesValue = array("true", "Y", "Да");

	const NEW_PRODUCT = "NEWPRODUCT";
	const SALE_LEADER = "SALELEADER";
	const STOCK = "PROMO";
	const DISCOUNT = "DISCOUNT";

	private $label = [
		self::NEW_PRODUCT => [
			"mesage" => "New",
			"css" => "new"
		],
		self::SALE_LEADER => [
			"mesage" => "Хит",
			"css" => "hit"
		],
		self::STOCK => [
			"mesage" => "Акция",
			"css" => "stock"
		],
		self::DISCOUNT => [
			"mesage" => "%",
			"css" => "discount"
		]
	];

	public function __construct($arElement)
	{
		if (isset($arElement["CURRENT_DISCOUNT"])) {
			$this->curentDiscount = $arElement["CURRENT_DISCOUNT"];
		}
		if (isset($arElement["PROPERTIES"])) {
			$this->properties = $arElement["PROPERTIES"];
		}
		if (isset($arElement["OFFERS"])) {
			$this->offers = $arElement["OFFERS"];
		}
		if (isset($arElement["TOTAL_OFFERS"])) {
			$this->totalOffers = $arElement["TOTAL_OFFERS"];
		}
		$this->genDiscountText();
		$this->generationHtml();
	}

	private function genDiscountText() {
		$this->discountText = "";
		if($this->curentDiscount != null && !empty($this->curentDiscount)) {
			$type = $this->curentDiscount["VALUE_TYPE"];
			$value = $this->curentDiscount["VALUE"];
			switch ($type) {
				case "F":
					if($value < 10000) {
						$this->discountText = "-".$value." &#8381;";
					} else {
						$this->discountText = "%";
					}
					break;
				case "P":
					$this->discountText = "- ".$value." %";
					break;
				default:
					$this->discountText = "%";
			}
			$this->label[self::DISCOUNT]["mesage"] = $this->discountText;
		}
	}

	private function generationHtml()
	{
		$this->html = $this->genLabelYN(self::NEW_PRODUCT);
		$this->html .= $this->genLabelYN(self::SALE_LEADER);
		$this->html .= $this->genLabelYN(self::STOCK,true);
		$this->html .= $this->genLabelOffesr(self::DISCOUNT);
	}

	private function genLabelYN($key, $anyValue = false)
	{
		if (
			isset($this->properties[$key]["VALUE"]) && $this->properties[$key]["VALUE"] != "" && (
				in_array($this->properties[$key]["VALUE"], $this->yesValue) ||
				$anyValue
			)
		) {
			return "<span class='" . $this->label[$key]["css"] . "'>" . $this->label[$key]["mesage"] . "</span>";
		}
		return "";
	}

	private function genLabelOffesr($key)
	{
		$out = "";
		if (!empty($this->offers)) {
			if ($this->totalOffers["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"] > 0) {
				$out .= "<span class='" . $this->label[$key]["css"] . "'>-" . $this->totalOffers["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"] . "%</span>";
			} else {
				$out .= $this->genLabelYN($key, true);
			}
		} else {
			$out .= $this->genLabelYN($key, true);
		}
		return $out;
	}

	public function get()
	{
		echo $this->getHTML();
	}

	public function getHTML()
	{
		return $this->html;
	}
}