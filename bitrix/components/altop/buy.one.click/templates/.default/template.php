<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

$intElementID = $arResult["ELEMENT_ID"];

$popupParams["COMPONENT_PATH"] = $this->__component->__path;
$popupParams["FORM_ACTION"] = POST_FORM_ACTION_URI;
$popupParams["PARAMS"] = $arParams;
$popupParams["ELEMENT_ID"] = $intElementID;
$popupParams["ELEMENT_NAME"] = $arResult["ELEMENT_NAME"];
$popupParams["PREVIEW_IMG"] = $arResult["PREVIEW_IMG"];
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
	"MFT_BUY" => GetMessage("MFT_BOC_BUY"),
	"MFT_BOC_DESCRIPTION" => GetMessage("MFT_BOC_DESCRIPTION")
);?>

<script type="text/javascript">	
	BX.bind(BX("boc_anch_<?=$arParams['ELEMENT_CODE'].$intElementID?>"), "click", function() {
		BX.BocSet =
		{			
			popup: null,
			arParams: {}
		};
		BX.BocSet.popup = BX.PopupWindowManager.create("boc-<?=$arParams['ELEMENT_CODE'].$intElementID?>", null, {
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
					if(!BX("bocForm-<?=$arParams['ELEMENT_CODE'].$intElementID?>")) {
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

								setWindow = BX("boc-<?=$arParams['ELEMENT_CODE'].$intElementID?>");
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
							var selPropValueArr = [];
							ActiveItems = BX.findChildren(BX("<?=$arParams['SELECT_PROP_DIV']?>"), {tagName: "li", className: "active"}, true);
							if(!!ActiveItems && 0 < ActiveItems.length) {
								for(i = 0; i < ActiveItems.length; i++) {
									selPropValueArr[i] = ActiveItems[i].getAttribute("data-select-onevalue");			
								}
							}
							if(0 < selPropValueArr.length) {
								selPropValue = selPropValueArr.join("||");
								BX("bocElementSelectProps-<?=$arParams['ELEMENT_CODE'].$intElementID?>").value = selPropValue;
							}
						<?endif;?>
						/***QUANTITY***/
						BX("bocQuantity-<?=$arParams['ELEMENT_CODE'].$intElementID?>").value = BX("quantity_<?=$arParams['ELEMENT_CODE'].$intElementID?>").value;
					}
				}
			}
		});
		
		BX.addClass(BX("boc-<?=$arParams['ELEMENT_CODE'].$intElementID?>"), "pop-up boc");
		close = BX.findChildren(BX("boc-<?=$arParams['ELEMENT_CODE'].$intElementID?>"), {className: "popup-window-close-icon"}, true);
		if(!!close && 0 < close.length) {
			for(i = 0; i < close.length; i++) {					
				close[i].innerHTML = "<i class='fa fa-times'></i>";
			}
		}

		BX.BocSet.popup.show();		
	});
</script>