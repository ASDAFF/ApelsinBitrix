<?php

/**
 * Created by PhpStorm.
 * User: olga
 * Date: 08.11.16
 * Time: 9:30
 */

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

class DoItAfterUpdateUser
{
	const HLBL_ID = "2";                    // ID Highload-блока "Kontragenty"
	const SMALL_WHOLESALE_GROUP = "8";      // мелкий опт
	const AVERAGE_WHOLESALE_GROUP = "9";    // средний опт
	const WHOLESALE_GROUP = "10";           // опт
	const BIG_WHOLESALE_GROUP = "11";       // крупный опт
	const DEFAULT_PRICE_TYPE_FOR_REGISTR_USER = "86157e22-e56b-11dc-8b6b-000e0c431b58"; // мелкий опт (1с тип цен)
	const DEFAULT_USER_GROUP_FOR_REGISTR_USER = self::SMALL_WHOLESALE_GROUP;    // мелкий опт (id группы в Bitrix)

	const USER_FIELD_TYPE_KEY = "UF";       // дополнительные поля пользователя
	const USER_GROUP_TYPE_KEY = "UG";       // группы пользователей

	private $APLS_USER;                     // объект пользователя
	private $userID;                        // ID пользователя
	private $dataUsersField;                // данные "штатных" полей пользователя $this->dataUsersField['код поля']
	private $groupID;                       // группа пользователя для update
	private $updateData = array();          // массив изменений (массив для записи обновления полей пользователя)

	private $arTypePriceGroups = [
		self::SMALL_WHOLESALE_GROUP,
		self::AVERAGE_WHOLESALE_GROUP,
		self::WHOLESALE_GROUP,
		self::BIG_WHOLESALE_GROUP
	];                                      // массив соответствия содержащий ID групп пользователя

	private $arTypePrice = [
		"86157e22-e56b-11dc-8b6b-000e0c431b58" => self::SMALL_WHOLESALE_GROUP,
		"feff0693-99ab-11db-937f-000e0c431b59" => self::AVERAGE_WHOLESALE_GROUP,
		"feff0694-99ab-11db-937f-000e0c431b59" => self::WHOLESALE_GROUP,
		"feff0695-99ab-11db-937f-000e0c431b59" => self::BIG_WHOLESALE_GROUP
	];

	public function __construct(&$arFields)
	{
		$this->APLS_USER = new CUser;
		$this->userID = isset($arFields['USER_ID']) ?
			$arFields['USER_ID'] :
			(isset($arFields['ID']) ?
				$arFields['ID'] :
				$this->APLS_USER->GetID());
		$rsUser = CUser::GetByID($this->userID);
		$this->dataUsersField = $rsUser->Fetch();
	}

	/**
	 * Основная фукция обновления данных пользователя
	 */
	public function updateUser()
	{
		// если номер карты введен
		if (isset($this->dataUsersField["UF_CARD_NUMBER"]) && $this->dataUsersField["UF_CARD_NUMBER"] != "") {
			// проверка номера карты на дубликат
			if (!$this->issetNumberCard()) {
				// получаем данные о контрагентах
				$dataContractor = $this->getDataUserHLBl();
				// если они совпадают с данными о пользователе
				if (
					$dataContractor &&
					APLS_TextInspections::chekFIOString(
						$dataContractor["UF_NAME"],
						$this->dataUsersField["NAME"],
						$this->dataUsersField["LAST_NAME"]
					) &&
					$this->dataUsersField["UF_CARD_NUMBER"] == $dataContractor["UF_NOMERKARTYKLIENTA"]
				) {
					$priceType = $this->getUserPriceType($dataContractor["UF_OSNOVNOYTIPTSEN"]);
					$this->groupID = $this->getUserGroup($priceType);
					$update = array(
						"UF_MESSAGE_ERROR" => null,
						"UF_1C_TYPE_PRICE" => $priceType
					);
					$this->addUpdateDate(self::USER_FIELD_TYPE_KEY, $update);
				} else {  // если не совпадают данные о контрагентах с данными о пользователе - ошибка ввода данных
					$update = array(
						"UF_MESSAGE_ERROR" => "error",
						"UF_CARD_NUMBER" => null,
						"UF_1C_TYPE_PRICE" => self::DEFAULT_PRICE_TYPE_FOR_REGISTR_USER,
					);
					$this->addUpdateDate(self::USER_FIELD_TYPE_KEY, $update);
				}
			} else {  // если используется дубликат - ошибка - номер занят
				$update = array(
					"UF_MESSAGE_ERROR" => "error1",
					"UF_CARD_NUMBER" => null,
					"UF_1C_TYPE_PRICE" => self::DEFAULT_PRICE_TYPE_FOR_REGISTR_USER,
				);
				$this->addUpdateDate(self::USER_FIELD_TYPE_KEY, $update);
			}
		} else {  // если номер карты не введен
			// включаем пользователя в группу "Мелкий опт"
			// заносим соответствующий тип цен
			$update = Array(
				"UF_1C_TYPE_PRICE" => self::DEFAULT_PRICE_TYPE_FOR_REGISTR_USER
			);
			$this->addUpdateDate(self::USER_FIELD_TYPE_KEY, $update);
		}
		// генерируем внешний код пользователя, если его нет
		$this->checkXML_ID();
		// Обновление групп
		$this->groupDataUpdate();
		// делаем Update
		$this->executeUpdate();
	}

	/**
	 * Выполняем обновление всех изменяемых данных пользователя
	 */
	private function executeUpdate()
	{
		// данные по полям
		$this->unsetDuplicateData(self::USER_FIELD_TYPE_KEY, "UF_MESSAGE_ERROR");
		$this->unsetDuplicateData(self::USER_FIELD_TYPE_KEY, "UF_1C_TYPE_PRICE");
		if (
			isset($this->updateData[self::USER_FIELD_TYPE_KEY]) &&
			!empty($this->updateData[self::USER_FIELD_TYPE_KEY])
		) {
			$this->APLS_USER->Update($this->userID, $this->updateData[self::USER_FIELD_TYPE_KEY]);
		}
		// данные по группам
		if (
			isset($this->updateData[self::USER_GROUP_TYPE_KEY]) &&
			!empty($this->updateData[self::USER_GROUP_TYPE_KEY])
		) {
			CUser::SetUserGroup($this->userID, $this->updateData[self::USER_GROUP_TYPE_KEY]);
			if ($this->APLS_USER->GetID() == $this->userID) {
				$this->APLS_USER->SetUserGroupArray($this->updateData[self::USER_GROUP_TYPE_KEY]);
			}
		}
	}

	/**
	 * Редактирование массива измениений (оставляем только ИЗМЕНЯЕМЫЕ данные)
	 * @param $type - тип данных
	 * @param $key - поле
	 */
	private function unsetDuplicateData($type, $key)
	{
		if (
			(isset($this->updateData[$type][$key]) && $this->updateData[$type][$key] == $this->dataUsersField[$key]) ||
			(is_null($this->updateData[$type][$key]) && is_null($this->dataUsersField[$key]))
		) {
			unset($this->updateData[$type][$key]);
		}
	}

	/**
	 * Фактически получаем ID группы по типу цены для пользоватля
	 * @param $typePrice
	 * @return string
	 */
	private function getUserPriceType($typePrice)
	{
		return isset($this->arTypePrice[$typePrice]) ? $typePrice : self::DEFAULT_PRICE_TYPE_FOR_REGISTR_USER;
	}

	/**
	 * Страхуемся на случай вознкновения неизвестного типа цены
	 * @param $typePrice
	 * @return mixed|string
	 */
	private function getUserGroup($typePrice)
	{
		return isset($this->arTypePrice[$typePrice]) ?
			$this->arTypePrice[$typePrice] :
			self::DEFAULT_USER_GROUP_FOR_REGISTR_USER;
	}

	/**
	 * Подготавливаем данные о группах пользователя для Update
	 */
	private function groupDataUpdate()
	{
		// Данные о текущих группах пользователя
		$arCurrentGroup = CUser::GetUserGroup($this->userID);
		// данные о нужных группах пользователя
		if (!isset($this->groupID) || $this->groupID === self::DEFAULT_USER_GROUP_FOR_REGISTR_USER) {
			$newUserGroup = array(self::DEFAULT_USER_GROUP_FOR_REGISTR_USER);
		} else {
			$newUserGroup = array($this->groupID, self::DEFAULT_USER_GROUP_FOR_REGISTR_USER);
		}
		// удаляем из массива соответствий типов цен нужные группы
		$groupArr = array_diff($this->arTypePriceGroups, $newUserGroup);
		// соединяем нужные с текущими группами пользователя
		$newUserGroupArr = array_unique(array_merge($arCurrentGroup, $newUserGroup));
		// удаляем, если они были, другие "ценовые" группы у пользователя
		$newUserGroupArr = array_diff($newUserGroupArr, $groupArr);
		// заносим в массив изменений
		if (
			!empty(array_diff($arCurrentGroup, $newUserGroupArr)) ||
			!empty(array_diff($newUserGroupArr, $arCurrentGroup))
		) {
			$this->addUpdateDate(self::USER_GROUP_TYPE_KEY, $newUserGroupArr);
		}
	}

	/**
	 * Генерирование массива изменений
	 * @param $type - USER_FIELD_TYPE_KEY | USER_GROUP_TYPE_KEY
	 * @param array $date - массив с данными формата key => value
	 */
	private function addUpdateDate($type, array $date)
	{
		if (isset($this->updateData[$type]) && !empty($this->updateData[$type])) {
			$this->updateData[$type] = array_merge($this->updateData[$type], $date);
		} else {
			$this->updateData[$type] = $date;
		}
	}

	/**
	 * Пооверка наличия (и генерирования при необходимости) XML_ID у пользователя
	 */
	private function checkXML_ID()
	{
		if (APLS_TextInspections::isEmpty($this->dataUsersField['XML_ID'])) {
			$data = Array(
				"XML_ID" => ID_GENERATOR::generateID()
			);
			$this->addUpdateDate(self::USER_FIELD_TYPE_KEY, $data);
		}
	}

	/**
	 * Получить данные о всех контрагентах
	 * @return array|false
	 */
	public function getDataUserHLBl()
	{
		CModule::IncludeModule("highloadblock");
		$hlblock = HL\HighloadBlockTable::getById(self::HLBL_ID)->fetch();
		$entity = HL\HighloadBlockTable::compileEntity($hlblock);
		$entity_data_class = $entity->getDataClass();
		$rsData = $entity_data_class::getList(
			array(
				'select' => array('*'),
				'filter' => array('UF_NOMERKARTYKLIENTA' => $this->dataUsersField["UF_CARD_NUMBER"])
			)
		);
		$dataUser = $rsData->fetch();
		return $dataUser;
	}

	/**
	 * Проверка карты пользователя на дубликат
	 * @return bool
	 */
	private function issetNumberCard()
	{
		$filter = Array("UF_CARD_NUMBER" => $this->dataUsersField["UF_CARD_NUMBER"]);
		$by = "ID";
		$order = "desc";
		$rsUsers = CUser::GetList($by, $order, $filter, array("SELECT" => array("UF_*"))); // выбираем пользователей
		while ($el = $rsUsers->fetch()) {
			if ($el['ID'] != $this->userID) {
				return true;
			}
		}
		return false;
	}
}