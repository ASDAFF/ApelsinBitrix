<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iblock/iblock.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iblock/prolog.php");
$APPLICATION->SetTitle("Выгрузка изображений");

$_SESSION["uploader"]["message"] = array();
$_SESSION["uploader"]["images_data"] = array();
$_SESSION["uploader"]["elements_id"] = array();
$_SESSION["uploader"]["elements_xml_id"] = array();
$_SESSION["uploader"]["counter"] = 0;

// Задаем константы
define(APLS_ALLOWED_USER, "1");
define(APLS_TEST_DEBUG, FALSE);
define(APLS_IMAGE_KEY, "image");
define(APLS_THUMBS_KEY, "thumbs");

function checkPostValue($key)
{
	return isset($_REQUEST[$key]) && $_REQUEST[$key] !== NULL && $_REQUEST[$key] != "";
}

function getElementString($id)
{
	if (isset($_SESSION["uploader"]["elements_xml_id"][$id]['NAME']) && isset($_SESSION["uploader"]["elements_xml_id"][$id]['XML_ID'])) {
		return $id . " | " . $_SESSION["uploader"]["elements_xml_id"][$id]['XML_ID'] . " | " . $_SESSION["uploader"]["elements_xml_id"][$id]['NAME'];
	} else {
		return $id . " | UNDEFIND | UNDEFIND";
	}
}

function setDebugElementMessage($id)
{
	$_SESSION["uploader"]["message"][] = getElementString($id);
	$_SESSION["uploader"]["message"][] = "IMAGES: " . $_SESSION["uploader"]["images_data"][$id][APLS_IMAGE_KEY];
	$_SESSION["uploader"]["message"][] = "THUMBS: " . $_SESSION["uploader"]["images_data"][$id][APLS_THUMBS_KEY];
	$_SESSION["uploader"]["message"][] = "";
}

function setInfoblockElementsImages($key, $iblock_id, $dir)
{
	// Временный массив изображений
	$arImages = array();
	// получаем список файлов изображенией
	if ($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
			if ($file != "." && $file != ".." && is_file($dir . "/" . $file)) {
				$arImages[] = $file;
			}
		}
	} else {
		$_SESSION["uploader"]["message"][] = "Ошибка открытия каталога $dir";
	}
	if (count($arImages) > 0) {
		// Создаем соответствие изображения элементу инфоблока
		foreach ($arImages as $file) {
			$XML_ID = pathinfo($file, PATHINFO_FILENAME);
			// ищем соответствие внешнего кода к ID элемента инфоблока. Если соответствий нет, то поулчаем ID инфоблока и добавляем соответствие
			if (!isset($_SESSION["uploader"]["elements_id"][$XML_ID])) {
				$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM");
				$arFilter = Array("IBLOCK_ID" => $iblock_id, "XML_ID" => $XML_ID);
				$result = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 50), $arSelect);
				while ($ob = $result->GetNextElement()) {
					$arFields = $ob->GetFields();
					// записываем соответствие внешнего кода к ID элемента инфоблока
					$_SESSION["uploader"]["elements_id"][$XML_ID]["ID"] = $arFields["ID"];
					$_SESSION["uploader"]["elements_id"][$XML_ID]["NAME"] = $arFields["NAME"];
					// записываем соответствие ID к внешнему коду элемента инфоблока
					$_SESSION["uploader"]["elements_xml_id"][$arFields["ID"]]['XML_ID'] = $XML_ID;
					$_SESSION["uploader"]["elements_xml_id"][$arFields["ID"]]['NAME'] = $arFields["NAME"];
				}
			}
			// проверяем соответствие файла к элементу
			if (isset($_SESSION["uploader"]["elements_id"][$XML_ID]["ID"])) {
				$ID = $_SESSION["uploader"]["elements_id"][$XML_ID]["ID"];
				$_SESSION["uploader"]["images_data"][$ID][$key] = $dir . "/" . $file;
			} else {
				$_SESSION["uploader"]["message"][] = "Не найден елемента с внешним кодом { $XML_ID } для файла - $dir/$file";
			}
		}
	} else {
		$_SESSION["uploader"]["message"][] = "Не найдено ни одного изображения";
	}
}

// проверяем был ли получен запрос и если да, то приступаем к обработке
if ($REQUEST_METHOD == "POST" && $_REQUEST["Load"] != "" && $USER->GetID() == APLS_ALLOWED_USER && check_bitrix_sessid()) {
	if (checkPostValue("iblock_id") && checkPostValue("images_dir")) {
		// получаем ID инфоблока
		$_SESSION["uploader"]["iblock_id"] = $_REQUEST["iblock_id"];
		// получаем путь к папке с картинками
		$_SESSION["uploader"]["images_dir"] = $_REQUEST["images_dir"];
		// получаем путь к папке с превьюшками. Если путь не задан, то в качестве превью грузим из каталога с картинками
		if (checkPostValue("thumbs_dir")) {
			$_SESSION["uploader"]["thumbs_dir"] = $_REQUEST["thumbs_dir"];
		} else {
			$_SESSION["uploader"]["thumbs_dir"] = $_SESSION["uploader"]["images_dir"];
		}
		// гененрируем путь к папке с изображениями
		$images_dir = $_SERVER["DOCUMENT_ROOT"] . $_SESSION["uploader"]["images_dir"];
		// гененрируем путь к папке с превью
		$thumbs_dir = $_SERVER["DOCUMENT_ROOT"] . $_SESSION["uploader"]["thumbs_dir"];

		// Проверяем что указанные пути, это пути к каталогам
		if (is_dir($images_dir) && is_dir($thumbs_dir)) {
			// Получение соответствий оригинальным изображениям
			setInfoblockElementsImages(APLS_IMAGE_KEY, $_SESSION["uploader"]["iblock_id"], $images_dir);
			// Проверяем одинаковые ли пути для папок с изображениями и превью
			if ($images_dir == $thumbs_dir) {
				// Если пути одинаковые, то копиурем список превью, в список изображений
				foreach ($_SESSION["uploader"]["images_data"] as $key => $element) {
					$_SESSION["uploader"]["images_data"][$key][APLS_THUMBS_KEY] = $element[APLS_IMAGE_KEY];
				}
			} else {
				// Если пути разные, то поулчаем соответствия превью изображениям
				setInfoblockElementsImages(APLS_THUMBS_KEY, $_SESSION["uploader"]["iblock_id"], $thumbs_dir);
			}
			// Заполняем отсутствующие файлы аналогами из другого типа.
			foreach ($_SESSION["uploader"]["images_data"] as $key => $element) {
				if ((!isset($element[APLS_IMAGE_KEY]) && isset($element[APLS_THUMBS_KEY]))) {
					$_SESSION["uploader"]["images_data"][$key][APLS_IMAGE_KEY] = $element[APLS_THUMBS_KEY];
					$_SESSION["uploader"]["message"][] = "Для товара { " . getElementString($key) . " }  вместо файла изображения используется файл превью";
				} elseif (!isset($element[APLS_THUMBS_KEY]) && isset($element[APLS_IMAGE_KEY])) {
					$_SESSION["uploader"]["images_data"][$key][APLS_THUMBS_KEY] = $element[APLS_IMAGE_KEY];
					$_SESSION["uploader"]["message"][] = "Для товара { " . getElementString($key) . " }  вместо файла превью используется файл изображения";
				}
			}
			// Генерация заголовка для дампа в режиме отладки
			if (APLS_TEST_DEBUG) {
				$_SESSION["uploader"]["message"][] = "";
				$_SESSION["uploader"]["message"][] = "РЕЖИМ ОТЛАДКИ";
				$_SESSION["uploader"]["message"][] = "Изменения в инфоблоке " . $_SESSION["uploader"]["iblock_id"];
				$_SESSION["uploader"]["message"][] = "";
			}
			// Добавляем картинки
			foreach ($_SESSION["uploader"]["images_data"] as $PRODUCT_ID => $FILE_DATA) {
				if (APLS_TEST_DEBUG) {
					setDebugElementMessage($PRODUCT_ID);
				} else {
					$el = new CIBlockElement;
					$arLoadProductArray = Array(
						"MODIFIED_BY" => $USER->GetID(), // элемент изменен текущим пользователем
						"DETAIL_PICTURE" => CFile::MakeFileArray($FILE_DATA[APLS_IMAGE_KEY]),
						"PREVIEW_PICTURE" => CFile::MakeFileArray($FILE_DATA[APLS_THUMBS_KEY])
					);
					$REZULT = intval($el->Update($PRODUCT_ID, $arLoadProductArray));
					if ($REZULT <= 0) {
						$_SESSION["uploader"]["message"][] = "Ошибка загрузки файла: $dir/$file\n";
					} else {
						$_SESSION["uploader"]["counter"]++;
					}
				}
			}
		} else {
			$_SESSION["uploader"]["message"][] = "Указанные пути, должны быть путями к каталогу с картинками";
		}
		if (count($_SESSION["uploader"]["message"]) == 0) {
			$_SESSION["uploader"]["message"][] = "Все файлы были импортированны без замечаний";
		}
		array_unshift($_SESSION["uploader"]["message"], "Изменено " . $_SESSION["uploader"]["counter"] . " елементов инфоблока " . $_SESSION["uploader"]["iblock_id"], "");
	} else {
		$_SESSION["uploader"]["message"][] = "Не все обязательные поля были заполнены";
	}
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

if ($USER->GetID() != APLS_ALLOWED_USER) {
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}
?>
	<p>Для корректной работы скрипта необходимо указать ID инфоблока и папку с изображениями для исходного размера и для
		превью.</p>
	<p>Если не будет указана папка для превью изображений, то в качестве превью будет использованы оригинальыне
		изображения.</p>
	<p>После импорта файлы из папки не удаляюстя, поэтому чтобы не перегружать скрипт рекомендуется удалять файлы после
		импорта.</p>
	<hr>
	<form method="post" name="apls_upload">
		<table cellborder="0" cellpadding="2" cellspacing="0">
			<tr>
				<td>ID инфоблока:</td>
				<td><input type="text" size="45" name="iblock_id"
						   value="<?= htmlspecialchars($_SESSION["uploader"]["iblock_id"]) ?>"></td>
			</tr>
			<tr>
				<td>Оригинальыне:</td>
				<td><input type="text" id="images_dir" name="images_dir" size="30"
						   value="<?= htmlspecialchars($_SESSION["uploader"]["images_dir"]) ?>">
					<input type="button" value="Выбрать" OnClick="BtnClickOrig()">
					<?
					CAdminFileDialog::ShowScript
					(
						Array(
							"event" => "BtnClickOrig",
							"arResultDest" => array("FORM_NAME" => "apls_upload", "FORM_ELEMENT_NAME" => "images_dir"),
							"arPath" => array("SITE" => SITE_ID, "PATH" => "/upload"),
							"select" => 'D',// F - file only, D - folder only
							"operation" => 'O',// O - open, S - save
							"showUploadTab" => true,
							"showAddToMenuTab" => false,
							"fileFilter" => '',
							"allowAllFiles" => true,
							"SaveConfig" => true,
						)
					);
					?></td>
			</tr>
			<tr>
				<td>Превью:</td>
				<td><input type="text" id="thumbs_dir" name="thumbs_dir" size="30"
						   value="<?= htmlspecialchars($_SESSION["uploader"]["thumbs_dir"]) ?>">
					<input type="button" value="Выбрать" OnClick="BtnClickPrew()">
					<?
					CAdminFileDialog::ShowScript
					(
						Array(
							"event" => "BtnClickPrew",
							"arResultDest" => array("FORM_NAME" => "apls_upload", "FORM_ELEMENT_NAME" => "thumbs_dir"),
							"arPath" => array("SITE" => SITE_ID, "PATH" => "/upload"),
							"select" => 'D',// F - file only, D - folder only
							"operation" => 'O',// O - open, S - save
							"showUploadTab" => true,
							"showAddToMenuTab" => false,
							"fileFilter" => '',
							"allowAllFiles" => true,
							"SaveConfig" => true,
						)
					);
					?></td>
			</tr>
		</table>
		<? echo bitrix_sessid_post(); ?>
		<input type="submit" name="Load" value="Загрузить">
	</form>
<?
CAdminMessage::ShowMessage(implode("<br>", $_SESSION["uploader"]["message"]));
unset($_SESSION["uploader"]["message"]);
unset($_SESSION["uploader"]["images_data"]);
unset($_SESSION["uploader"]["elements_id"]);
unset($_SESSION["uploader"]["elements_xml_id"]);
unset($_SESSION["uploader"]["counter"]);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php"); ?>