<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * Получает данные по контактам магазинов одного региона
 *
 * @author Olga Rjabchikova
 * @copyright © 2010-2016, CompuProjec
 * @created 24.06.2016 13:28:24
 */
class DataContactsShop {
    
    private $idBlock;                   // id инфоблока (Контакты магазинов)
    private $codeSection;               // id раздела инфоблока (Контакты магазинов)
    private $dataTimeTableShop;
    private $dataContactsShop;
    private $displayDataContactsShop;
    private $allDataContactsShops;
    private $displayDataTimeTableShop;

    public function __construct($idBlock, $codeSection) {
        $this->idBlock = $idBlock;
        $this->codeSection = $codeSection;
        $this->dataContactsShop = [];
        $this->dataTimeTableShop = [];
        if (CModule::IncludeModule('iblock')) {
            $this->getDataContacts();
            $this->getDataTimeTable();
            $this->setContactsShop();
            $this->setTimeTableShop();
            $this->setAllDataContactsShops();
        }
    }

    public function getMainMapData() {
        $arFilter = array("IBLOCK_ID"=>$this->idBlock , "ID"=>$this->codeSection) ;
        $rsResult = CIBlockSection::GetList(array("SORT" => "ASC"), $arFilter  , false, $arSelect = array( "UF_APLS_CONTACTS_MAP"));
        while ($ar = $rsResult -> GetNext()) {
            $this->allDataContactsShops["MAIN_MAP_DATA"] = $ar['UF_APLS_CONTACTS_MAP'];
        }
    }
    
    /**
     * Возвращает ВСЕ ПОДГОТОВЛЕННЫЕ контактные данные по магазинам
     * @return type
     */
    public function getAllDataContactsShops() {
        return $this->allDataContactsShops;
    }

    /**
     * Получаем данные из инфоблока "Контакты магазина"
     * @param type $idBlock
     * @return type
     */
    private function getDataContacts() {
        $arSort= Array("PROPERTY_SEQUENCE"=>"ASC");
        $arFilter = Array("IBLOCK_ID"=>$this->idBlock, "ACTIVE"=>"Y", "SECTION_ID"=>$this->codeSection);
        $arSelect = Array(
            "NAME",
            "CODE",
            "PROPERTY_ADRESS",
            "PROPERTY_SID",
            "PROPERTY_EMAIL",
            "PROPERTY_PHONETEXT1",
            "PROPERTY_PHONETEXT2"
            );
        $res =  CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
        while($ob = $res->GetNextElement()) {
            $temp = $ob->GetFields();
            $this->dataContactsShop[$temp['CODE']] = $temp;
        }
    }
    
    /**
     * Получаем данные из инфоблока "Контакты магазина"
     * @param type $idBlock
     * @return type
     */
    private function getDataTimeTable() {
        $arSort= Array("PROPERTY_SEQUENCE"=>"ASC");
        $arFilter = Array("IBLOCK_ID" =>$this->idBlock, "ACTIVE"=>"Y", "IBLOCK_SECTION_ID" =>$this->codeSection);
        $arSelect = Array(
            "CODE",

            "PROPERTY_MONH_S",
            "PROPERTY_MONM_S",
            "PROPERTY_MONH_E",
            "PROPERTY_MONM_E",

            "PROPERTY_TUEH_S",
            "PROPERTY_TUEM_S",
            "PROPERTY_TUEH_E",
            "PROPERTY_TUEM_E",

            "PROPERTY_WEDH_S",
            "PROPERTY_WEDM_S",
            "PROPERTY_WEDH_E",
            "PROPERTY_WEDM_E",

            "PROPERTY_THUH_S",
            "PROPERTY_THUM_S",
            "PROPERTY_THUH_E",
            "PROPERTY_THUM_E",

            "PROPERTY_FRIH_S",
            "PROPERTY_FRIM_S",
            "PROPERTY_FRIH_E",
            "PROPERTY_FRIM_E",

            "PROPERTY_SATH_S",
            "PROPERTY_SATM_S",
            "PROPERTY_SATH_E",
            "PROPERTY_SATM_E",

            "PROPERTY_SUNH_S",
            "PROPERTY_SUNM_S",
            "PROPERTY_SUNH_E",
            "PROPERTY_SUNM_E"
            );
        $res =  CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
        while($ob = $res->GetNextElement()) {
            $temp = $ob->GetFields();
            $this->dataTimeTableShop[$temp['CODE']] = $temp;
        }

    }
    
    /**
     * Контакты
     * @param type $data
     * @return type
     */
    private function setContactsShop() {
        $this->displayDataContactsShop = [];
        $alias = array_keys($this->dataContactsShop);
        foreach ($alias as $shop) {
            $this->displayDataContactsShop[$shop]['name'] = $this->dataContactsShop[$shop]['NAME'];
            $this->displayDataContactsShop[$shop]['adress'] = $this->dataContactsShop[$shop]['PROPERTY_ADRESS_VALUE'];
            $this->displayDataContactsShop[$shop]['email'] = $this->dataContactsShop[$shop]['PROPERTY_EMAIL_VALUE'];
            $this->displayDataContactsShop[$shop]['phoneText1'] = $this->dataContactsShop[$shop]['PROPERTY_PHONETEXT1_VALUE'];
            $this->displayDataContactsShop[$shop]['phoneText2'] = $this->dataContactsShop[$shop]['PROPERTY_PHONETEXT2_VALUE'];
            $this->displayDataContactsShop[$shop]['maps'] = $this->dataContactsShop[$shop]['PROPERTY_SID_VALUE'];
        }
    }

    /**
     * Расписание
     * @param type $data
     * @return type
     */
    private function setTimeTableShop() {
        $times = [];
        $alias = array_keys($this->dataTimeTableShop);
        foreach ($alias as $shop) {
            $times[$shop]['monH_s'] = $this->dataTimeTableShop[$shop]['PROPERTY_MONH_S_VALUE'];
            $times[$shop]['monM_s'] = $this->dataTimeTableShop[$shop]['PROPERTY_MONM_S_VALUE'];
            $times[$shop]['monH_e'] = $this->dataTimeTableShop[$shop]['PROPERTY_MONH_E_VALUE'];
            $times[$shop]['monM_e'] = $this->dataTimeTableShop[$shop]['PROPERTY_MONM_E_VALUE'];

            $times[$shop]['tueH_s'] = $this->dataTimeTableShop[$shop]['PROPERTY_TUEH_S_VALUE'];
            $times[$shop]['tueM_s'] = $this->dataTimeTableShop[$shop]['PROPERTY_TUEM_S_VALUE'];
            $times[$shop]['tueH_e'] = $this->dataTimeTableShop[$shop]['PROPERTY_TUEH_E_VALUE'];
            $times[$shop]['tueM_e'] = $this->dataTimeTableShop[$shop]['PROPERTY_TUEM_E_VALUE'];

            $times[$shop]['wedH_s'] = $this->dataTimeTableShop[$shop]['PROPERTY_WEDH_S_VALUE'];
            $times[$shop]['wedM_s'] = $this->dataTimeTableShop[$shop]['PROPERTY_WEDM_S_VALUE'];
            $times[$shop]['wedH_e'] = $this->dataTimeTableShop[$shop]['PROPERTY_WEDH_E_VALUE'];
            $times[$shop]['wedM_e'] = $this->dataTimeTableShop[$shop]['PROPERTY_WEDM_E_VALUE'];

            $times[$shop]['thuH_s'] = $this->dataTimeTableShop[$shop]['PROPERTY_THUH_S_VALUE'];
            $times[$shop]['thuM_s'] = $this->dataTimeTableShop[$shop]['PROPERTY_THUM_S_VALUE'];
            $times[$shop]['thuH_e'] = $this->dataTimeTableShop[$shop]['PROPERTY_THUH_E_VALUE'];
            $times[$shop]['thuM_e'] = $this->dataTimeTableShop[$shop]['PROPERTY_THUM_E_VALUE'];

            $times[$shop]['friH_s'] = $this->dataTimeTableShop[$shop]['PROPERTY_FRIH_S_VALUE'];
            $times[$shop]['friM_s'] = $this->dataTimeTableShop[$shop]['PROPERTY_FRIM_S_VALUE'];
            $times[$shop]['friH_e'] = $this->dataTimeTableShop[$shop]['PROPERTY_FRIH_E_VALUE'];
            $times[$shop]['friM_e'] = $this->dataTimeTableShop[$shop]['PROPERTY_FRIM_E_VALUE'];

            $times[$shop]['satH_s'] = $this->dataTimeTableShop[$shop]['PROPERTY_SATH_S_VALUE'];
            $times[$shop]['satM_s'] = $this->dataTimeTableShop[$shop]['PROPERTY_SATM_S_VALUE'];
            $times[$shop]['satH_e'] = $this->dataTimeTableShop[$shop]['PROPERTY_SATH_E_VALUE'];
            $times[$shop]['satM_e'] = $this->dataTimeTableShop[$shop]['PROPERTY_SATM_E_VALUE'];

            $times[$shop]['sunH_s'] = $this->dataTimeTableShop[$shop]['PROPERTY_SUNH_S_VALUE'];
            $times[$shop]['sunM_s'] = $this->dataTimeTableShop[$shop]['PROPERTY_SUNM_S_VALUE'];
            $times[$shop]['sunH_e'] = $this->dataTimeTableShop[$shop]['PROPERTY_SUNH_E_VALUE'];
            $times[$shop]['sunM_e'] = $this->dataTimeTableShop[$shop]['PROPERTY_SUNM_E_VALUE'];
        }
        // Из базы приходят минуты размером 1 символ, поэтому:
        //  1) приводим их к размеру в 2 символа ( ->timeTableMinutesData ($minute))
        //  2) генерим строку типа: 9:00 - 20:00 ( ->timeTableDayData($data, $key))
        //  3) подготавливаем данные для визуализации ( ->timeTableData($data)) 
        $this->displayDataTimeTableShop = [];
        foreach ($alias as $shop) {
            $this->displayDataTimeTableShop[$shop] = $this->timeTableData($times[$shop]);
        }
    }

    /**
     * Все подготовленные контактные данные
     */
    private function setAllDataContactsShops() {
        $this->allDataContactsShops = [];
        $this->getMainMapData();
        $alias = array_keys($this->dataContactsShop);
        foreach ($alias as $shop) {
            foreach ($this->displayDataContactsShop[$shop] as $key => $value) {
                $this->allDataContactsShops[$shop][$key] = $value;
            }
            foreach ($this->displayDataTimeTableShop[$shop] as $keys => $values) {
                $this->allDataContactsShops[$shop]['table'][$keys] = $values;
            }
        }
    }

    /**
     * Подготавливает данные расписания для класса ContactsTimeTableUI
     * @param type $data - данные о расписании из базы
     * @return type
     */
    private function timeTableData($data) {
        $timeData = array();
        $timeData[0] = $this->timeTableDayData($data, 'mon');
        $timeData[1] = $this->timeTableDayData($data, 'tue');
        $timeData[2] = $this->timeTableDayData($data, 'wed');
        $timeData[3] = $this->timeTableDayData($data, 'thu');
        $timeData[4] = $this->timeTableDayData($data, 'fri');
        $timeData[5] = $this->timeTableDayData($data, 'sat');
        $timeData[6] = $this->timeTableDayData($data, 'sun');
        return $timeData;
    }
    
    /**
     * Собираем строку расписания за один день
     * @param array $data данные по объекту
     * @param string $key ключ дня
     * @return string стркоа расписания
     */
    private function timeTableDayData($data, $key) {
        $time = null;
        if (isset($data[$key.'H_s']) && $data[$key.'H_s'] != 0 && $data[$key.'H_s'] != null && $data[$key.'H_s'] != "" 
                && isset($data[$key.'H_e']) && $data[$key.'H_e'] != 0 && $data[$key.'H_e'] != null && $data[$key.'H_e'] != "") {
        $time = $data[$key.'H_s'].":".$this->timeTableMinutesData ($data[$key.'M_s'])." - ".$data[$key.'H_e'].":".$this->timeTableMinutesData($data[$key.'M_e']);
        } else {
            $time = "Выходной";
        }
        return $time;
    }
    
    /**
     * Эмулируем 2 символа минуты
     * @param type $minute
     * @return string
     */
    private function timeTableMinutesData ($minute) {
        if ($minute == null || $minute == "") {
            $minute = '00';
        } else if (strlen($minute) == 1) {
            $minute = '0'.$minute;
        }
        return $minute;
    }
}