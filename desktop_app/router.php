<?
define("BX_IM_FULLSCREEN", true);
define("EXTRANET_NO_REDIRECT", true);

if (isset($_GET['alias']))
{
	define("BX_SKIP_SESSION_EXPAND", true);
	if (!isset($_GET['iframe']))
	{
		define("BX_PULL_SKIP_INIT", true);
	}
	define("BX_PULL_COMMAND_PATH", "/desktop_app/pull.ajax.php");
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/im/install/public/desktop_app/router.php");

$APPLICATION->SetTitle(GetMessage("IM_ROUTER_PAGE_TITLE"));
$APPLICATION->IncludeComponent("bitrix:im.router", "", Array(), false, Array("HIDE_ICONS" => "Y"));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
