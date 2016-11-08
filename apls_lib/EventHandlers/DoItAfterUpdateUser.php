<?php
/**
 * Created by PhpStorm.
 * User: olga
 * Date: 07.10.16
 * Time: 11:32
 */
	use Bitrix\Highloadblock as HL;
	use Bitrix\Main\Entity;

	class DoItAfterUpdateUserExecute {

		public function OnAfterUserUpdateHandler (&$arFields) {
			$update = new DoItAfterUpdateUserMain($arFields);
			$update->updateUser();
		}
	}

	class DoItAfterUpdateUserMain {

		const HLBL_ID = "2";                    // ID Highload-блока "Kontragenty"
		const SMALL_WHOLESALE_GROUP = "8";      // мелкий опт
		const AVERAGE_WHOLESALE_GROUP = "9";    // средний опт
		const WHOLESALE_GROUP = "10";           // опт
		const BIG_WHOLESALE_GROUP = "11";       // крупный опт
		const SMALL_WHOLESALE_1C = "86157e22-e56b-11dc-8b6b-000e0c431b58";      // мелкий опт (1с тип цен)

		private $arTypePriceGroups = [
			self::SMALL_WHOLESALE_GROUP,
			self::AVERAGE_WHOLESALE_GROUP,
			self::WHOLESALE_GROUP,
			self::BIG_WHOLESALE_GROUP
		];
		private $arTypePrice= [
			"86157e22-e56b-11dc-8b6b-000e0c431b58" => self::SMALL_WHOLESALE_GROUP,
			"feff0693-99ab-11db-937f-000e0c431b59" => self::AVERAGE_WHOLESALE_GROUP,
			"feff0694-99ab-11db-937f-000e0c431b59" => self::WHOLESALE_GROUP,
			"feff0695-99ab-11db-937f-000e0c431b59" => self::BIG_WHOLESALE_GROUP
		];

		private $arFields;              // данные о обрабатываемом пользователе
		private $userID;                // ID пользователя
		private $USER_CURRENT;          // глобальная $USER
		private $USERS_FIELD_MANAGER;   // глобальная $USER_FIELD_MANAGER
		private $arCurrentGroup;        // текущие группы пользователя
		private $dataUser;              // данные о пользователе для поиска дубликата
		private $typePrice;             // тип цены (для выбора группы)
		private $newGroupID;            // новая группа

		public function __construct(&$arFields) {
			$this->arFields = $arFields;
			global $USER;
			global $USER_FIELD_MANAGER;
			$this->USER_CURRENT = $USER;
			$this->USERS_FIELD_MANAGER = $USER_FIELD_MANAGER;
			$this->userID = $this->USER_CURRENT->GetID();
			$this->arCurrentGroup = [];
		}

		public function updateUser() {
			// данные о группах текущего пользователя
			$this->getDataCurrentGroup();
			$this->addUser();
		}

		private function addUser() {
			$this->newGroupID = self::SMALL_WHOLESALE_GROUP;
			// номер вбит пользователем
			if (isset($this->arFields["UF_CARD_NUMBER"]) && $this->arFields["UF_CARD_NUMBER"] != "") {
				// проверка - номер уже используется?
				if (!$this->issetNumberCard()) {
					// данные о контрагенте
					$this->getDataUserHLBl();
					// если они совпадают
					if (
						APLS_TextInspections::chekFIOString(
							$this->dataUser["UF_NAME"],
							$this->arFields["NAME"],
							$this->arFields["LAST_NAME"]
						) &&
						$this->arFields["UF_CARD_NUMBER"] == $this->dataUser["UF_NOMERKARTYKLIENTA"]
					) {
						$this->typePrice = $this->dataUser["UF_OSNOVNOYTIPTSEN"];
						$this->newGroupID = $this->arTypePrice[$this->typePrice];
						$this->editFieldValue("UF_MESSAGE_ERROR");
						$this->editFieldValue("UF_1C_TYPE_PRICE", $this->typePrice);
					// если нет - ошибка ввода данных
					} else {
						$this->editFieldValue("UF_MESSAGE_ERROR", "error");
						$this->editFieldValue("UF_CARD_NUMBER");
						$this->editFieldValue("UF_1C_TYPE_PRICE", self::SMALL_WHOLESALE_1C);
					}
				// если используется - ошибка номер занят
				} else {
					$this->editFieldValue("UF_MESSAGE_ERROR", "error1");
					$this->editFieldValue("UF_CARD_NUMBER");
					$this->editFieldValue("UF_1C_TYPE_PRICE", self::SMALL_WHOLESALE_1C);
				}
			} else {
				$this->editFieldValue("UF_1C_TYPE_PRICE", self::SMALL_WHOLESALE_1C);
			}
			// прмиеняем данные
			$this->editDataUser();
		}

		private function issetNumberCard() {
			$filter = Array("UF_CARD_NUMBER" => $this->arFields["UF_CARD_NUMBER"]);
			$by="ID";
			$order="desc";
			$rsUsers = CUser::GetList($by, $order, $filter,array("SELECT"=>array("UF_*"))); // выбираем пользователей
			while($el = $rsUsers->fetch()) {
				if ($el['ID'] != $this->userID) {
					return true;
				}
			}
			return false;
		}

		/**
		 * Получить данные о всех пользователях
		 */
		private function getDataUserHLBl() {
			CModule::IncludeModule("highloadblock");
			$hlblock = HL\HighloadBlockTable::getById(self::HLBL_ID)->fetch();
			$entity = HL\HighloadBlockTable::compileEntity($hlblock);
			$entity_data_class = $entity->getDataClass();
			$rsData = $entity_data_class::getList(
				array(
					'select' => array('*'),
					'filter' => array('UF_NOMERKARTYKLIENTA' => $this->arFields["UF_CARD_NUMBER"])
				)
			);
			$this->dataUser = $rsData->fetch();
		}

		/**
		 * Данные о текущих группах пользователя
		 */
		private function getDataCurrentGroup() {
			$this->arCurrentGroup = $this->USER_CURRENT->GetUserGroupArray();
		}

		/**
		 * Формирование нового списка групп пользователя
		 */
		private function deleteGroupsUser() {
			// удаляем текущую из AR_TYPE_PRICE
//			if (in_array($this->newGroupID, $this->arCurrentGroup)) {
//				$key = array_search($this->newGroupID, $this->arCurrentGroup);
//				unset($this->arTypePrice[$key]);
//			}
			// удаляем из текущих групп группы с типами цен
			$userGroup = array_diff($this->arCurrentGroup, $this->arTypePriceGroups);
			$userGroup[] = $this->newGroupID;
			return $userGroup;
		}

		/**
		 * Обновление данных о группах пользователя
		 */
		private function editDataUser() {
			$userGroup = $this->deleteGroupsUser();
			$this->USER_CURRENT->SetUserGroupArray($userGroup);
			CUser::SetUserGroup($this->userID, $userGroup);
		}

		/**
		 * Редактирование полей пользователя
		 * @param $field - наименование поля
		 * @param null $value - новое значение (по умолчанию - null)
		 * @return bool
		 */
		private function editFieldValue($field, $value = null) {
			return $this->USERS_FIELD_MANAGER->Update("USER", $this->userID, Array($field => $value));
		}

	}

	AddEventHandler("main", "OnAfterUserRegister", Array("DoItAfterUpdateUserExecute", "OnAfterUserUpdateHandler"));
	AddEventHandler("main", "OnAfterUserUpdate", Array("DoItAfterUpdateUserExecute", "OnAfterUserUpdateHandler"));
