<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
function includeSistemClasses($path = './') {
	include_once $path.'apls_lib/main/inspections/APLS_TextInspections.php';
	include_once $path.'apls_lib/main/parser/AplsXmlParser.php';
	include_once $path.'apls_lib/main/textgenerator/ID_GENERATOR.php';
	include_once $path.'apls_lib/main/textgenerator/APLS_TextGenerator.php';

	include_once $path.'apls_lib/contacts/ContactsMain.php';
	include_once $path.'apls_lib/contacts/DataRegionsShop.php';
	include_once $path.'apls_lib/contacts/DisplayPageContactsShop.php';
	include_once $path.'apls_lib/contacts/DataContactsShop.php';
	include_once $path.'apls_lib/contacts/ContactsTimeTableUI.php';
	include_once $path.'apls_lib/contacts/DisplayContactsShop.php';

	include_once $path.'apls_lib/contacts/DataContactsWokers.php';
	include_once $path.'apls_lib/contacts/DisplayContactsWokers.php';

	include_once $path.'apls_lib/service_centers/APLS_ServiceCenters.php';

	include_once $path.'apls_lib/vacancies/APLS_Data_Vacancies.php';
	include_once $path.'apls_lib/vacancies/APLS_View_Vacancies.php';
	include_once $path.'apls_lib/vacancies/APLS_Controller_Vacancies.php';

}
?>
