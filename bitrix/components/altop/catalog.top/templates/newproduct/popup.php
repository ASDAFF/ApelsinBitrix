<?define("NOT_CHECK_PERMISSIONS", true);
define("NO_KEEP_STATISTIC", true);
define('NO_AGENT_CHECK', true);
define("NO_AGENT_STATISTIC", true);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->ShowAjaxHead();
$APPLICATION->AddHeadScript("/bitrix/js/main/dd.js");

if(!CModule::IncludeModule("catalog"))
	return;

if(SITE_CHARSET != "utf-8")
	$_REQUEST["arParams"] = $APPLICATION->ConvertCharsetArray($_REQUEST["arParams"], "utf-8", SITE_CHARSET);

$arElement = unserialize(gzuncompress(stripslashes(base64_decode(strtr($_REQUEST["arParams"]["ELEMENT"], "-_,", "+/=")))));

if(!is_array($arElement))
	return;

$arResult["SKU_PROPS"] = unserialize(gzuncompress(stripslashes(base64_decode(strtr($_REQUEST["arParams"]["SKU_PROPS"], "-_,", "+/=")))));
$arMessage = $_REQUEST["arParams"]["MESS"];
$arParams = unserialize(gzuncompress(stripslashes(base64_decode(strtr($_REQUEST["arParams"]["PARAMS"], "-_,", "+/=")))));
$arSetting = unserialize(gzuncompress(stripslashes(base64_decode(strtr($_REQUEST["arParams"]["SETTINGS"], "-_,", "+/=")))));
$strMainID = $_REQUEST["arParams"]["STR_MAIN_ID"];
$arItemIDs = array(
	"ID" => $strMainID,
	"PICT" => $strMainID."_picture",
	"PRICE" => $strMainID."_price",
	"BUY" => $strMainID."_buy",
	"PROP_DIV" => $strMainID."_sku_tree",
	"PROP" => $strMainID."_prop_",
	"SELECT_PROP_DIV" => $strMainID."_propdiv",
	"SELECT_PROP" => $strMainID."_select_prop_",
	"BTN_BUY" => $strMainID."_btn_buy"
);
$strObName = "ob".preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

/***JS***/?>
<script type="text/javascript">
	BX.ready(function() {
		<?/***OFFERS_LIST_PROPS***/
		if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) && $arSetting["OFFERS_VIEW"]["VALUE"] == "LIST"):
			foreach($arElement["OFFERS"] as $key_off => $arOffer):?>
				props = BX.findChildren(BX("catalog-offer-item-new-<?=$arOffer['ID']?>"), {className: "catalog-item-prop"}, true);
				if(!!props && 0 < props.length) {
					for(i = 0; i < props.length; i++) {
						if(!BX.hasClass(props[i], "empty")) {
							BX("catalog-item-props-mob-new-<?=$arOffer['ID']?>").appendChild(BX.create(
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
		
		/***QUANTITY***/?>
		qntItems = BX.findChildren(BX("<?=$arItemIDs['ID']?>"), {className: "quantity"}, true);			
		if(!!qntItems && 0 < qntItems.length) {
			for(i = 0; i < qntItems.length; i++) {					
				qntItems[i].value = BX("quantity_new_<?=$arElement['ID']?>").value;
			}
		}
	});
</script>

<div id="<?=$strMainID?>_info" class="item_info">	
	<div class="item_image" id="<?=$arItemIDs['PICT']?>">
		<?/***OFFERS_IMAGE***/
		if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) && $arSetting["OFFERS_VIEW"]["VALUE"] != "LIST"):
			foreach($arElement["OFFERS"] as $key_off => $arOffer):?>
				<div id="img_new_<?=$arElement['ID']?>_<?=$arOffer['ID']?>" class="img <?=$arElement['ID']?> hidden">
					<?if(isset($arOffer["PREVIEW_IMG"])):?>
						<img src="<?=$arOffer['PREVIEW_IMG']['SRC']?>" width="<?=$arOffer['PREVIEW_IMG']["WIDTH"]?>" height="<?=$arOffer['PREVIEW_IMG']["HEIGHT"]?>" alt="<?=(isset($arOffer['NAME']) && !empty($arOffer['NAME']) ? $arOffer['NAME'] : $arElement['NAME']);?>"/>
					<?else:?>
						<img src="<?=$arElement["PREVIEW_IMG"]["SRC"]?>" width="<?=$arElement["PREVIEW_IMG"]["WIDTH"]?>" height="<?=$arElement["PREVIEW_IMG"]["HEIGHT"]?>" alt="<?=(isset($arOffer['NAME']) && !empty($arOffer['NAME']) ? $arOffer['NAME'] : $arElement['NAME']);?>"/>
					<?endif;?>
				</div>
			<?endforeach;
		/***ITEM_IMAGE***/
		else:?>
			<div class="img">
				<?if(isset($arElement["PREVIEW_IMG"])):?>
					<img src="<?=$arElement["PREVIEW_IMG"]["SRC"]?>" width="<?=$arElement["PREVIEW_IMG"]["WIDTH"]?>" height="<?=$arElement["PREVIEW_IMG"]["HEIGHT"]?>" alt="<?=$arElement["NAME"]?>"/>
				<?else:?>
					<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="150" height="150" alt="<?=$arElement['NAME']?>" />
				<?endif;?>
			</div>
		<?endif;
		/***ITEM_NAME***/?>
		<div class="item_name">
			<?=$arElement["NAME"]?>
		</div>
	</div>
	<div class="item_block<?=($arSetting['REFERENCE_PRICE']['VALUE'] == 'Y' && !empty($arSetting['REFERENCE_PRICE_COEF']['VALUE']) ? ' reference' : '').(isset($arElement['OFFERS']) && !empty($arElement['OFFERS']) && $arSetting['OFFERS_VIEW']['VALUE'] == 'LIST' ? ' offers-list' : '');?>">
		<?/***OFFERS_PROPS***/
		if($arSetting["OFFERS_VIEW"]["VALUE"] != "LIST"):
			if(!empty($arElement["OFFERS_PROP"])):?>
				<table class="offer_block" id="<?=$arItemIDs['PROP_DIV'];?>">
					<?$arSkuProps = array();
					foreach($arResult["SKU_PROPS"] as $arProp) {
						if(!isset($arElement["OFFERS_PROP"][$arProp["CODE"]]))
							continue;
						$arSkuProps[] = array(
							"ID" => $arProp["ID"],
							"SHOW_MODE" => $arProp["SHOW_MODE"]
						);?>
						<tr class="<?=$arProp['CODE']?>" id="<?=$arItemIDs['PROP'].$arProp['ID'];?>_cont">
							<td class="h3">
								<?=htmlspecialcharsex($arProp["NAME"]);?>:
							</td>
							<td class="props">
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
								<div class="clr"></div>
							</td>
						</tr>
					<?}
					unset($arProp);?>
				</table>
			<?endif;
		endif;
		/***SELECT_PROPS***/
		if(!empty($arElement["SELECT_PROPS"])):?>
			<table class="offer_block" id="<?=$arItemIDs['SELECT_PROP_DIV'];?>">
				<?$arSelProps = array();
				foreach($arElement["SELECT_PROPS"] as $key_prop => $arProp):
					$arSelProps[] = array(
						"ID" => $arProp["ID"]
					);?>
					<tr class="<?=$arProp['CODE']?>" id="<?=$arItemIDs['SELECT_PROP'].$arProp['ID'];?>">
						<td class="h3"><?=htmlspecialcharsex($arProp["NAME"]);?></td>
						<td class="props">												
							<ul class="<?=$arProp['CODE']?>">
								<?$props = array();
								foreach($arProp["DISPLAY_VALUE"] as $arOneValue) {
									$props[$key_prop] = array(
										"NAME" => $arProp["NAME"],
										"CODE" => $arProp["CODE"],
										"VALUE" => strip_tags($arOneValue)
									);
									$props[$key_prop] = strtr(base64_encode(addslashes(gzcompress(serialize($props[$key_prop]),9))), '+/=', '-_,');?>
									<li data-select-onevalue="<?=$props[$key_prop]?>">
										<span title="<?=$arOneValue;?>"><?=$arOneValue?></span>
									</li>
								<?}?>
							</ul>
							<div class="clr"></div>
						</td>
					</tr>
				<?endforeach;
				unset($arProp);?>
			</table>
		<?endif;		
		/***OFFERS_LIST***/		
		if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) && $arSetting["OFFERS_VIEW"]["VALUE"] == "LIST"):?>
			<div class="catalog-detail-offers-list">
				<div class="h3"><?=$arMessage["CATALOG_ELEMENT_OFFERS_LIST"]?></div>
				<div class="offers-items">
					<div class="thead">
						<div class="offers-items-image"><?=$arMessage["CATALOG_ELEMENT_OFFERS_LIST_IMAGE"]?></div>
						<div class="offers-items-name"><?=$arMessage["CATALOG_ELEMENT_OFFERS_LIST_NAME"]?></div>
						<?$i = 1;										
						foreach($arResult["SKU_PROPS"] as $arProp):											
							if(!isset($arElement["OFFERS_PROP"][$arProp["CODE"]]))
								continue;
							if($i > 3)
								continue;?>						
							<div class="offers-items-prop"><?=htmlspecialcharsex($arProp["NAME"]);?></div>
							<?$i++;											
						endforeach;
						unset($arProp);?>
						<div class="offers-items-price"></div>
						<div class="offers-items-buy"><?=$arMessage["CATALOG_ELEMENT_OFFERS_LIST_PRICE"]?></div>
					</div>
					<div class="tbody">
						<?foreach($arElement["OFFERS"] as $keyOffer => $arOffer):							
							$sticker = "";
							if($arOffer["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"] > 0) {
								$sticker .= "<span class='discount'>-".$arOffer["MIN_PRICE"]["DISCOUNT_DIFF_PERCENT"]."%</span>";	
							}?>
							<div class="catalog-item" id="catalog-offer-item-new-<?=$arOffer['ID']?>">
								<div class="catalog-item-info">							
									<?/***OFFER_IMAGE***/?>
									<div class="catalog-item-image-cont">
										<div class="catalog-item-image">
											<?if(!empty($arOffer["PREVIEW_IMG"]["SRC"])):?>
												<img src="<?=$arOffer['PREVIEW_IMG']['SRC']?>" width="<?=$arOffer['PREVIEW_IMG']['WIDTH']?>" height="<?=$arOffer['PREVIEW_IMG']['HEIGHT']?>" alt="<?=(isset($arOffer['NAME']) && !empty($arOffer['NAME'])) ? $arOffer['NAME'] : $arElement['NAME'];?>" />
											<?else:?>
												<img src="<?=$arElement['PREVIEW_IMG']['SRC']?>" width="<?=$arElement['PREVIEW_IMG']['WIDTH']?>" height="<?=$arElement['PREVIEW_IMG']['HEIGHT']?>" alt="<?=(isset($arOffer['NAME']) && !empty($arOffer['NAME'])) ? $arOffer['NAME'] : $arElement['NAME'];?>" />
											<?endif;?>
											<div class="sticker">
												<?=$sticker?>
											</div>
										</div>
									</div>
									<?/***OFFER_NAME_ARTNUMBER***/?>
									<div class="catalog-item-title">
										<?/***OFFER_NAME***/?>
										<span class="name"><?=(isset($arOffer["NAME"]) && !empty($arOffer["NAME"])) ? $arOffer["NAME"] : $arElement["NAME"];?></span>
										<?/***OFFER_ARTNUMBER***/?>
										<span class="article"><?=$arMessage["CATALOG_ELEMENT_ARTNUMBER"]?><?=!empty($arOffer["PROPERTIES"]["ARTNUMBER"]["VALUE"]) ? $arOffer["PROPERTIES"]["ARTNUMBER"]["VALUE"] : "-";?></span>
									</div>
									<?/***OFFER_PROPS***/
									$i = 1;
									foreach($arResult["SKU_PROPS"] as $arProp):									
										if(!isset($arElement["OFFERS_PROP"][$arProp["CODE"]]))
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
																<?=$arMessage["CATALOG_ELEMENT_NO_PRICE"]?>
																<br />
																<span><?=(!empty($arOffer["CATALOG_MEASURE_NAME"])) ? $arMessage["CATALOG_ELEMENT_UNIT"]." ".$arOffer["CATALOG_MEASURE_NAME"] : "";?></span>
															</span>
														</span>
													<?else:?>
														<span class="catalog-item-price">
															<?=number_format($arPrice["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
															<span class="unit">
																<?=$currency?>
																<span><?=(!empty($arOffer["CATALOG_MEASURE_NAME"])) ? $arMessage["CATALOG_ELEMENT_UNIT"]." ".$arOffer["CATALOG_MEASURE_NAME"] : "";?></span>
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
																<?=$arMessage['CATALOG_ELEMENT_SKIDKA']?>
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
										<div id="catalog-item-props-mob-new-<?=$arOffer['ID']?>" class="catalog-item-props-mob"></div>
									<?endif;
									/***OFFER_AVAILABILITY_BUY***/?>
									<div class="buy_more">
										<?/***OFFER_AVAILABILITY***/?>
										<div class="available">
											<?if($arOffer["CAN_BUY"]):?>
												<div class="avl">
													<i class="fa fa-check-circle"></i>
													<span>
														<?=$arMessage["CATALOG_ELEMENT_AVAILABLE"];
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
													<span><?=$arMessage["CATALOG_ELEMENT_NOT_AVAILABLE"]?></span>
												</div>
											<?endif;?>
										</div>
										<div class="clr"></div>											
										<?/***OFFER_BUY***/
										if($arOffer["CAN_BUY"]):
											if($arOffer["ASK_PRICE"]):?>
												<a class="btn_buy apuo" id="ask_price_anch_new_<?=$arOffer['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-comment-o"></i><span class="short"><?=$arMessage["CATALOG_ELEMENT_ASK_PRICE_SHORT"]?></span></a>
												<?$properties = false;
												foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer) {
													$properties[] = $propOffer["NAME"].": ".strip_tags($propOffer["DISPLAY_VALUE"]);
												}
												$properties = implode("; ", $properties);
												if(!empty($properties)):
													$offer_name = ((isset($arOffer["NAME"]) && !empty($arOffer["NAME"])) ? $arOffer["NAME"] : $arElement["NAME"])." (".$properties.")";
												else:
													$offer_name = (isset($arOffer["NAME"]) && !empty($arOffer["NAME"])) ? $arOffer["NAME"] : $arElement["NAME"];
												endif;?>
												<?$APPLICATION->IncludeComponent("altop:ask.price", "",
													Array(
														"ELEMENT_ID" => "new_".$arOffer["ID"],		
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
																<a href="javascript:void(0)" id="catalog-item-delay-new-<?=$arOffer['ID']?>" class="catalog-item-delay" onclick="return addToDelay('<?=$arOffer["ID"]?>', '<?=$arOffer["CATALOG_MEASURE_RATIO"]?>', '<?=$props?>', '', 'catalog-item-delay-new-<?=$arOffer["ID"]?>', '<?=SITE_DIR?>')" rel="nofollow"><i class="fa fa-heart-o"></i><i class="fa fa-check"></i></a>
															</div>
														<?endif;
													endforeach;
													/***OFFER_BUY_FORM***/?>
													<form action="<?=SITE_DIR?>ajax/add2basket.php" class="add2basket_form">
														<div class="qnt_cont">
															<a href="javascript:void(0)" class="minus" onclick="if (BX('quantity_new_<?=$arOffer["ID"]?>').value > <?=$arOffer["CATALOG_MEASURE_RATIO"]?>) BX('quantity_new_<?=$arOffer["ID"]?>').value = parseFloat(BX('quantity_new_<?=$arOffer["ID"]?>').value)-<?=$arOffer["CATALOG_MEASURE_RATIO"]?>;"><span>-</span></a>
															<input type="text" id="quantity_new_<?=$arOffer['ID']?>" name="quantity" class="quantity" value="<?=$arOffer['CATALOG_MEASURE_RATIO']?>"/>
															<a href="javascript:void(0)" class="plus" onclick="BX('quantity_new_<?=$arOffer["ID"]?>').value = parseFloat(BX('quantity_new_<?=$arOffer["ID"]?>').value)+<?=$arOffer["CATALOG_MEASURE_RATIO"]?>;"><span>+</span></a>
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
														<?if(!empty($arElement["SELECT_PROPS"])):?>
															<input type="hidden" name="SELECT_PROPS" id="select_props_new_<?=$arOffer['ID']?>" value="" />
														<?endif;?>
														<button type="button" id="<?=$arItemIDs['BTN_BUY']?>" class="btn_buy" name="add2basket" value="<?=$arMessage['CATALOG_ELEMENT_ADD_TO_CART']?>"><i class="fa fa-shopping-cart"></i></button>
													</form>
													<button name="boc_anch" id="boc_anch_new_<?=$arOffer['ID']?>" class="btn_buy boc_anch" value="<?=$arMessage['CATALOG_ELEMENT_BOC']?>"><i class="fa fa-bolt"></i><?=$arMessage['CATALOG_ELEMENT_BOC_SHORT']?></button>
													<?$APPLICATION->IncludeComponent("altop:buy.one.click", "", 
														array(
															"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
															"IBLOCK_ID" => $arParams["IBLOCK_ID"],
															"ELEMENT_ID" => $arOffer["ID"],
															"ELEMENT_CODE" => "new",
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
											<a class="btn_buy apuo" id="order_anch_new_<?=$arOffer['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-clock-o"></i><span class="short"><?=$arMessage["CATALOG_ELEMENT_UNDER_ORDER"]?></span></a>
											<?$properties = false;
											foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer) {
												$properties[] = $propOffer["NAME"].": ".strip_tags($propOffer["DISPLAY_VALUE"]);
											}
											$properties = implode("; ", $properties);
											if(!empty($properties)):
												$offer_name = ((isset($arOffer["NAME"]) && !empty($arOffer["NAME"])) ? $arOffer["NAME"] : $arElement["NAME"])." (".$properties.")";
											else:
												$offer_name = (isset($arOffer["NAME"]) && !empty($arOffer["NAME"])) ? $arOffer["NAME"] : $arElement["NAME"];
											endif;?>
											<?$APPLICATION->IncludeComponent("altop:ask.price", "order",
												Array(
													"ELEMENT_ID" => "new_".$arOffer["ID"],		
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
		<?/***OFFERS_ITEM***/
		else:
			/***OFFERS_ITEM_PRICE***/?>
			<div class="catalog_price" id="<?=$arItemIDs['PRICE'];?>">
				<?/***OFFERS_PRICE***/
				if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])):
					foreach($arElement["OFFERS"] as $key_off => $arOffer):?>
						<div id="price_new_<?=$arElement['ID']?>_<?=$arOffer['ID']?>" class="price <?=$arElement["ID"]?> hidden">
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
											$arElement["OFFERS"][$key_off]["ASK_PRICE"] = 1;?>			
											<span class="no-price">
												<?=$arMessage["CATALOG_ELEMENT_NO_PRICE"]?>
												<?=(!empty($arOffer["CATALOG_MEASURE_NAME"])) ? $arMessage["CATALOG_ELEMENT_UNIT"]." ".$arOffer["CATALOG_MEASURE_NAME"] : "";?>
											</span>
										<?else:
											if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>				
												<span class="price-old">
													<?=$arPrice["PRINT_VALUE"];?>
												</span>
												<span class="price-percent">
													<?=$arMessage["CATALOG_ELEMENT_SKIDKA"]." ".$arPrice["PRINT_DISCOUNT_DIFF"];?>
												</span>
											<?endif;?>
											<span class="price-normal">
												<?=number_format($arPrice["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
												<span class="unit">
													<?=$currency?>
													<?=(!empty($arOffer["CATALOG_MEASURE_NAME"])) ? $arMessage["CATALOG_ELEMENT_UNIT"]." ".$arOffer["CATALOG_MEASURE_NAME"] : "";?>
												</span>
											</span>
											<?if($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
												<span class="price-reference">
													<?=CCurrencyLang::CurrencyFormat($arPrice["DISCOUNT_VALUE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arPrice["CURRENCY"], true);?>
												</span>
											<?endif;
										endif;												
									endif;
								endif;
							endforeach;
							/***OFFERS_AVAILABILITY***/?>
							<div class="available">
								<?if($arOffer["CAN_BUY"]):?>													
									<div class="avl">
										<i class="fa fa-check-circle"></i>
										<span>
											<?=$arMessage["CATALOG_ELEMENT_AVAILABLE"];
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
										<span><?=$arMessage["CATALOG_ELEMENT_NOT_AVAILABLE"]?></span>
									</div>
								<?endif;?>
							</div>
						</div>
					<?endforeach;
				/***ITEM_PRICE***/
				else:
					foreach($arElement["PRICES"] as $code => $arPrice):
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

								if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
									<span class="price-old">
										<?=$arPrice["PRINT_VALUE"];?>
									</span>
									<span class="price-percent">
										<?=$arMessage["CATALOG_ELEMENT_SKIDKA"]." ".$arPrice["PRINT_DISCOUNT_DIFF"];?>
									</span>
								<?endif;?>
								<span class="price-normal">
									<?=number_format($arPrice["DISCOUNT_VALUE"], $price["DECIMALS"], $price["DEC_POINT"], $price["THOUSANDS_SEP"]);?>
									<span class="unit">
										<?=$currency?>
										<?=(!empty($arElement["CATALOG_MEASURE_NAME"])) ? $arMessage["CATALOG_ELEMENT_UNIT"]." ".$arElement["CATALOG_MEASURE_NAME"] : "";?>
									</span>
								</span>
								<?if($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
									<span class="price-reference">
										<?=CCurrencyLang::CurrencyFormat($arPrice["DISCOUNT_VALUE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arPrice["CURRENCY"], true);?>
									</span>
								<?endif;
							endif;
						endif;
					endforeach;
					/***ITEM_AVAILABILITY***/?>
					<div class="available">
						<?if($arElement["CAN_BUY"]):?>												
							<div class="avl">
								<i class="fa fa-check-circle"></i>
								<span>
									<?=$arMessage["CATALOG_ELEMENT_AVAILABLE"];
									if($arElement["CATALOG_QUANTITY_TRACE"] == "Y"):
										if(in_array("PRODUCT_QUANTITY", $arSetting["GENERAL_SETTINGS"]["VALUE"])):
											echo " ".$arElement["CATALOG_QUANTITY"];
										endif;
									endif;?>
								</span>
							</div>
						<?elseif(!$arElement["CAN_BUY"]):?>												
							<div class="not_avl">
								<i class="fa fa-times-circle"></i>
								<span><?=$arMessage["CATALOG_ELEMENT_NOT_AVAILABLE"]?></span>
							</div>
						<?endif;?>
					</div>
				<?endif;?>
			</div>
			<?/***OFFERS_ITEM_BUY***/?>
			<div class="catalog_buy_more" id="<?=$arItemIDs['BUY'];?>">
				<?/***OFFERS_BUY***/
				if(isset($arElement["OFFERS"]) && !empty($arElement["OFFERS"])):
					foreach($arElement["OFFERS"] as $key_off => $arOffer):?>
						<div id="buy_more_new_<?=$arElement['ID']?>_<?=$arOffer['ID']?>" class="buy_more <?=$arElement['ID']?> hidden">
							<?if($arOffer["CAN_BUY"]):											
								if($arOffer["ASK_PRICE"]):?>
									<a class="btn_buy apuo" id="ask_price_anch_new_<?=$arOffer['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-comment-o"></i><span><?=$arMessage["CATALOG_ELEMENT_ASK_PRICE_FULL"]?></span></a>
									<?$properties = false;
									foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer) {
										$properties[] = $propOffer["NAME"].": ".strip_tags($propOffer["DISPLAY_VALUE"]);
									}
									$properties = implode("; ", $properties);
									if(!empty($properties)):
										$offer_name = $arElement["NAME"]." (".$properties.")";
									else:
										$offer_name = $arElement["NAME"];
									endif;?>
									<?$APPLICATION->IncludeComponent("altop:ask.price", "",
										Array(
											"ELEMENT_ID" => "new_".$arOffer["ID"],		
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
												<a href="javascript:void(0)" class="minus" onclick="if (BX('quantity_new_<?=$arOffer["ID"]?>').value > <?=$arOffer["CATALOG_MEASURE_RATIO"]?>) BX('quantity_new_<?=$arOffer["ID"]?>').value = parseFloat(BX('quantity_new_<?=$arOffer["ID"]?>').value)-<?=$arOffer["CATALOG_MEASURE_RATIO"]?>;"><span>-</span></a>
												<input type="text" id="quantity_new_<?=$arOffer['ID']?>" name="quantity" class="quantity" value="<?=$arOffer['CATALOG_MEASURE_RATIO']?>"/>
												<a href="javascript:void(0)" class="plus" onclick="BX('quantity_new_<?=$arOffer["ID"]?>').value = parseFloat(BX('quantity_new_<?=$arOffer["ID"]?>').value)+<?=$arOffer["CATALOG_MEASURE_RATIO"]?>;"><span>+</span></a>
											</div>
											<input type="hidden" name="ID" class="offer_id" value="<?=$arOffer["ID"]?>" />
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
											<?if(!empty($arElement["SELECT_PROPS"])):?>
												<input type="hidden" name="SELECT_PROPS" id="select_props_new_<?=$arOffer['ID']?>" value="" />
											<?endif;?>
											<button type="button" class="btn_buy" name="add2basket" value="<?=$arMessage['CATALOG_ELEMENT_ADD_TO_CART']?>"><i class="fa fa-shopping-cart"></i><span><?=$arMessage["CATALOG_ELEMENT_ADD_TO_CART"]?></span></button>
										</form>
									</div>
								<?endif;
							elseif(!$arOffer["CAN_BUY"]):?>
								<a class="btn_buy apuo" id="order_anch_new_<?=$arOffer['ID']?>" href="javascript:void(0)" rel="nofollow"><i class="fa fa-clock-o"></i><span><?=$arMessage["CATALOG_ELEMENT_UNDER_ORDER"]?></span></a>
								<?$properties = false;
								foreach($arOffer["DISPLAY_PROPERTIES"] as $propOffer) {
									$properties[] = $propOffer["NAME"].": ".strip_tags($propOffer["DISPLAY_VALUE"]);
								}
								$properties = implode("; ", $properties);
								if(!empty($properties)):
									$offer_name = $arElement["NAME"]." (".$properties.")";
								else:
									$offer_name = $arElement["NAME"];
								endif;?>
								<?$APPLICATION->IncludeComponent("altop:ask.price", "order",
									Array(
										"ELEMENT_ID" => "new_".$arOffer["ID"],		
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
					<?endforeach;
				/***ITEM_BUY***/
				else:?>
					<div class="buy_more">
						<?if($arElement["CAN_BUY"]):?>
							<div class="add2basket_block">
								<form action="<?=SITE_DIR?>ajax/add2basket.php" class="add2basket_form">
									<div class="qnt_cont">
										<a href="javascript:void(0)" class="minus" onclick="if (BX('quantity_select_new_<?=$arElement["ID"]?>').value > <?=$arElement["CATALOG_MEASURE_RATIO"]?>) BX('quantity_select_new_<?=$arElement["ID"]?>').value = parseFloat(BX('quantity_select_new_<?=$arElement["ID"]?>').value)-<?=$arElement["CATALOG_MEASURE_RATIO"]?>;"><span>-</span></a>
										<input type="text" id="quantity_select_new_<?=$arElement['ID']?>" name="quantity" class="quantity" value="<?=$arElement['CATALOG_MEASURE_RATIO']?>"/>
										<a href="javascript:void(0)" class="plus" onclick="BX('quantity_select_new_<?=$arElement["ID"]?>').value = parseFloat(BX('quantity_select_new_<?=$arElement["ID"]?>').value)+<?=$arElement["CATALOG_MEASURE_RATIO"]?>;"><span>+</span></a>
									</div>
									<input type="hidden" name="ID" class="id" value="<?=$arElement['ID']?>" />
									<?if(!empty($arElement["PROPERTIES"]["ARTNUMBER"]["VALUE"])):
										$props = array();
										$props[] = array(
											"NAME" => $arElement["PROPERTIES"]["ARTNUMBER"]["NAME"],
											"CODE" => $arElement["PROPERTIES"]["ARTNUMBER"]["CODE"],
											"VALUE" => $arElement["PROPERTIES"]["ARTNUMBER"]["VALUE"]
										);												
										$props = strtr(base64_encode(addslashes(gzcompress(serialize($props),9))), '+/=', '-_,');?>
										<input type="hidden" name="PROPS" value="<?=$props?>" />
									<?endif;?>
									<input type="hidden" name="SELECT_PROPS" id="select_props_new_<?=$arElement['ID']?>" value="" />
									<button type="button" id="<?=$arItemIDs['BTN_BUY']?>" class="btn_buy" name="add2basket" value="<?=$arMessage['CATALOG_ELEMENT_ADD_TO_CART']?>"><i class="fa fa-shopping-cart"></i><span><?=$arMessage["CATALOG_ELEMENT_ADD_TO_CART"]?></span></button>
								</form>
							</div>
						<?endif;?>
					</div>
				<?endif;?>
			</div>
		<?endif;?>		
	</div>
</div>
<?if($arElement["OFFERS"]):
	$arJSParams = array(
		"PRODUCT_TYPE" => $arElement["CATALOG_TYPE"],
		"VISUAL" => array(
			"ID" => $arItemIDs["ID"],
			"PICT_ID" => $arItemIDs["PICT"],
			"PRICE_ID" => $arItemIDs["PRICE"],
			"BUY_ID" => $arItemIDs["BUY"],
			"TREE_ID" => $arItemIDs["PROP_DIV"],
			"TREE_ITEM_ID" => $arItemIDs["PROP"],			
		),
		"PRODUCT" => array(
			"ID" => $arElement["ID"],
			"NAME" => $arElement["NAME"],
			"PICT" => is_array($arElement["PREVIEW_IMG"]) ? $arElement["PREVIEW_IMG"] : array("SRC" => SITE_TEMPLATE_PATH."/images/no-photo.jpg", "WIDTH" => 150, "HEIGHT" => 150),
		),		
		"OFFERS_VIEW" => $arSetting["OFFERS_VIEW"]["VALUE"],
		"OFFERS" => $arElement["JS_OFFERS"],
		"OFFER_SELECTED" => $arElement["OFFERS_SELECTED"],
		"TREE_PROPS" => $arSkuProps
	);
else:
	$arJSParams = array(
		"PRODUCT_TYPE" => $arElement["CATALOG_TYPE"],
		"VISUAL" => array(
			"ID" => $arItemIDs["ID"],
			"BTN_BUY_ID" => $arItemIDs["BTN_BUY"],
		),
		"PRODUCT" => array(
			"ID" => $arElement["ID"],
			"NAME" => $arElement["NAME"],
			"PICT" => is_array($arElement["PREVIEW_IMG"]) ? $arElement["PREVIEW_IMG"] : array("SRC" => SITE_TEMPLATE_PATH."/images/no-photo.jpg", "WIDTH" => 150, "HEIGHT" => 150),
		)
	);
endif;				
if(isset($arElement["SELECT_PROPS"]) && !empty($arElement["SELECT_PROPS"])):
	$arJSParams["VISUAL"]["SELECT_PROP_ID"] = $arItemIDs["SELECT_PROP_DIV"];
	$arJSParams["VISUAL"]["SELECT_PROP_ITEM_ID"] = $arItemIDs["SELECT_PROP"];
	$arJSParams["SELECT_PROPS"] = $arSelProps;
endif;?>				
<script type="text/javascript">
	var <?=$strObName;?> = new JCCatalogSectionNew(<?=CUtil::PhpToJSObject($arJSParams, false, true);?>);
</script>