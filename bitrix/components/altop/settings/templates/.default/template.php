<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(false);?>

<div class="style-switcher <?=$_COOKIE['styleSwitcher'] == 'open' ? 'active' : ''?>">
	<div class="header">
		<?=GetMessage("THEME_MODIFY")?><span class="switch"><i class="fa fa-cog"></i></span>		
	</div>
	<form action="<?=$APPLICATION->GetCurPage()?>" method="POST" name="style-switcher">
		<?=bitrix_sessid_post();
		$i = 1;
		foreach($arResult as $optionCode => $arOption):			
			if($arOption["IN_SETTINGS_PANEL"] == "Y"):
				if($optionCode == "COLOR_SCHEME_CUSTOM" || $optionCode == "SMART_FILTER_VISIBILITY"):
					continue;
				else:?>
					<div class="block">					
						<div class="block-title">
							<span><?=$optionCode == "SMART_FILTER_LOCATION" ? GetMessage("SMART_FILTER") : $arOption["TITLE"]?></span>
							<a class="plus" id="plus-minus-<?=$optionCode?>" href="javascript:void(0)"><i class="fa fa-plus-circle"></i><i class="fa fa-minus-circle"></i></a>
						</div>
						<div class="options" id="options-<?=$optionCode?>" style="display:none;">							
							<?$k = 1;
							if($optionCode == "COLOR_SCHEME"):
								foreach($arOption["LIST"] as $colorCode => $arColor):
									if($colorCode !== "CUSTOM"):?>
										<div class="custom-forms colors" data-color="<?=$arColor['COLOR']?>">
											<input type="radio" id="option-<?=$i?>-<?=$k?>" name="<?=$optionCode?>" <?=$arColor["CURRENT"] == "Y" ? "checked=\"checked\"" : ""?> value="<?=$colorCode?>" />
											<label for="option-<?=$i?>-<?=$k?>" title="<?=$arColor['TITLE']?>">
												<i class="fa fa-check" style="color:<?=$arColor['COLOR']?>; background:<?=$arColor['COLOR']?>;"></i>
											</label>
										</div>
										<?$k++;
									endif;
								endforeach;?>
								<div class="clr"></div>								
								<div class="color-scheme-custom">
									<?foreach($arOption["LIST"] as $colorCode => $arColor):
										if($colorCode == "CUSTOM"):?>											
											<div class="custom-forms colors" data-color="<?=(strlen($arResult['COLOR_SCHEME_CUSTOM']['VALUE']) > 0) ? $arResult['COLOR_SCHEME_CUSTOM']['VALUE'] : $arResult['COLOR_SCHEME_CUSTOM']['DEFAULT']?>">
												<input type="radio" id="option-<?=$i?>-<?=$k?>" name="<?=$optionCode?>" <?=$arColor["CURRENT"] == "Y" ? "checked=\"checked\"" : ""?> value="<?=$colorCode?>" />
												<label for="option-<?=$i?>-<?=$k?>" title="<?=$arColor['TITLE']?>">
													<i class="fa fa-check" style="<?=(strlen($arResult['COLOR_SCHEME_CUSTOM']['VALUE']) > 0) ? 'color:'.$arResult['COLOR_SCHEME_CUSTOM']['VALUE'].'; background:'.$arResult['COLOR_SCHEME_CUSTOM']['VALUE'].';' : 'color:'.$arResult['COLOR_SCHEME_CUSTOM']['DEFAULT'].'; background:'.$arResult['COLOR_SCHEME_CUSTOM']['DEFAULT'].';'?>"></i>
												</label>
											</div>
											<input type="text" id="option-color-scheme-custom" name="COLOR_SCHEME_CUSTOM" maxlength="7" value="<?=(strlen($arResult['COLOR_SCHEME_CUSTOM']['VALUE']) > 0) ? $arResult['COLOR_SCHEME_CUSTOM']['VALUE'] : $arResult['COLOR_SCHEME_CUSTOM']['DEFAULT']?>" />
											<button type="button" name="palette_button" class="btn_buy apuo"><i class="fa fa-eyedropper"></i><span><?=GetMessage("PALETTE")?></span></button>
											<?$k++;
										endif;
									endforeach;?>
									<div class="clr"></div>
								</div>
							<?else:
								if($arOption["TYPE"] == "selectbox"):							
									foreach($arOption["LIST"] as $variantCode => $arVariant):?>								
										<div class="custom-forms">
											<input type="radio" id="option-<?=$i?>-<?=$k?>" name="<?=$optionCode?>" <?=$arVariant["CURRENT"] == "Y" ? "checked=\"checked\"" : ""?> value="<?=$variantCode?>" />
											<label for="option-<?=$i?>-<?=$k?>"><?=$arVariant["TITLE"]?></label>
										</div>
										<?$k++;
									endforeach;									
									if($optionCode == "SMART_FILTER_LOCATION"):
										foreach($arResult as $optionCode => $arOption):
											if($arOption["IN_SETTINGS_PANEL"] == "Y"):
												if($optionCode == "SMART_FILTER_VISIBILITY"):
													foreach($arOption["LIST"] as $variantCode => $arVariant):?>						
														<div class="custom-forms">
															<input type="radio" id="option-<?=$i?>-<?=$k?>" name="<?=$optionCode?>" <?=$arVariant["CURRENT"] == "Y" ? "checked=\"checked\"" : ""?> value="<?=$variantCode?>" />
															<label for="option-<?=$i?>-<?=$k?>"><?=$arVariant["TITLE"]?></label>
														</div>
														<?$k++;
													endforeach;
												endif;
											endif;
										endforeach;
									endif;?>									
									<div class="clr"></div>
								<?elseif($arOption["TYPE"] == "multiselectbox"):							
									foreach($arOption["LIST"] as $variantCode => $arVariant):?>								
										<div class="custom-forms option">
											<input type="checkbox" id="option-<?=$i?>-<?=$k?>" name="<?=$optionCode?>[]" <?=$arVariant["CURRENT"] == "Y" ? "checked=\"checked\"" : ""?> value="<?=$variantCode?>" />
											<label for="option-<?=$i?>-<?=$k?>"><span class="check-cont"><span class="check"><i class="fa fa-check"></i></span></span><span class="check-title"><?=$arVariant["TITLE"]?></span></label>
										</div>
										<?$k++;
									endforeach;
								endif;
							endif;?>
						</div>						
					</div>
					<?$i++;
				endif;
			else:?>
				<input type="hidden" name="<?=$optionCode?>" value="<?=$arOption["VALUE"]?>" />
			<?endif;			
		endforeach;?>
		<div class="reset">
			<button type="button" name="reset_button" class="btn_buy apuo"><i class="fa fa-repeat"></i><span><?=GetMessage("THEME_RESET")?></span></button>
		</div>
	</form>
	
	<script type="text/javascript">
		$(function() {
			if($.cookie("styleSwitcher") == "open") {
				$(".style-switcher").addClass("active");
			}
			
			$(".style-switcher .switch").hover(function(e) {
				$(".fa-cog").addClass("fa-spin");
			}, function() {
				$(".fa-cog").removeClass("fa-spin");
			});
			
			$(".style-switcher .switch").click(function(e) {
				e.preventDefault();
				var styleswitcher = $(this).closest(".style-switcher");
				if(styleswitcher.hasClass("active")) {
					styleswitcher.animate({right: "-" + styleswitcher.outerWidth() + "px"}, 300).removeClass("active");
					$.removeCookie("styleSwitcher", {path: "/"});
				} else {
					styleswitcher.animate({right: "0"}, 300).addClass("active");
					var pos = styleswitcher.offset().top;
					if($(window).scrollTop() > pos){
						$("html, body").animate({scrollTop: pos}, 500);
					}
					$.cookie("styleSwitcher", "open", {path: "/"});
				}
			});
			
			<?foreach($arResult as $optionCode => $arOption):
				if($arOption["IN_SETTINGS_PANEL"] == "Y"):?>
					if($.cookie("plus-minus-<?=$optionCode?>") == "open") {
						$("#plus-minus-<?=$optionCode?>").removeClass().addClass("minus");
						$(".style-switcher .block #options-<?=$optionCode?>").show();
					}	
						
					$("#plus-minus-<?=$optionCode?>").click(function() {
						var clickitem = $(this);
						if(clickitem.hasClass("plus")) {
							clickitem.removeClass().addClass("minus");
							$.cookie("plus-minus-<?=$optionCode?>", "open", {path: "/"});
						} else {
							clickitem.removeClass().addClass("plus");
							$.removeCookie("plus-minus-<?=$optionCode?>", {path: "/"});
						}
						$(".style-switcher .block #options-<?=$optionCode?>").slideToggle();
					});
				<?endif;
			endforeach;?>			

			var curColor = $(".colors.custom-forms.active").data("color");				
				customColorDiv = $(".color-scheme-custom .colors.custom-forms i"),
				customColorInput = $(".color-scheme-custom input[id=option-color-scheme-custom]"),
				paletteButton = $(".color-scheme-custom button[name=palette_button]"),
				formSwitcher = $("form[name=style-switcher]");

			paletteButton.spectrum({				
				clickoutFiresChange: false,
				cancelText: "<i class='fa fa-times'></i>",
				chooseText: "<?=GetMessage('PALETTE_CHOOSE_COLOR')?>",
				containerClassName:"palette_cont",				
				move: function(color) {
					var colorCode = color.toHexString();					
					customColorDiv.attr("style", "color:" + colorCode + "; background:" + colorCode + ";");
					customColorInput.val(colorCode);
				},
				hide: function(color) {
					var colorCode = color.toHexString();
					customColorDiv.attr("style", "color:" + colorCode + "; background:" + colorCode + ";");
					customColorInput.val(colorCode);
				},
				change: function(color) {
					customColorDiv.parent().parent().find("input").attr("checked", "checked");					
					formSwitcher.append("<input type='hidden' name='CHANGE_THEME' value='Y' />");
					formSwitcher.submit();
				}
			});			
					
			if(curColor != undefined && curColor.length > 0) {
				paletteButton.spectrum("set", curColor);
				customColorDiv.attr("style", "color:" + curColor + "; background:" + curColor + ";");
				customColorInput.val(curColor);
			}
			
			customColorInput.change(function() {				
				var colorCode = $(this).val();
				if(colorCode.length > 0) {
					colorCode = colorCode.replace(/#/g, "");
					if(colorCode.length < 3) {
						for($i = 0, $l = 6 - colorCode.length; $i < $l; ++$i) {
							colorCode = colorCode + "0";
						}					
					}
					colorCode = "#" + colorCode;
					$(this).val(colorCode);
					customColorDiv.attr("style", "color:" + colorCode + "; background:" + colorCode + ";");
				} else {
					if(curColor != undefined && curColor.length > 0) {
						$(this).val(curColor);
						customColorDiv.attr("style", "color:" + curColor + "; background:" + curColor + ";");
					}
				}
			});
			
			$(".color-scheme-custom").on("keypress", "input[id=option-color-scheme-custom]", function(e) {
				if(e.keyCode == 13){	
					e.preventDefault();
					$(this).parents(".color-scheme-custom").find(".colors.custom-forms input").attr("checked", "checked");
					formSwitcher.append("<input type='hidden' name='CHANGE_THEME' value='Y' />");
					formSwitcher.submit();
				}
			});
			
			$(".style-switcher .reset button[name=reset_button]").click(function(e) {
				formSwitcher.append("<input type='hidden' name='CHANGE_THEME' value='Y' />");
				formSwitcher.append("<input type='hidden' name='THEME' value='default' />");
				formSwitcher.submit();
			});
			
			$(".style-switcher .options input[type=radio], .style-switcher .options input[type=checkbox]").click(function(e) {		
				formSwitcher.append("<input type='hidden' name='CHANGE_THEME' value='Y' />");
				formSwitcher.submit();
			});
		});
	</script>	
</div>