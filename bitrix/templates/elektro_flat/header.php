<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);?>
<!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
<head>
	<link rel="shortcut icon" type="image/x-icon" href="<?=SITE_TEMPLATE_PATH?>/favicon.ico" />	
	<link rel="apple-touch-icon" sizes="57x57" href="<?=SITE_TEMPLATE_PATH?>/images/apple-touch-icon-114.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?=SITE_TEMPLATE_PATH?>/images/apple-touch-icon-114.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="<?=SITE_TEMPLATE_PATH?>/images/apple-touch-icon-144.png" />
	<link rel="apple-touch-icon" sizes="144x144" href="<?=SITE_TEMPLATE_PATH?>/images/apple-touch-icon-144.png" />
	<script src="//api-maps.yandex.ru/2.1/?lang=ru-RU" type="text/javascript"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?$APPLICATION->ShowTitle()?></title>
	<?$APPLICATION->SetAdditionalCSS("https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css");	
	$APPLICATION->SetAdditionalCSS("https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,cyrillic-ext");
	$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/colors.css");	
	$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/anythingslider/slider.css");
	$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/custom-forms/custom-forms.css");
	$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/fancybox/jquery.fancybox-1.3.1.css");	
	$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/js/spectrum/spectrum.css");	
	CJSCore::Init(array("jquery"));	
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/jquery.cookie.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/jquery.scrollUp.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/moremenu.js");	
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/anythingslider/jquery.easing.1.2.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/anythingslider/jquery.anythingslider.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/custom-forms/jquery.custom-forms.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/fancybox/jquery.fancybox-1.3.1.pack.js");	
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/spectrum/spectrum.js");	
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/countUp.min.js");	
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/countdown/jquery.plugin.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/countdown/jquery.countdown.js");	
	$APPLICATION->AddHeadString("
		<script type='text/javascript'>
			$(function() {
				$.countdown.regionalOptions['ru'] = {
					labels: ['".GetMessage("COUNTDOWN_REGIONAL_LABELS_YEAR")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS_MONTH")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS_WEEK")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS_DAY")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS_HOUR")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS_MIN")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS_SEC")."'],
					labels1: ['".GetMessage("COUNTDOWN_REGIONAL_LABELS1_YEAR")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS1_MONTH")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS1_WEEK")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS1_DAY")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS1_HOUR")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS_MIN")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS_SEC")."'],
					labels2: ['".GetMessage("COUNTDOWN_REGIONAL_LABELS2_YEAR")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS2_MONTH")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS2_WEEK")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS2_DAY")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS2_HOUR")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS_MIN")."', '".GetMessage("COUNTDOWN_REGIONAL_LABELS_SEC")."'],
					compactLabels: ['".GetMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_YEAR")."', '".GetMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_MONTH")."', '".GetMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_WEEK")."', '".GetMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_DAY")."'],
					compactLabels1: ['".GetMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS1_YEAR")."', '".GetMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_MONTH")."', '".GetMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_WEEK")."', '".GetMessage("COUNTDOWN_REGIONAL_COMPACT_LABELS_DAY")."'],
					whichLabels: function(amount) {
						var units = amount % 10;
						var tens = Math.floor((amount % 100) / 10);
						return (amount == 1 ? 1 : (units >= 2 && units <= 4 && tens != 1 ? 2 : (units == 1 && tens != 1 ? 1 : 0)));
					},
					digits: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
					timeSeparator: ':',
					isRTL: false
				};
				$.countdown.setDefaults($.countdown.regionalOptions['ru']);
			});
		</script>
	", true);	
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/main.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/script.js");
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/APLS_ContentToColumns.js");
	$APPLICATION->ShowHead();?>
</head>
<body itemscope itemtype="http://schema.org/WebPage">
	<?global $arSetting;?>
	<?$arSetting = $APPLICATION->IncludeComponent("altop:settings", "", array(), false, array("HIDE_ICONS" => "Y"));?>	
	<div class="bx-panel<?=($arSetting['CART_LOCATION']['VALUE'] == 'TOP') ? ' clvt' : ''?>">
		<?$APPLICATION->ShowPanel();?>
	</div>	
	<div class="body<?=($arSetting['CATALOG_LOCATION']['VALUE'] == 'HEADER') ? ' clvh' : ''?><?=($arSetting['CART_LOCATION']['VALUE'] == 'TOP') ? ' clvt' : ''?><?=($arSetting['CART_LOCATION']['VALUE'] == 'RIGHT') ? ' clvr' : ''?><?=($arSetting['CART_LOCATION']['VALUE'] == 'LEFT') ? ' clvl' : ''?><?=($arSetting['SITE_BACKGROUND']['VALUE'] == 'DARK') ? ' sbg_dark' : ''?>">
		<div class="page-wrapper">
			<?if($arSetting["CATALOG_LOCATION"]["VALUE"] == "HEADER"):?>
				<div class="top-menu-header">					
					<div class="top-menu">							
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/top_menu.php"), false, array("HIDE_ICONS" => "Y"));?>
					</div>					
				</div>
			<?endif;?>
			<div class="center">
				<header>					
					<div class="header_1">
						<div class="logo">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/company_logo.php"), false);?>
						</div>
					</div>
					<div class="header_2">
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/header_search.php"), false, array("HIDE_ICONS" => "Y"));?>
					</div>
					<div class="header_3">
						<div class="schedule">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/schedule.php"), false);?>
						</div>
					</div>
					<div class="header_4">
						<div class="telephone">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/telephone.php"), false);?>
							<a class="btn_buy apuo callback_anch" href="javascript:void(0)" onclick="OpenCallbackPopup();"><span class="cont"><i class="fa fa-phone"></i><span class="text"><?=GetMessage("ALTOP_CALL_BACK")?></span></span></a>
							<?$APPLICATION->IncludeComponent("altop:callback", "",
								array(
									"EMAIL_TO" => "",
									"REQUIRED_FIELDS" => array("NAME", "TEL", "TIME")
								),
								false
							);?>
						</div>
					</div>
					<?if($arSetting["CATALOG_LOCATION"]["VALUE"] == "LEFT"):?>
						<div class="top-menu">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/top_menu.php"), false, array("HIDE_ICONS" => "Y"));?>
						</div>
					<?elseif($arSetting["CATALOG_LOCATION"]["VALUE"] == "HEADER"):?>
						<div class="top-catalog">							
							<?$APPLICATION->IncludeComponent("bitrix:menu", $arSetting["CATALOG_VIEW"]["VALUE"] == "FOUR_LEVELS" ? "tree" : "sections",
								array(
									"ROOT_MENU_TYPE" => "left",
									"MENU_CACHE_TYPE" => "A",
									"MENU_CACHE_TIME" => "86400",
									"MENU_CACHE_USE_GROUPS" => "Y",
									"MENU_CACHE_GET_VARS" => array(),
									"MAX_LEVEL" => "4",
									"CHILD_MENU_TYPE" => "left",
									"USE_EXT" => "Y",
									"DELAY" => "N",
									"ALLOW_MULTI_SELECT" => "N"
								),
								false
							);?>
						</div>
					<?endif;?>					
				</header>
				<div class="top_panel">
					<div class="panel_1">
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/sections.php"), false, array("HIDE_ICONS" => "Y"));?>
					</div>
					<div class="panel_2">
						<?$APPLICATION->IncludeComponent("bitrix:menu", "panel", 
							array(
								"ROOT_MENU_TYPE" => "top",
								"MENU_CACHE_TYPE" => "A",
								"MENU_CACHE_TIME" => "86400",
								"MENU_CACHE_USE_GROUPS" => "Y",
								"MENU_CACHE_GET_VARS" => array(),
								"MAX_LEVEL" => "3",
								"CHILD_MENU_TYPE" => "topchild",
								"USE_EXT" => "N",
								"ALLOW_MULTI_SELECT" => "N"
							),
							false
						);?>
					</div>
					<div class="panel_3">
						<ul class="contacts-vertical">
							<li>
								<a class="showcontacts" href="javascript:void(0)"><i class="fa fa-phone"></i></a>
							</li>
						</ul>
					</div>
					<div class="panel_4">
						<ul class="search-vertical">
							<li>
								<a class="showsearch" href="javascript:void(0)"><i class="fa fa-search"></i></a>
							</li>
						</ul>
					</div>
				</div>
				<div class="content-wrapper">
					<div class="content">
						<div class="left-column">
							<?if($APPLICATION->GetDirProperty("PERSONAL_SECTION")):?>
								<div class="h3"><?=GetMessage("PERSONAL_HEADER");?></div>
								<?$APPLICATION->IncludeComponent("altop:user", ".default", array(), false);?>
								<?$APPLICATION->IncludeComponent("bitrix:menu", "tree",
									array(
										"ROOT_MENU_TYPE" => "personal",
										"MENU_CACHE_TYPE" => "A",
										"MENU_CACHE_TIME" => "86400",
										"MENU_CACHE_USE_GROUPS" => "Y",
										"MENU_CACHE_GET_VARS" => array(),
										"MAX_LEVEL" => "1",
										"CHILD_MENU_TYPE" => "personal",
										"USE_EXT" => "Y",
										"DELAY" => "N",
										"ALLOW_MULTI_SELECT" => "N"
									),
									false
								);?>
							<?else:
								if($arSetting["CATALOG_LOCATION"]["VALUE"] == "LEFT"):?>
									<div class="h3"><?=GetMessage("BASE_HEADER");?></div>
									<?$APPLICATION->IncludeComponent("bitrix:menu", $arSetting["CATALOG_VIEW"]["VALUE"] == "FOUR_LEVELS" ? "tree" : "sections",
										array(
											"ROOT_MENU_TYPE" => "left",
											"MENU_CACHE_TYPE" => "A",
											"MENU_CACHE_TIME" => "86400",
											"MENU_CACHE_USE_GROUPS" => "Y",
											"MENU_CACHE_GET_VARS" => array(),
											"MAX_LEVEL" => "4",
											"CHILD_MENU_TYPE" => "left",
											"USE_EXT" => "Y",
											"DELAY" => "N",
											"ALLOW_MULTI_SELECT" => "N"
										),
										false
									);?>									
								<?endif;
							endif;
							if($arSetting["SMART_FILTER_LOCATION"]["VALUE"] == "VERTICAL"):
								$APPLICATION->ShowViewContent("filter_vertical");
							endif;
							if($arSetting["CATALOG_LOCATION"]["VALUE"] == "HEADER"):?>
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
									array(
										"AREA_FILE_SHOW" => "file",
										"PATH" => SITE_DIR."include/banner_left.php",
										"AREA_FILE_RECURSIVE" => "N",
										"EDIT_MODE" => "html",
									),
									false,
									array("HIDE_ICONS" => "Y")
								);?>													
								<?if($APPLICATION->GetCurPage(true)!= SITE_DIR."index.php") {?>
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
										array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/discount_left.php",
											"AREA_FILE_RECURSIVE" => "N",
											"EDIT_MODE" => "html",
										),
										false,
										array("HIDE_ICONS" => "Y")
									);?>
								<?}?>
							<?endif;?>
							<!--
							<ul class="new_leader_disc">
								<li>
									<a class="new" href="<?=SITE_DIR?>catalog/newproduct/">
										<span class="icon"><?=GetMessage("CR_TITLE_ICON_NEWPRODUCT")?></span>
										<span class="text"><?=GetMessage("CR_TITLE_NEWPRODUCT")?></span>
									</a>
								</li>
								<li>
									<a class="hit" href="<?=SITE_DIR?>catalog/saleleader/">
										<span class="icon"><?=GetMessage("CR_TITLE_ICON_SALELEADER")?></span>
										<span class="text"><?=GetMessage("CR_TITLE_SALELEADER")?></span>
									</a>
								</li>
								<li>
									<a class="discount" href="<?=SITE_DIR?>catalog/discount/">
										<span class="icon"><?=GetMessage("CR_TITLE_ICON_DISCOUNT")?></span>
										<span class="text"><?=GetMessage("CR_TITLE_DISCOUNT")?></span>
									</a>
								</li>
							</ul>
							-->
							<?if($arSetting["CATALOG_LOCATION"]["VALUE"] == "LEFT"):?>
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
									array(
										"AREA_FILE_SHOW" => "file",
										"PATH" => SITE_DIR."include/banner_left.php",
										"AREA_FILE_RECURSIVE" => "N",
										"EDIT_MODE" => "html",
									),
									false,
									array("HIDE_ICONS" => "Y")
								);?>													
								<?if($APPLICATION->GetCurPage(true)!= SITE_DIR."index.php" && 1==2) {?>
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
										array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/discount_left.php",
											"AREA_FILE_RECURSIVE" => "N",
											"EDIT_MODE" => "html",
										),
										false,
										array("HIDE_ICONS" => "Y")
									);?>
								<?}?>
							<?endif;?>
							<!--
							<div class="vendors">
								<div class="h3"><?=GetMessage("MANUFACTURERS");?></div>
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
									array(
										"AREA_FILE_SHOW" => "file",
										"PATH" => SITE_DIR."include/vendors_left.php",
										"AREA_FILE_RECURSIVE" => "N",
										"EDIT_MODE" => "html",
									),
									false,
									array("HIDE_ICONS" => "Y")
								);?>
							</div>
							-->
							<!--
							<div class="subscribe">
								<div class="h3"><?=GetMessage("SUBSCRIBE");?></div>
								<p><?=GetMessage("SUBSCRIBE_TEXT");?></p>
								<?$APPLICATION->IncludeComponent("bitrix:subscribe.form", "left", 
									array(
										"USE_PERSONALIZATION" => "Y",	
										"PAGE" => SITE_DIR."personal/subscribe/",
										"SHOW_HIDDEN" => "N",
										"CACHE_TYPE" => "A",
										"CACHE_TIME" => "86400",
										"CACHE_NOTES" => ""
									),
									false
								);?>
							</div>
							-->
							<!--
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
								array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => SITE_DIR."include/stati_left.php",
									"AREA_FILE_RECURSIVE" => "N",
									"EDIT_MODE" => "html",
								),
								false,
								array("HIDE_ICONS" => "Y")
							);?>
							-->
						</div>
						<div class="workarea">
							<?if($APPLICATION->GetCurPage(true)== SITE_DIR."index.php"):
								if(in_array("SLIDER", $arSetting["HOME_PAGE"]["VALUE"])):?>
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
										array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/slider.php",
											"AREA_FILE_RECURSIVE" => "N",
											"EDIT_MODE" => "html",
										),
										false,
										array("HIDE_ICONS" => "Y")
									);?>
								<?endif;
								if(in_array("ADVANTAGES", $arSetting["HOME_PAGE"]["VALUE"])):
									global $arAdvFilter;
									$arAdvFilter = array(
										"!PROPERTY_SHOW_HOME" => false
									);?>
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
										array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/advantages.php",
											"AREA_FILE_RECURSIVE" => "N",
											"EDIT_MODE" => "html",
										),
										false,
										array("HIDE_ICONS" => "Y")
									);?>
								<?endif;
								if(in_array("BANNERS", $arSetting["HOME_PAGE"]["VALUE"])):?>
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
										array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/banners_main.php",
											"AREA_FILE_RECURSIVE" => "N",
											"EDIT_MODE" => "html",
										),
										false,
										array("HIDE_ICONS" => "Y")
									);?>
								<?endif;
								if(in_array("NEWS", $arSetting["HOME_PAGE"]["VALUE"])):?>
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
										array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/news_home.php",
											"AREA_FILE_RECURSIVE" => "N",
											"EDIT_MODE" => "html",
										),
										false,
										array("HIDE_ICONS" => "Y")
									);?>
								<?endif;
								if(in_array("TABS", $arSetting["HOME_PAGE"]["VALUE"])):?>
									<div class="ndl_tabs">
										<div class="section">
											<ul class="tabs">
												<li class="new">
													<a href="javascript:void(0)"><span><?=GetMessage("CR_TITLE_NEWPRODUCT")?></span></a>
												</li>
												<li class="hit">
													<a href="javascript:void(0)"><span><?=GetMessage("CR_TITLE_SALELEADER")?></span></a>
												</li>
												<li class="discount">
													<a href="javascript:void(0)"><span><?=GetMessage("CR_TITLE_DISCOUNT")?></span></a>
												</li>
											</ul>
											<div class="new box">
												<div class="catalog-top">
													<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
														array(
															"AREA_FILE_SHOW" => "file",
															"PATH" => SITE_DIR."include/newproduct.php",
															"AREA_FILE_RECURSIVE" => "N",
															"EDIT_MODE" => "html",
														),
														false,
														array("HIDE_ICONS" => "Y")
													);?>
													<a class="all" href="<?=SITE_DIR?>catalog/newproduct/"><?=GetMessage("CR_TITLE_ALL_NEWPRODUCT");?></a>
												</div>
											</div>
											<div class="hit box">
												<div class="catalog-top">
													<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
														array(
															"AREA_FILE_SHOW" => "file",
															"PATH" => SITE_DIR."include/saleleader.php",
															"AREA_FILE_RECURSIVE" => "N",
															"EDIT_MODE" => "html",
														),
														false,
														array("HIDE_ICONS" => "Y")
													);?>
													<a class="all" href="<?=SITE_DIR?>catalog/saleleader/"><?=GetMessage("CR_TITLE_ALL_SALELEADER");?></a>
												</div>
											</div>
											<div class="discount box">
												<div class="catalog-top">
													<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
														array(
															"AREA_FILE_SHOW" => "file",
															"PATH" => SITE_DIR."include/discount.php",
															"AREA_FILE_RECURSIVE" => "N",
															"EDIT_MODE" => "html",
														),
														false,
														array("HIDE_ICONS" => "Y")
													);?>
													<a class="all" href="<?=SITE_DIR?>catalog/discount/"><?=GetMessage("CR_TITLE_ALL_DISCOUNT");?></a>
												</div>
											</div>
										</div>
									</div>
								<?endif;?>
								<div class="clr"></div>
							<?endif;?>

							<div class="body_text" style="<?=($APPLICATION->GetCurPage(true) == SITE_DIR.'index.php') ? 'padding:0px 15px;' : 'padding:0px;';?>">
								<?if($APPLICATION->GetCurPage(true)!= SITE_DIR."index.php"):?>
									<div class="breadcrumb-search">
										<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", ".default", 
											array(
												"START_FROM" => "0",
												"PATH" => "",
												"SITE_ID" => "-"
											),
											false,
											array("HIDE_ICONS" => "Y")
										);?>
										<!--
										<div class="podelitsya">											
											<script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script>
											<div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="small" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,gplus" data-yashareTheme="counter"></div>
										</div>
										-->
										<div class="clr"></div>
									</div>
									<h1><?=$APPLICATION->ShowTitle(false);?></h1>
								<?endif;?>
								<script type="text/javascript" src="http://jira.compuproject.com/s/ac00d99ae309ce302e93eaa22bfc31d8-T/-e0dbtr/72006/b6b48b2829824b869586ac216d119363/2.0.22/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector.js?locale=ru-RU&collectorId=563bf4ab"></script>
								<script type="text/javascript">
									var breadcrumb_path = "";
									var breadcrumb_size = $('.breadcrumb .breadcrumb__item').size();
									$('.breadcrumb .breadcrumb__item').each(function(i,elem) {
										if(i < breadcrumb_size - 1) {
											breadcrumb_path = breadcrumb_path + $(elem).text() + " | ";
										} else {
											breadcrumb_path = breadcrumb_path + $(elem).text();
										}
									});
									window.ATL_JQ_PAGE_PROPS =  {
										"triggerFunction": function(showCollectorDialog) {
											//Requires that jQuery is available!
											jQuery("#feedback-button").click(function(e) {
												e.preventDefault();
												showCollectorDialog();
											});
										},
										fieldValues : {
											summary : "",
											description : "",
											priority : '3',
											"customfield_10201" : "<?$APPLICATION->ShowTitle()?>",
											"customfield_10202" : breadcrumb_path,
											"customfield_10203" : "<?echo "http://".$_SERVER['SERVER_NAME'].$APPLICATION->GetCurUri("",false)?>"
										}};
								</script>
