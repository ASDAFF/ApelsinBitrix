<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

$frame = $this->createFrame("callback")->begin("");

$popupParams["COMPONENT_PATH"] = $this->__component->__path;
$popupParams["FORM_ACTION"] = POST_FORM_ACTION_URI;
$popupParams["PARAMS"] = $arParams;
$popupParams["CAPTCHA_CODE"] = $arResult["CAPTCHA_CODE"];
$popupParams["EMAIL_TO"] = $arResult["EMAIL_TO"];
$popupParams["REQUIRED"] = $arResult["REQUIRED"];
$popupParams["NAME"] = $arResult["NAME"];
$popupParams["MESS"] = array(	
	"MFT_NAME" => GetMessage("MFT_CALLBACK_NAME"),
	"MFT_TEL" => GetMessage("MFT_CALLBACK_TEL"),
	"MFT_TIME" => GetMessage("MFT_CALLBACK_TIME"),
	"MFT_MESSAGE" => GetMessage("MFT_CALLBACK_MESSAGE"),
	"MFT_CAPTCHA" => GetMessage("MFT_CALLBACK_CAPTCHA"),
	"MFT_ORDER" => GetMessage("MFT_CALLBACK_ORDER")
);?>

<script type="text/javascript">
	function OpenCallbackPopup() {		
		BX.CallbackSet =
		{			
			popup: null,
			arParams: {}
		};
		BX.CallbackSet.popup = BX.PopupWindowManager.create("callback", null, {
			autoHide: true,
			offsetLeft: 0,
			offsetTop: 0,			
			overlay: {
				opacity: 100
			},
			draggable: false,
			closeByEsc: false,
			closeIcon: { right : "-10px", top : "-10px"},
			titleBar: {content: BX.create("span", {html: "<?=GetMessage('MFT_CALLBACK_TITLE')?>"})},
			content: "<div class='popup-window-wait'><i class='fa fa-spinner fa-pulse'></i></div>",			
			events: {
				onAfterPopupShow: function()
				{
					if(!BX("callbackForm")) {
						BX.ajax.post(
							'<?=$this->GetFolder();?>/popup.php',
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

								setWindow = BX("callback");
								if(!!setWindow)
								{
									popupTop = wndScroll.scrollTop + (wndSize.innerHeight - setWindow.offsetHeight)/2;
									setWindow.style.left = (wndSize.innerWidth - setWindow.offsetWidth)/2 +"px";
									setWindow.style.top = popupTop > 0 ? popupTop+"px" : 0;
								}
							},
							this)
						);
					}
				}
			}			
		});
		
		BX.addClass(BX("callback"), "pop-up callback");
		close = BX.findChildren(BX("callback"), {className: "popup-window-close-icon"}, true);
		if(!!close && 0 < close.length) {
			for(i = 0; i < close.length; i++) {					
				close[i].innerHTML = "<i class='fa fa-times'></i>";
			}
		}

		BX.CallbackSet.popup.show();		
	}
</script>

<?$frame->end();?>