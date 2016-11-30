<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!empty($arResult["DELIVERY"])):?>
	<script type="text/javascript">
		function fShowStore(id, showImages, formWidth, siteId) {
			var strUrl = '<?=$templateFolder?>' + '/map.php';
			var strUrlPost = 'delivery=' + id + '&showImages=' + showImages + '&siteId=' + siteId;

			var storeForm = new BX.CDialog({
				'title': '<?=GetMessage('SOA_ORDER_GIVE')?>',
				head: '',
				'content_url': strUrl,
				'content_post': strUrlPost,
				'width': formWidth,
				'height':400,
				'resizable':false,
				'draggable':false
			});
			BX.addClass(BX('bx-admin-prefix'), 'popup-store');
			
			close = BX.findChildren(BX('bx-admin-prefix'), {className: 'bx-core-adm-icon-close'}, true);
			if(!!close && 0 < close.length) {
				for(i = 0; i < close.length; i++) {					
					close[i].innerHTML = "<i class='fa fa-times'></i>";
				}
			}
			
			var button = ['<button id="crmOk" class="btn_buy ppp" name="crmOk" onclick="GetBuyerStore();BX.WindowManager.Get().Close();"><?=GetMessage("SOA_POPUP_SAVE")?></button>', '<button id="cancel" class="btn_buy popdef" name="cancel" onclick="BX.WindowManager.Get().Close();"><?=GetMessage("SOA_POPUP_CANCEL")?></button>'];
			
			storeForm.ClearButtons();
			storeForm.SetButtons(button);
			storeForm.Show();
		}

		function GetBuyerStore() {
			BX('BUYER_STORE').value = BX('POPUP_STORE_ID').value;
			BX('store_desc').innerHTML = BX('POPUP_STORE_NAME').value;
			BX.show(BX('select_store'));
		}

		function showExtraParamsDialog(deliveryId)
		{
			var strUrl = '<?=$templateFolder?>' + '/delivery_extra_params.php';
			var formName = 'extra_params_form';
			var strUrlPost = 'deliveryId=' + deliveryId + '&formName=' + formName;

			if(window.BX.SaleDeliveryExtraParams)
			{
				for(var i in window.BX.SaleDeliveryExtraParams)
				{
					strUrlPost += '&'+encodeURI(i)+'='+encodeURI(window.BX.SaleDeliveryExtraParams[i]);
				}
			}

			var paramsDialog = new BX.CDialog({
				'title': '<?=GetMessage('SOA_ORDER_DELIVERY_EXTRA_PARAMS')?>',
				head: '',
				'content_url': strUrl,
				'content_post': strUrlPost,
				'width': 500,
				'height':200,
				'resizable':true,
				'draggable':false
			});

			var button = [
				{
					title: '<?=GetMessage('SOA_POPUP_SAVE')?>',
					id: 'saleDeliveryExtraParamsOk',
					'action': function ()
					{
						insertParamsToForm(deliveryId, formName);
						BX.WindowManager.Get().Close();
					}
				},
				BX.CDialog.btnCancel
			];

			paramsDialog.ClearButtons();
			paramsDialog.SetButtons(button);
			//paramsDialog.adjustSizeEx();
			paramsDialog.Show();
		}

		function insertParamsToForm(deliveryId, paramsFormName)
		{
			var orderForm = BX("ORDER_FORM"),
				paramsForm = BX(paramsFormName);
			wrapDivId = deliveryId + "_extra_params";

			var wrapDiv = BX(wrapDivId);
			window.BX.SaleDeliveryExtraParams = {};

			if(wrapDiv)
				wrapDiv.parentNode.removeChild(wrapDiv);

			wrapDiv = BX.create('div', {props: { id: wrapDivId}});

			for(var i = paramsForm.elements.length-1; i >= 0; i--)
			{
				var input = BX.create('input', {
						props: {
							type: 'hidden',
							name: 'DELIVERY_EXTRA['+deliveryId+']['+paramsForm.elements[i].name+']',
							value: paramsForm.elements[i].value
						}
					}
				);

				window.BX.SaleDeliveryExtraParams[paramsForm.elements[i].name] = paramsForm.elements[i].value;

				wrapDiv.appendChild(input);
			}

			orderForm.appendChild(wrapDiv);

			BX.onCustomEvent('onSaleDeliveryGetExtraParams',[window.BX.SaleDeliveryExtraParams]);
		}

		if(typeof submitForm === 'function')
			BX.addCustomEvent('onDeliveryExtraServiceValueChange', function(){ submitForm(); });

	</script>
	
	<input type="hidden" name="BUYER_STORE" id="BUYER_STORE" value="<?=$arResult["BUYER_STORE"]?>" />
	<h2><?=GetMessage("SOA_TEMPL_DELIVERY")?></h2>
	<div class="order-info">
		<div class="order-info_in order-info_in_table">
			<table>
				<?$width = ($arParams["SHOW_STORES_IMAGES"] == "Y") ? 800 : 750;
				foreach($arResult["DELIVERY"] as $delivery_id => $arDelivery):
					if($delivery_id !== 0 && intval($delivery_id) <= 0):
						foreach($arDelivery["PROFILES"] as $profile_id => $arProfile):?>
							<tr>
								<td valign="top">
									<input type="radio" id="ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>" name="<?=htmlspecialcharsbx($arProfile["FIELD_NAME"])?>" value="<?=$delivery_id.":".$profile_id;?>" <?=$arProfile["CHECKED"] == "Y" ? "checked=\"checked\"" : "";?> onclick="submitForm();" />
								</td>
								<td valign="top">
									<label for="ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>">
										<table>
											<tr>
												<td valign="top">
													<?if(!empty($arDelivery["LOGOTIP"]["SRC"])):?>
														<img src="<?=$arDelivery["LOGOTIP"]["SRC"]?>" width="<?=$arDelivery["LOGOTIP"]["WIDTH"]?>" height="<?=$arDelivery["LOGOTIP"]["HEIGHT"]?>" />
													<?endif;?>
												</td>
												<td valign="top">
													<div class="name">
														<?=htmlspecialcharsbx($arDelivery["TITLE"])?> - <?=htmlspecialcharsbx($arProfile["TITLE"])?>
													</div>
													<p>
														<?if(strlen($arProfile["DESCRIPTION"]) > 0) {
															echo nl2br($arProfile["DESCRIPTION"]);
														}?>
														<?$APPLICATION->IncludeComponent('bitrix:sale.ajax.delivery.calculator', '', 
															array(
																"NO_AJAX" => $arParams["DELIVERY_NO_AJAX"],
																"DELIVERY" => $delivery_id,
																"PROFILE" => $profile_id,
																"ORDER_WEIGHT" => $arResult["ORDER_WEIGHT"],
																"ORDER_PRICE" => $arResult["ORDER_PRICE"],
																"LOCATION_TO" => $arResult["USER_VALS"]["DELIVERY_LOCATION"],
																"LOCATION_ZIP" => $arResult["USER_VALS"]["DELIVERY_LOCATION_ZIP"],
																"CURRENCY" => $arResult["BASE_LANG_CURRENCY"],
																"ITEMS" => $arResult["BASKET_ITEMS"],
																"EXTRA_PARAMS_CALLBACK" => $extraParams
															),
															null,
															array('HIDE_ICONS' => 'Y')
														);?>
													</p>
												</td>
											</tr>
										</table>
									</label>
								</td>
							</tr>
						<?endforeach; 
					else:?>
						<tr>
							<td valign="top">
								<?if(count($arDelivery["STORE"]) > 0):
									$clickHandler = "onClick = \"fShowStore('".$arDelivery["ID"]."','".$arParams["SHOW_STORES_IMAGES"]."','".$width."','".SITE_ID."');submitForm();\"";
								else:
									$clickHandler = "onClick = \"submitForm();\"";
								endif;?>
								<input type="radio" class="radio" id="ID_DELIVERY_ID_<?=$arDelivery["ID"]?>" name="<?=htmlspecialcharsbx($arDelivery["FIELD_NAME"])?>" value="<?=$arDelivery["ID"]?>"<?if($arDelivery["CHECKED"]=="Y") echo " checked";?> <?=$clickHandler?>/>
                                <label for="ID_DELIVERY_ID_<?=$arDelivery["ID"]?>"></label>
                            </td>
							<td valign="top">
								<label for="ID_DELIVERY_ID_<?=$arDelivery["ID"]?>" onclick="BX('ID_DELIVERY_ID_<?=$arDelivery["ID"]?>').checked=true;submitForm();">
									<table>
										<tr>
											<td valign="top">
												<?if(!empty($arDelivery["LOGOTIP"]["SRC"])):?>
													<img src="<?=$arDelivery["LOGOTIP"]["SRC"]?>" width="<?=$arDelivery["LOGOTIP"]["WIDTH"]?>" height="<?=$arDelivery["LOGOTIP"]["HEIGHT"]?>" />
												<?endif;?>
											</td>
											<td valign="top">												
												<div class="name">
													<?=htmlspecialcharsbx($arDelivery["NAME"])?>
												</div>
												<p>
													<?if(strlen($arDelivery["PERIOD_TEXT"])>0):
														echo $arDelivery["PERIOD_TEXT"]."<br />";
													endif;
													if(DoubleVal($arDelivery["PRICE"]) > 0):
														echo "<span class='sale_delivery_price'>".GetMessage("SALE_DELIV_PRICE")." ".$arDelivery["PRICE_FORMATED"].($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"]) ? " (".CCurrencyLang::CurrencyFormat($arDelivery["PRICE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arDelivery["CURRENCY"], true).")" : "")."</span><br />";
													endif;
													if(strlen($arDelivery["DESCRIPTION"])>0):
														echo $arDelivery["DESCRIPTION"];
													endif;
													if(count($arDelivery["STORE"]) > 0):?>
														<span id="select_store"<?if(strlen($arResult["STORE_LIST"][$arResult["BUYER_STORE"]]["TITLE"]) <= 0) echo " style=\"display:none;\"";?>>
															<span class="select_store"><?=GetMessage('SOA_ORDER_GIVE_TITLE');?>: </span>
															<span class="ora-store" id="store_desc"><?=htmlspecialcharsbx($arResult["STORE_LIST"][$arResult["BUYER_STORE"]]["TITLE"])?></span>
														</span>
													<?endif;?>
												</p>
											</td>
										</tr>
									</table>
								</label>

								<?if ($arDelivery['CHECKED'] == 'Y'):?>
									<table class="delivery_extra_services">
										<?foreach ($arDelivery['EXTRA_SERVICES'] as $extraServiceId => $extraService):?>
											<?if(!$extraService->canUserEditValue()) continue;?>
											<tr>
												<td class="name">
													<?=$extraService->getName()?>
												</td>
												<td class="control">
													<?=$extraService->getEditControl('DELIVERY_EXTRA_SERVICES['.$arDelivery['ID'].']['.$extraServiceId.']')	?>
												</td>
												<td rowspan="2" class="price">
													<?

													if ($price = $extraService->getPrice())
													{
														echo GetMessage('SOA_TEMPL_SUM_PRICE').': ';
														echo '<strong>'.SaleFormatCurrency($price, $arResult['BASE_LANG_CURRENCY']).'</strong>';
													}

													?>
												</td>
											</tr>
											<tr>
												<td colspan="2" class="description">
													<?=$extraService->getDescription()?>
												</td>
											</tr>
										<?endforeach?>
									</table>
								<?endif?>
							</td>
						</tr>
					<?endif;
				endforeach;?>
			</table>
		</div>
	</div>
<?endif;?>