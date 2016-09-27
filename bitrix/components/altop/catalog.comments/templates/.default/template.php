<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

$frame = $this->createFrame("review")->begin("");

$popupParams["COMPONENT_PATH"] = $this->__component->__path;
$popupParams["FORM_ACTION"] = POST_FORM_ACTION_URI;
$popupParams["PARAMS"] = $arParams;
$popupParams["RESULT"] = $arResult;
$popupParams["MESS"] = array(	
	"MFT_NAME" => GetMessage("MFT_REVIEW_NAME"),
	"MFT_REVIEW" => GetMessage("MFT_REVIEW_REVIEW"),	
	"MFT_CAPTCHA" => GetMessage("MFT_REVIEW_CAPTCHA"),
	"MFT_SEND" => GetMessage("MFT_REVIEW_SEND"),
	"MFT_NON_AUTH" => GetMessage("MFT_REVIEW_NON_AUTH")
);?>

<div class="reviews-collapse reviews-minimized">
	<a class="btn_buy apuo reviews-collapse-link" href="javascript:void(0)" onclick="OpenReviewPopup();"><i class="fa fa-pencil"></i><span><?=GetMessage("MFT_REVIEW_TITLE")?></span></a>
</div>
<div class="clr"></div>

<?if($arResult["COMMENTS_COUNT"]):
	$count = 0;
	foreach($arResult["COMMENTS"] as $arReview):?>
    	<div id="comment_<?=$arReview['ID']?>" class="comment">
			<div class="userpic">
				<?if(!empty($arReview["USER"]["PICT"]["SRC"])):?>
					<img src="<?=$arReview["USER"]["PICT"]["SRC"]?>" width="<?=$arReview["USER"]["PICT"]["WIDTH"]?>" height="<?=$arReview["USER"]["PICT"]["HEIGHT"]?>" alt="userpic" />
				<?else:?>
					<img src="<?=SITE_TEMPLATE_PATH?>/images/userpic.jpg" width="57" height="57" alt="userpic" />
				<?endif;?>
			</div>
			<div class="text">
				<span class="comment_name"><?=$arReview["USER"]["NAME"]?></span>
				<span class="comment_date"><?=$arReview["DATE"]?></span>
				<span class="comment_text"><?=$arReview["TEXT"]?></span>
			</div>
		</div>
		<?$count++;
	endforeach;?>
	<div class="clr"></div>
<?endif;?>

<script type="text/javascript">
	function OpenReviewPopup() {		
		BX.ReviewSet =
		{			
			popup: null,
			arParams: {}
		};
		BX.ReviewSet.popup = BX.PopupWindowManager.create("review", null, {
			autoHide: true,
			offsetLeft: 0,
			offsetTop: 0,			
			overlay: {
				opacity: 100
			},
			draggable: false,
			closeByEsc: false,
			closeIcon: { right : "-10px", top : "-10px"},
			titleBar: {content: BX.create("span", {html: "<?=GetMessage('MFT_REVIEW_TITLE')?>"})},
			content: "<div class='popup-window-wait'><i class='fa fa-spinner fa-pulse'></i></div>",			
			events: {
				onAfterPopupShow: function()
				{
					if(!BX("reviewForm")) {
						BX.ajax.post(
							"<?=$this->GetFolder();?>/popup.php",
							{							
								arParams: <?=CUtil::PhpToJSObject($popupParams)?>
							},
							BX.delegate(function(result)
							{
								var wndScroll = BX.GetWindowScrollPos(),
									wndSize = BX.GetWindowInnerSize(),
									setWindow,
									popupTop;
								
								this.setContent(result);

								setWindow = BX("review");
								if(!!setWindow)
								{
									popupTop = wndScroll.scrollTop + (wndSize.innerHeight - setWindow.offsetHeight)/2;
									setWindow.style.left = (wndSize.innerWidth - setWindow.offsetWidth)/2 + "px";
									setWindow.style.top = popupTop > 0 ? popupTop + "px" : 0;
								}
							},
							this)
						);
					}
				}
			}			
		});
		
		BX.addClass(BX("review"), "pop-up review");
		close = BX.findChildren(BX("review"), {className: "popup-window-close-icon"}, true);
		if(!!close && 0 < close.length) {
			for(i = 0; i < close.length; i++) {					
				close[i].innerHTML = "<i class='fa fa-times'></i>";
			}
		}

		BX.ReviewSet.popup.show();		
	}
</script>

<?$frame->end();?>