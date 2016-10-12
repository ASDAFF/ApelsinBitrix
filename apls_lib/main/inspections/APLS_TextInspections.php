<?php

/**
 * Created by PhpStorm.
 * User: Максим
 * Date: 12.10.2016
 * Time: 9:19
 */
class APLS_TextInspections
{

	/**
	 * fioString в виде массива
	 * @param $fioString
	 * @return array
	 */
	private static function getFIOStrinfArray($fioString)
	{
		$fioString = APLS_TextGenerator::lettersAndSpaces($fioString);
		$fioArr = explode(" ", $fioString);
		foreach ($fioArr as $key => $val) {
			if ($val == "") {
				unset($fioArr[$val]);
			}
		}
		return $fioArr;
	}

	/**
	 * Пустая строка или null
	 * @param $string
	 * @return bool
	 */
	public static function isEmpty($string)
	{
		return $string == "" || $string === null;
	}

	/**
	 * првоеряем фамилию и имя на наличие в строке $fioString
	 * @param $fioString
	 * @param $fName
	 * @param $sName
	 * @return bool
	 */
	public static function chekFIonFIOString($fioString, $fName, $sName)
	{
		$fName = APLS_TextGenerator::lettersAndSpaces($fName);
		$sName = APLS_TextGenerator::lettersAndSpaces($sName);
		if (
			!self::isEmpty($fName) &&
			!self::isEmpty($sName) &&
			$fName != $sName
		) {
			$fioArr = self::getFIOStrinfArray($fioString);
			return in_array($fName, $fioArr) && in_array($sName, $fioArr);
		}
		return false;
	}

	/**
	 * првоеряем фамилию, имя и отчество на наличие в строке $fioString
	 * @param $fioString
	 * @param $fName
	 * @param $sName
	 * @param $lName
	 * @return bool
	 */
	public static function chekFIOonFIOString($fioString, $fName, $sName, $lName)
	{
		$fName = APLS_TextGenerator::lettersAndSpaces($fName);
		$sName = APLS_TextGenerator::lettersAndSpaces($sName);
		$lName = APLS_TextGenerator::lettersAndSpaces($lName);
		if (
			!self::isEmpty($fName) &&
			!self::isEmpty($sName) &&
			!self::isEmpty($lName) &&
			$fName != $sName &&
			$fName != $lName &&
			$sName != $lName
		) {

			$fioArr = self::getFIOStrinfArray($fioString);
			return in_array($fName, $fioArr) && in_array($sName, $fioArr) && in_array($lName, $fioArr);
		}
		return false;
	}

	/**
	 * првоеряем фамилию, имя и отчество на наличие в строке $fioString
	 * отчество не обязательно. Если отчество не заполнено или null то будет осуществлен поиск
	 * только по имени и фамилии
	 * @param $fioString
	 * @param $fName
	 * @param $sName
	 * @param null $lName
	 * @return bool
	 */
	public static function chekFIOString($fioString, $fName, $sName, $lName = null) {
		if(!self::isEmpty($lName)) {
			return self::chekFIOonFIOString($fioString, $fName, $sName, $lName);
		} else {
			return self::chekFIonFIOString($fioString, $fName, $sName);
		}
	}
}