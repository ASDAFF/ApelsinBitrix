<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * Класс для генерации расписания магазинов
 * Работает с подготовленными данными и 
 * генерирует таблицу расписания
 */
class ContactsTimeTableUI {
    
    private $days;
    private $mainDays;
    private $timeTable;
    private $finalTimeTable;

    public function __construct($timeTable) {
        $this->days = array("ПН", "ВТ", "СР", "ЧТ", "ПТ", "СБ", "ВС");
        $this->mainDays = array("СБ", "ВС");
        $this->timeTable = $timeTable;
        $this->generateTimeTable();
        $this->generateHTML();
    }
    
    public function getTimeTable(){
        echo $this->html;
    }
    
    public function getTimeTableHTML(){
        return $this->html;
    }
    
    private function setStartElementForTimeTable($i, $j) {
        foreach ($this->mainDays as $mainDays) {
            if($mainDays == $this->days[$j]) {
                $this->finalTimeTable[$i]['statrtText'] = "<span class='mainDay'>".$this->days[$j]."</span>";
                $this->finalTimeTable[$i]['time'] = $this->timeTable[$j];
                return;
            }
        }
        $this->finalTimeTable[$i]['statrtText'] = $this->days[$j];
        $this->finalTimeTable[$i]['time'] = $this->timeTable[$j];
    }
    
    private function setEndElementForTimeTable($i, $j){
        $text1 = $this->finalTimeTable[$i]['statrtText'];
        $text2 = $this->days[$j - 1];
        foreach ($this->mainDays as $mainDays) {
            if($mainDays == $this->days[$j - 1]) {
                $text2 = "<span class='mainDay'>".$this->days[$j - 1]."</span>";
            }
        }
        if($text1 == $text2) {
            $this->finalTimeTable[$i]['endText'] = "";
        } else {
            $this->setEndElementForTimeTableText($i, $j);
        }
    }
    
    private function setEndElementForTimeTableText($i, $j){
        foreach ($this->mainDays as $mainDays) {
            if($mainDays == $this->days[$j - 1]) {
                $this->finalTimeTable[$i]['endText'] = " - <span class='mainDay'>".$this->days[$j - 1]."</span>";
                return;
            }
        }
        $this->finalTimeTable[$i]['endText'] = " - ".$this->days[$j-1];
    }

    private function generateTimeTable() {
        $this->finalTimeTable = null;
        $i = 0;
        $this->setStartElementForTimeTable($i, $i);
        for ($j = 1; $j < count($this->timeTable); $j++){
            if ($this->finalTimeTable[$i]['time'] != $this->timeTable[$j]){
                $this->setEndElementForTimeTable($i, $j);
                $i++;
                $this->setStartElementForTimeTable($i, $j);
            }
            if ($j == count($this->timeTable) - 1){
                $this->setEndElementForTimeTable($i, $j + 1);
            }
        }
    }
    
    private function generateHTML() {
        $this->html = "<table class='time_table'>";
        foreach ($this->finalTimeTable as $timeTable) {
            $this->html .= "<tr>";
            $this->html .= "<td class='time_table_text'>";
            $this->html .= $timeTable['statrtText'];  
            $this->html .= $timeTable['endText'];
            $this->html .= "</td>";
            $this->html .= "<td class='time_table_time'>";
            $this->html .= $timeTable['time'];
            $this->html .= "</td>";
            $this->html .= "</tr>";  
        }
        $this->html .= "</table>";
    }
    
}
