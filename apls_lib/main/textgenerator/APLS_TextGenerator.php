<?php

/**
 * Created by PhpStorm.
 * User: Максим
 * Date: 12.10.2016
 * Time: 9:56
 */
class APLS_TextGenerator
{
	/**
	 * убирает из строки цыфры, символы, знаки припенания и лишние пробелы
	 * @param $string
	 * @return string
	 */
	public static function lettersAndSpaces($string) {
		$string = preg_replace('/[^\w\s]/u', ' ', $string);
		$string = preg_replace('|[\d]|i',' ',$string);
		$string = preg_replace('|[\s]+|i',' ',$string);
		return trim($string);
	}
}