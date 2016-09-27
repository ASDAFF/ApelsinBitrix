<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require(dirname(__FILE__)."/lang/".LANGUAGE_ID."/script.php");

if($_SERVER["REQUEST_METHOD"] == "POST" && ($_POST["FORM_NAME"] == "ASKPRICE" || $_POST["FORM_NAME"] == "ORDER")) {
	$error = "";

	$REQUIRED = array();
	$REQUIRED = explode("/", $_POST["REQUIRED"]);

	//Проверка заполненности Имени
	if(empty($REQUIRED) || in_array("NAME", $REQUIRED)) {
		if(!isset($_POST["NAME"]) || !strlen($_POST["NAME"])) {
			$error .= GetMessage("NAME_NOT_FILLED")."<br>";
			$return = true;
		}
	}	

	//Проверка заполненности Телефона
	if(empty($REQUIRED) || in_array("TEL", $REQUIRED)) {
		if(!isset($_POST["TEL"]) || !strlen($_POST["TEL"])) {
			$error .= GetMessage("TEL_NOT_FILLED")."<br>";
			$return = true;
		}
	}

	//Проверка заполненности Времени звонка
	if(empty($REQUIRED) || in_array("TIME", $REQUIRED)) {
		if(!isset($_POST["TIME"]) || !strlen($_POST["TIME"])) {
			$error .= GetMessage("TIME_NOT_FILLED")."<br>";
			$return = true;
		}
	}

	//Проверка заполненности Вопроса
	if(empty($REQUIRED) || in_array("MESSAGE", $REQUIRED)) {
		if(!isset($_POST["MESSAGE"]) || !strlen($_POST["MESSAGE"])) {
			$error .= GetMessage("MESSAGE_NOT_FILLED")."<br>";
			$return = true;
		}
	}

	if(!$USER->IsAuthorized()) {
		//Затираем значение введенной капчи
		if($_POST["FORM_NAME"] == "ASKPRICE") {
			echo "<script>BX.adjust(BX('askPriceCaptchaWord-".$_POST["ELEMENT_ID"]."'), {props: {value: ''}});</script>";
		} elseif($_POST["FORM_NAME"] == "ORDER") {
			echo "<script>BX.adjust(BX('orderCaptchaWord-".$_POST["ELEMENT_ID"]."'), {props: {value: ''}});</script>";
		}
		if(!$APPLICATION->CaptchaCheckCode($_POST["CAPTCHA_WORD"], $_POST["CAPTCHA_SID"])) {
	        $error .= GetMessage("WRONG_CAPTCHA")."<br>";
	        $return = true;
		}
	}

	//Если есть ошибки, то выдаем текст ошибки
	if($return == true) {
		//обновляем капчу
	    if(!$USER->IsAuthorized()) {
    		$cCode = $APPLICATION->CaptchaGetCode();
			if($_POST["FORM_NAME"] == "ASKPRICE") {
				echo "<script>BX.adjust(BX('askPriceCImg-".$_POST["ELEMENT_ID"]."'), {props: {src: '/bitrix/tools/captcha.php?captcha_sid=".$cCode."'}});BX.adjust(BX('askPriceCaptchaSid-".$_POST["ELEMENT_ID"]."'), {props: {value: '".$cCode."'}});</script>";
			} elseif($_POST["FORM_NAME"] == "ORDER") {
				echo "<script>BX.adjust(BX('orderCImg-".$_POST["ELEMENT_ID"]."'), {props: {src: '/bitrix/tools/captcha.php?captcha_sid=".$cCode."'}});BX.adjust(BX('orderCaptchaSid-".$_POST["ELEMENT_ID"]."'), {props: {value: '".$cCode."'}});</script>";
			}
		}
		echo "<span class='alertMsg bad'><i class='fa fa-times'></i><span class='text'>".$error."</span></span>";
        return;
    }

	// В случае ошибки, заполненные поля сохраняют свои значения
    $_POST["NAME"]		= iconv("UTF-8", SITE_CHARSET, strip_tags(trim($_POST["NAME"])));    
	$_POST["TEL"]		= iconv("UTF-8", SITE_CHARSET, strip_tags(trim($_POST["TEL"])));
	$_POST["TIME"]		= iconv("UTF-8", SITE_CHARSET, strip_tags(trim($_POST["TIME"])));
	$_POST["MESSAGE"]	= iconv("UTF-8", SITE_CHARSET, strip_tags(trim($_POST["MESSAGE"])));
	
	
	//Отправка письма
	$headers = "From: ".$_POST["EMAIL_TO"]."\r\n";
	$headers .= "Content-type: text/plain; charset=KOI8-R\r\n";
	$headers .= "Mime-Version: 1.0\r\n";

	if($_POST["FORM_NAME"] == "ASKPRICE") {
		$title = SITE_SERVER_NAME.": ".GetMessage("MF_ASK_PRICE_MESSAGE_TITLE");
	} elseif($_POST["FORM_NAME"] == "ORDER") {
		$title = SITE_SERVER_NAME.": ".GetMessage("MF_ORDER_MESSAGE_TITLE");
	}

	$message = GetMessage("MF_MESSAGE_INFO")." ".SITE_SERVER_NAME."\r\n";
	$message.= "------------------------------------------\r\n";
	if($_POST["FORM_NAME"] == "ASKPRICE") {
		$message.= GetMessage("MF_ASK_PRICE_MESSAGE_ZAKAZ")."\n";
	} elseif($_POST["FORM_NAME"] == "ORDER") {
		$message.= GetMessage("MF_ORDER_MESSAGE_ZAKAZ")."\n";
	}
	$message.= GetMessage("MF_MESSAGE_NAME")." ".$_POST["NAME"]."\r\n";	
	$message.= GetMessage("MF_MESSAGE_TEL")." ".$_POST["TEL"]."\r\n";
	$message.= GetMessage("MF_MESSAGE_TIME")." ".$_POST["TIME"]."\r\n";
	$message.= GetMessage("MF_MESSAGE_MESSAGE")." ".$_POST["MESSAGE"]."\r\n";
	$message.= GetMessage("MF_MESSAGE_GENERAT")."\r\n";

	if(mail($_POST["EMAIL_TO"], iconv(SITE_CHARSET, "KOI8-R", $title), iconv(SITE_CHARSET, "KOI8-R//TRANSLIT", $message), $headers)) {
		echo "<span class='alertMsg good'><i class='fa fa-check'></i><span class='text'>".GetMessage("MF_OK_MESSAGE")."</span></span>";
		
		if($_POST["FORM_NAME"] == "ASKPRICE") {			
			echo "<script>BX.adjust(BX('askPriceSendButton-".$_POST["ELEMENT_ID"]."'), {props: {disabled: true}});</script>";
		} elseif($_POST["FORM_NAME"] == "ORDER") {			
			echo "<script>BX.adjust(BX('orderSendButton-".$_POST["ELEMENT_ID"]."'), {props: {disabled: true}});</script>";
		}
	}
}?>