<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

$intElementID = $arParams["ELEMENT_ID"];

$popupParams["COMPONENT_PATH"] = $this->__component->__path;
$popupParams["FORM_ACTION"] = POST_FORM_ACTION_URI;
$popupParams["PARAMS"] = $arParams;
$popupParams["ELEMENT_ID"] = $intElementID;
$popupParams["ELEMENT_NAME"] = $arResult["ELEMENT_NAME"];
$popupParams["PREVIEW_IMG"] = $arResult["PREVIEW_IMG"];
$popupParams["CAPTCHA_CODE"] = $arResult["CAPTCHA_CODE"];
$popupParams["EMAIL_TO"] = $arResult["EMAIL_TO"];
$popupParams["REQUIRED"] = $arResult["REQUIRED"];
$popupParams["NAME"] = $arResult["NAME"];
$popupParams["MESSAGE"] = $arResult["MESSAGE"];
$popupParams["MESS"] = array(	
	"MFT_NAME" => GetMessage("MFT_ASKPRICE_NAME"),
	"MFT_TEL" => GetMessage("MFT_ASKPRICE_TEL"),
	"MFT_TIME" => GetMessage("MFT_ASKPRICE_TIME"),
	"MFT_MESSAGE" => GetMessage("MFT_ASKPRICE_MESSAGE"),
	"MFT_CAPTCHA" => GetMessage("MFT_ASKPRICE_CAPTCHA"),
	"MFT_REQUEST" => GetMessage("MFT_ASKPRICE_REQUEST")
);?>

<script type="text/javascript">		
	BX.bind(BX("ask_price_anch_<?=$intElementID?>"), "click", function() {
		BX.AskPriceSet =
		{			
			popup: null,
			arParams: {}
		};
		BX.AskPriceSet.popup = BX.PopupWindowManager.create("askPrice-<?=$intElementID?>", null, {
			autoHide: true,
			offsetLeft: 0,
			offsetTop: 0,
			overlay: {
				opacity: 100
			},
			draggable: false,
			closeByEsc: false,
			closeIcon: { right : "-10px", top : "-10px"},
			titleBar: {content: BX.create("span", {html: "<?=GetMessage('MFT_ASKPRICE_TITLE')?>"})},
			content: "<div class='popup-window-wait'><i class='fa fa-spinner fa-pulse'></i></div>",
			events: {
				onAfterPopupShow: function()					
				{						
					if(!BX("askPriceForm-<?=$intElementID?>")) {
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

								setWindow = BX("askPrice-<?=$intElementID?>");
								if(!!setWindow)
								{
									popupTop = wndScroll.scrollTop + (wndSize.innerHeight - setWindow.offsetHeight)/2;
									setWindow.style.left = (wndSize.innerWidth - setWindow.offsetWidth)/2 + "px";
									setWindow.style.top = popupTop > 0 ? popupTop + "px" : 0;
								}
							},
							this)
						);
					} else {
						/***SELECT_PROPS***/
						<?if(!empty($arParams["SELECT_PROP_DIV"])):?>						
							var selAskOrderValueArr = [],
								askPriceMessageArr = [];
							ActiveItems = BX.findChildren(BX("<?=$arParams['SELECT_PROP_DIV']?>"), {tagName: "li", className: "active"}, true);
							if(!!ActiveItems && 0 < ActiveItems.length) {
								for(i = 0; i < ActiveItems.length; i++) {			
									SelectName = BX.findChildren(ActiveItems[i].parentNode.parentNode.parentNode, {className: "h3"}, true);
									SelectValue = BX.findChildren(ActiveItems[i], {tagName: "span"}, true);
									if((!!SelectName && 0 < SelectName.length) && (!!SelectValue && 0 < SelectValue.length)) {					
										selAskOrderValueArr[i] = SelectName[0].innerHTML+': '+SelectValue[0].innerHTML;
									}
								}
							}
							if(0 < selAskOrderValueArr.length) {
								selAskOrderValue = selAskOrderValueArr.join('; ');								
								askPriceMessageArr = "<?=$arResult['MESSAGE']?>".split(')');
								askPriceMessageNew = askPriceMessageArr[0];
								BX("askPriceMessage-<?=$intElementID?>").innerHTML = askPriceMessageNew + "; " + selAskOrderValue + ")";
							}
						<?endif;?>
					}
				}
			}
		});

		BX.addClass(BX("askPrice-<?=$intElementID?>"), "pop-up ask-price");
		close = BX.findChildren(BX("askPrice-<?=$intElementID?>"), {className: "popup-window-close-icon"}, true);
		if(!!close && 0 < close.length) {
			for(i = 0; i < close.length; i++) {					
				close[i].innerHTML = "<i class='fa fa-times'></i>";
			}
		}

		BX.AskPriceSet.popup.show();
	});
</script>