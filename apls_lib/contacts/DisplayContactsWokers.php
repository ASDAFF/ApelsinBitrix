<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * Выводит контакты работников 
 *
 * @author Olga Rjabchikova
 * @copyright © 2010-2016, CompuProjec
 * @created 01.07.2016 13:09:25
 */
class DisplayContactsWokers {
    
    private $html;
    private $data;
    
    public function __construct($data) {
        $this->data = $data;
        $this->displayContactsWokers();
    }
    
    public function getDisplayContactsWokersHtml() {
        return $this->html;
    }

    public function getDisplayContactsWokers() {
        echo $this->html;
    }

    private function displayContactsWokers() {
        $this->html = '';
        $this->html .= '<div class="WokersWrapper">';
            foreach ($this->data as $woker) {
                $this->html .= $this->generationBlockWoker($woker);
            }
        $this->html .= '<div class="clear" />';
        $this->html .= '</div>'; 
    }
    
    private function generationBlockWoker($woker) {
        $html = "";
        $html .= '<div class="WokerBlock">';
        $html .= '<div class="WorkerContactFIO">'.$woker['fio'].'</div>';
        $html .= '<div class="WorkerContactPost">'.$woker['post_wokers'].'</div>';
        if (count($woker) > 2) {
            $html .= '<div class="WorkerContactBlock">';
                $html .= $this->generationWorkerContactBlock($woker);
            $html .= '</div>';
        }
        $html .= '</div>'; 
        return $html;
    }

    private function generationWorkerContactBlock($woker) {
        $html = "";
        $html .= '<div class="WokerDataBlock">';
            $html .= '<div class="MainWokerData">';
                $html .= $this->checkEmpty($woker['phoneText1']);
                $html .= $this->checkEmpty($woker['phoneText2']);
                $html .= $this->checkEmptyEmail($woker['email']);
            $html .= '</div>';
            $html .= $this->checkEmpty($woker['text_info'], "text");
        $html .= '</div>'; 
        return $html;
    }
    
    private function checkEmpty($contact,$class = "") {
        $html = "";
        if ($contact != null) {
            $html .= '<div class="WorkerContactContact '.$class.'">';
                $html .= $contact;
            $html .= '</div>'; 
        } 
        return $html;
    }
    
    private function checkEmptyEmail($contact) {
        $html = "";
        if (isset($contact) && $contact != null) {
            $html .= '<div class="WorkerContactContact">';
                $html .= '<a>'.$contact.'</a>';
            $html .= '</div>'; 
        } 
        return $html;
    }

}
