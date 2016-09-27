<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(count($arResult["ITEMS"]) < 1)
	return;?>

<div class="banners_main">	
	<?$width = 0;
	foreach($arResult["ITEMS"] as $arItem):			
		if($width == 0):?>
			<div class="row">
		<?endif;?>
		<a class="banner-item" href="<?=(!empty($arItem['DISPLAY_PROPERTIES']['URL'])) ? $arItem['DISPLAY_PROPERTIES']['URL']['VALUE'] : 'javascript:void(0)'?>" style="width:<?=$arItem['DISPLAY_PROPERTIES']['WIDTH']['VALUE']?>%; background-image:url(<?=$arItem['PREVIEW_PICTURE']['SRC']?>);">			
			<div class="item-block-cont">
				<div class="item-block">
					<?if(!empty($arItem["DISPLAY_PROPERTIES"]["BUTTON_TEXT"])):?>
						<div class="item-btn">
					<?endif;?>					
					<div class="item-text<?=($arItem['DISPLAY_PROPERTIES']['WIDTH']['VALUE'] == '25') ? ' small' : '';?>"><?=$arItem["NAME"]?></div>					
					<?if(!empty($arItem["DISPLAY_PROPERTIES"]["BUTTON_TEXT"])):?>
						<button name="item-button" class="btn_buy" value="<?=$arItem['DISPLAY_PROPERTIES']['BUTTON_TEXT']['VALUE']?>"><?=$arItem["DISPLAY_PROPERTIES"]["BUTTON_TEXT"]["VALUE"]?></button>					
						</div>
					<?endif;?>				
				</div>				
			</div>
		</a>
		<?$width += $arItem["DISPLAY_PROPERTIES"]["WIDTH"]["VALUE"];
		if($width == 100):?>
			</div>
			<?$width = 0;
		endif;
	endforeach;?>
</div>