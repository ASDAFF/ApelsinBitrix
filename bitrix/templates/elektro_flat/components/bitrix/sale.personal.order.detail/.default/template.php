<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $arSetting;

if(strlen($arResult["ERROR_MESSAGE"])<=0):?>	
	<div class="cart-items" style="margin:0px 0px 10px 0px;">
		<div class="equipment-order detail">
			<div class="thead">				
				<div class="cart-item-number-date"><?=GetMessage("SPOD_ORDER_NUMBER_DATE")?></div>
				<div class="cart-item-status"><?=GetMessage("SPOD_ORDER_STATUS")?></div>
				<div class="cart-item-payment"><?=GetMessage("SPOD_ORDER_PAYMENT")?></div>
				<div class="cart-item-payed"><?=GetMessage("SPOD_ORDER_PAYED")?></div>
				<div class="cart-item-summa"><?=GetMessage("SPOD_ORDER_SUMMA")?></div>
			</div>
			<div class="tbody">
				<div class="tr">
					<div class="tr_into">						
						<div class="cart-item-number-date">
							<span class="cart-item-number"><?=$arResult["ACCOUNT_NUMBER"]?></span>
							<?=$arResult["DATE_INSERT_FORMATED"];?>
						</div>
						<div class="cart-item-status">
							<?if($arResult["CANCELED"] == "N"):?>
								<span class="item-status-<?=toLower($arResult["STATUS"]["ID"])?>">
									<?=$arResult["STATUS"]["NAME"];?>
								</span>
							<?elseif($arResult["CANCELED"] == "Y"):?>
								<span class="item-status-d">
									<?=GetMessage("SPOD_ORDER_DELETE");?>
								</span>
							<?endif;?>
						</div>
						<div class="cart-item-payment">
							<?if(IntVal($arResult["PAY_SYSTEM_ID"]) > 0):
								echo $arResult["PAY_SYSTEM"]["NAME"];								
								if($arResult["CAN_REPAY"]=="Y"):
									if($arResult["PAY_SYSTEM"]["PSA_NEW_WINDOW"] == "Y"):?>
										<br />
										<a href="<?=$arResult["PAY_SYSTEM"]["PSA_ACTION_FILE"]?>" target="_blank"><?=GetMessage("SALE_REPEAT_PAY")?></a>
									<?endif;
								endif;
							else:
								echo GetMessage("SPOD_NONE");
							endif;?>
						</div>
						<div class="cart-item-payed">
							<?if($arResult["PAYED"] == "Y"):
								echo "<span class='item-payed-yes'>".GetMessage("SALE_YES")."</span>";
							else:
								echo GetMessage("SALE_NO");
							endif;?>
						</div>
						<div class="cart-item-summa">
							<span class="sum">
								<?=$arResult["PRICE_FORMATED"];?>
							</span>
							<?if($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
								<span class="reference-sum">
									<?=CCurrencyLang::CurrencyFormat($arResult["PRICE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arResult["CURRENCY"], true);?>
								</span>
							<?endif;?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="cart-items basket">
		<div class="equipment-order basket">
			<div class="thead">
				<div class="cart-item-name"><?=GetMessage("SPOD_ORDER_NAME")?></div>
				<div class="cart-item-price"><?=GetMessage("SPOD_ORDER_PRICE")?></div>
				<div class="cart-item-quantity"><?=GetMessage("SPOD_ORDER_QUANTITY")?></div>
				<div class="cart-item-summa"><?=GetMessage("SPOD_ORDER_SUMMA")?></div>
			</div>
			<div class="tbody">
				<?$i = 1;
				foreach($arResult["BASKET"] as $arBasketItems):?>
					<div class="tr">
						<div class="tr_into">
							<div class="cart-item-number"><?=$i?></div>
							<div class="cart-item-image">
								<?if(is_array($arBasketItems["DETAIL_PICTURE"])):?>
									<img src="<?=$arBasketItems['DETAIL_PICTURE']['src']?>" width="<?=$arBasketItems['DETAIL_PICTURE']['width']?>" height="<?=$arBasketItems['DETAIL_PICTURE']['height']?>" />
								<?else:?>
									<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" width="30" height="30" />
								<?endif?>
							</div>
							<div class="cart-item-name">
								<?if(strlen($arBasketItems["DETAIL_PAGE_URL"])>0):?>
									<a href="<?=$arBasketItems["DETAIL_PAGE_URL"]?>">
								<?endif;
									echo $arBasketItems["NAME"];
								if(strlen($arBasketItems["DETAIL_PAGE_URL"])>0):?>
									</a>
								<?endif;
								if(!empty($arBasketItems["PROPS"])) {?>
									<div class="item-props">
										<?foreach($arBasketItems["PROPS"] as $val) {
											echo "<span style='display:block;'>".$val["NAME"].": ".$val["VALUE"]."</span>";
										}?>
										<div class="clr"></div>
									</div>
								<?}?>
							</div>
							<div class="cart-item-price">
								<div class="price">
									<?=$arBasketItems["PRICE_FORMATED"]?>
								</div>
								<?if($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
									<span class="reference-price">
										<?=CCurrencyLang::CurrencyFormat($arBasketItems["PRICE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arBasketItems["CURRENCY"], true);?>
									</span>
								<?endif;?>
							</div>
							<div class="cart-item-quantity">
								<?=$arBasketItems["QUANTITY"];
								if(!empty($arBasketItems["MEASURE_TEXT"])):
									echo " ".$arBasketItems["MEASURE_TEXT"];
								endif;?>
							</div>
							<div class="cart-item-summa">
								<span class="sum">
									<?=CCurrencyLang::CurrencyFormat($arBasketItems["PRICE"] * $arBasketItems["QUANTITY"], $arBasketItems["CURRENCY"], true);?>
								</span>							
								<?if($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
									<span class="reference-sum">
										<?=CCurrencyLang::CurrencyFormat($arBasketItems["PRICE"] * $arBasketItems["QUANTITY"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arBasketItems["CURRENCY"], true);?>
									</span>
								<?endif;?>								
							</div>
						</div>
					</div>
					<?$i++;
				endforeach;
				if(IntVal($arResult["DELIVERY_ID"]) > 0):?>
					<div class="tr">
						<div class="tr_into">
							<div class="cart-itogo"><?=$arResult["DELIVERY"]["NAME"]?></div>
							<div class="cart-allsum">
								<span class="allsum">
									<?=$arResult["PRICE_DELIVERY_FORMATED"]?>
								</span>
								<?if($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
									<span class="reference-allsum">
										<?=CCurrencyLang::CurrencyFormat($arResult["PRICE_DELIVERY"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arResult["CURRENCY"], true);?>
									</span>
								<?endif;?>
							</div>
						</div>
					</div>
				<?endif;?>
			</div>
			<div class="myorders_itog<?=($arSetting['REFERENCE_PRICE']['VALUE'] == 'Y' && !empty($arSetting['REFERENCE_PRICE_COEF']['VALUE']) ? ' reference' : '');?>">
				<div class="cart-itogo"><?=GetMessage("SPOD_ORDER_SUM_IT")?></div>
				<div class="cart-allsum">
					<span class="allsum">
						<?=$arResult["PRICE_FORMATED"]?>
					</span>
					<?if($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"])):?>
						<span class="reference-allsum">
							<?=CCurrencyLang::CurrencyFormat($arResult["PRICE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arResult["CURRENCY"], true);?>
						</span>
					<?endif;?>
				</div>
			</div>
		</div>
	</div>
	
	<table class="order-recipient">
		<?if(!empty($arResult["ORDER_PROPS"])) {
			foreach($arResult["ORDER_PROPS"] as $val) {?>
				<tr>
					<td class="field-name"><?echo $val["NAME"] ?>:</td>
					<td class="field-value">
						<?if($val["TYPE"] == "CHECKBOX") {
							if($val["VALUE"] == "Y")
								echo GetMessage("SALE_YES");
							else
								echo GetMessage("SALE_NO");
						} else {
							echo $val["VALUE"];
						}?>
					</td>
				</tr>
			<?}
		}?>
		<?if(strlen($arResult["USER_DESCRIPTION"])>0):?>
			<tr>
				<td class="field-name"><?=GetMessage("P_ORDER_USER_COMMENT")?></td>
				<td class="field-value"><?=$arResult["USER_DESCRIPTION"]?></td>
			</tr>
		<?endif;?>
	</table>

	<div class="order-item-actions">
		<a class="btn_buy apuo order_repeat" href="<?=$arResult['URL_TO_LIST']?>?COPY_ORDER=Y&ID=<?=$arResult['ACCOUNT_NUMBER']?>" title="<?=GetMessage('SALE_REPEAT_ORDER')?>"><i class="fa fa-repeat"></i><span><?=GetMessage("SALE_REPEAT_ORDER")?></span></a>
		<?if($arResult["CAN_CANCEL"]=="Y"):?>
			<a class="btn_buy apuo order_delete" href="<?=$arResult["URL_TO_CANCEL"]?>" title="<?=GetMessage('SALE_CANCEL_ORDER')?>"><i class="fa fa-times"></i><span><?=GetMessage("SALE_CANCEL_ORDER")?></span></a>
		<?endif;?>
		<div class="clr"></div>
	</div>
<?else:	
	echo ShowError($arResult["ERROR_MESSAGE"]);
endif;?>