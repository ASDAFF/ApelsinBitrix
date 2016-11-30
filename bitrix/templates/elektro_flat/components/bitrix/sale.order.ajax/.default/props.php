<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
define("DEFAULT_PRICE_TYPE_FOR_1C_ORDER","86157e22-e56b-11dc-8b6b-000e0c431b58");
include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props_format.php");?>

<h2><?=GetMessage("SOA_TEMPL_PROP_INFO")?></h2>
<div class="order-info">
	<div class="order-info_in">
		<?if(!empty($arResult["ORDER_PROP"]["USER_PROFILES"])) {?>
			<div class="user_profile">
				<div class="label">
					<?if($arParams["ALLOW_NEW_PROFILE"] == "Y"):
						echo GetMessage("SOA_TEMPL_PROP_CHOOSE");
					else:
						echo GetMessage("SOA_TEMPL_EXISTING_PROFILE");
					endif;?>
				</div>
				<div class="block">
					<?if($arParams["ALLOW_NEW_PROFILE"] == "Y"):?>
						<select name="PROFILE_ID" id="ID_PROFILE_ID" class="selectbox" onChange="SetContact(this.value)">
							<option value="0"><?=GetMessage("SOA_TEMPL_PROP_NEW_PROFILE")?></option>
							<?foreach($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles) {?>
								<option value="<?= $arUserProfiles["ID"] ?>"<?if ($arUserProfiles["CHECKED"]=="Y") echo " selected";?>><?=$arUserProfiles["NAME"]?></option>
							<?}?>
						</select>
					<?else:
						if(count($arResult["ORDER_PROP"]["USER_PROFILES"]) == 1) {
							foreach($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles) {
								echo "<b>".$arUserProfiles["NAME"]."</b>";?>
								<input type="hidden" name="PROFILE_ID" id="ID_PROFILE_ID" value="<?=$arUserProfiles["ID"]?>" />
							<?}
						} else {?>
							<select name="PROFILE_ID" id="ID_PROFILE_ID" class="selectbox" onChange="SetContact(this.value)">
								<?foreach($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles) {?>
									<option value="<?= $arUserProfiles["ID"] ?>"<?if ($arUserProfiles["CHECKED"]=="Y") echo " selected";?>><?=$arUserProfiles["NAME"]?></option>
								<?}?>
							</select>
						<?}
					endif;?>
				</div>
				<div class="clr"></div>
			</div>
		<?}
		global $USER;
		$rsUser = CUser::GetList($by="ID", $order="desc", array("ID"=>$USER->GetID()),array("SELECT"=>array("UF_CARD_NUMBER","UF_1C_TYPE_PRICE")));
		$rsUserArr = $rsUser->Fetch();
		if($rsUserArr["UF_1C_TYPE_PRICE"] == "") {
			$rsUserArr["UF_1C_TYPE_PRICE"] = DEFAULT_PRICE_TYPE_FOR_1C_ORDER;
		}
		// пробрасываем номер карты
		$arResult["ORDER_PROP"]["USER_PROPS_N"][31]["DEFAULT_VALUE"] = $rsUserArr["UF_CARD_NUMBER"];
		$arResult["ORDER_PROP"]["USER_PROPS_N"][31]["~DEFAULT_VALUE"] = $rsUserArr["UF_CARD_NUMBER"];
		$arResult["ORDER_PROP"]["USER_PROPS_N"][31]["VALUE"] = $rsUserArr["UF_CARD_NUMBER"];
		$arResult["ORDER_PROP"]["USER_PROPS_N"][31]["~VALUE"] = $rsUserArr["UF_CARD_NUMBER"];
		$arResult["ORDER_PROP"]["PRINT"][31]["VALUE"] = $rsUserArr["UF_CARD_NUMBER"];
		// пробрасываем тип цен
		$arResult["ORDER_PROP"]["USER_PROPS_N"][32]["DEFAULT_VALUE"] = $rsUserArr["UF_1C_TYPE_PRICE"];
		$arResult["ORDER_PROP"]["USER_PROPS_N"][32]["~DEFAULT_VALUE"] = $rsUserArr["UF_1C_TYPE_PRICE"];
		$arResult["ORDER_PROP"]["USER_PROPS_N"][32]["VALUE"] = $rsUserArr["UF_1C_TYPE_PRICE"];
		$arResult["ORDER_PROP"]["USER_PROPS_N"][32]["~VALUE"] = $rsUserArr["UF_1C_TYPE_PRICE"];
		$arResult["ORDER_PROP"]["PRINT"][32]["VALUE"] = $rsUserArr["UF_1C_TYPE_PRICE"];
		PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"], $arParams["TEMPLATE_LOCATION"]);
		PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_N"], $arParams["TEMPLATE_LOCATION"]);
		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/adress_map.php");
		PrintPropsForm($arResult["ORDER_PROP"]["RELATED"], $arParams["TEMPLATE_LOCATION"]);
		?>
	</div>
</div>

<?if(!CSaleLocation::isLocationProEnabled()):?>
	<div style="display:none;">
		<?$APPLICATION->IncludeComponent("bitrix:sale.ajax.locations", $arParams["TEMPLATE_LOCATION"],
			array(
				"AJAX_CALL" => "N",
				"COUNTRY_INPUT_NAME" => "COUNTRY_tmp",
				"REGION_INPUT_NAME" => "REGION_tmp",
				"CITY_INPUT_NAME" => "tmp",
				"CITY_OUT_LOCATION" => "Y",
				"LOCATION_VALUE" => "",
				"ONCITYCHANGE" => "submitForm()",
			),
			null,
			array('HIDE_ICONS' => 'Y')
		);?>
	</div>
<?endif?>