<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Адреса магазинов");
?>

<?
include_once '../../apls_lib/apls_lib.php';
includeSistemClasses("../../");

ContactsMain::contactsShop(16, 116, "", 660);
?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>