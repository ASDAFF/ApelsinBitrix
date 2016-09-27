<?php

class APLS_Controller_Vacancies {
	private $html = "";

	public function __construct($PathToRoot = "../") {
		$APLS_Data_Vacancies = new APLS_Data_Vacancies($PathToRoot);
		$APLS_View_Vacancies = new APLS_View_Vacancies($APLS_Data_Vacancies->getData());
		$this->html = $APLS_View_Vacancies->getHTML();
	}

	public function get(){
		echo $this->html;
	}

	public function getHTML(){
		return $this->html;
	}

}