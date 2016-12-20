<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);?>
							</div>
						</div>
						<?if($APPLICATION->GetCurPage(true)== SITE_DIR."index.php"):?>
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
								array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => SITE_DIR."include/vendors_bottom.php",
									"AREA_FILE_RECURSIVE" => "N",
									"EDIT_MODE" => "html",
								),
								false,
								array("HIDE_ICONS" => "Y")
							);?>
						<?endif;?>
						<?if(!CSite::InDir('/reviews/')):?>
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
								array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => SITE_DIR."include/stati_bottom.php",
									"AREA_FILE_RECURSIVE" => "N",
									"EDIT_MODE" => "html",
								),
								false,
								array("HIDE_ICONS" => "Y")
							);?>
						<?endif;?>
					</div>
					<?$APPLICATION->IncludeComponent("bitrix:subscribe.form", "bottom", 
						array(
							"USE_PERSONALIZATION" => "Y",	
							"PAGE" => "/personal/subscribe/",
							"SHOW_HIDDEN" => "N",
							"CACHE_TYPE" => "A",
							"CACHE_TIME" => "86400",
							"CACHE_NOTES" => ""
						),
						false
					);?>
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/viewed_products.php"), false);?>
				</div>
				<footer>
					<div class="footer_menu_soc_pay">
						<div class="footer_menu">
							<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", 
								array(
									"ROOT_MENU_TYPE" => "footer1",
									"MENU_CACHE_TYPE" => "A",
									"MENU_CACHE_TIME" => "86400",
									"MENU_CACHE_USE_GROUPS" => "Y",
									"MENU_CACHE_GET_VARS" => array(),
									"MAX_LEVEL" => "1",
									"CHILD_MENU_TYPE" => "",
									"USE_EXT" => "N",
									"ALLOW_MULTI_SELECT" => "N"
								),
								false
							);?>
							<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", 
								array(
									"ROOT_MENU_TYPE" => "footer2",
									"MENU_CACHE_TYPE" => "A",
									"MENU_CACHE_TIME" => "86400",
									"MENU_CACHE_USE_GROUPS" => "Y",
									"MENU_CACHE_GET_VARS" => array(),
									"MAX_LEVEL" => "1",
									"CHILD_MENU_TYPE" => "",
									"USE_EXT" => "N",
									"ALLOW_MULTI_SELECT" => "N"
								),
								false
							);?>
							<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", 
								array(
									"ROOT_MENU_TYPE" => "footer3",
									"MENU_CACHE_TYPE" => "A",
									"MENU_CACHE_TIME" => "86400",
									"MENU_CACHE_USE_GROUPS" => "Y",
									"MENU_CACHE_GET_VARS" => array(),
									"MAX_LEVEL" => "1",
									"CHILD_MENU_TYPE" => "",
									"USE_EXT" => "N",
									"ALLOW_MULTI_SELECT" => "N"
								),
								false
							);?>
							<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", 
								array(
									"ROOT_MENU_TYPE" => "footer4",
									"MENU_CACHE_TYPE" => "A",
									"MENU_CACHE_TIME" => "86400",
									"MENU_CACHE_USE_GROUPS" => "Y",
									"MENU_CACHE_GET_VARS" => array(),
									"MAX_LEVEL" => "1",
									"CHILD_MENU_TYPE" => "",
									"USE_EXT" => "N",
									"ALLOW_MULTI_SELECT" => "N"
								),
								false
							);?>
						</div>
						<div class="footer_soc_pay">							
							<div class="footer_soc">
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/join_us.php"), false, array("HIDE_ICONS" => "Y"));?>
							</div>
							<div class="footer_pay">
								<?global $arPayIcFilter;
								$arPayIcFilter = array();?>
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/payments_icons.php"), false, array("HIDE_ICONS" => "Y"));?>
							</div>
						</div>
					</div>
					<div class="footer_left">
						<div class="copyright">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/copyright.php"), false);?>
						</div>
					</div>
					<div class="footer_center">
						<div class="footer-links">
							<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", 
								array(
									"ROOT_MENU_TYPE" => "bottom",
									"MENU_CACHE_TYPE" => "A",
									"MENU_CACHE_TIME" => "86400",
									"MENU_CACHE_USE_GROUPS" => "Y",
									"MENU_CACHE_GET_VARS" => array(),
									"MAX_LEVEL" => "1",
									"CHILD_MENU_TYPE" => "",
									"USE_EXT" => "N",
									"ALLOW_MULTI_SELECT" => "N"
								),
								false
							);?>
						</div>
					</div>
					<div class="footer_right">
						<div class="counters">
							<div class="counter_1">
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/counter_1.php"), false);?>
							</div>
							<div class="counter_2">
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/counter_2.php"), false);?>
							</div>
						</div>
						<div class="footer-design">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/developer.php"), false);?>
						</div>
					</div>
					<div class="foot_panel_all">
						<div class="foot_panel">
							<div class="foot_panel_1">
								<?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "login",
									array(
										"REGISTER_URL" => SITE_DIR."personal/profile/",
										"FORGOT_PASSWORD_URL" => SITE_DIR."personal/profile/",
										"PROFILE_URL" => SITE_DIR."personal/profile/",
										"SHOW_ERRORS" => "N" 
									 ),
									 false,
									 array("HIDE_ICONS" => "Y")
								);?>
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
									array(
										"AREA_FILE_SHOW" => "file", 
										"PATH" => SITE_DIR."include/footer_compare.php"
									),
									false,
									array("HIDE_ICONS" => "Y")
								);?>
								<?$APPLICATION->IncludeComponent("altop:sale.basket.delay", ".default", 
									array(
										"PATH_TO_DELAY" => SITE_DIR."personal/cart/?delay=Y",
									),
									false,
									array("HIDE_ICONS" => "Y")
								);?>
							</div>
							<div class="foot_panel_2">
								<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", ".default", 
									array(
										"PATH_TO_BASKET" => SITE_DIR."personal/cart/",
										"PATH_TO_ORDER" => SITE_DIR."personal/order/make/",
										"HIDE_ON_BASKET_PAGES" => "N",
										"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
									),
									false,
									array("HIDE_ICONS" => "Y")
								);?>
								<a href="#" id="feedback-button" class="jira-button" title="Сообщить об ошибке"><i class="fa fa-jira"></i></a>
							</div>
						</div>
					</div>
				</footer>
				<div class="clr"></div>
			</div>
		</div>
	</div>
</body>
</html>