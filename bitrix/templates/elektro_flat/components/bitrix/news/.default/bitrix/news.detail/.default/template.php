<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);?>

<div class="news-detail">
	<?if($arParams["DISPLAY_DATE"] == "Y"):?>
		<div class="news-date"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></div>
	<?endif?>
	<?if(is_array($arResult["DETAIL_PICTURE"])):?>
		<img class="detail_picture" src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>" height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>" alt="<?=$arResult["NAME"]?>" />
	<?endif?>
	<div class="detail-text"><?=$arResult["DETAIL_TEXT"];?></div>
</div>

<?if(is_array($arResult["PREV_ITEM"]) || is_array($arResult["NEXT_ITEM"])):?>
	<ul class="news_prev_next"> 
		<?if(is_array($arResult["PREV_ITEM"])):?>
			<li class="prev">
				<a href="<?=$arResult["PREV_ITEM"]["URL"]?>">
					<span class="date"><?=$arResult["PREV_ITEM"]["DISPLAY_ACTIVE_FROM"]?></span>
					<span class="title-link">
						<span><?=$arResult["PREV_ITEM"]["NAME"]?></span>
					</span>
				</a>
			</li>
		<?endif?>
		<?if(is_array($arResult["NEXT_ITEM"])):?>
			<li class="next">
				<a href="<?=$arResult["NEXT_ITEM"]["URL"]?>">
					<span class="title-link">
						<span><?=$arResult["NEXT_ITEM"]["NAME"]?></span>
					</span>
					<span class="date"><?=$arResult["NEXT_ITEM"]["DISPLAY_ACTIVE_FROM"]?></span>
				</a>
			</li>
		<?endif?>
	</ul>
<?endif?>