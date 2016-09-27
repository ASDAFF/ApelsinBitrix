<?global $arSetting;?>

<a href="<?=SITE_DIR?>">	
	<?if($arSetting["COLOR_SCHEME"]["VALUE"] == "YELLOW"):?>
		<img src="<?=SITE_TEMPLATE_PATH?>/images/logo.png" alt="logo" />
	<?else:
		if($arSetting["SITE_BACKGROUND"]["VALUE"] == "DARK"):?>
			<img src="<?=SITE_TEMPLATE_PATH?>/images/logo_dark.png" alt="logo" />
		<?else:?>
			<img src="<?=SITE_TEMPLATE_PATH?>/images/logo_gray.png" alt="logo" />
		<?endif;
	endif;?>
	<span>Готовый интернет-магазин на 1С-Битрикс</span>
</a>