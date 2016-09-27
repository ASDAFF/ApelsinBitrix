<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="kabinet" id="kabinet">
	<?$frame = $this->createFrame("kabinet")->begin("");	
	if(!$USER->IsAuthorized()):?>
		<a class="login_anch" href="javascript:void(0)" title="<?=GetMessage("LOGIN")?>" onclick="OpenLoginPopup();"><i class="fa fa-user"></i><span><?=GetMessage("LOGIN")?></span></a>
		<a class="register" href="<?=SITE_DIR?>personal/profile/?register=yes" title="<?=GetMessage("REGISTRATION")?>" rel="nofollow"><i class="fa fa-user-plus"></i><span><?=GetMessage("REGISTRATION")?></span></a>
	<?else:?>
		<a class="personal" href="<?=SITE_DIR?>personal/" title="<?=GetMessage("PERSONAL")?>" rel="nofollow"><i class="fa fa-user"></i><span><?=GetMessage("PERSONAL")?></span></a>
	<?endif;
	$popupParams["RESULT"] = $arResult;
	$popupParams["MESS"] = array(	
		"AUTH_LOGIN" => GetMessage("AUTH_LOGIN"),
		"AUTH_PASSWORD" => GetMessage("AUTH_PASSWORD"),
		"LOGIN" => GetMessage("LOGIN"),
		"AUTH_FORGOT_PASSWORD" => GetMessage("AUTH_FORGOT_PASSWORD"),
		"LOGIN_AS_USER" => GetMessage("LOGIN_AS_USER")	
	);?>
	<script type="text/javascript">	
		function OpenLoginPopup() {		
			BX.LoginSet =
			{			
				popup: null,
				arParams: {}
			};
			BX.LoginSet.popup = BX.PopupWindowManager.create("login", null, {
				autoHide: true,
				offsetLeft: 0,
				offsetTop: 0,			
				overlay: {
					opacity: 100
				},
				draggable: false,
				closeByEsc: false,
				closeIcon: { right : "-10px", top : "-10px"},			
				titleBar: false,
				content: "<div class='popup-window-wait'><i class='fa fa-spinner fa-pulse'></i></div>",				
				events: {				
					onAfterPopupShow: function()
					{
						if(!BX("loginForm")) {
							BX.ajax.post(
								"<?=$this->GetFolder();?>/popup.php",
								{							
									arParams: <?=CUtil::PhpToJSObject($popupParams)?>
								},
								BX.delegate(function(result)
								{
									this.setContent(result);
								},
								this)
							);					
						}
					}
				}			
			});
			
			BX.addClass(BX("login"), "pop-up login");
			close = BX.findChildren(BX("login"), {className: "popup-window-close-icon"}, true);
			if(!!close && 0 < close.length) {
				for(i = 0; i < close.length; i++) {					
					close[i].innerHTML = "<i class='fa fa-times'></i>";
				}
			}		
			
			BX("kabinet").appendChild(BX("popup-window-overlay-login"));
			BX("kabinet").appendChild(BX("login"));		
			
			BX.LoginSet.popup.show();		
		}
	</script>
	<?$frame->end();?>
</div>