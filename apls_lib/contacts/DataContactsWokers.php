<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * Получение данных контактов менеджеров
 *
 * @author Olga Rjabchikova
 * @copyright © 2010-2016, CompuProjec
 * @created 01.07.2016 10:39:55
 */
class DataContactsWokers {
    
    private $idBlock;                   // id инфоблока (Контакты магазинов)
    private $codeSection;               // id раздела инфоблока (Контакты магазинов)
    private $dataContacts;

    public function __construct($idBlock, $codeSection) {
        $this->idBlock = $idBlock;
        $this->codeSection = $codeSection;
        $this->dataContacts = [];
        if (CModule::IncludeModule('iblock')) {
            $this->setDataContacts();
        }
    }

    public function getDataContacts() {
        return $this->dataContacts;
    }

    private function setDataContacts() {
        $arSort= Array("NAME"=>"ASC");
        $arFilter = Array("IBLOCK_ID"=>$this->idBlock, "ACTIVE"=>"Y", "SECTION_ID"=>$this->codeSection);
        $arSelect = Array(
            "NAME",
            "ID",
            "PROPERTY_POST_WOKERS",
            "PROPERTY_TEXT_INFO",
            "PROPERTY_PHONETEXT1",
            "PROPERTY_ADDITIONAL1",
            "PROPERTY_PHONETEXT2",
            "PROPERTY_ADDITIONAL2",
            "PROPERTY_EMAIL"
            );
        $res =  CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
        while($ob = $res->GetNextElement()) {
            $temp = $ob->GetFields();
            $this->setData($temp);
        }
    }
    
    private function setData($temp) {
        $this->dataContacts[$temp['ID']]['fio'] = $temp['NAME'];
        $this->dataContacts[$temp['ID']]['post_wokers'] = $temp['PROPERTY_POST_WOKERS_VALUE'];
        $this->dataContacts[$temp['ID']]['text_info'] = $temp['PROPERTY_TEXT_INFO_VALUE'];
        $this->dataContacts[$temp['ID']]['phoneText1'] = $temp['PROPERTY_PHONETEXT1_VALUE'];
        if ($temp['PROPERTY_ADDITIONAL1_VALUE'] != null) {
            $this->dataContacts[$temp['ID']]['phoneText1'] = $temp['PROPERTY_PHONETEXT1_VALUE']." доб. ".$temp['PROPERTY_ADDITIONAL1_VALUE'];
        } 
        $this->dataContacts[$temp['ID']]['phoneText2'] = $temp['PROPERTY_PHONETEXT2_VALUE'];
        if ($temp['PROPERTY_ADDITIONAL2_VALUE'] != null) {
            $this->dataContacts[$temp['ID']]['phoneText2'] = $temp['PROPERTY_PHONETEXT2_VALUE']." доб. ".$temp['PROPERTY_ADDITIONAL2_VALUE'];
        } 
        $this->dataContacts[$temp['ID']]['email'] = $temp['PROPERTY_EMAIL_VALUE'];
    }

}
