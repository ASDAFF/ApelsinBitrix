<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$frame = $this->createFrame("discount_left")->begin("");
	if(count($arResult["ITEMS"]) > 0):
		global $arSetting;?>
		<script type="text/javascript">
			//<![CDATA[
			$(function() {
				$('.discountSlider').anythingSlider({
					'theme': "discount-left",
					'mode': 'horiz',
					'expand': false,
					'resizeContents': false,
					'easing': 'easeInOutExpo',
					'buildArrows': false,
					'buildNavigation': true,
					'buildStartStop': false,
					'hashTags': false,
					'autoPlay': true,
					'pauseOnHover': true,
					'delay': 3000,
				});				
			});
			//]]>
		</script>		
		<div class="discount_left<?=($arSetting['REFERENCE_PRICE']['VALUE'] == 'Y' && !empty($arSetting['REFERENCE_PRICE_COEF']['VALUE']) ? ' reference' : '');?>">
			<ul class="discountSlider">
				<?foreach($arResult["ITEMS"] as $key => $arItem):					
					
					$bPicture = is_array($arItem["PREVIEW_IMG"]);

					$sticker = "";
					if(array_key_exists("PROPERTIES", $arItem) && is_array($arItem["PROPERTIES"])):
						/***NEW***/
						if(array_key_exists("NEWPRODUCT", $arItem["PROPERTIES"]) && !$arItem["PROPERTIES"]["NEWPRODUCT"]["VALUE"] == false):
							$sticker .= "<span class='new'>".GetMessage("CATALOG_ELEMENT_NEWPRODUCT")."</span>";
						endif;
						/***HIT***/
						if(array_key_exists("SALELEADER", $arItem["PROPERTIES"]) && !$arItem["PROPERTIES"]["SALELEADER"]["VALUE"] == false):
							$sticker .= "<span class='hit'>".GetMessage("CATALOG_ELEMENT_SALELEADER")."</span>";
						endif;
						/***DISCOUNT***/
						if(isset($arItem["OFFERS"]) && !empty($arItem["OFFERS"])):						
							if($arItem["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"] > 0):
								$sticker .= "<span class='discount'>-".$arItem["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"]."%</span>";
							else:
								if(array_key_exists("DISCOUNT", $arItem["PROPERTIES"]) && !$arItem["PROPERTIES"]["DISCOUNT"]["VALUE"] == false):
									$sticker .= "<span class='discount'>%</span>";
								endif;
							endif;
						else:
							if($arItem["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"] > 0):
								$sticker .= "<span class='discount'>-".$arItem["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"]."%</span>";
							else:
								if(array_key_exists("DISCOUNT", $arItem["PROPERTIES"]) && !$arItem["PROPERTIES"]["DISCOUNT"]["VALUE"] == false):
									$sticker .= "<span class='discount'>%</span>";
								endif;
							endif;
						endif;
					endif;?>

					<li>
						<div class="item-image">
							<?/***PICTURE***/?>									
							<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
								<?if($bPicture):?>
									<img class="item_img" src="<?=$arItem['PREVIEW_IMG']['SRC']?>" width="<?=$arItem['PREVIEW_IMG']['WIDTH']?>" height="<?=$arItem['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arItem['NAME']?>" />
								<?else:?>
									<img class="item_img" src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="150" height="150" alt="<?=$arItem['NAME']?>" />
								<?endif;?>								
								<span class="sticker">
									<?=$sticker?>
								</span>
								<?if(!empty($arItem["PROPERTIES"]["MANUFACTURER"]["PREVIEW_IMG"]["SRC"])):?>
									<img class="manufacturer" src="<?=$arItem['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['SRC']?>" width="<?=$arItem['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['WIDTH']?>" height="<?=$arItem['PROPERTIES']['MANUFACTURER']['PREVIEW_IMG']['HEIGHT']?>" alt="<?=$arItem['PROPERTIES']['MANUFACTURER']['NAME']?>" />
								<?endif;?>
							</a>							
						</div>
						<a class="item-title" href="<?=$arItem['DETAIL_PAGE_URL']?>" title="<?=$arItem['NAME']?>">
							<?=$arItem["NAME"]?>
						</a>
						<div class="item-price">
							<?/***PRICE***/
							if(isset($arItem["OFFERS"]) && !empty($arItem["OFFERS"])):
								$price = CCurrencyLang::GetCurrencyFormat($arItem["TOTAL_OFFERS"]["MIN_PRICE"]["CURRENCY"], "ru");
								if(empty($price["THOUSANDS_SEP"])):
									$price["THOUSANDS_SEP"] = " ";
								endif;								
								if($price["HIDE_ZERO"] == "Y"):									
									if(round($arItem["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_VALUE"], $price["DECIMALS"]) == round($arItem["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_VALUE"], 0)):
										$price["DECIMALS"] = 0;
									endif;
								endif;
								$currency = str_replace("# ", " ", $price["FORMAT_STRING"]);
									
								if($arItem["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_VALUE"] <= 0):?>									
									<span class="catalog-item-no-price">
										<?=GetMessage("CATALOG_ELEMENT_NO_PRICE")?>											
									</span>									
								<?else:?>
									<span class="catalog-item-price">
										<?=($arItem["TOTAL_OFFERS"]["FROM"] == "Y") ? "<span class='from'>".GetMessage("CATALOG_ELEMENT_FROM")."</span>" : "";?>
										<?=number_format($arItem["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
										<span><?=$currency?></span>
										<?if($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
											<span class="catalog-item-price-reference">
												<?=CCurrencyLang::CurrencyFormat($arItem["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_VALUE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arItem["TOTAL_OFFERS"]["MIN_PRICE"]["CURRENCY"], true);?>
											</span>
										<?endif;?>
									</span>
									<?if($arItem["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_VALUE"] < $arItem["TOTAL_OFFERS"]["MIN_PRICE"]["VALUE"]):?>
										<span class="catalog-item-price-old">
											<?=$arItem["TOTAL_OFFERS"]["MIN_PRICE"]["PRINT_VALUE"];?>
										</span>
									<?endif;															
								endif;
							else:
								foreach($arItem["PRICES"] as $code=>$arPrice):
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

											if($arPrice["DISCOUNT_VALUE"] <= 0):?>
												<span class="catalog-item-no-price">
													<?=GetMessage("CATALOG_ELEMENT_NO_PRICE")?>
												</span>
											<?else:?>
												<span class="catalog-item-price">
													<?=number_format($arPrice["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
													<span><?=$currency?></span>
													<?if($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
														<span class="catalog-item-price-reference">
															<?=CCurrencyLang::CurrencyFormat($arPrice["DISCOUNT_VALUE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arPrice["CURRENCY"], true);?>
														</span>
													<?endif;?>
												</span>
												<?if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
													<span class="catalog-item-price-old">
														<?=$arPrice["PRINT_VALUE"];?>
													</span>
												<?endif;											
											endif;											
										endif;
									endif;
								endforeach;
							endif;?>
						</div>
					</li>
				<?endforeach;?>
			</ul>
		</div>
	<?else:?>
		<div class="discount_left_empty"></div>
	<?endif;
$frame->end();?>