<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * Получает данные о регионах
 *
 * @author Olga Rjabchikova
 * @copyright © 2010-2016, CompuProjec
 * @created 30.06.2016 8:55:57
 */
class DataRegionsShop {

    private $idBlock;
    private $idParent;
    private $dataRegionsShop;
    
    public function __construct($idBlock,$idParent = null) {
        $this->idBlock = $idBlock;
        $this->idParent = $idParent;
        if (CModule::IncludeModule('iblock')) {
            $this->setDataRegionsShop($idBlock);
        }
    }

    private function setDataRegionsShop() {
        $arFilter = Array('IBLOCK_ID'=>$this->idBlock, 'GLOBAL_ACTIVE'=>'Y');
        if($this->idParent !== null) {
            $arFilter['SECTION_ID'] = $this->idParent;
        }
        $res = CIBlockSection::GetList(array("LEFT_MARGIN"=>"ASC"), $arFilter, true);
        $this->dataRegionsShop = [];
        while($regions = $res->GetNext()) {
            $this->dataRegionsShop[$regions['CODE']]['id'] = $regions['ID'];
            $this->dataRegionsShop[$regions['CODE']]['name'] = $regions['NAME'];
        }
    }
    
    public function getDataRegionsShop() {
        return $this->dataRegionsShop;
    }
}
