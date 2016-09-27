<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * Description of ID_GENERATOR
 *
 * @author maxim
 */
class ID_GENERATOR {
    
    public static function generateID($prefix=null,$postfix=null) {
        $codeletters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $date = date("YmdHis");
        $mtime = microtime(true);
        $time = floor($mtime);
        $ms = round(($mtime - $time) * 1000);
        if(strlen($ms) === 0) {
            $date .= "000";
        } else if(strlen($ms) === 1) {
            $date .= "00";
        } else if(strlen($ms) === 2) {
            $date .= "0";
        }
        $date .= $ms;
        if($prefix == null) {
            $code = '';
        } else {
            $code = $prefix.'-';
        }
        for ($i = 0, $j=0; $i < strlen($date); $i++){
            $j++;
            $code .= $codeletters[$date[$i]];
            $code .= $codeletters[rand(0, strlen($codeletters)-1)];
            if($j === 6) {
                $code .= $codeletters[rand(0, strlen($codeletters)-1)];
                $code .= "-";
                $j = 0;
            }
        }
        if($postfix == null) {
            return substr($code, 0,  strlen($code)-1);
        } else {
            return $code."-".$postfix;
        }
    }
}