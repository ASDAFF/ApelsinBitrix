<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Отдел закупок");
?>

<?
include_once '../../apls_lib/apls_lib.php';
includeSistemClasses("../../");

ContactsMain::contactsPurchasingDepartment(17, 124);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>