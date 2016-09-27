<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

$intElementID = $arResult["ELEMENT_ID"];

$popupParams["COMPONENT_PATH"] = $this->__component->__path;
$popupParams["FORM_ACTION"] = POST_FORM_ACTION_URI;
$popupParams["PARAMS"] = $arParams;
$popupParams["ELEMENT_ID"] = $intElementID;
$popupParams["CAPTCHA_CODE"] = $arResult["CAPTCHA_CODE"];
$popupParams["REQUIRED"] = $arResult["REQUIRED"];
$popupParams["NAME"] = $arResult["NAME"];
$popupParams["EMAIL"] = $arResult["EMAIL"];
$popupParams["MESS"] = array(	
	"MFT_NAME" => GetMessage("MFT_BOC_NAME"),
	"MFT_TEL" => GetMessage("MFT_BOC_TEL"),
	"MFT_EMAIL" => GetMessage("MFT_BOC_EMAIL"),
	"MFT_MESSAGE" => GetMessage("MFT_BOC_MESSAGE"),
	"MFT_CAPTCHA" => GetMessage("MFT_BOC_CAPTCHA"),
	"MFT_BUY" => GetMessage("MFT_BOC_BUY")
);?>

<script type="text/javascript">	
	BX.ready(function() {
		BX.bind(BX("boc_anch_<?=$intElementID?>"), "click", function() {
			BX.BocSet =
			{			
				popup: null,
				arParams: {}
			};
			BX.BocSet.popup = BX.PopupWindowManager.create("boc-<?=$intElementID?>", null, {
				autoHide: true,
				offsetLeft: 0,
				offsetTop: 0,
				overlay: {
					opacity: 100
				},
				draggable: false,
				closeByEsc: false,
				closeIcon: { right : "-10px", top : "-10px"},
				titleBar: {content: BX.create("span", {html: "<?=GetMessage('MFT_BOC_TITLE')?>"})},
				content: "<div class='popup-window-wait'><i class='fa fa-spinner fa-pulse'></i></div>",
				events: {
					onAfterPopupShow: function()					
					{						
						if(!BX("bocForm-<?=$intElementID?>")) {
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

									setWindow = BX("boc-<?=$intElementID?>");
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
			
			BX.addClass(BX("boc-<?=$intElementID?>"), "pop-up boc_cart");
			close = BX.findChildren(BX("boc-<?=$intElementID?>"), {className: "popup-window-close-icon"}, true);
			if(!!close && 0 < close.length) {
				for(i = 0; i < close.length; i++) {					
					close[i].innerHTML = "<i class='fa fa-times'></i>";
				}
			}

			BX.BocSet.popup.show();		
		});
	});
</script>