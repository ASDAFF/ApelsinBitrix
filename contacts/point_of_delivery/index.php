<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Пункты выдачи");
?>

<?
include_once '../../apls_lib/apls_lib.php';
includeSistemClasses("../../");

ContactsMain::contactsPointOfDelivery(16,119,"",660);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>