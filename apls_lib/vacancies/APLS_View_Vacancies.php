<?php

class APLS_View_Vacancies {
	private $vacanciesData;
	private $UI;

	public function __construct(array $vacanciesData) {
		$this->vacanciesData = $vacanciesData;
		$this->getVacancies();
	}

	private function getVacancies() {
		$this->UI = "<div id='VacanciesValidBlockArchive' class='VacanciesValidBlockArchive'>";
		foreach (array_keys($this->vacanciesData) as  $key) {
			$this->UI .= $this->getVacanciesBlock($this->vacanciesData, $key);
		}
		$this->UI .= "</div>";
		$this->UI .= "<div id='VacanciesValidBlockColumn_01' class='VacanciesValidBlockColumn_01'></div>";
		$this->UI .= "<div id='VacanciesValidBlockColumn_02' class='VacanciesValidBlockColumn_02'></div>";
		$this->UI .= "<div class='clear'></div>";

		$this->UI .= "<script type=\"text/javascript\">
			$(document).ready(function() {
				ContentToColumns('#VacanciesValidBlockArchive',['#VacanciesValidBlockColumn_01','#VacanciesValidBlockColumn_02']);
			});
			</script>";
	}

	private function getVacanciesBlock($vacancies,$key) {
		$html = "";
		$html .= "<div class='VacanciesValidBlock'>";
			$html .= "<div class='VacanciesValidBlockTitle'>";
				$html .= "<div class='VacanciesValidBlockTitleText'>".$key."</div>";
			$html .= "</div>";
			foreach ($vacancies[$key] as $vacancy) {
				$html .= $this->getVacanciesString($vacancy);
			}
		$html .= "</div>";
		return $html;
	}

	private function getVacanciesString($vacancy) {
		$html = "";
		$html .= "<div class='VacanciesValidString'>";
		$html .= "<div class='validVacancyAdress'>".$vacancy['adress']."</div>";
//		$html .= "<div class='validVacancyContactUser'><span class='validVacancyContactUserText'>".$vacancy['contactUser']."</span></div>";
//		$html .= "<div class='validVacancyContactInfo'><span class='validVacancyContactInfoText'>".$vacancy['contactInfo']."</span></div>";

		$html .= "<div class='validVacancyContactInfo'>
			<span class='validVacancyContactUserText'>".$vacancy['contactUser']."</span><br>
			<span class='validVacancyContactInfoText'>".$vacancy['contactInfo']."</span>
			</div>";
		$html .= "<div class='clear'></div>";
		$html .= "</div>";
		return $html;
	}

	public function get(){
		echo $this->UI;
	}

	public function getHTML(){
		return $this->UI;
	}
}