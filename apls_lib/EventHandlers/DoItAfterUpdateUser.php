<?php
/**
 * Created by PhpStorm.
 * User: olga
 * Date: 07.10.16
 * Time: 11:32
 */

AddEventHandler("main", "OnAfterUserRegister", "OnAfterUserUpdateHandler");
AddEventHandler("main", "OnAfterUserUpdate", "OnAfterUserUpdateHandler");

function OnAfterUserUpdateHandler (&$arFields) {
	$update = new DoItAfterUpdateUser($arFields);
	$update->updateUser();
}