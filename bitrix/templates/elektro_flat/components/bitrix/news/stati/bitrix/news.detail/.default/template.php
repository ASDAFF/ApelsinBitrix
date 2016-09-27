<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);?>

<div class="news-detail">
	<?if(is_array($arResult["DETAIL_PICTURE"])):?>
		<img class="detail_picture" src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" width="<?=$arResult['DETAIL_PICTURE']['WIDTH']?>" height="<?=$arResult['DETAIL_PICTURE']['HEIGHT']?>" alt="<?=$arResult['NAME']?>" />
	<?endif;?>
	<div class="detail-text"><?=$arResult["DETAIL_TEXT"]?></div>
</div>

<?if(is_array($arResult["PREV_ITEM"]) || is_array($arResult["NEXT_ITEM"])):?>
	<ul class="stati_prev_next"> 
		<?if(is_array($arResult["PREV_ITEM"])):?>
			<li class="prev">
				<a href="<?=$arResult['PREV_ITEM']['URL']?>">
					<span class="arrow_prev"></span>
					<span class="image_cont">
						<span class="image">
							<i<?=!empty($arResult["PREV_ITEM"]["PREVIEW_PICTURE"]["src"]) ? " style='background-image:url(".$arResult["PREV_ITEM"]["PREVIEW_PICTURE"]["src"].");'" : "";?>></i>
						</span>
					</span>					
					<span class="title-link"><?=$arResult["PREV_ITEM"]["NAME"]?></span>
				</a>
			</li>
		<?endif;
		if(is_array($arResult["NEXT_ITEM"])):?>
			<li class="next">
				<a href="<?=$arResult['NEXT_ITEM']['URL']?>">
					<span class="title-link"><?=$arResult["NEXT_ITEM"]["NAME"]?></span>
					<span class="image_cont">
						<span class="image">
							<i<?=!empty($arResult["NEXT_ITEM"]["PREVIEW_PICTURE"]["src"]) ? " style='background-image:url(".$arResult["NEXT_ITEM"]["PREVIEW_PICTURE"]["src"].");'" : "";?>></i>
						</span>
					</span>					
					<span class="arrow_next"></span>
				</a>
			</li>
		<?endif;?>
	</ul>
<?endif;?>