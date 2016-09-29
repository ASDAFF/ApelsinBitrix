<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Вакансии");
?>

<p class="TextP">АПЕЛЬСИН – активная быстрорастущая компания, поэтому мы постоянно нуждаемся в сотрудниках.
Нам требуются инициативные, трудолюбивые и ответственные люди.</p>
<p class="TextP">Мы предлагаем обучение и стабильную заработную плату.</p>
<p class="TextP">Если Вас интересует достойная оплата за достойный труд – МЫ ЖДЕМ ВАС!!!</p>
<p class="TextP">Для получения более подробной информации об открытых предложениях звоните 8(4912)240-220,8(4912)502-020, 
либо по телефонам, указанным напротив конкретной вакансии.</p>

<a class="content_button btn_buy apuo send_rezume" href="mailto:info@apelsin.ru">
<span class="cont">
<i class="fa fa-file"></i>
<span class="text">Отправить резюме</span>
</span>
</a>

<?
include_once '../../apls_lib/apls_lib.php';
includeSistemClasses("../../");
$APLS_Controller_Vacancies = new APLS_Controller_Vacancies("../../");
$APLS_Controller_Vacancies->get();
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>