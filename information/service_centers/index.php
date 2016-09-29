<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Сервисные центры");
?>

<?
include_once '../../apls_lib/apls_lib.php';
includeSistemClasses("../../");
$APLS_ServiceCenters = new APLS_ServiceCenters("../../");
echo $APLS_ServiceCenters->getJS();
echo $APLS_ServiceCenters->getHtml();
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>