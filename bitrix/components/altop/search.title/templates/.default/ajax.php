<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $arSetting;

/***JS***/?>
<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$(".search_close").click(function() {
			$(".title-search-result").fadeOut(300);
		});
		$(this).keydown(function(eventObject) {
			if(eventObject.which == 27)
				$(".title-search-result").fadeOut(300);
		});
	});
	//]]>
</script>

<?/***CATALOG_SEARCH***/
if(!empty($arResult["CATEGORIES"])):?>
	<a href="javascript:void(0)" class="search_close"><i class="fa fa-times"></i></a>		
	<div id="catalog_search">
		<?foreach($arResult["CATEGORIES"] as $category_id => $arCategory):
			foreach($arCategory["ITEMS"] as $i => $arItem):
				$strMainID = $this->GetEditAreaId($arItem["ITEM_ID"]);
				$arItemIDs = array(
					"ID" => $strMainID,
					"BTN_BUY" => $strMainID."_btn_buy"
				);						
				if($category_id === "all"):
					/***SEARCH_ALL***/
					if($arParams["SHOW_ALL_RESULTS"]=="Y"):?>
						<a class="search_all" href="<?=$arItem['URL']?>"><?=$arItem["NAME"]?></a>
					<?endif;
				elseif(isset($arItem["ICON"])):
					/***SEARCH_ITEM***/?>
					<div class="tvr_search">						
						<?/***ITEM_IMAGE***/?>
						<a class="image" href="<?=$arItem['URL']?>">
							<?if(!empty($arItem["PICTURE"]["SRC"])):?>
								<img src="<?=$arItem['PICTURE']['SRC']?>" width="<?=$arItem['PICTURE']['WIDTH']?>" height="<?=$arItem['PICTURE']['HEIGHT']?>" alt="<?=$arItem['NAME']?>" />
							<?elseif(!empty($arItem["PREVIEW_PICTURE"]["SRC"])):?>
								<img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" width="<?=$arItem['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$arItem['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$arItem['NAME']?>" />
							<?else:?>
								<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="62" height="62" alt="<?=$arItem['NAME']?>" />
							<?endif?>
						</a>
						<div class="<?if(!empty($arItem['PRICES']) || !empty($arItem['TOTAL_OFFERS']['MIN_PRICE'])): echo 'item_'; else: echo 'cat_'; endif;?>title">
							<?/***ITEM_ARTICLE***/
							if(!empty($arItem["PROPERTIES"]["ARTNUMBER"]["VALUE"])):?>
								<span class="article"><?=GetMessage("CATALOG_ELEMENT_ARTNUMBER").$arItem["PROPERTIES"]["ARTNUMBER"]["VALUE"];?></span>
							<?endif;
							/***ITEM_TITLE***/?>
							<a href="<?=$arItem['URL']?>"><?=$arItem["NAME"]?></a>
							<?/***ITEM_PROPERTIES***/
							if(!empty($arItem["DISPLAY_PROPERTIES"])):?>
								<div class="properties">
									<?foreach($arItem["DISPLAY_PROPERTIES"] as $k => $v):?>
										<span class="property"><?=$v["NAME"].": ".strip_tags($v["DISPLAY_VALUE"])?></span>
									<?endforeach;?>
								</div>
							<?endif;?>
						</div>						
						<?/***TOTAL_OFFERS_ITEM_PRICE***/
						if($arParams["SHOW_PRICE"]=="Y"):
							/***TOTAL_OFFERS_PRICE***/
							if(isset($arItem["OFFERS"]) && !empty($arItem["OFFERS"])):
								$price = CCurrencyLang::GetCurrencyFormat($arItem["TOTAL_OFFERS"]["MIN_PRICE"]["CURRENCY"], "ru");
								if(empty($price["THOUSANDS_SEP"])):
									$price["THOUSANDS_SEP"] = " ";
								endif;
								$price["REFERENCE_DECIMALS"] = $price["DECIMALS"];
								if($price["HIDE_ZERO"] == "Y"):
									if($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):
										if(round($arItem["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_VALUE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $price["DECIMALS"]) == round($arItem["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_VALUE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], 0)):
											$price["REFERENCE_DECIMALS"] = 0;
										endif;
									endif;
									if(round($arItem["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_VALUE"], $price["DECIMALS"]) == round($arItem["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_VALUE"], 0)):
										$price["DECIMALS"] = 0;
									endif;
								endif;
								$currency = str_replace("# ", " ", $price["FORMAT_STRING"]);?>

								<div class="search_price">
									<?if($arItem["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_VALUE"] <= 0):?>
										<span class="no-price">											
											<span class="unit">
												<?=GetMessage("CATALOG_ELEMENT_NO_PRICE")?>
												<br />
												<span><?=(!empty($arItem["TOTAL_OFFERS"]["MIN_PRICE"]["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arItem["TOTAL_OFFERS"]["MIN_PRICE"]["CATALOG_MEASURE_NAME"] : "";?></span>
											</span>
										</span>													
									<?else:?>										
										<span class="price">
											<?=($arItem["TOTAL_OFFERS"]["FROM"] == "Y") ? "<span class='from'>".GetMessage("CATALOG_ELEMENT_FROM")."</span>" : "";?>
											<?=number_format($arItem["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);
											if($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
												<span class="price-reference">
													<?=number_format($arItem["TOTAL_OFFERS"]["MIN_PRICE"]["DISCOUNT_VALUE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $price["REFERENCE_DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
												</span>
											<?endif;?>
											<span class="unit">												
												<?=$currency?>
												<span><?=(!empty($arItem["TOTAL_OFFERS"]["MIN_PRICE"]["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arItem["TOTAL_OFFERS"]["MIN_PRICE"]["CATALOG_MEASURE_NAME"] : "";?></span>
											</span>											
										</span>									
									<?endif;?>
								</div>
							<?/***ITEM_PRICE***/
							else:
								foreach($arItem["PRICES"] as $code=>$arPrice):
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
											$currency = str_replace("# ", " ", $price["FORMAT_STRING"]);?>

											<div class="search_price">
												<?if($arPrice["DISCOUNT_VALUE"] <= 0):
													$arItem["ASK_PRICE"]=1;?>
													<span class="no-price">
														<span class="unit">
															<?=GetMessage("CATALOG_ELEMENT_NO_PRICE")?>
															<br />
															<span><?=(!empty($arItem["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arItem["CATALOG_MEASURE_NAME"] : "";?></span>
														</span>
													</span>																
												<?else:?>													
													<span class="price">
														<?=number_format($arPrice["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);
														if($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
															<span class="price-reference">
																<?=number_format($arPrice["DISCOUNT_VALUE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $price["REFERENCE_DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
															</span>
														<?endif;?>
														<span class="unit">
															<?=$currency?>
															<span><?=(!empty($arItem["CATALOG_MEASURE_NAME"])) ? GetMessage("CATALOG_ELEMENT_UNIT")." ".$arItem["CATALOG_MEASURE_NAME"] : "";?></span>
														</span>														
													</span>
												<?endif;?>
											</div>
										
										<?endif;
									endif;
								endforeach;
							endif;
						endif;						
						/***OFFERS_ITEM_BUY***/
						if($arParams["SHOW_ADD_TO_CART"]=="Y"):
							/***OFFERS_BUY***/
							if(isset($arItem["OFFERS"]) && !empty($arItem["OFFERS"])):?>
								<div class="buy_more">
									<div class="add2basket_block">
										<form action="<?=$APPLICATION->GetCurPage()?>" class="add2basket_form">
											<a href="javascript:void(0)" class="minus" onclick="if (BX('quantity_search_<?=$arItem["ITEM_ID"]?>').value > <?=$arItem["TOTAL_OFFERS"]["MIN_PRICE"]["CATALOG_MEASURE_RATIO"]?>) BX('quantity_search_<?=$arItem["ITEM_ID"]?>').value = parseFloat(BX('quantity_search_<?=$arItem["ITEM_ID"]?>').value)-<?=$arItem["TOTAL_OFFERS"]["MIN_PRICE"]["CATALOG_MEASURE_RATIO"]?>;"><span>-</span></a>
											<input type="text" id="quantity_search_<?=$arItem['ITEM_ID']?>" name="quantity" class="quantity" value="<?=$arItem['TOTAL_OFFERS']['MIN_PRICE']['CATALOG_MEASURE_RATIO']?>"/>
											<a href="javascript:void(0)" class="plus" onclick="BX('quantity_search_<?=$arItem["ITEM_ID"]?>').value = parseFloat(BX('quantity_search_<?=$arItem["ITEM_ID"]?>').value)+<?=$arItem["TOTAL_OFFERS"]["MIN_PRICE"]["CATALOG_MEASURE_RATIO"]?>;"><span>+</span></a>			
											<button type="button" class="btn_buy" name="add2basket" value="<?=GetMessage('CATALOG_ELEMENT_ADD_TO_CART')?>" onclick="OpenPropsPopupSearch('<?=$arItemIDs["ID"]?>', '<?=$arItem["ITEM_ID"]?>'<?=($arSetting["OFFERS_VIEW"]["VALUE"] == "LIST" ? ", true" : "");?>);"><i class="fa fa-shopping-cart"></i></button>
										</form>
									</div>
								</div>
							<?/***ITEM_BUY***/
							else:
								if($arItem["CAN_BUY"]):?>
									<div class="buy_more">
										<div class="add2basket_block">
											<?if($arItem["ASK_PRICE"]):?>
												<a class="btn_buy apuo" id="ask_price_anch_search_<?=$arItem['ITEM_ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-comment-o"></i><span><?=GetMessage("CATALOG_ELEMENT_ASK_PRICE_SHORT")?></span></a>
												<?if(!empty($arItem["DISPLAY_PROPERTIES"])):
													$properties = array();
													foreach($arItem["DISPLAY_PROPERTIES"] as $propItem) {
														$properties[] = $propItem["NAME"].": ".strip_tags($propItem["DISPLAY_VALUE"]);
													}
													$properties = implode("; ", $properties);
													$item_name = strip_tags($arItem["NAME"])." (".$properties.")";
												else:
													$item_name = strip_tags($arItem["NAME"]);
												endif;?>
												<?$APPLICATION->IncludeComponent("altop:ask.price", "",
													Array(
														"ELEMENT_ID" => "search_".$arItem["ITEM_ID"],		
														"ELEMENT_NAME" => $item_name,
														"SELECT_PROP_DIV" => "",
														"EMAIL_TO" => "",				
														"REQUIRED_FIELDS" => array("NAME", "EMAIL", "TEL"),
													),
													false
												);?>
											<?else:?>
												<?if(isset($arItem["SELECT_PROPS"]) && !empty($arItem["SELECT_PROPS"])):?>
													<form action="<?=$APPLICATION->GetCurPage()?>" class="add2basket_form">
												<?else:?>
													<form action="<?=SITE_DIR?>ajax/add2basket.php" class="add2basket_search_form">
												<?endif;?>
													<a href="javascript:void(0)" class="minus" onclick="if (BX('quantity_search_<?=$arItem["ITEM_ID"]?>').value > <?=$arItem["CATALOG_MEASURE_RATIO"]?>) BX('quantity_search_<?=$arItem["ITEM_ID"]?>').value = parseFloat(BX('quantity_search_<?=$arItem["ITEM_ID"]?>').value)-<?=$arItem["CATALOG_MEASURE_RATIO"]?>;"><span>-</span></a>
													<input type="text" id="quantity_search_<?=$arItem['ITEM_ID']?>" name="quantity" class="quantity" value="<?=$arItem['CATALOG_MEASURE_RATIO']?>"/>
													<a href="javascript:void(0)" class="plus" onclick="BX('quantity_search_<?=$arItem["ITEM_ID"]?>').value = parseFloat(BX('quantity_search_<?=$arItem["ITEM_ID"]?>').value)+<?=$arItem["CATALOG_MEASURE_RATIO"]?>;"><span>+</span></a>
													<?if(!isset($arItem["SELECT_PROPS"]) || empty($arItem["SELECT_PROPS"])):?>
														<input type="hidden" name="ID" value="<?=$arItem['ITEM_ID']?>"/>
														<?$props = array();
														if(!empty($arItem["PROPERTIES"]["ARTNUMBER"]["VALUE"])):			
															$props[] = array(
																"NAME" => $arItem["PROPERTIES"]["ARTNUMBER"]["NAME"],
																"CODE" => $arItem["PROPERTIES"]["ARTNUMBER"]["CODE"],
																"VALUE" => $arItem["PROPERTIES"]["ARTNUMBER"]["VALUE"]
															);												
														endif;
														if(!empty($arItem["DISPLAY_PROPERTIES"])):										
															foreach($arItem["DISPLAY_PROPERTIES"] as $propOffer) {
																$props[] = array(
																	"NAME" => $propOffer["NAME"],
																	"CODE" => $propOffer["CODE"],
																	"VALUE" => strip_tags($propOffer["DISPLAY_VALUE"])
																);
															}
														endif;
														$props = !empty($props) ? strtr(base64_encode(addslashes(gzcompress(serialize($props),9))), '+/=', '-_,') : "";?>
														<input type="hidden" name="PROPS" value="<?=$props?>" />
													<?endif;?>															
													<button type="button" class="btn_buy" name="add2basket" value="<?=GetMessage('CATALOG_ELEMENT_ADD_TO_CART')?>"<?=(isset($arItem["SELECT_PROPS"]) && !empty($arItem["SELECT_PROPS"]) ? " onclick=\"OpenPropsPopupSearch('".$arItemIDs["ID"]."', '".$arItem["ITEM_ID"]."')\"" : " id='".$arItemIDs["BTN_BUY"]."'");?>><i class="fa fa-shopping-cart"></i></button>
												</form>
											<?endif;?>
										</div>
									</div>
								<?elseif(!$arItem["CAN_BUY"]):
									if(!empty($arItem["PRICES"])):?>
										<div class="buy_more">
											<div class="add2basket_block">
												<a class="btn_buy apuo" id="order_anch_search_<?=$arItem['ITEM_ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-clock-o"></i><span><?=GetMessage("CATALOG_ELEMENT_UNDER_ORDER")?></span></a>
												<?if(!empty($arItem["DISPLAY_PROPERTIES"])):
													$properties = array();
													foreach($arItem["DISPLAY_PROPERTIES"] as $propItem) {
														$properties[] = $propItem["NAME"].": ".strip_tags($propItem["DISPLAY_VALUE"]);
													}
													$properties = implode("; ", $properties);
													$item_name = strip_tags($arItem["NAME"])." (".$properties.")";
												else:
													$item_name = strip_tags($arItem["NAME"]);
												endif;?>
												<?$APPLICATION->IncludeComponent("altop:ask.price", "order",
													Array(
														"ELEMENT_ID" => "search_".$arItem["ITEM_ID"],		
														"ELEMENT_NAME" => $item_name,
														"SELECT_PROP_DIV" => "",
														"EMAIL_TO" => "",				
														"REQUIRED_FIELDS" => array("NAME", "TEL", "TIME"),
													),
													false,
													array("HIDE_ICONS" => "Y")
												);?>
											</div>
										</div>
									<?endif;												
								endif;
							endif;
						endif;?>										
					</div>							
				<?endif;
			endforeach;
		endforeach;?>
	</div>
	<?/***POPUP_JS***/	
	$popupParams["MESS"] = array(	
		"CATALOG_ELEMENT_ARTNUMBER" => GetMessage("CATALOG_ELEMENT_ARTNUMBER"),
		"CATALOG_ELEMENT_NO_PRICE" => GetMessage("CATALOG_ELEMENT_NO_PRICE"),
		"CATALOG_ELEMENT_SKIDKA" => GetMessage("CATALOG_ELEMENT_SKIDKA"),
		"CATALOG_ELEMENT_UNIT" => GetMessage("CATALOG_ELEMENT_UNIT"),
		"CATALOG_ELEMENT_AVAILABLE" => GetMessage("CATALOG_ELEMENT_AVAILABLE"),
		"CATALOG_ELEMENT_NOT_AVAILABLE" => GetMessage("CATALOG_ELEMENT_NOT_AVAILABLE"),
		"CATALOG_ELEMENT_ADD_TO_CART" => GetMessage("CATALOG_ELEMENT_ADD_TO_CART"),
		"CATALOG_ELEMENT_ADDED" => GetMessage("CATALOG_ELEMENT_ADDED"),
		"CATALOG_ELEMENT_ASK_PRICE_FULL" => GetMessage("CATALOG_ELEMENT_ASK_PRICE_FULL"),
		"CATALOG_ELEMENT_ASK_PRICE_SHORT" => GetMessage("CATALOG_ELEMENT_ASK_PRICE_SHORT"),
		"CATALOG_ELEMENT_UNDER_ORDER" => GetMessage("CATALOG_ELEMENT_UNDER_ORDER"),									
		"CATALOG_ELEMENT_OFFERS_LIST" => GetMessage("CATALOG_ELEMENT_OFFERS_LIST"),
		"CATALOG_ELEMENT_OFFERS_LIST_IMAGE" => GetMessage("CATALOG_ELEMENT_OFFERS_LIST_IMAGE"),
		"CATALOG_ELEMENT_OFFERS_LIST_NAME" => GetMessage("CATALOG_ELEMENT_OFFERS_LIST_NAME"),
		"CATALOG_ELEMENT_OFFERS_LIST_PRICE" => GetMessage("CATALOG_ELEMENT_OFFERS_LIST_PRICE"),
		"CATALOG_ELEMENT_BOC_SHORT" => GetMessage("CATALOG_ELEMENT_BOC_SHORT")
	);	
	$popupParams["SKU_PROPS"] = strtr(base64_encode(addslashes(gzcompress(serialize($arResult["SKU_PROPS"]),9))), '+/=', '-_,');	
	$popupParams["PARAMS"] = strtr(base64_encode(addslashes(gzcompress(serialize($arParams),9))), '+/=', '-_,');	
	$popupParams["SETTINGS"] = strtr(base64_encode(addslashes(gzcompress(serialize($arSetting),9))), '+/=', '-_,');	
	foreach($arResult["CATEGORIES"] as $category_id => $arCategory):
		foreach($arCategory["ITEMS"] as $key => $arItem):
			$strMainID = $this->GetEditAreaId($arItem["ITEM_ID"]);
			$arItemIDs = array(
				"ID" => $strMainID,				
				"BTN_BUY" => $strMainID."_btn_buy"
			);
			$strObName = "ob".preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);			
			if($arItem["OFFERS"] || $arItem["SELECT_PROPS"]):
				/***POPUP***/
				$popupParams["STR_MAIN_ID"] = $strMainID;
				$popupParams["ELEMENT"] = strtr(base64_encode(addslashes(gzcompress(serialize($arItem),9))), '+/=', '-_,');?>
				<script type="text/javascript">
					if(!window.arSetParams) {
						window.arSetParams = [{'<?=$arItemIDs["ID"]?>' : <?=CUtil::PhpToJSObject($popupParams)?>}];
					} else {
						window.arSetParams.push({'<?=$arItemIDs["ID"]?>' : <?=CUtil::PhpToJSObject($popupParams)?>});
					}
				</script>
			<?else:
				/***JS***/
				$arJSParams = array(
					"PRODUCT_TYPE" => $arItem["CATALOG_TYPE"],
					"VISUAL" => array(
						"ID" => $arItemIDs["ID"],
						"BTN_BUY_ID" => $arItemIDs["BTN_BUY"],
					),
					"PRODUCT" => array(
						"ID" => $arItem["ITEM_ID"],
						"NAME" => $arItem["NAME"],
						"PICT" => is_array($arItem["PICTURE_150"]) ? $arItem["PICTURE_150"] : array("SRC" => SITE_TEMPLATE_PATH."/images/no-photo.jpg", "WIDTH" => 150, "HEIGHT" => 150),
					)
				);?>
				<script type="text/javascript">
					var <?=$strObName;?> = new JCCatalogSectionSearch(<?=CUtil::PhpToJSObject($arJSParams, false, true);?>);
				</script>			
			<?endif;
		endforeach;
	endforeach;
	/***JS***/?>	
	<script type="text/javascript">
		BX.message({			
			ADDITEMINCART_ADDED: "<?=GetMessageJS('CATALOG_ELEMENT_ADDED')?>",
			POPUP_WINDOW_TITLE: "<?=GetMessageJS('CATALOG_ELEMENT_ADDITEMINCART_TITLE')?>",			
			POPUP_WINDOW_BTN_CLOSE: "<?=GetMessageJS('CATALOG_ELEMENT_ADDITEMINCART_BTN_CLOSE')?>",
			POPUP_WINDOW_BTN_ORDER: "<?=GetMessageJS('CATALOG_ELEMENT_ADDITEMINCART_BTN_ORDER')?>",
			SITE_DIR: "<?=SITE_DIR?>"
		});
		function OpenPropsPopupSearch(visual_id, element_id, offers_list) {
			offers_list = offers_list || false;

			if(window.arSetParams) {
				for(var obj in window.arSetParams) {
					if(window.arSetParams.hasOwnProperty(obj)) {
						for(var obj2 in window.arSetParams[obj]) {
							if(window.arSetParams[obj].hasOwnProperty(obj2)) {
								if(obj2 == visual_id)
									var curSetParams = window.arSetParams[obj][obj2]
							}
						}
					}
				}
			}
			BX.PropsSet =
			{			
				popup: null,
				arParams: {}
			};
			BX.PropsSet.popup = BX.PopupWindowManager.create(visual_id, null, {
				autoHide: true,
				offsetLeft: 0,
				offsetTop: 0,
				overlay: {
					opacity: 100
				},
				draggable: false,
				closeByEsc: false,
				closeIcon: { right : "-10px", top : "-10px"},
				titleBar: {content: BX.create("span", {html: "<?=GetMessage('CATALOG_ELEMENT_MORE_OPTIONS')?>"})},
				content: "<div class='popup-window-wait'><i class='fa fa-spinner fa-pulse'></i></div>",
				events: {
					onAfterPopupShow: function()
					{													
						if(!BX(visual_id + "_info")) {
							BX.ajax.post(
								'<?=$this->GetFolder();?>/popup.php',
								{							
									arParams:curSetParams
								},
								BX.delegate(function(result)
								{
									var wndScroll = BX.GetWindowScrollPos(),
										wndSize = BX.GetWindowInnerSize(),
										setWindow,
										popupTop;
									
									this.setContent(result);

									setWindow = BX(visual_id);
									if(!!setWindow)
									{
										popupTop = wndScroll.scrollTop + (wndSize.innerHeight - setWindow.offsetHeight)/2;
										setWindow.style.left = (wndSize.innerWidth - setWindow.offsetWidth)/2 + "px";
										setWindow.style.top = popupTop > 0 ? popupTop + "px" : 0;
									}
								},
								this)
							);
						} else {
							qntItems = BX.findChildren(BX(visual_id), {className: "quantity"}, true);
							if(!!qntItems && 0 < qntItems.length) {
								for(i = 0; i < qntItems.length; i++) {					
									qntItems[i].value = BX("quantity_search_" + element_id).value;
								}
							}
						}
					}
				}
			});			
			BX.addClass(BX(visual_id), "pop-up more_options");
			if(offers_list == true) {
				BX.addClass(BX(visual_id), "offers-list");
			}
			close = BX.findChildren(BX(visual_id), {className: "popup-window-close-icon"}, true);
			if(!!close && 0 < close.length) {
				for(i = 0; i < close.length; i++) {					
					close[i].innerHTML = "<i class='fa fa-times'></i>";
				}
			}
			BX.PropsSet.popup.show();
		}		
	</script>
<?else:?>
	<a href="javascript:void(0)" class="pop-up-close search_close"><i class="fa fa-times"></i></a>	
	<div id="catalog_search_empty">
		<?=GetMessage("CATALOG_EMPTY_RESULT")?>
	</div>			
<?endif;?>