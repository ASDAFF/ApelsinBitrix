<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * 
 */
class ContactsMain {

    private function __construct() {}
    
    public static function contactsShop($idBlock, $idParent, $mapsWidth, $mapsHeight){
		$regions = new DataRegionsShop($idBlock, $idParent);
		$dataRegions = $regions->getDataRegionsShop();
		$contactsUI = new DisplayPageContactsShop($idBlock, $dataRegions, $mapsHeight, $mapsWidth);
		$contactsUI->getDisplayPageContactsShop();
    }
    
    public static function contactsStudioFurniture($idBlock,$idParent,$mapsWidth,$mapsHeight){
		$regions = new DataRegionsShop($idBlock, $idParent);
		$dataRegions = $regions->getDataRegionsShop();
		$contactsUI = new DisplayPageContactsShop($idBlock, $dataRegions, $mapsHeight, $mapsWidth);
		$contactsUI->getDisplayPageContactsShop();
    }

    public static function contactsPointOfDelivery($idBlock, $idParent, $mapsWidth, $mapsHeight){
		$regions = new DataRegionsShop($idBlock, $idParent);
		$dataRegions = $regions->getDataRegionsShop();
		$contactsUI = new DisplayPageContactsShop($idBlock, $dataRegions, $mapsHeight, $mapsWidth);
		$contactsUI->getDisplayPageContactsShop();
    }

    public static function contactsPurchasingDepartment($idBlock, $idSection){
		$cotactsWokers = new DataContactsWokers($idBlock, $idSection);
		$data = $cotactsWokers->getDataContacts();
		$display = new DisplayContactsWokers($data);
		$display->getDisplayContactsWokers();
    }

    
}
