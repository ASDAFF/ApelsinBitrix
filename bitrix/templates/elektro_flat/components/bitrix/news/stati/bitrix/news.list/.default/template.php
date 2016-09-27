<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if(count($arResult["ITEMS"]) < 1):
	echo "<p>".GetMessage("REVIEWS_EMPTY_RESULT")."</p>";
	return;
endif;?>

<div class="reviews-list">
	<?foreach($arResult["ITEMS"] as $arItem):?>
		<div class="reviews-item">			
			<div class="item-image-cont">
				<div class="item-image">
					<a href="<?=$arItem['DETAIL_PAGE_URL']?>"<?=!empty($arItem["PICTURE_PREVIEW"]["SRC"]) ? " style='background-image:url(".$arItem["PICTURE_PREVIEW"]["SRC"].");'" : "";?>></a>
				</div>
			</div>			
			<div class="item-block">
				<a class="item-title" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem["NAME"]?></a>				
				<div class="item-text"><?=$arItem["PREVIEW_TEXT"]?></div>
			</div>
		</div>
	<?endforeach;?>
</div>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):
	echo $arResult["NAV_STRING"];
endif;?>