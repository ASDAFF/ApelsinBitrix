<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Информация");
?>
	<div class="NavigatorBigIcon">
		<a href="customers_memo/" class="NavigatorBigIconElementHref">
			<div class="NavigatorBigIconElement">
			<img class="main" src="img/icon/customers_memo-01.svg">
			<img class="hover" src="img/icon/customers_memo_hover-01.svg">
			<div class="title">Памятка покупателю</div>
			</div>
		</a>
		<a href="service_centers/" class="NavigatorBigIconElementHref">
			<div class="NavigatorBigIconElement">
				<img class="main" src="img/icon/service_centers-01.svg">
				<img class="hover" src="img/icon/service_centers_hover-01.svg">
				<div class="title">Сервисные центры</div>
			</div>
		</a>
		<a href="vacancies/" class="NavigatorBigIconElementHref">
			<div class="NavigatorBigIconElement">
				<img class="main" src="img/icon/vacancies-01.svg">
				<img class="hover" src="img/icon/vacancies_hover-01.svg">
				<div class="title">Вакансии</div>
			</div>
		</a>
		<a href="features_products/" class="NavigatorBigIconElementHref">
			<div class="NavigatorBigIconElement">
				<img class="main" src="img/icon/features_products-01.svg">
				<img class="hover" src="img/icon/features_products_hover-01.svg">
				<div class="title">Особенности товаров</div>
			</div>
		</a>
		<div class="clear"></div>
	</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>