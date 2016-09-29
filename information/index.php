<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Информация");
?>
	<div class="NavigatorBigIcon">
		<a href="customers_memo/" class="NavigatorBigIconElementHref">
			<div class="NavigatorBigIconElement">
			<img class="main" src="img/icon/customers_memo.png">
			<img class="hover" src="img/icon/customers_memo_hover.png">
			<div class="title">Памятка покупателю</div>
			</div>
		</a>
		<a href="service_centers/" class="NavigatorBigIconElementHref">
			<div class="NavigatorBigIconElement">
				<img class="main" src="img/icon/service_centers.png">
				<img class="hover" src="img/icon/service_centers_hover.png">
				<div class="title">Сервисные центры</div>
			</div>
		</a>
		<a href="vacancies/" class="NavigatorBigIconElementHref">
			<div class="NavigatorBigIconElement">
				<img class="main" src="img/icon/vacancies.png">
				<img class="hover" src="img/icon/vacancies_hover.png">
				<div class="title">Вакансии</div>
			</div>
		</a>
		<a href="features_products/" class="NavigatorBigIconElementHref">
			<div class="NavigatorBigIconElement">
				<img class="main" src="img/icon/features_products.png">
				<img class="hover" src="img/icon/features_products_hover.png">
				<div class="title">Особенности товаров</div>
			</div>
		</a>
		<div class="clear"></div>
	</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>