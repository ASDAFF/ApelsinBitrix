<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(count($arResult["ITEMS"]) < 1)
	return;?>

<div class="stati_left">
	<div class="h3"><?=GetMessage("STATI_TITLE")?></div>
	<ul class="lsnn"> 
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<li>
				<div class="image_cont">
					<div class="image">
						<a href="<?=$arItem['DETAIL_PAGE_URL']?>"<?=!empty($arItem["PICTURE_PREVIEW"]["SRC"]) ? " style='background-image:url(".$arItem["PICTURE_PREVIEW"]["SRC"].");'" : "";?>></a>							
					</div>
				</div>
				<a class="title-link" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem["NAME"]?></a>
			</li>
		<?endforeach;?>
	</ul>
	<a class="all" href="<?=SITE_DIR?>reviews/"><?=GetMessage("ALL_STATI")?></a>
</div>