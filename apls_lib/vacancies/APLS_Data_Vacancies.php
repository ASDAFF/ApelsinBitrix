<?php
class APLS_Data_Vacancies {

	private $file;
	private $xmlwebi;
	private $xmlData;
	private $vacancies;

	public function __construct($PathToRoot = "../") {
		$this->file = $PathToRoot.'/apls_resources/vacancies/Job.xml';
		$this->xmlwebi = new AplsXmlParser();
		$this->xmlData = $this->xmlwebi->xmlwebi($this->file);
		$this->GetDataFromXML();
	}

	private function GetDataFromXML() {
		$this->vacancies = array();
		foreach ($this->xmlData['BodyJobFile'][0]['#']['Item'] as $item) {
			if($item['#']['InGlobalSite'][0]['#'] == "Да") {
                $adress = $item['#']['Unit'][0]['#'];
                $fullAdress = DataVacanses::getAdres($adress);
				$job['adress'] = $fullAdress;
//                $job['adress'] = $item['#']['Unit'][0]['#'];
                $name = $item['#']['Author'][0]['#'];
                $fullName = DataVacanses::getName($name);
                $job['contactUser'] = $fullName;
//				$job['contactUser'] = $item['#']['Author'][0]['#'];
				$job['job'] = $item['#']['Job'][0]['#'];
				$job['contactInfo'] = $item['#']['Telefon'][0]['#'];
				if($item['#']['ExtensionPhone'][0]['#'] !== "") {
					$job['contactInfo']." / ".$item['#']['ExtensionPhone'][0]['#'];
				}
				$this->vacancies[$job['job']][] = $job;
			}
		}
	}

	public function getData() {
		return $this->vacancies;
	}
}

/**
 * Class DataVacanses Изменения в размещаемой ин-фы по вакансиям
 */
class DataVacanses {

    static $adress = [
        "Бутырки"=>"г. Рязань, ул. 3 Бутырки, д.1В",
        "Верхняя"=>"г. Рязань, ул. Верхняя, д. 50",
        "Дмитров"=>"г. Дмитров, Ревякинский переулок, д. 2",
        "Есенина"=>"г. Рязань, ул. Есенина, д. 13",
        "Зубковой"=>"г. Рязань, ул. Зубковой, д. 27Б",
        "Коломна 1"=>"г. Коломна, пр. Озерский, д. 1",
        "Луховицы"=>"г. Луховицы, ул. Пушкина, д. 170B",
        "Новаторов"=>"г. Рязань, ул. Новаторов, д. 2 к.7",
        "Новоселов"=>"г. Рязань, ул. Новоселов, д. 12",
        "Окружная (Ангар)"=>"г. Рязань, ул. Окружная дорога, 185 км (Ангар)",
        "Островского"=>"г. Рязань, ул. Островского, д. 109/2",
        "Соколовка"=>"г. Рязань, пос. Соколовка, ул. Связи, 10Б",
        "ТЦ на Окружной"=>"г. Рязань, ул. Окружная дорога, 185 км",
        "Фирсова"=>"г. Рязань, ул. Фирсова, д. 23Б",
        "Черновицкая"=>"г. Рязань, ул. Черновицкая, д. 5",
        "Чкалова"=>"г. Рязань, ул. Чкалова, д. 70Б",
        "Шабулина"=>"г. Рязань, Проезд Шабулина, д. 24Б",
    ];

    /** Замена названия "Точки" на ее адрес
     * @param $data - название "Точки"
     * @return string - ее адрес
     */
    public static function getAdres($data) {
        if (array_key_exists($data, self::$adress)) {
            $fullAdress = self::$adress[$data];
        } else {
            $fullAdress = $data;
        }
        return $fullAdress;
    }

    /** Получить имя контакта
     * @param $fullName - имя-фамилия
     * @return string - только ИМЯ
     */
    public static function getName($fullName) {
        $name = substr($fullName, strrpos($fullName, ' ') +1);
        return $name;
    }
}