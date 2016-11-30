<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $arSetting;

$strMainID = $this->GetEditAreaId($arResult["ID"]);
$arItemIDs = array(
	"ID" => $strMainID,
	"PICT" => $strMainID."_picture",
	"PRICE" => $strMainID."_price",
	"BUY" => $strMainID."_buy",
	"DELAY" => $strMainID."_delay",
	"ARTICLE" => $strMainID."_article",
	"PROPERTIES" => $strMainID."_properties",
	"CONSTRUCTOR" => $strMainID."_constructor",
	"STORE" => $strMainID."_store",
	"PROP_DIV" => $strMainID."_skudiv",
	"PROP" => $strMainID."_prop_",
	"SELECT_PROP_DIV" => $strMainID."_propdiv",
	"SELECT_PROP" => $strMainID."_select_prop_",
	"BTN_BUY" => $strMainID."_btn_buy"
);
$strObName = "ob".preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
$templateData["JS_OBJ"] = $strObName;

/***JS***/?>
<script type="text/javascript">
	BX.ready(function() {			
		<?/***OFFERS_LIST_PROPS***/
		if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"]) && $arSetting["OFFERS_VIEW"]["VALUE"] == "LIST"):
			foreach($arResult["OFFERS"] as $key_off => $arOffer):?>
				props = BX.findChildren(BX("catalog-offer-item-<?=$arOffer['ID']?>"), {className: "catalog-item-prop"}, true);
				if(!!props && 0 < props.length) {
					for(i = 0; i < props.length; i++) {
						if(!BX.hasClass(props[i], "empty")) {
							BX("catalog-item-props-mob-<?=$arOffer['ID']?>").appendChild(BX.create(
								"DIV",
								{
									props: {
										className: "catalog-item-prop"
									},
									html: props[i].innerHTML
								}
							));
						}
					}
				}
			<?endforeach;
		endif;
		
		/***DETAIL_CONSTRUCTOR***/
		if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"])):
			if($arSetting["OFFERS_VIEW"]["VALUE"] != "LIST"):
				foreach($arResult["OFFERS"] as $key => $arOffer):?>
					BX("<?=$arItemIDs['CONSTRUCTOR']?>").appendChild(BX.style(BX("set-constructor-from-<?=$arOffer['ID']?>"), "display", ""));
				<?endforeach;
			endif;
		else:?>
			BX("<?=$arItemIDs['CONSTRUCTOR']?>").appendChild(BX.style(BX("set-constructor-from"), "display", ""));
		<?endif;?>		
		
		/***ACCESSORIES***/
		BX("accessories-to").appendChild(BX.style(BX("accessories-from"), "display", ""));

		/***REVIEWS***/
		BX("catalog-reviews-to").appendChild(BX.style(BX("catalog-reviews-from"), "display", ""));

		/***STORES***/
		<?if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"])):
			if($arSetting["OFFERS_VIEW"]["VALUE"] != "LIST"):
				foreach($arResult["OFFERS"] as $key => $arOffer):?>
					BX("<?=$arItemIDs['STORE']?>").appendChild(BX.style(BX("catalog-detail-stores-from-<?=$arOffer['ID']?>"), "display", ""));
				<?endforeach;
			endif;
		else:?>
			BX("<?=$arItemIDs['STORE']?>").appendChild(BX.style(BX("catalog-detail-stores-from"), "display", ""));
		<?endif;?>
	});
	
	//<![CDATA[
	$(function() {		
		/***FANCYBOX***/
		$(function() {
			$("div.catalog-detail-pictures a").fancybox({
				"transitionIn": "elastic",
				"transitionOut": "elastic",
				"speedIn": 600,
				"speedOut": 200,
				"overlayShow": false,
				"cyclic" : true,
				"padding": 20,
				"titlePosition": "over",
				"onComplete": function() {
					$("#fancybox-title").css({"top":"100%", "bottom":"auto"});
				} 
			});
		});
	});
	//]]>
</script>

<?$sticker = "";
$timeBuy = "";
if(array_key_exists("PROPERTIES", $arResult) && is_array($arResult["PROPERTIES"])):
	$catalogItemLabels = new APLS_CatalogItemLabels($arResult);
	$sticker .= $catalogItemLabels->getHTML();
//	/***NEW***/
//	if(array_key_exists("NEWPRODUCT", $arResult["PROPERTIES"]) && !$arResult["PROPERTIES"]["NEWPRODUCT"]["VALUE"] == false):
//		$sticker .= "<span class='new'>".GetMessage("CATALOG_ELEMENT_NEWPRODUCT")."</span>";
//	endif;
//	/***HIT***/
//	if(array_key_exists("SALELEADER", $arResult["PROPERTIES"]) && !$arResult["PROPERTIES"]["SALELEADER"]["VALUE"] == false):
//		$sticker .= "<span class='hit'>".GetMessage("CATALOG_ELEMENT_SALELEADER")."</span>";
//	endif;
//	/***DISCOUNT***/
//	if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"])):
//		if($arSetting["OFFERS_VIEW"]["VALUE"] == "LIST"):
//			if($arResult["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"] > 0):
//				$sticker .= "<span class='discount'>-".$arResult["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"]."%</span>";
//			else:
//				if(array_key_exists("DISCOUNT", $arResult["PROPERTIES"]) && !$arResult["PROPERTIES"]["DISCOUNT"]["VALUE"] == false):
//					$sticker .= "<span class='discount'>%</span>";
//				endif;
//			endif;
//		endif;
//	else:
//		if($arResult["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"] > 0):
//			$sticker .= "<span class='discount'>-".$arResult["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"]."%</span>";
//		else:
//			if(array_key_exists("DISCOUNT", $arResult["PROPERTIES"]) && !$arResult["PROPERTIES"]["DISCOUNT"]["VALUE"] == false):
//				$sticker .= "<span class='discount'>%</span>";
//			endif;
//		endif;
//	endif;
	/***TIME_BUY***/
	if(array_key_exists("TIME_BUY", $arResult["PROPERTIES"]) && !$arResult["PROPERTIES"]["TIME_BUY"]["VALUE"] == false):
		if(!empty($arResult["CURRENT_DISCOUNT"]["ACTIVE_TO"])):
			if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"])):
				$timeBuy = "<span class='time_buy_figure'></span><span class='time_buy_text'>".GetMessage("CATALOG_ELEMENT_TIME_BUY")."</span>";
			else:
				if($arResult["CAN_BUY"]):
					$timeBuy = "<span class='time_buy_figure'></span><span class='time_buy_text'>".GetMessage("CATALOG_ELEMENT_TIME_BUY")."</span>";
				endif;
			endif;
		endif;
	endif;
endif;

/***CATALOG_DETAIL***/?>
<div id="<?=$arItemIDs['ID']?>" class="catalog-detail-element" itemscope itemtype="http://schema.org/Product">
	<meta content="<?=$arResult['NAME']?>" itemprop="name" />
	<div class="catalog-detail">
		<div class="column first">
			<div class="catalog-detail-pictures">
				<?/***OFFERS_DETAIL_PICTURE***/?>
				<div class="catalog-detail-picture" id="<?=$arItemIDs['PICT']?>">
					<?/***OFFERS_PICTURE***/
					if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"]) && $arSetting["OFFERS_VIEW"]["VALUE"] != "LIST"):
						foreach($arResult["OFFERS"] as $key => $arOffer):?>							
							<div id="detail_picture_<?=$arOffer['ID']?>" class="detail_picture <?=$arResult['ID']?> hidden">
								<meta content="<?=is_array($arOffer['DETAIL_IMG']) ? $arOffer['DETAIL_PICTURE']['SRC'] : $arResult['DETAIL_PICTURE']['SRC']?>" itemprop="image" />
								<a rel="" class="catalog-detail-images" id="catalog-detail-images-<?=$arOffer['ID']?>" href="<?=is_array($arOffer['DETAIL_IMG']) ? $arOffer['DETAIL_PICTURE']['SRC'] : $arResult['DETAIL_PICTURE']['SRC']?>">
									<?if(is_array($arOffer["DETAIL_IMG"])):?>
										<img src="<?=$arOffer['DETAIL_IMG']['SRC']?>" width="<?=$arOffer['DETAIL_IMG']['WIDTH']?>" height="<?=$arOffer['DETAIL_IMG']['HEIGHT']?>" alt="<?=(isset($arOffer['NAME']) && !empty($arOffer['NAME']) ? $arOffer['NAME'] : $arResult['NAME']);?>" />
									<?else:?>
										<img src="<?=$arResult['DETAIL_IMG']['SRC']?>" width="<?=$arResult['DETAIL_IMG']['WIDTH']?>" height="<?=$arResult['DETAIL_IMG']['HEIGHT']?>" alt="<?=(isset($arOffer['NAME']) && !empty($arOffer['NAME']) ? $arOffer['NAME'] : $arResult['NAME']);?>" />
									<?endif;?>
									<div class="time_buy_sticker">
										<?=$timeBuy?>
									</div>
									<div class="sticker">
										<?=$sticker;
										if($arOffer["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"] > 0):?>
											<span class="discount">-<?=$arOffer["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"]?>%</span>
										<?else:
											if(array_key_exists("DISCOUNT", $arResult["PROPERTIES"]) && !$arResult["PROPERTIES"]["DISCOUNT"]["VALUE"] == false):?>	
												<span class="discount">%</span>
											<?endif;
										endif;?>
									</div>
									<?if(!empty($arResult["PROPERTIES"]["MANUFACTURER"]["PREVIEW_IMG"]["SRC"])):?>
										<img class="manufacturer" src="<?=$arResult['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['SRC']?>" width="<?=$arResult['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['WIDTH']?>" height="<?=$arResult['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arResult['PROPERTIES']['MANUFACTURER']['NAME']?>" />
									<?endif;?>
								</a>
							</div>
						<?endforeach;
					/***DETAIL_PICTURE***/
					else:?>	
						<div class="detail_picture">
							<meta content="<?=is_array($arResult['DETAIL_IMG']) ? $arResult['DETAIL_PICTURE']['SRC'] : SITE_TEMPLATE_PATH.'/images/no-photo.jpg'?>" itemprop="image" />
							<?if(is_array($arResult["DETAIL_IMG"])):?>
								<a rel="lightbox" class="catalog-detail-images" href="<?=$arResult['DETAIL_PICTURE']['SRC']?>"> 
									<img src="<?=$arResult['DETAIL_IMG']['SRC']?>" width="<?=$arResult['DETAIL_IMG']['WIDTH']?>" height="<?=$arResult['DETAIL_IMG']['HEIGHT']?>" alt="<?=$arResult['NAME']?>" />
							<?else:?>
								<div class="catalog-detail-images">
									<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="150" height="150" alt="<?=$arResult['NAME']?>" />
							<?endif;?>
							<div class="time_buy_sticker">
								<?=$timeBuy?>
							</div>
							<div class="sticker">
								<?=$sticker?>
							</div>
							<?if(!empty($arResult["PROPERTIES"]["MANUFACTURER"]["PREVIEW_IMG"]["SRC"])):?>
								<img class="manufacturer" src="<?=$arResult['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['SRC']?>" width="<?=$arResult['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['WIDTH']?>" height="<?=$arResult['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arResult['PROPERTIES']['MANUFACTURER']['NAME']?>" />
							<?endif;?>
							<?=is_array($arResult["DETAIL_IMG"]) ? "</a>" : "</div>";?>							
						</div>					
					<?endif;?>
				</div>
				<?/***DETAIL_VIDEO_MORE_PHOTO***/
				if(!empty($arResult["PROPERTIES"]["VIDEO"]) || count($arResult["MORE_PHOTO"])>0):?>
					<div class="clr"></div>
					<div class="more_photo">
						<ul>
							<?if(!empty($arResult["PROPERTIES"]["VIDEO"]["VALUE"])):?>
								<li class="catalog-detail-video" style="<?=($arParams['DISPLAY_MORE_PHOTO_WIDTH'] ? 'width:'.$arParams['DISPLAY_MORE_PHOTO_WIDTH'].'px;' : '').($arParams['DISPLAY_MORE_PHOTO_HEIGHT'] ? 'height:'.$arParams['DISPLAY_MORE_PHOTO_HEIGHT'].'px;' : '');?>">
									<a rel="lightbox" class="catalog-detail-images" href="#video">
										<i class="fa fa-play-circle-o"></i>
										<span><?=GetMessage("CATALOG_ELEMENT_VIDEO")?></span>
									</a>
									<div id="video" style="overflow:hidden;">
										<?=$arResult["PROPERTIES"]["VIDEO"]["~VALUE"]["TEXT"];?>
									</div>
								</li>
							<?endif;
							if(count($arResult["MORE_PHOTO"]) > 0):
								foreach($arResult["MORE_PHOTO"] as $PHOTO):?>
									<li style="<?=($arParams['DISPLAY_MORE_PHOTO_WIDTH'] ? 'width:'.$arParams['DISPLAY_MORE_PHOTO_WIDTH'].'px;' : '').($arParams['DISPLAY_MORE_PHOTO_HEIGHT'] ? 'height:'.$arParams['DISPLAY_MORE_PHOTO_HEIGHT'].'px;' : '');?>">
										<a rel="lightbox" class="catalog-detail-images" href="<?=$PHOTO['SRC']?>">
											<img src="<?=$PHOTO['PREVIEW']['SRC']?>" width="<?=$PHOTO['PREVIEW']['WIDTH']?>" height="<?=$PHOTO['PREVIEW']['HEIGHT']?>" alt="<?=$arResult['NAME']?>" />
										</a>
									</li>
								<?endforeach;
							endif;?>
						</ul>
					</div>
				<?endif?>
			</div>
		</div>
		<div class="column second">			
			<div class="catalog-detail">
				<div class="column three">
					<div class="article_rating">
						<?/***OFFERS_DETAIL_ARTICLE***/?>
						<div class="catalog-detail-article" id="<?=$arItemIDs['ARTICLE']?>">
							<?/***OFFERS_ARTICLE***/
							if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"]) && $arSetting["OFFERS_VIEW"]["VALUE"] != "LIST"):
								foreach($arResult["OFFERS"] as $key => $arOffer):?>
									<div id="article_<?=$arOffer['ID']?>" class="article <?=$arResult['ID']?> hidden">
										<?=GetMessage("CATALOG_ELEMENT_ARTNUMBER")?><?=!empty($arOffer["PROPERTIES"]["ARTNUMBER"]["VALUE"]) ? $arOffer["PROPERTIES"]["ARTNUMBER"]["VALUE"] : "-";?>
									</div>
								<?endforeach;
							/***DETAIL_ARTICLE***/
							else:?>
								<div class="article">
									<?=GetMessage("CATALOG_ELEMENT_ARTNUMBER")?><?=!empty($arResult["PROPERTIES"]["ARTNUMBER"]["VALUE"]) ? $arResult["PROPERTIES"]["ARTNUMBER"]["VALUE"] : "-";?>
								</div>
							<?endif;?>
						</div>
						<?/***DETAIL_RATING***/?>
						<div class="rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
							<?$frame = $this->createFrame("vote")->begin("");?>
								<?$APPLICATION->IncludeComponent("bitrix:iblock.vote", "ajax",
									Array(
										"DISPLAY_AS_RATING" => "vote_avg",
										"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
										"IBLOCK_ID" => $arParams["IBLOCK_ID"],
										"ELEMENT_ID" => $arResult["ID"],
										"ELEMENT_CODE" => "",
										"MAX_VOTE" => "5",
										"VOTE_NAMES" => array("1","2","3","4","5"),
										"SET_STATUS_404" => "N",
										"CACHE_TYPE" => $arParams["CACHE_TYPE"],
										"CACHE_TIME" => $arParams["CACHE_TIME"],
										"CACHE_NOTES" => "",
										"READ_ONLY" => "N"
									),
									false,
									array("HIDE_ICONS" => "Y")
								);?>
							<?$frame->end();
							if($arResult["PROPERTIES"]["vote_count"]["VALUE"]):?>
								<meta content="<?=round($arResult['PROPERTIES']['vote_sum']['VALUE']/$arResult['PROPERTIES']['vote_count']['VALUE'], 2);?>" itemprop="ratingValue" />
								<meta content="<?=$arResult['PROPERTIES']['vote_count']['VALUE']?>" itemprop="ratingCount" />
							<?else:?>
								<meta content="0" itemprop="ratingValue" />
								<meta content="0" itemprop="ratingCount" />
							<?endif;?>					
						</div>				
					</div>
					<?/***DETAIL_PREVIEW_TEXT***/
					if(!empty($arResult["PREVIEW_TEXT"])):?>				
						<div class="catalog-detail-preview-text" itemprop="description">
							<?=$arResult["PREVIEW_TEXT"]?>
						</div>
					<?endif;
					/***DETAIL_GIFT***/					
					if(!empty($arResult["PROPERTIES"]["GIFT"]["FULL_VALUE"])):?>
						<div class="catalog-detail-gift">
							<div class="h3"><?=$arResult["PROPERTIES"]["GIFT"]["NAME"]?></div>
							<?foreach($arResult["PROPERTIES"]["GIFT"]["FULL_VALUE"] as $key => $arGift):?>							
								<div class="gift-item">
									<div class="gift-image-cont">
										<div class="gift-image">
											<div class="gift-image-col">
												<?if(!empty($arGift["PREVIEW_PICTURE"]["SRC"])):?>
													<img src="<?=$arGift['PREVIEW_PICTURE']['SRC']?>" width="<?=$arGift['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$arGift['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$arGift['NAME']?>" />
												<?else:?>
													<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="70" height="70" alt="<?=$arGift['NAME']?>" />
												<?endif;?>
											</div>
										</div>
									</div>
									<div class="gift-text"><?=$arGift["NAME"]?></div>
								</div>
							<?endforeach;?>
						</div>
					<?endif;
					/***DETAIL_ADVANTAGES***/
					if(in_array("ADVANTAGES", $arSetting["CATALOG_DETAIL"]["VALUE"]) && !empty($arResult["ADVANTAGES"])):
						global $arAdvFilter;
						$arAdvFilter = array(
							"ID" => $arResult["ADVANTAGES"]
						);?>
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "",
							Array(
								"AREA_FILE_SHOW" => "file",
								"PATH" => SITE_DIR."include/advantages.php",
								"AREA_FILE_RECURSIVE" => "N",
								"EDIT_MODE" => "html",
							),
							false,
							Array("HIDE_ICONS" => "Y")
						);?>
					<?endif;?>
					<?
					/*** Выводим наш блок с реквизитами товаров ***/
					include_once $_SERVER["DOCUMENT_ROOT"].'/apls_lib/catalog/APLS_CatalogItemDetailsPropertiesBlock.php';
					$APLS_CIDPB = new APLS_CatalogItemDetailsPropertiesBlock($arResult["PROPERTIES"]);
					$APLS_CIDPB->get();
					include_once $_SERVER["DOCUMENT_ROOT"].'/apls_lib/catalog/APLS_CatalogItemDetailsAction.php';
					$APLS_Action = new APLS_CatalogItemDetailsAction($arResult["PROPERTIES"]);
					$APLS_Action->getAction();
					?>
				</div>
				<div class="column four">
					<div class="price_buy_detail" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
						<?/***OFFERS_DETAIL_PRICE***/?>
						<div class="catalog-detail-price" id="<?=$arItemIDs['PRICE'];?>">
							<?/***OFFERS_PRICE***/
							if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"])):
								if($arSetting["OFFERS_VIEW"]["VALUE"] != "LIST"):
									foreach($arResult["OFFERS"] as $key => $arOffer):?>
										<div id="detail_price_<?=$arOffer['ID']?>" class="detail_price <?=$arResult['ID']?> hidden">
											<?foreach($arOffer["PRICES"] as $code => $arPrice):
												if($arPrice["MIN_PRICE"] == "Y"):
													if($arPrice["CAN_ACCESS"]):
														
														$price = CCurrencyLang::GetCurrencyFormat($arPrice["CURRENCY"], "ru");
														if(empty($price["THOUSANDS_SEP"])):
															$price["THOUSANDS_SEP"] = " ";
														endif;														
														if($price["HIDE_ZERO"] == "Y"):															
															if(round($arPrice["DISCOUNT_VALUE"], $price["DECIMALS"]) == round($arPrice["DISCOUNT_VALUE"], 0)):
																$price["DECIMALS"] = 0;
															endif;
														endif;
														$currency = str_replace("# ", " ", $price["FORMAT_STRING"]);

														if($arPrice["DISCOUNT_VALUE"] <= 0):
															$arResult["OFFERS"][$key]["ASK_PRICE"] = 1;?>
															<span class="catalog-detail-item-no-price">
																<?=GetMessage("CATALOG_ELEMENT_NO_PRICE")?>
																<?=(!empty($arOffer["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arOffer["CATALOG_MEASURE_NAME"] : "";?>
															</span>
														<?else:
															if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
																<span class="catalog-detail-item-price-old">
																	<?=$arPrice["PRINT_VALUE"];?>
																</span>
																<span class="catalog-detail-item-price-percent">
																	<?=GetMessage('CATALOG_ELEMENT_SKIDKA')." ".$arPrice["PRINT_DISCOUNT_DIFF"];?>
																</span>
															<?endif;?>
															<span class="catalog-detail-item-price">
																<?=number_format($arPrice["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
																<span class="unit">
																	<?=$currency?>
																	<?=(!empty($arOffer["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arOffer["CATALOG_MEASURE_NAME"] : "";?>
																</span>
															</span>															
															<?if($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
																<span class="catalog-detail-item-price-reference">
																	<?=CCurrencyLang::CurrencyFormat($arPrice["DISCOUNT_VALUE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arPrice["CURRENCY"], true);?>
																</span>
															<?endif;
														endif;?>
														<meta itemprop="price" content="<?=$arPrice['DISCOUNT_VALUE']?>" />
														<meta itemprop="priceCurrency" content="<?=$arPrice['CURRENCY']?>" />
													<?endif;
												endif;
											endforeach;
											/***OFFERS_AVAILABILITY***/?>
											<div class="available">
												<?if($arOffer["CAN_BUY"]):?>
													<meta content="InStock" itemprop="availability" />
													<div class="avl">
														<i class="fa fa-check-circle"></i>
														<span>
															<?=GetMessage("CATALOG_ELEMENT_AVAILABLE");
															if($arOffer["CATALOG_QUANTITY_TRACE"] == "Y"):
																if(in_array("PRODUCT_QUANTITY", $arSetting["GENERAL_SETTINGS"]["VALUE"])):
																	echo " ".$arOffer["CATALOG_QUANTITY"];
																endif;
															endif;?>
														</span>
													</div>
												<?elseif(!$arOffer["CAN_BUY"]):?>
													<meta content="OutOfStock" itemprop="availability" />
													<div class="not_avl">
														<i class="fa fa-times-circle"></i>
														<span><?=GetMessage("CATALOG_ELEMENT_NOT_AVAILABLE")?></span>
													</div>
												<?endif;?>
											</div>
										</div>
									<?endforeach;
								/***OFFERS_LIST_PRICE***/
								elseif($arSetting["OFFERS_VIEW"]["VALUE"] == "LIST"):?>
									<div class="detail_price">
										<?$price = CCurrencyLang::GetCurrencyFormat($arResult["TOTAL_OFFERS"]["MIN_PRICE"]["CURRENCY"], "ru");
										if(empty($price["THOUSANDS_SEP"])):
											$price["THOUSANDS_SEP"] = " ";
										endif;										
										if($price["HIDE_ZERO"] == "Y"):											
											if(round($arResult["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_VALUE"], $price["DECIMALS"]) == round($arResult["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_VALUE"], 0)):
												$price["DECIMALS"] = 0;
											endif;
										endif;
										$currency = str_replace("# ", " ", $price["FORMAT_STRING"]);
										
										if($arResult["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_VALUE"] <= 0):?>
											<span class="catalog-detail-item-no-price">
												<?=GetMessage("CATALOG_ELEMENT_NO_PRICE")?>
												<?=(!empty($arResult["TOTAL_OFFERS"]["MIN_PRICE"]["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arResult["TOTAL_OFFERS"]["MIN_PRICE"]["CATALOG_MEASURE_NAME"] : "";?>
											</span>									
										<?else:
											if($arResult["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_VALUE"] < $arResult["TOTAL_OFFERS"]["MIN_PRICE"]["VALUE"]):?>
												<span class="catalog-detail-item-price-old">
													<?=$arResult["TOTAL_OFFERS"]["MIN_PRICE"]["PRINT_VALUE"];?>
												</span>
												<span class="catalog-detail-item-price-percent">
													<?=GetMessage('CATALOG_ELEMENT_SKIDKA')." ".$arResult["TOTAL_OFFERS"]["MIN_PRICE"]["PRINT_DISCOUNT_DIFF"];?>
												</span>
											<?endif;?>
											<span class="catalog-detail-item-price">
												<?=($arResult["TOTAL_OFFERS"]["FROM"] == "Y") ? "<span class='from'>".GetMessage("CATALOG_ELEMENT_FROM")."</span>" : "";?>							
												<?=number_format($arResult["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
												<span class="unit">
													<?=$currency?>
													<?=(!empty($arResult["TOTAL_OFFERS"]["MIN_PRICE"]["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arResult["TOTAL_OFFERS"]["MIN_PRICE"]["CATALOG_MEASURE_NAME"] : "";?>
												</span>
											</span>											
											<?if($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
												<span class="catalog-detail-item-price-reference">
													<?=CCurrencyLang::CurrencyFormat($arResult["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_VALUE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arResult["TOTAL_OFFERS"]["MIN_PRICE"]["CURRENCY"], true);?>
												</span>
											<?endif;
										endif;?>
										<meta itemprop="price" content="<?=$arResult['TOTAL_OFFERS']['MIN_PRICE']['DISCOUNT_VALUE']?>" />
										<meta itemprop="priceCurrency" content="<?=$arResult['TOTAL_OFFERS']['MIN_PRICE']['CURRENCY']?>" />
										<?/***OFFERS_LIST_AVAILABILITY***/?>
										<div class="available">
											<?if($arResult["TOTAL_OFFERS"]["QUANTITY"] > 0 || $arResult["CATALOG_QUANTITY_TRACE"] == "N"):?>
												<meta content="InStock" itemprop="availability" />
												<div class="avl">
													<i class="fa fa-check-circle"></i>
													<span>
														<?=GetMessage("CATALOG_ELEMENT_AVAILABLE");
														if($arResult["CATALOG_QUANTITY_TRACE"] == "Y"):
															if(in_array("PRODUCT_QUANTITY", $arSetting["GENERAL_SETTINGS"]["VALUE"])):
																echo " ".$arResult["TOTAL_OFFERS"]["QUANTITY"];
															endif;
														endif;?>
													</span>
												</div>
											<?else:?>
												<meta content="OutOfStock" itemprop="availability" />
												<div class="not_avl">
													<i class="fa fa-times-circle"></i>
													<span><?=GetMessage("CATALOG_ELEMENT_NOT_AVAILABLE")?></span>
												</div>
											<?endif;?>											
										</div>								
									</div>						
								<?endif;						
								/***OFFERS_TIME_BUY_QUANTITY***/								
								if(array_key_exists("TIME_BUY", $arResult["PROPERTIES"]) && !$arResult["PROPERTIES"]["TIME_BUY"]["VALUE"] == false):
									if(!empty($arResult["CURRENT_DISCOUNT"]["ACTIVE_TO"])):								
										if($arResult["TOTAL_OFFERS"]["MIN_PRICE"]["CATALOG_QUANTITY_TRACE"] == "Y"):
											$startQnt = $arResult["PROPERTIES"]["TIME_BUY_FROM"]["VALUE"];
											$currQnt = $arResult["PROPERTIES"]["TIME_BUY_TO"]["VALUE"] ? $arResult["PROPERTIES"]["TIME_BUY_TO"]["VALUE"] : $arResult["TOTAL_OFFERS"]["QUANTITY"];
											$currQntPercent = round($currQnt * 100 / $startQnt);
										else:
											$currQntPercent = 100;
										endif;?>
										
										<div class="progress_bar_block">
											<span class="progress_bar_title"><?=GetMessage("CATALOG_ELEMENT_QUANTITY_PERCENT")?></span>
											<div class="progress_bar_cont">
												<div class="progress_bar_bg">
													<div class="progress_bar_line" style="width:<?=$currQntPercent?>%;"></div>
												</div>
											</div>
											<span class="progress_bar_percent"><?=$currQntPercent?>%</span>
										</div>
									<?endif;
								endif;
							/***DETAIL_PRICE***/
							else:
								foreach($arResult["PRICES"] as $code => $arPrice):
									if($arPrice["MIN_PRICE"] == "Y"):
										if($arPrice["CAN_ACCESS"]):									
													
											$price = CCurrencyLang::GetCurrencyFormat($arPrice["CURRENCY"], "ru");
											if(empty($price["THOUSANDS_SEP"])):
												$price["THOUSANDS_SEP"] = " ";
											endif;											
											if($price["HIDE_ZERO"] == "Y"):												
												if(round($arPrice["DISCOUNT_VALUE"], $price["DECIMALS"]) == round($arPrice["DISCOUNT_VALUE"], 0)):
													$price["DECIMALS"] = 0;
												endif;
											endif;
											$currency = str_replace("# ", " ", $price["FORMAT_STRING"]);

											if($arPrice["DISCOUNT_VALUE"] <= 0):
												$arResult["ASK_PRICE"] = 1;?>										
												<span class="catalog-detail-item-no-price">
													<?=GetMessage("CATALOG_ELEMENT_NO_PRICE")?>
													<?=(!empty($arResult["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arResult["CATALOG_MEASURE_NAME"] : "";?>
												</span>																	
											<?else:
												if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
													<span class="catalog-detail-item-price-old">											
														<?=$arPrice["PRINT_VALUE"];?>
													</span>
													<span class="catalog-detail-item-price-percent">
														<?=GetMessage('CATALOG_ELEMENT_SKIDKA')." ".$arPrice["PRINT_DISCOUNT_DIFF"];?>
													</span>
												<?endif;?>
												<span class="catalog-detail-item-price">
													<?=number_format($arPrice["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
													<span class="unit">
														<?=$currency?>
														<?=(!empty($arResult["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arResult["CATALOG_MEASURE_NAME"] : "";?>
													</span>
												</span>
												<?if($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
													<span class="catalog-detail-item-price-reference">
														<?=CCurrencyLang::CurrencyFormat($arPrice["DISCOUNT_VALUE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arPrice["CURRENCY"], true);?>
													</span>
												<?endif;
											endif;?>
											<meta itemprop="price" content="<?=$arPrice['DISCOUNT_VALUE']?>" />
											<meta itemprop="priceCurrency" content="<?=$arPrice['CURRENCY']?>" />
										<?endif;
									endif;
								endforeach;
								/***DETAIL_AVAILABILITY***/?>
								<div class="available">
									<?if($arResult["CAN_BUY"]):?>
										<meta content="InStock" itemprop="availability" />
										<div class="avl">
											<i class="fa fa-check-circle"></i>
											<span>
												<?=GetMessage("CATALOG_ELEMENT_AVAILABLE");
												if($arResult["CATALOG_QUANTITY_TRACE"] == "Y"):
													if(in_array("PRODUCT_QUANTITY", $arSetting["GENERAL_SETTINGS"]["VALUE"])):
														echo " ".$arResult["CATALOG_QUANTITY"];
													endif;
												endif;?>
											</span>
										</div>
									<?elseif(!$arResult["CAN_BUY"]):?>
										<meta content="OutOfStock" itemprop="availability" />
										<div class="not_avl">
											<i class="fa fa-times-circle"></i>
											<span><?=GetMessage("CATALOG_ELEMENT_NOT_AVAILABLE")?></span>
										</div>
									<?endif;?>
								</div>						
								<?/***DETAIL_TIME_BUY_QUANTITY***/
								if(array_key_exists("TIME_BUY", $arResult["PROPERTIES"]) && !$arResult["PROPERTIES"]["TIME_BUY"]["VALUE"] == false):
									if(!empty($arResult["CURRENT_DISCOUNT"]["ACTIVE_TO"])):
										if($arResult["CAN_BUY"]):
											if($arResult["CATALOG_QUANTITY_TRACE"] == "Y"):
												$startQnt = $arResult["PROPERTIES"]["TIME_BUY_FROM"]["VALUE"];
												$currQnt = $arResult["PROPERTIES"]["TIME_BUY_TO"]["VALUE"] ? $arResult["PROPERTIES"]["TIME_BUY_TO"]["VALUE"] : $arResult["CATALOG_QUANTITY"];			
												$currQntPercent = round($currQnt * 100 / $startQnt);
											else:
												$currQntPercent = 100;
											endif;?>

											<div class="progress_bar_block">
												<span class="progress_bar_title"><?=GetMessage("CATALOG_ELEMENT_QUANTITY_PERCENT")?></span>
												<div class="progress_bar_cont">
													<div class="progress_bar_bg">
														<div class="progress_bar_line" style="width:<?=$currQntPercent?>%;"></div>
													</div>
												</div>
												<span class="progress_bar_percent"><?=$currQntPercent?>%</span>
											</div>
										<?endif;
									endif;
								endif;										
							endif;?>
						</div>
						<?/***OFFERS_DETAIL_TIME_BUY_TIMER_BUY***/?>
						<div class="catalog-detail-buy" id="<?=$arItemIDs['BUY'];?>">
							<?if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"])):						
								/***OFFERS_TIME_BUY_TIMER***/
								if(array_key_exists("TIME_BUY", $arResult["PROPERTIES"]) && !$arResult["PROPERTIES"]["TIME_BUY"]["VALUE"] == false):
									if(!empty($arResult["CURRENT_DISCOUNT"]["ACTIVE_TO"])):								
										$new_date = ParseDateTime($arResult["CURRENT_DISCOUNT"]["ACTIVE_TO"], FORMAT_DATETIME);?>
										<script type="text/javascript">												
											$(function() {														
												$("#time_buy_timer").countdown({
													until: new Date(<?=$new_date["YYYY"]?>, <?=$new_date["MM"]?> - 1, <?=$new_date["DD"]?>, <?=$new_date["HH"]?>, <?=$new_date["MI"]?>),
													format: "DHMS",
													expiryText: "<div class='over'><?=GetMessage('CATALOG_ELEMENT_TIME_BUY_EXPIRY')?></div>"
												});
											});												
										</script>
										<div class="time_buy_cont">
											<div class="time_buy_clock">
												<i class="fa fa-clock-o"></i>
											</div>
											<div class="time_buy_timer" id="time_buy_timer"></div>
										</div>
									<?endif;
								endif;						
								/***OFFERS_BUY***/
								if($arSetting["OFFERS_VIEW"]["VALUE"] != "LIST"):
									foreach($arResult["OFFERS"] as $key => $arOffer):?>
										<div id="buy_more_detail_<?=$arOffer['ID']?>" class="buy_more_detail <?=$arResult['ID']?> hidden">
											<?if($arOffer["CAN_BUY"]):
												if($arOffer["ASK_PRICE"]):?>
													<a class="btn_buy apuo_detail" id="ask_price_anch_<?=$arOffer['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-comment-o"></i><?=GetMessage("CATALOG_ELEMENT_ASK_PRICE")?></a>
													<?$properties = false;
													foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer) {
														$properties[] = $propOffer["NAME"].": ".strip_tags($propOffer["DISPLAY_VALUE"]);
													}
													$properties = implode("; ", $properties);
													if(!empty($properties)):
														$offer_name = $arResult["NAME"]." (".$properties.")";
													else:
														$offer_name = $arResult["NAME"];
													endif;?>
													<?$APPLICATION->IncludeComponent("altop:ask.price", "",
														Array(
															"ELEMENT_ID" => $arOffer["ID"],		
															"ELEMENT_NAME" => $offer_name,
															"SELECT_PROP_DIV" => $arItemIDs["SELECT_PROP_DIV"],
															"EMAIL_TO" => "",				
															"REQUIRED_FIELDS" => array("NAME", "TEL", "TIME")
														),
														false,
														array("HIDE_ICONS" => "Y")
													);?>
												<?elseif(!$arOffer["ASK_PRICE"]):?>
													<div class="add2basket_block">
														<form action="<?=SITE_DIR?>ajax/add2basket.php" class="add2basket_form">
															<div class="qnt_cont">
																<a href="javascript:void(0)" class="minus" onclick="if (BX('quantity_<?=$arOffer["ID"]?>').value > <?=$arOffer["CATALOG_MEASURE_RATIO"]?>) BX('quantity_<?=$arOffer["ID"]?>').value = parseFloat(BX('quantity_<?=$arOffer["ID"]?>').value)-<?=$arOffer["CATALOG_MEASURE_RATIO"]?>;"><span>-</span></a>
																<input type="text" id="quantity_<?=$arOffer['ID']?>" name="quantity" class="quantity" value="<?=$arOffer['CATALOG_MEASURE_RATIO']?>"/>
																<a href="javascript:void(0)" class="plus" onclick="BX('quantity_<?=$arOffer["ID"]?>').value = parseFloat(BX('quantity_<?=$arOffer["ID"]?>').value)+<?=$arOffer["CATALOG_MEASURE_RATIO"]?>;"><span>+</span></a>
															</div>
															<input type="hidden" name="ID" class="offer_id" value="<?=$arOffer['ID']?>" />
															<?$props = array();
															if(!empty($arOffer["PROPERTIES"]["ARTNUMBER"]["VALUE"])):		
																$props[] = array(
																	"NAME" => $arOffer["PROPERTIES"]["ARTNUMBER"]["NAME"],
																	"CODE" => $arOffer["PROPERTIES"]["ARTNUMBER"]["CODE"],
																	"VALUE" => $arOffer["PROPERTIES"]["ARTNUMBER"]["VALUE"]
																);																
															endif;
															foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer) {
																$props[] = array(
																	"NAME" => $propOffer["NAME"],
																	"CODE" => $propOffer["CODE"],
																	"VALUE" => strip_tags($propOffer["DISPLAY_VALUE"])
																);
															}
															$props = !empty($props) ? strtr(base64_encode(addslashes(gzcompress(serialize($props),9))), '+/=', '-_,') : "";?>
															<input type="hidden" name="PROPS" value="<?=$props?>" />
															<?if(!empty($arResult["SELECT_PROPS"])):?>
																<input type="hidden" name="SELECT_PROPS" id="select_props_<?=$arOffer['ID']?>" value="" />
															<?endif;?>
															<button type="button" class="btn_buy detail" name="add2basket" value="<?=GetMessage('CATALOG_ELEMENT_ADD_TO_CART')?>"><i class="fa fa-shopping-cart"></i><?=GetMessage('CATALOG_ELEMENT_ADD_TO_CART')?></button>
														</form>
														<button name="boc_anch" id="boc_anch_<?=$arOffer['ID']?>" class="btn_buy boc_anch" value="<?=GetMessage('CATALOG_ELEMENT_BOC')?>"><i class="fa fa-bolt"></i><?=GetMessage('CATALOG_ELEMENT_BOC')?></button>
														<?$APPLICATION->IncludeComponent("altop:buy.one.click", "", 
															array(
																"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
																"IBLOCK_ID" => $arParams["IBLOCK_ID"],
																"ELEMENT_ID" => $arOffer["ID"],
																"ELEMENT_CODE" => "",
																"ELEMENT_PROPS" => $props,
																"SELECT_PROP_DIV" => $arItemIDs["SELECT_PROP_DIV"],
																"REQUIRED_ORDER_FIELDS" => array(
																	0 => "NAME",
																	1 => "TEL",
																),
																"DEFAULT_PERSON_TYPE" => "1",
																"DEFAULT_ORDER_PROP_NAME" => "1",
																"DEFAULT_ORDER_PROP_TEL" => "3",
																"DEFAULT_ORDER_PROP_EMAIL" => "2",
																"DEFAULT_DELIVERY" => "0",
																"DEFAULT_PAYMENT" => "0",
																"BUY_MODE" => "ONE",
																"DUPLICATE_LETTER_TO_EMAILS" => array(
																	0 => "sales",
																),
															),
															false,
															array("HIDE_ICONS" => "Y")
														);?>
													</div>
												<?endif;
											elseif(!$arOffer["CAN_BUY"]):?>
												<a class="btn_buy apuo_detail" id="order_anch_<?=$arOffer['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-clock-o"></i><?=GetMessage("CATALOG_ELEMENT_UNDER_ORDER")?></a>
												<?$properties = false;
												foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer) {
													$properties[] = $propOffer["NAME"].": ".strip_tags($propOffer["DISPLAY_VALUE"]);
												}
												$properties = implode("; ", $properties);
												if(!empty($properties)):
													$offer_name = $arResult["NAME"]." (".$properties.")";
												else:
													$offer_name = $arResult["NAME"];
												endif;?>
												<?$APPLICATION->IncludeComponent("altop:ask.price", "order",
													Array(
														"ELEMENT_ID" => $arOffer["ID"],		
														"ELEMENT_NAME" => $offer_name,
														"SELECT_PROP_DIV" => $arItemIDs["SELECT_PROP_DIV"],
														"EMAIL_TO" => "",				
														"REQUIRED_FIELDS" => array("NAME", "TEL", "TIME")
													),
													false,
													array("HIDE_ICONS" => "Y")
												);?>
												<?$APPLICATION->IncludeComponent("bitrix:sale.notice.product", "", 
													array(
														"NOTIFY_ID" => $arOffer["ID"],
														"NOTIFY_URL" => htmlspecialcharsback($arOffer["SUBSCRIBE_URL"]),
														"NOTIFY_USE_CAPTHA" => "Y"
													),									
													false,
													array("HIDE_ICONS" => "Y")
												);?>
											<?endif;?>								
										</div>
									<?endforeach;
								/***OFFERS_LIST_BUY***/
								elseif($arSetting["OFFERS_VIEW"]["VALUE"] == "LIST"):?>
									<div class="buy_more_detail">								
										<script type="text/javascript">
											$(function() {
												$("button[name=choose_offer]").click(function() {											
													var destination = $("#catalog-detail-offers-list").offset().top;
													$("html:not(:animated),body:not(:animated)").animate({scrollTop: destination}, 500);
													return false;
												});
											});
										</script>
										<button name="choose_offer" class="btn_buy detail" value="<?=GetMessage('CATALOG_ELEMENT_CHOOSE_OFFER')?>"><?=GetMessage('CATALOG_ELEMENT_CHOOSE_OFFER')?></button>
									</div>							
								<?endif;
							else:						
								/***DETAIL_TIME_BUY_TIMER***/
								if(array_key_exists("TIME_BUY", $arResult["PROPERTIES"]) && !$arResult["PROPERTIES"]["TIME_BUY"]["VALUE"] == false):
									if(!empty($arResult["CURRENT_DISCOUNT"]["ACTIVE_TO"])):
										if($arResult["CAN_BUY"]):
											$new_date = ParseDateTime($arResult["CURRENT_DISCOUNT"]["ACTIVE_TO"], FORMAT_DATETIME);?>
											<script type="text/javascript">												
												$(function() {														
													$("#time_buy_timer").countdown({
														until: new Date(<?=$new_date["YYYY"]?>, <?=$new_date["MM"]?> - 1, <?=$new_date["DD"]?>, <?=$new_date["HH"]?>, <?=$new_date["MI"]?>),
														format: "DHMS",
														expiryText: "<div class='over'><?=GetMessage('CATALOG_ELEMENT_TIME_BUY_EXPIRY')?></div>"
													});
												});												
											</script>
											<div class="time_buy_cont">
												<div class="time_buy_clock">
													<i class="fa fa-clock-o"></i>
												</div>
												<div class="time_buy_timer" id="time_buy_timer"></div>
											</div>
										<?endif;
									endif;
								endif;
								/***DETAIL_BUY***/?>						
								<div class="buy_more_detail">							
									<?if($arResult["CAN_BUY"]):
										if($arResult["ASK_PRICE"]):?>
											<a class="btn_buy apuo_detail" id="ask_price_anch_<?=$arResult['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-comment-o"></i><?=GetMessage("CATALOG_ELEMENT_ASK_PRICE")?></a>
											<?$APPLICATION->IncludeComponent("altop:ask.price", "",
												Array(
													"ELEMENT_ID" => $arResult["ID"],		
													"ELEMENT_NAME" => $arResult["NAME"],
													"SELECT_PROP_DIV" => $arItemIDs["SELECT_PROP_DIV"],
													"EMAIL_TO" => "",				
													"REQUIRED_FIELDS" => array("NAME", "TEL", "TIME")
												),
												false
											);?>
										<?elseif(!$arResult["ASK_PRICE"]):?>
											<form action="<?=SITE_DIR?>ajax/add2basket.php" class="add2basket_form">
												<div class="qnt_cont">
													<a href="javascript:void(0)" class="minus" onclick="if(BX('quantity_<?=$arResult["ID"]?>').value > <?=$arResult["CATALOG_MEASURE_RATIO"]?>) BX('quantity_<?=$arResult["ID"]?>').value = parseFloat(BX('quantity_<?=$arResult["ID"]?>').value)-<?=$arResult["CATALOG_MEASURE_RATIO"]?>;"><span>-</span></a>
													<input type="text" id="quantity_<?=$arResult['ID']?>" name="quantity" class="quantity" value="<?=$arResult['CATALOG_MEASURE_RATIO']?>"/>
													<a href="javascript:void(0)" class="plus" onclick="BX('quantity_<?=$arResult["ID"]?>').value = parseFloat(BX('quantity_<?=$arResult["ID"]?>').value)+<?=$arResult["CATALOG_MEASURE_RATIO"]?>;"><span>+</span></a>
												</div>
												<input type="hidden" name="ID" class="id" value="<?=$arResult['ID']?>" />
												<?$props = "";
												if(!empty($arResult["PROPERTIES"]["ARTNUMBER"]["VALUE"])):				
													$props[] = array(
														"NAME" => $arResult["PROPERTIES"]["ARTNUMBER"]["NAME"],
														"CODE" => $arResult["PROPERTIES"]["ARTNUMBER"]["CODE"],
														"VALUE" => $arResult["PROPERTIES"]["ARTNUMBER"]["VALUE"]
													);
													$props = strtr(base64_encode(addslashes(gzcompress(serialize($props),9))), '+/=', '-_,');?>
													<input type="hidden" name="PROPS" value="<?=$props?>" />
												<?endif;
												if(!empty($arResult["SELECT_PROPS"])):?>
													<input type="hidden" name="SELECT_PROPS" id="select_props_<?=$arResult['ID']?>" value="" />
												<?endif;?>
												<button type="button" id="<?=$arItemIDs['BTN_BUY']?>" class="btn_buy detail" name="add2basket" value="<?=GetMessage('CATALOG_ELEMENT_ADD_TO_CART')?>"><i class="fa fa-shopping-cart"></i><?=GetMessage('CATALOG_ELEMENT_ADD_TO_CART')?></button>
											</form>									
											<button name="boc_anch" id="boc_anch_<?=$arResult['ID']?>" class="btn_buy boc_anch" value="<?=GetMessage('CATALOG_ELEMENT_BOC')?>"><i class="fa fa-bolt"></i><?=GetMessage('CATALOG_ELEMENT_BOC')?></button>
											<?$APPLICATION->IncludeComponent("altop:buy.one.click", ".default", 
												array(
													"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
													"IBLOCK_ID" => $arParams["IBLOCK_ID"],
													"ELEMENT_ID" => $arResult["ID"],
													"ELEMENT_CODE" => "",
													"ELEMENT_PROPS" => $props,
													"SELECT_PROP_DIV" => $arItemIDs["SELECT_PROP_DIV"],
													"REQUIRED_ORDER_FIELDS" => array(
														0 => "NAME",
														1 => "TEL",
													),
													"DEFAULT_PERSON_TYPE" => "1",
													"DEFAULT_ORDER_PROP_NAME" => "1",
													"DEFAULT_ORDER_PROP_TEL" => "3",
													"DEFAULT_ORDER_PROP_EMAIL" => "2",
													"DEFAULT_DELIVERY" => "0",
													"DEFAULT_PAYMENT" => "0",											
													"BUY_MODE" => "ONE",											
													"DUPLICATE_LETTER_TO_EMAILS" => array(
														0 => "sales",
													),
												),
												false
											);?>
										<?endif;
									elseif(!$arResult["CAN_BUY"]):?>
										<a class="btn_buy apuo_detail" id="order_anch_<?=$arResult['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-clock-o"></i><?=GetMessage("CATALOG_ELEMENT_UNDER_ORDER")?></a>
										<?$APPLICATION->IncludeComponent("altop:ask.price", "order",
											Array(
												"ELEMENT_ID" => $arResult["ID"],		
												"ELEMENT_NAME" => $arResult["NAME"],
												"SELECT_PROP_DIV" => $arItemIDs["SELECT_PROP_DIV"],
												"EMAIL_TO" => "",				
												"REQUIRED_FIELDS" => array("NAME", "TEL", "TIME")
											),
											false
										);?>
										<?$APPLICATION->IncludeComponent("bitrix:sale.notice.product", "", 
											array(
												"NOTIFY_ID" => $arResult["ID"],
												"NOTIFY_URL" => htmlspecialcharsback($arResult["SUBSCRIBE_URL"]),
												"NOTIFY_USE_CAPTHA" => "Y"
											),									
											false
										);?>
									<?endif;?>										
								</div>
							<?endif;?>
						</div>
						<?/***COMPARE_DELAY***/?>
						<div class="compare_delay">
							<?/***DETAIL_COMPARE***/
							if($arParams["DISPLAY_COMPARE"] == "Y"):?>
								<div class="compare">
									<a href="javascript:void(0)" class="catalog-item-compare" id="catalog_add2compare_link_<?=$arResult['ID']?>" onclick="return addToCompare('<?=$arResult["COMPARE_URL"]?>', 'catalog_add2compare_link_<?=$arResult["ID"]?>', '<?=SITE_DIR?>');" rel="nofollow"><span class="compare_cont"><i class="fa fa-bar-chart"></i><i class="fa fa-check"></i><span class="compare_text"><?=GetMessage('CATALOG_ELEMENT_ADD_TO_COMPARE')?></span></span></a>
								</div>
							<?endif;?>
							<div class="catalog-detail-delay" id="<?=$arItemIDs['DELAY']?>">
								<?/***OFFERS_DELAY***/
								if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"])):
									if($arSetting["OFFERS_VIEW"]["VALUE"] != "LIST"):
										foreach($arResult["OFFERS"] as $key => $arOffer):
											if($arOffer["CAN_BUY"]):
												foreach($arOffer["PRICES"] as $code => $arPrice):
													if($arPrice["MIN_PRICE"] == "Y"):
														if($arPrice["DISCOUNT_VALUE"] > 0):
															$props = array();
															if(!empty($arOffer["PROPERTIES"]["ARTNUMBER"]["VALUE"])):		
																$props[] = array(
																	"NAME" => $arOffer["PROPERTIES"]["ARTNUMBER"]["NAME"],
																	"CODE" => $arOffer["PROPERTIES"]["ARTNUMBER"]["CODE"],
																	"VALUE" => $arOffer["PROPERTIES"]["ARTNUMBER"]["VALUE"]
																);																
															endif;
															foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer) {
																$props[] = array(
																	"NAME" => $propOffer["NAME"],
																	"CODE" => $propOffer["CODE"],
																	"VALUE" => strip_tags($propOffer["DISPLAY_VALUE"])
																);
															}
															$props = !empty($props) ? strtr(base64_encode(addslashes(gzcompress(serialize($props),9))), '+/=', '-_,') : "";?>
															<div id="delay_<?=$arOffer['ID']?>" class="delay <?=$arResult['ID']?> hidden">
																<a href="javascript:void(0)" id="catalog-item-delay-<?=$arOffer['ID']?>" class="catalog-item-delay" onclick="return addToDelay('<?=$arOffer["ID"]?>', 'quantity_<?=$arOffer["ID"]?>', '<?=$props?>', '', 'catalog-item-delay-<?=$arOffer["ID"]?>', '<?=SITE_DIR?>')" rel="nofollow"><span class="delay_cont"><i class="fa fa-heart-o"></i><i class="fa fa-check"></i><span class="delay_text"><?=GetMessage('CATALOG_ELEMENT_ADD_TO_DELAY')?></span></span></a>
															</div>
														<?endif;
													endif;
												endforeach;
											endif;
										endforeach;
									endif;
								/***DETAIL_DELAY***/
								else:
									if($arResult["CAN_BUY"]):
										foreach($arResult["PRICES"] as $code => $arPrice):
											if($arPrice["MIN_PRICE"] == "Y"):
												if($arPrice["DISCOUNT_VALUE"] > 0):												
													$props = "";
													if(!empty($arResult["PROPERTIES"]["ARTNUMBER"]["VALUE"])):				
														$props[] = array(
															"NAME" => $arResult["PROPERTIES"]["ARTNUMBER"]["NAME"],
															"CODE" => $arResult["PROPERTIES"]["ARTNUMBER"]["CODE"],
															"VALUE" => $arResult["PROPERTIES"]["ARTNUMBER"]["VALUE"]
														);
														$props = strtr(base64_encode(addslashes(gzcompress(serialize($props),9))), '+/=', '-_,');
													endif;?>
													<div class="delay">
														<a href="javascript:void(0)" id="catalog-item-delay-<?=$arResult['ID']?>" class="catalog-item-delay" onclick="return addToDelay('<?=$arResult["ID"]?>', 'quantity_<?=$arResult["ID"]?>', '<?=$props?>', '', 'catalog-item-delay-<?=$arResult["ID"]?>', '<?=SITE_DIR?>')" rel="nofollow"><span class="delay_cont"><i class="fa fa-heart-o"></i><i class="fa fa-check"></i><span class="delay_text"><?=GetMessage('CATALOG_ELEMENT_ADD_TO_DELAY')?></span></span></a>
													</div>
												<?endif;
											endif;
										endforeach;
									endif;
								endif?>
							</div>
						</div>
						<?/***DETAIL_DELIVERY***/
						if(!empty($arResult["PROPERTIES"]["DELIVERY"]["VALUE"])):?>
							<div class="catalog-detail-delivery">
								<span class="name"><?=$arResult["PROPERTIES"]["DELIVERY"]["NAME"]?></span> 
								<span class="val"><?=$arResult["PROPERTIES"]["DELIVERY"]["VALUE"]?></span>
							</div>
						<?endif;
						/***DETAIL_PAYMENTS***/
						global $arPayIcFilter;
						$arPayIcFilter = array(
							"!PROPERTY_SHOW_PRODUCT_DETAIL" => false
						);?>					
						<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/payments_icons.php"), false, array("HIDE_ICONS" => "Y"));?>
						<?/***DETAIL_BUTTONS***/					
						if(in_array("BUTTON_PAYMENTS", $arSetting["CATALOG_DETAIL"]["VALUE"]) || in_array("BUTTON_CREDIT", $arSetting["CATALOG_DETAIL"]["VALUE"]) || in_array("BUTTON_DELIVERY", $arSetting["CATALOG_DETAIL"]["VALUE"])):?>
							<div class="catalog-detail-buttons">
								<?if(in_array("BUTTON_PAYMENTS", $arSetting["CATALOG_DETAIL"]["VALUE"])):?>
									<a rel="nofollow" target="_blank" href="<?=!empty($arParams['BUTTON_PAYMENTS_HREF']) ? $arParams['BUTTON_PAYMENTS_HREF'] : 'javascript:void(0)'?>" class="btn_buy apuo pcd"><i class="fa fa-credit-card"></i><span><?=GetMessage('CATALOG_ELEMENT_BUTTON_PAYMENTS')?></span></a>
								<?endif;
								if(in_array("BUTTON_CREDIT", $arSetting["CATALOG_DETAIL"]["VALUE"])):?>
									<a rel="nofollow" target="_blank" href="<?=!empty($arParams['BUTTON_CREDIT_HREF']) ? $arParams['BUTTON_CREDIT_HREF'] : 'javascript:void(0)'?>" class="btn_buy apuo pcd"><i class="fa fa-percent"></i><span><?=GetMessage('CATALOG_ELEMENT_BUTTON_CREDIT')?></span></a>
								<?endif;
								if(in_array("BUTTON_DELIVERY", $arSetting["CATALOG_DETAIL"]["VALUE"])):?>
									<a rel="nofollow" target="_blank" href="<?=!empty($arParams['BUTTON_DELIVERY_HREF']) ? $arParams['BUTTON_DELIVERY_HREF'] : 'javascript:void(0)'?>" class="btn_buy apuo pcd"><i class="fa fa-truck"></i><span><?=GetMessage('CATALOG_ELEMENT_BUTTON_DELIVERY')?></span></a>
								<?endif;?>
							</div>
						<?endif;?>
					</div>
				</div>
			</div>
			<?/***OFFERS_SELECT_PROPS***/
			if((isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"]) && $arSetting["OFFERS_VIEW"]["VALUE"] != "LIST") || (isset($arResult["SELECT_PROPS"]) && !empty($arResult["SELECT_PROPS"]))):?>
				<div class="catalog-detail-offers-cont">
					<?/***OFFERS_PROPS***/
					if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"])):
						if($arSetting["OFFERS_VIEW"]["VALUE"] != "LIST"):
							$arSkuProps = array();?>
							<div class="catalog-detail-offers" id="<?=$arItemIDs['PROP_DIV'];?>">
								<?foreach($arResult["SKU_PROPS"] as &$arProp) {
									if(!isset($arResult["OFFERS_PROP"][$arProp["CODE"]]))
										continue;
									$arSkuProps[] = array(
										"ID" => $arProp["ID"],
										"SHOW_MODE" => $arProp["SHOW_MODE"]
									);?>						
									<div class="offer_block" id="<?=$arItemIDs['PROP'].$arProp['ID'];?>_cont">
										<div class="h3"><?=htmlspecialcharsex($arProp["NAME"]);?></div>
										<ul id="<?=$arItemIDs['PROP'].$arProp['ID'];?>_list" class="<?=$arProp['CODE']?><?=$arProp['SHOW_MODE'] == 'PICT' ? ' COLOR' : '';?>">
											<?foreach($arProp["VALUES"] as $arOneValue) {
												$arOneValue["NAME"] = htmlspecialcharsbx($arOneValue["NAME"]);?>
												<li data-treevalue="<?=$arProp['ID'].'_'.$arOneValue['ID'];?>" data-onevalue="<?=$arOneValue['ID'];?>" style="display:none;">
													<span title="<?=$arOneValue['NAME'];?>">
														<?if("TEXT" == $arProp["SHOW_MODE"]) {
															echo $arOneValue["NAME"];
														} elseif("PICT" == $arProp["SHOW_MODE"]) {
															if(!empty($arOneValue["PICT"]["src"])):?>
																<img src="<?=$arOneValue['PICT']['src']?>" width="<?=$arOneValue['PICT']['width']?>" height="<?=$arOneValue['PICT']['height']?>" alt="<?=$arOneValue['NAME']?>" />
															<?else:?>
																<i style="background:#<?=$arOneValue['HEX']?>"></i>
															<?endif;
														}?>
													</span>
												</li>
											<?}?>
										</ul>
										<div class="bx_slide_left" style="display:none;" id="<?=$arItemIDs['PROP'].$arProp['ID']?>_left" data-treevalue="<?=$arProp['ID']?>"></div>
										<div class="bx_slide_right" style="display:none;" id="<?=$arItemIDs['PROP'].$arProp['ID']?>_right" data-treevalue="<?=$arProp['ID']?>"></div>
									</div>
								<?}
								unset($arProp);?>
							</div>
						<?endif;
					endif;
					/***SELECT_PROPS***/
					if(isset($arResult["SELECT_PROPS"]) && !empty($arResult["SELECT_PROPS"])):
						$arSelProps = array();?>
						<div class="catalog-detail-offers" id="<?=$arItemIDs['SELECT_PROP_DIV'];?>">
							<?foreach($arResult["SELECT_PROPS"] as $key => &$arProp):
								$arSelProps[] = array(
									"ID" => $arProp["ID"]
								);?>
								<div class="offer_block" id="<?=$arItemIDs['SELECT_PROP'].$arProp['ID'];?>">
									<div class="h3"><?=htmlspecialcharsex($arProp["NAME"]);?></div>
									<ul class="<?=$arProp['CODE']?>">
										<?$props = array();
										foreach($arProp["DISPLAY_VALUE"] as $arOneValue) {
											$props[$key] = array(
												"NAME" => $arProp["NAME"],
												"CODE" => $arProp["CODE"],
												"VALUE" => strip_tags($arOneValue)
											);
											$props[$key] = !empty($props[$key]) ? strtr(base64_encode(addslashes(gzcompress(serialize($props[$key]),9))), '+/=', '-_,') : "";?>
											<li data-select-onevalue="<?=$props[$key]?>">
												<span title="<?=$arOneValue;?>"><?=$arOneValue?></span>
											</li>
										<?}?>
									</ul>
								</div>
							<?endforeach;
							unset($arProp);?>
						</div>
					<?endif;?>
				</div>
			<?endif;
			/***OFFERS_DETAIL_PROPERTIES***/?>
			<div id="<?=$arItemIDs['PROPERTIES']?>">
				<?$sPropOffers = array();
				if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"])):
					if($arSetting["OFFERS_VIEW"]["VALUE"] != "LIST"):
						foreach($arResult["OFFERS"] as $key => $arOffer):
							foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer):
								if($propOffer["PROPERTY_TYPE"] == "S"):								
									$sPropOffers[$arOffer["ID"]]["NAME"] = $propOffer["NAME"];
									$sPropOffers[$arOffer["ID"]]["VALUE"] = $propOffer["VALUE"];
								endif;
							endforeach;
						endforeach;
					endif;
				endif;			
				if(!empty($arResult["DISPLAY_PROPERTIES"]) || !empty($sPropOffers)):?>
					<div class="catalog-detail-properties">
						<div class="h4"><?=GetMessage("CATALOG_ELEMENT_PROPERTIES")?></div>
						<?/***DETAIL_PROPERTIES***/
						if(!empty($arResult["DISPLAY_PROPERTIES"])):
							foreach($arResult["DISPLAY_PROPERTIES"] as $k => $v):?>
								<div class="catalog-detail-property">
									<span class="name"><?=$v["NAME"]?></span> 
									<span class="val"><?=is_array($v["DISPLAY_VALUE"]) ? implode(", ", $v["DISPLAY_VALUE"]) : $v["DISPLAY_VALUE"];?></span>
								</div>
							<?endforeach;
						endif;
						/***OFFERS_PROPERTIES***/
						if(!empty($sPropOffers)):
							foreach($sPropOffers as $keySProp => $sPropOffer):?>
								<div id="offer-property_<?=$keySProp?>" class="catalog-detail-property offer-property <?=$arResult['ID']?> hidden">
									<span class="name"><?=$sPropOffer["NAME"]?></span>
									<span class="val"><?=$sPropOffer["VALUE"]?></span>
								</div>
							<?endforeach;
						endif;?>
					</div>
				<?endif;?>
			</div>
		</div>
	</div>
	<?/***OFFERS_LIST***/
	if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"]) && $arSetting["OFFERS_VIEW"]["VALUE"] == "LIST"):?>
		<div id="catalog-detail-offers-list" class="catalog-detail-offers-list">
			<div class="h3"><?=GetMessage("CATALOG_ELEMENT_OFFERS_LIST")?></div>
			<div class="offers-items">
				<div class="thead">
					<div class="offers-items-image"><?=GetMessage("CATALOG_ELEMENT_OFFERS_LIST_IMAGE")?></div>
					<div class="offers-items-name"><?=GetMessage("CATALOG_ELEMENT_OFFERS_LIST_NAME")?></div>
					<?$i = 1;						
					foreach($arResult["SKU_PROPS"] as $arProp):
						if(!isset($arResult["OFFERS_PROP"][$arProp["CODE"]]))
							continue;
						if($i > 3)
							continue;?>						
						<div class="offers-items-prop"><?=htmlspecialcharsex($arProp["NAME"]);?></div>
						<?$i++;
					endforeach;?>
					<div class="offers-items-price"></div>
					<div class="offers-items-buy"><?=GetMessage("CATALOG_ELEMENT_OFFERS_LIST_PRICE")?></div>
				</div>
				<div class="tbody">
					<?foreach($arResult["OFFERS"] as $keyOffer => $arOffer):
						$sticker = "";
						if($arOffer["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"] > 0) {
							$sticker .= "<span class='discount'>-".$arOffer["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"]."%</span>";	
						}?>
						<div class="catalog-item" id="catalog-offer-item-<?=$arOffer['ID']?>">
							<div class="catalog-item-info">							
								<?/***OFFER_IMAGE***/?>
								<div class="catalog-item-image-cont">
									<div class="catalog-item-image">
										<?if(!empty($arOffer["PREVIEW_IMG"]["SRC"])):?>							
											<img src="<?=$arOffer['PREVIEW_IMG']['SRC']?>" width="<?=$arOffer['PREVIEW_IMG']['WIDTH']?>" height="<?=$arOffer['PREVIEW_IMG']['HEIGHT']?>" alt="<?=(isset($arOffer['NAME']) && !empty($arOffer['NAME'])) ? $arOffer['NAME'] : $arResult['NAME'];?>" />
										<?else:?>
											<img src="<?=$arResult['PREVIEW_IMG']['SRC']?>" width="<?=$arResult['PREVIEW_IMG']['WIDTH']?>" height="<?=$arResult['PREVIEW_IMG']['HEIGHT']?>" alt="<?=(isset($arOffer['NAME']) && !empty($arOffer['NAME'])) ? $arOffer['NAME'] : $arResult['NAME'];?>" />
										<?endif;?>
										<div class="sticker">
											<?=$sticker?>
										</div>
									</div>
								</div>
								<?/***OFFER_NAME_ARTNUMBER***/?>
								<div class="catalog-item-title">
									<?/***OFFER_NAME***/?>
									<span class="name"><?=(isset($arOffer["NAME"]) && !empty($arOffer["NAME"])) ? $arOffer["NAME"] : $arResult["NAME"];?></span>
									<?/***OFFER_ARTNUMBER***/?>
									<span class="article"><?=GetMessage("CATALOG_ELEMENT_ARTNUMBER")?><?=!empty($arOffer["PROPERTIES"]["ARTNUMBER"]["VALUE"]) ? $arOffer["PROPERTIES"]["ARTNUMBER"]["VALUE"] : "-";?></span>
								</div>								
								<?/***OFFER_PROPS***/
								$i = 1;
								foreach($arResult["SKU_PROPS"] as $arProp):									
									if(!isset($arResult["OFFERS_PROP"][$arProp["CODE"]]))
										continue;
									if($i > 3)
										continue;?>	
									<div class="catalog-item-prop<?=(!$arOffer["DISPLAY_PROPERTIES"][$arProp["CODE"]] ? ' empty' : '');?>">
										<?if($arOffer["DISPLAY_PROPERTIES"][$arProp["CODE"]]):
											$v = $arOffer["DISPLAY_PROPERTIES"][$arProp["CODE"]];
											if($arProp["SHOW_MODE"] == "TEXT"):
												echo strip_tags($v["DISPLAY_VALUE"]);
											elseif($arProp["SHOW_MODE"] == "PICT"):?>
												<span class="prop_cont">
													<span class="prop" title="<?=$arProp['VALUES'][$v['VALUE']]['NAME']?>">
														<?if(!empty($arProp["VALUES"][$v["VALUE"]]["PICT"]["src"])):?>
															<img src="<?=$arProp['VALUES'][$v['VALUE']]['PICT']['src']?>" width="<?=$arProp['VALUES'][$v['VALUE']]['PICT']['width']?>" height="<?=$arProp['VALUES'][$v['VALUE']]['PICT']['height']?>" alt="<?=$arProp['VALUES'][$v['VALUE']]['NAME']?>" />
														<?else:?>
															<i style="background:#<?=$arProp['VALUES'][$v['VALUE']]['HEX']?>"></i>
														<?endif;?>
													</span>
												</span>
											<?endif;
										endif;?>
									</div>
									<?$i++;
								endforeach;
								unset($arProp);
								/***OFFER_PRICE***/?>
								<div class="item-price">
									<?foreach($arOffer["PRICES"] as $code => $arPrice):
										if($arPrice["MIN_PRICE"] == "Y"):
											if($arPrice["CAN_ACCESS"]):
												
												$price = CCurrencyLang::GetCurrencyFormat($arPrice["CURRENCY"], "ru");
												if(empty($price["THOUSANDS_SEP"])):
													$price["THOUSANDS_SEP"] = " ";
												endif;
												$price["REFERENCE_DECIMALS"] = $price["DECIMALS"];
												if($price["HIDE_ZERO"] == "Y"):
													if($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):
														if(round($arPrice["DISCOUNT_VALUE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $price["DECIMALS"]) == round($arPrice["DISCOUNT_VALUE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], 0)):
															$price["REFERENCE_DECIMALS"] = 0;													
														endif;
													endif;
													if(round($arPrice["DISCOUNT_VALUE"], $price["DECIMALS"]) == round($arPrice["DISCOUNT_VALUE"], 0)):
														$price["DECIMALS"] = 0;
													endif;
												endif;
												$currency = str_replace("# ", " ", $price["FORMAT_STRING"]);

												if($arPrice["DISCOUNT_VALUE"] <= 0):
													$arOffer["ASK_PRICE"] = 1;?>							
													<span class="catalog-item-no-price">
														<span class="unit">
															<?=GetMessage("CATALOG_ELEMENT_NO_PRICE")?>
															<br />
															<span><?=(!empty($arOffer["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arOffer["CATALOG_MEASURE_NAME"] : "";?></span>
														</span>
													</span>
												<?else:?>
													<span class="catalog-item-price">
														<?=number_format($arPrice["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>															
														<span class="unit">
															<?=$currency?>
															<span><?=(!empty($arOffer["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arOffer["CATALOG_MEASURE_NAME"] : "";?></span>
														</span>
														<?if($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
															<span class="catalog-item-price-reference">
																<?=number_format($arPrice["DISCOUNT_VALUE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $price["REFERENCE_DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
																<span><?=$currency?></span>
															</span>
														<?endif;?>
													</span>
													<?if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
														<span class="catalog-item-price-old">
															<?=$arPrice["PRINT_VALUE"];?>
														</span>
														<span class="catalog-item-price-percent">
															<?=GetMessage('CATALOG_ELEMENT_SKIDKA')?>
															<br />
															<?=$arPrice["PRINT_DISCOUNT_DIFF"]?>
														</span>
													<?endif;
												endif;
											endif;
										endif;
									endforeach;?>
								</div>
								<?/***OFFER_MOBILE_PROPS***/
								if(!empty($arOffer["DISPLAY_PROPERTIES"])):?>
									<div id="catalog-item-props-mob-<?=$arOffer['ID']?>" class="catalog-item-props-mob"></div>
								<?endif;
								/***OFFER_AVAILABILITY_BUY***/?>
								<div class="buy_more">
									<?/***OFFER_AVAILABILITY***/?>
									<div class="available">
										<?if($arOffer["CAN_BUY"]):?>													
											<div class="avl">
												<i class="fa fa-check-circle"></i>													
												<span>
													<?=GetMessage("CATALOG_ELEMENT_AVAILABLE");
													if($arOffer["CATALOG_QUANTITY_TRACE"] == "Y"):
														if(in_array("PRODUCT_QUANTITY", $arSetting["GENERAL_SETTINGS"]["VALUE"])):
															echo " ".$arOffer["CATALOG_QUANTITY"];
														endif;
													endif;?>
												</span>
											</div>
										<?elseif(!$arOffer["CAN_BUY"]):?>													
											<div class="not_avl">
												<i class="fa fa-times-circle"></i>
												<span><?=GetMessage("CATALOG_ELEMENT_NOT_AVAILABLE")?></span>
											</div>
										<?endif;?>
									</div>
									<div class="clr"></div>											
									<?/***OFFER_BUY***/
									if($arOffer["CAN_BUY"]):
										if($arOffer["ASK_PRICE"]):?>
											<a class="btn_buy apuo" id="ask_price_anch_<?=$arOffer['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-comment-o"></i><span class="short"><?=GetMessage("CATALOG_ELEMENT_ASK_PRICE_SHORT")?></span></a>
											<?$properties = false;
											foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer) {
												$properties[] = $propOffer["NAME"].": ".strip_tags($propOffer["DISPLAY_VALUE"]);
											}
											$properties = implode("; ", $properties);
											if(!empty($properties)):
												$offer_name = ((isset($arOffer["NAME"]) && !empty($arOffer["NAME"])) ? $arOffer["NAME"] : $arResult["NAME"])." (".$properties.")";
											else:
												$offer_name = (isset($arOffer["NAME"]) && !empty($arOffer["NAME"])) ? $arOffer["NAME"] : $arResult["NAME"];
											endif;?>
											<?$APPLICATION->IncludeComponent("altop:ask.price", "",
												Array(
													"ELEMENT_ID" => $arOffer["ID"],		
													"ELEMENT_NAME" => $offer_name,
													"SELECT_PROP_DIV" => $arItemIDs["SELECT_PROP_DIV"],
													"EMAIL_TO" => "",				
													"REQUIRED_FIELDS" => array("NAME", "TEL", "TIME")
												),
												false,
												array("HIDE_ICONS" => "Y")
											);?>
										<?elseif(!$arOffer["ASK_PRICE"]):?>												
											<div class="add2basket_block">
												<?/***OFFER_DELAY***/
												foreach($arOffer["PRICES"] as $code => $arPrice):
													if($arPrice["MIN_PRICE"] == "Y"):
														$props = array();
														if(!empty($arOffer["PROPERTIES"]["ARTNUMBER"]["VALUE"])):		
															$props[] = array(
																"NAME" => $arOffer["PROPERTIES"]["ARTNUMBER"]["NAME"],
																"CODE" => $arOffer["PROPERTIES"]["ARTNUMBER"]["CODE"],
																"VALUE" => $arOffer["PROPERTIES"]["ARTNUMBER"]["VALUE"]
															);																
														endif;
														foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer) {
															$props[] = array(
																"NAME" => $propOffer["NAME"],
																"CODE" => $propOffer["CODE"],
																"VALUE" => strip_tags($propOffer["DISPLAY_VALUE"])
															);
														}
														$props = !empty($props) ? strtr(base64_encode(addslashes(gzcompress(serialize($props),9))), '+/=', '-_,') : "";?>
														<div class="delay">
															<a href="javascript:void(0)" id="catalog-item-delay-<?=$arOffer['ID']?>" class="catalog-item-delay" onclick="return addToDelay('<?=$arOffer["ID"]?>', '<?=$arOffer["CATALOG_MEASURE_RATIO"]?>', '<?=$props?>', '', 'catalog-item-delay-<?=$arOffer["ID"]?>', '<?=SITE_DIR?>')" rel="nofollow"><i class="fa fa-heart-o"></i><i class="fa fa-check"></i></a>
														</div>
													<?endif;
												endforeach;
												/***OFFER_BUY_FORM***/?>
												<form action="<?=SITE_DIR?>ajax/add2basket.php" class="add2basket_form">
													<div class="qnt_cont">
														<a href="javascript:void(0)" class="minus" onclick="if (BX('quantity_<?=$arOffer["ID"]?>').value > <?=$arOffer["CATALOG_MEASURE_RATIO"]?>) BX('quantity_<?=$arOffer["ID"]?>').value = parseFloat(BX('quantity_<?=$arOffer["ID"]?>').value)-<?=$arOffer["CATALOG_MEASURE_RATIO"]?>;"><span>-</span></a>
														<input type="text" id="quantity_<?=$arOffer['ID']?>" name="quantity" class="quantity" value="<?=$arOffer['CATALOG_MEASURE_RATIO']?>"/>
														<a href="javascript:void(0)" class="plus" onclick="BX('quantity_<?=$arOffer["ID"]?>').value = parseFloat(BX('quantity_<?=$arOffer["ID"]?>').value)+<?=$arOffer["CATALOG_MEASURE_RATIO"]?>;"><span>+</span></a>
													</div>
													<input type="hidden" name="ID" class="offer_id" value="<?=$arOffer['ID']?>" />
													<?$props = array();
													if(!empty($arOffer["PROPERTIES"]["ARTNUMBER"]["VALUE"])):		
														$props[] = array(
															"NAME" => $arOffer["PROPERTIES"]["ARTNUMBER"]["NAME"],
															"CODE" => $arOffer["PROPERTIES"]["ARTNUMBER"]["CODE"],
															"VALUE" => $arOffer["PROPERTIES"]["ARTNUMBER"]["VALUE"]
														);																
													endif;
													foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer) {
														$props[] = array(
															"NAME" => $propOffer["NAME"],
															"CODE" => $propOffer["CODE"],
															"VALUE" => strip_tags($propOffer["DISPLAY_VALUE"])
														);
													}
													$props = !empty($props) ? strtr(base64_encode(addslashes(gzcompress(serialize($props),9))), '+/=', '-_,') : "";?>
													<input type="hidden" name="PROPS" value="<?=$props?>" />
													<?if(!empty($arResult["SELECT_PROPS"])):?>
														<input type="hidden" name="SELECT_PROPS" id="select_props_<?=$arOffer['ID']?>" value="" />
													<?endif;?>
													<button type="button" class="btn_buy" name="add2basket" value="<?=$arMessage['CATALOG_ELEMENT_ADD_TO_CART']?>"><i class="fa fa-shopping-cart"></i></button>
												</form>
												<button name="boc_anch" id="boc_anch_<?=$arOffer['ID']?>" class="btn_buy boc_anch" value="<?=GetMessage('CATALOG_ELEMENT_BOC')?>"><i class="fa fa-bolt"></i><?=GetMessage('CATALOG_ELEMENT_BOC_SHORT')?></button>
												<?$APPLICATION->IncludeComponent("altop:buy.one.click", ".default", 
													array(
														"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
														"IBLOCK_ID" => $arParams["IBLOCK_ID"],
														"ELEMENT_ID" => $arOffer["ID"],
														"ELEMENT_CODE" => "",
														"ELEMENT_PROPS" => $props,
														"SELECT_PROP_DIV" => $arItemIDs["SELECT_PROP_DIV"],
														"REQUIRED_ORDER_FIELDS" => array(
															0 => "NAME",
															1 => "TEL",
														),
														"DEFAULT_PERSON_TYPE" => "1",
														"DEFAULT_ORDER_PROP_NAME" => "1",
														"DEFAULT_ORDER_PROP_TEL" => "3",
														"DEFAULT_ORDER_PROP_EMAIL" => "2",
														"DEFAULT_DELIVERY" => "0",
														"DEFAULT_PAYMENT" => "0",										
														"BUY_MODE" => "ONE",
														"DUPLICATE_LETTER_TO_EMAILS" => array(
															0 => "sales",
														),
													),
													false,
													array("HIDE_ICONS" => "Y")
												);?>
											</div>
										<?endif;
									elseif(!$arOffer["CAN_BUY"]):?>
										<a class="btn_buy apuo" id="order_anch_<?=$arOffer['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-clock-o"></i><span class="short"><?=GetMessage("CATALOG_ELEMENT_UNDER_ORDER_SHORT")?></span></a>
										<?$properties = false;
										foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer) {
											$properties[] = $propOffer["NAME"].": ".strip_tags($propOffer["DISPLAY_VALUE"]);
										}
										$properties = implode("; ", $properties);
										if(!empty($properties)):
											$offer_name = ((isset($arOffer["NAME"]) && !empty($arOffer["NAME"])) ? $arOffer["NAME"] : $arResult["NAME"])." (".$properties.")";
										else:
											$offer_name = (isset($arOffer["NAME"]) && !empty($arOffer["NAME"])) ? $arOffer["NAME"] : $arResult["NAME"];
										endif;?>
										<?$APPLICATION->IncludeComponent("altop:ask.price", "order",
											Array(
												"ELEMENT_ID" => $arOffer["ID"],		
												"ELEMENT_NAME" => $offer_name,
												"SELECT_PROP_DIV" => $arItemIDs["SELECT_PROP_DIV"],
												"EMAIL_TO" => "",				
												"REQUIRED_FIELDS" => array("NAME", "TEL", "TIME")
											),
											false,
											array("HIDE_ICONS" => "Y")
										);?>
									<?endif;?>										
								</div>										
							</div>
						</div>							
					<?endforeach;?>
				</div>
			</div>				
		</div>
	<?endif;
	/***DETAIL_KIT_ITEMS***/
	if(count($arResult["KIT_ITEMS"]) > 0):?>
		<div class="kit-items">
			<div class="h3"><?=GetMessage("CATALOG_ELEMENT_KIT_ITEMS")?></div>
			<div class="catalog-item-cards">
				<?foreach($arResult["KIT_ITEMS"] as $key => $arItem):?>
					<div class="catalog-item-card">
						<div class="catalog-item-info">
							<?/***KIT_ITEM_IMAGE***/?>
							<div class="item-image-cont">
								<div class="item-image">
									<?if(is_array($arItem["PREVIEW_IMG"])):?>
										<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
											<img class="item_img" src="<?=$arItem['PREVIEW_IMG']['SRC']?>" width="<?=$arItem['PREVIEW_IMG']['WIDTH']?>" height="<?=$arItem['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arItem['NAME']?>" />
										</a>
									<?else:?>
										<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
											<img class="item_img" src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="150" height="150" alt="<?=$arItem['NAME']?>" />
										</a>
									<?endif?>
								</div>
							</div>
							<?/***KIT_ITEM_TITLE***/?>
							<div class="item-all-title">
								<a class="item-title" href="<?=$arItem['DETAIL_PAGE_URL']?>" title="<?=$arItem['NAME']?>">
									<?=$arItem["NAME"]?>
								</a>
							</div>
							<?/***KIT_ITEM_PRICE***/?>
							<div class="item-price-cont<?=(!in_array('OLD_PRICE', $arSetting['PRODUCT_TABLE_VIEW']['VALUE']) ? ' one' : '').($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"]) ? ' reference' : '');?>">
								<?$price = CCurrencyLang::GetCurrencyFormat($arItem["PRICE_CURRENCY"], "ru");
								if(empty($price["THOUSANDS_SEP"])):
									$price["THOUSANDS_SEP"] = " ";
								endif;								
								if($price["HIDE_ZERO"] == "Y"):									
									if(round($arItem["PRICE_DISCOUNT_VALUE"], $price["DECIMALS"]) == round($arItem["PRICE_DISCOUNT_VALUE"], 0)):
										$price["DECIMALS"] = 0;
									endif;
								endif;
								$currency = str_replace("# ", " ", $price["FORMAT_STRING"]);?>

								<div class="item-price">
									<?if(in_array("OLD_PRICE", $arSetting["PRODUCT_TABLE_VIEW"]["VALUE"])):
										if($arItem["PRICE_DISCOUNT_VALUE"] < $arItem["PRICE_VALUE"]):?>
											<span class="catalog-item-price-old">
												<?=$arItem["PRICE_PRINT_VALUE"];?>										
											</span>
										<?endif;
									endif;?>
									<span class="catalog-item-price">
										<?=number_format($arItem["PRICE_DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
										<span class="unit"><?=$currency?></span>
									</span>
									<?if($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
										<span class="catalog-item-price-reference">
											<?=CCurrencyLang::CurrencyFormat($arItem["PRICE_DISCOUNT_VALUE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arItem["PRICE_CURRENCY"], true);?>
										</span>
									<?endif;?>
								</div>
							</div>
						</div>
					</div>
				<?endforeach;?>
			</div>
			<div class="clr"></div>
		</div>
	<?endif;
	/***DETAIL_CONSTRUCTOR***/?>	
	<div id="<?=$arItemIDs['CONSTRUCTOR']?>"></div>
	<?/***DETAIL_TABS***/?>
	<div class="section">
		<ul class="tabs">
			<li class="current">
				<a href="#tab1"><span><?=GetMessage("CATALOG_ELEMENT_FULL_DESCRIPTION")?></span></a>
			</li>
			<li<?=(empty($arResult["PROPERTIES"]["FREE_TAB"]["VALUE"])) ? " style='display:none;'" : "";?>>
				<a href="#tab2"><span><?=$arResult["PROPERTIES"]["FREE_TAB"]["NAME"]?></span></a>
			</li>
			<li<?=(empty($arResult["PROPERTIES"]["ACCESSORIES"]["VALUE"])) ? " style='display:none;'" : "";?>>
				<a href="#tab3"><span><?=$arResult["PROPERTIES"]["ACCESSORIES"]["NAME"]?></span></a>
			</li>
			<li<?=(empty($arResult["PROPERTIES"]["FILES_DOCS"]["FULL_VALUE"])) ? " style='display:none;'" : "";?>>
				<a href="#tab4"><span><?=$arResult["PROPERTIES"]["FILES_DOCS"]["NAME"]?></span></a>
			</li>
			<li>
				<a href="#tab5"><span><?=GetMessage("CATALOG_ELEMENT_REVIEWS")?> <span class="reviews_count">(<?=$arResult["REVIEWS"]["COUNT"]?>)</span></span></a>
			</li>			
			<li<?=($arParams["USE_STORE"] != "Y" ? " style='display:none;'" : (isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"]) ? ($arSetting["OFFERS_VIEW"]["VALUE"] == "LIST" ? " style='display:none;'" : "") : ""));?>>
				<a href="#tab6"><span><?=GetMessage("CATALOG_ELEMENT_SHOPS")?></span></a>
			</li>
		</ul>
		<?/***DETAIL_TEXT_TAB***/?>
		<div class="box visible">
			<div class="description">
				<?=$arResult["DETAIL_TEXT"];?>
			</div>
		</div>
		<?/***FREE_TAB***/?>
		<div class="box"<?=(empty($arResult["PROPERTIES"]["FREE_TAB"]["VALUE"])) ? " style='display:none;'" : "";?>>
			<div class="tab-content">
				<?=$arResult["PROPERTIES"]["FREE_TAB"]["~VALUE"]["TEXT"];?>
			</div>
		</div>
		<?/***ACCESSORIES_TAB***/?>
		<div class="box" id="accessories-to"<?=(empty($arResult["PROPERTIES"]["ACCESSORIES"]["VALUE"])) ? " style='display:none;'" : "";?>></div>
		<?/***FILES_DOCS_TAB***/?>
		<div class="box"<?=(empty($arResult["PROPERTIES"]["FILES_DOCS"]["FULL_VALUE"])) ? " style='display:none;'" : "";?>>
			<?if(!empty($arResult["PROPERTIES"]["FILES_DOCS"]["FULL_VALUE"])):?>
				<div class="catalog-detail-files-docs"><!--
				---><?foreach($arResult["PROPERTIES"]["FILES_DOCS"]["FULL_VALUE"] as $key => $arDoc):?><!--
					---><div class="files-docs-item-cont">
							<a class="files-docs-item" href="<?=$arDoc['SRC']?>" target="_blank">
								<div class="files-docs-icon">
									<?if($arDoc["TYPE"] == "doc" || $arDoc["TYPE"] == "docx" || $arDoc["TYPE"] == "rtf"):?>
										<i class="fa fa-file-word-o"></i>
									<?elseif($arDoc["TYPE"] == "xls" || $arDoc["TYPE"] == "xlsx"):?>
										<i class="fa fa-file-excel-o"></i>
									<?elseif($arDoc["TYPE"] == "pdf"):?>
										<i class="fa fa-file-pdf-o"></i>
									<?elseif($arDoc["TYPE"] == "rar" || $arDoc["TYPE"] == "zip" || $arDoc["TYPE"] == "gzip"):?>
										<i class="fa fa-file-archive-o"></i>
									<?elseif($arDoc["TYPE"] == "jpg" || $arDoc["TYPE"] == "jpeg" || $arDoc["TYPE"] == "png" || $arDoc["TYPE"] == "gif"):?>
										<i class="fa fa-file-image-o"></i>
									<?elseif($arDoc["TYPE"] == "ppt" || $arDoc["TYPE"] == "pptx"):?>
										<i class="fa fa-file-powerpoint-o"></i>
									<?elseif($arDoc["TYPE"] == "txt"):?>
										<i class="fa fa-file-text-o"></i>
									<?else:?>
										<i class="fa fa-file-o"></i>
									<?endif;?>
								</div>
								<div class="files-docs-block">
									<span class="files-docs-name"><?=!empty($arDoc["DESCRIPTION"]) ? $arDoc["DESCRIPTION"] : $arDoc["NAME"]?></span>
									<span class="files-docs-size"><?=GetMessage("CATALOG_ELEMENT_SIZE").$arDoc["SIZE"]?></span>
								</div>
							</a>
						</div><!--	
				---><?endforeach;?><!--
			---></div>
			<?endif;?>
		</div>
		<?/***REVIEWS_TAB***/?>
		<div class="box" id="catalog-reviews-to"></div>
		<?/***STORES_TAB***/?>
		<div class="box"<?=($arParams["USE_STORE"] != "Y" ? " style='display:none;'" : (isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"]) ? ($arSetting["OFFERS_VIEW"]["VALUE"] == "LIST" ? " style='display:none;'" : "") : ""));?>>
			<div id="<?=$arItemIDs['STORE'];?>"></div>
		</div>
	</div>	
	<div class="clr"></div>
</div>

<?if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"])) {
	$arJSParams = array(
		"CONFIG" => array(
			"USE_CATALOG" => $arResult["CATALOG"],
		),
		"PRODUCT_TYPE" => $arResult["CATALOG_TYPE"],
		"VISUAL" => array(
			"ID" => $arItemIDs["ID"],
			"PICT_ID" => $arItemIDs["PICT"],
			"PRICE_ID" => $arItemIDs["PRICE"],
			"BUY_ID" => $arItemIDs["BUY"],
			"DELAY_ID" => $arItemIDs["DELAY"],
			"ARTICLE_ID" => $arItemIDs["ARTICLE"],
			"PROPERTIES_ID" => $arItemIDs["PROPERTIES"],
			"CONSTRUCTOR_ID" => $arItemIDs["CONSTRUCTOR"],
			"STORE_ID" => $arItemIDs["STORE"],
			"TREE_ID" => $arItemIDs["PROP_DIV"],
			"TREE_ITEM_ID" => $arItemIDs["PROP"],
		),
		"PRODUCT" => array(
			"ID" => $arResult["ID"],
			"NAME" => $arResult["~NAME"],
			"PICT" => is_array($arResult["PREVIEW_IMG"]) ? $arResult["PREVIEW_IMG"] : array("SRC" => SITE_TEMPLATE_PATH."/images/no-photo.jpg", "WIDTH" => 150, "HEIGHT" => 150),
		),
		"OFFERS_VIEW" => $arSetting["OFFERS_VIEW"]["VALUE"],
		"OFFERS" => $arResult["JS_OFFERS"],
		"OFFER_SELECTED" => $arResult["OFFERS_SELECTED"],
		"TREE_PROPS" => $arSkuProps
	);	
} else {
	$arJSParams = array(
		"CONFIG" => array(
			"USE_CATALOG" => $arResult["CATALOG"]
		),
		"PRODUCT_TYPE" => $arResult["CATALOG_TYPE"],	
		"VISUAL" => array(
			"ID" => $arItemIDs["ID"],
			"BTN_BUY_ID" => $arItemIDs["BTN_BUY"],
		),
		"PRODUCT" => array(
			"ID" => $arResult["ID"],
			"NAME" => $arResult["~NAME"],
			"PICT" => is_array($arResult["PREVIEW_IMG"]) ? $arResult["PREVIEW_IMG"] : array("SRC" => SITE_TEMPLATE_PATH."/images/no-photo.jpg", "WIDTH" => 150, "HEIGHT" => 150),
		)
	);	
}

if(isset($arResult["SELECT_PROPS"]) && !empty($arResult["SELECT_PROPS"])) {
	$arJSParams["VISUAL"]["SELECT_PROP_ID"] = $arItemIDs["SELECT_PROP_DIV"];
	$arJSParams["VISUAL"]["SELECT_PROP_ITEM_ID"] = $arItemIDs["SELECT_PROP"];
	$arJSParams["SELECT_PROPS"] = $arSelProps;
}?>

<script type="text/javascript">
	var <?=$strObName;?> = new JCCatalogElement(<?=CUtil::PhpToJSObject($arJSParams, false, true);?>);	
	BX.message({			
		ADDITEMINCART_ADDED: "<?=GetMessageJS('CATALOG_ELEMENT_ADDED')?>",
		POPUP_WINDOW_TITLE: "<?=GetMessageJS('CATALOG_ELEMENT_ADDITEMINCART_TITLE')?>",			
		POPUP_WINDOW_BTN_CLOSE: "<?=GetMessageJS('CATALOG_ELEMENT_ADDITEMINCART_BTN_CLOSE')?>",
		POPUP_WINDOW_BTN_ORDER: "<?=GetMessageJS('CATALOG_ELEMENT_ADDITEMINCART_BTN_ORDER')?>",
		SITE_ID: "<?=SITE_ID;?>",
		SITE_DIR: "<?=SITE_DIR?>"
	});
</script>