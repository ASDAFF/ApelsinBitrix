<?php
/**
 * Класс выводит все контактные данные по ОДНОМУ региону
 *
 * @author Olga Rjabchikova
 * @copyright © 2010-2016, CompuProjec
 * @created 25.06.2016 11:05:48
 */
class DisplayContactsShop {
    
    private $html;
    private $data;
    private $MainMap;
    private $region;
    private $mapsHeight;
    private $mapsWidth;

    public function __construct($data, $region, $mapsHeight, $mapsWidth) {
        $this->MainMap = $data['MAIN_MAP_DATA'];
        unset($data['MAIN_MAP_DATA']);
        $this->data = $data;
        $this->region = $region;
        $this->mapsHeight = $mapsHeight;
        $this->mapsWidth = $mapsWidth;
        $this->displayContactsShop();
    }

    public function getDisplayContactsShopHtml() {
        return $this->html;
    }

    public function getDisplayContactsShop() {
        echo $this->html;
    }

    private function сontactsMap($sid, $height, $width) {
        return '<script type="text/javascript" charset="utf-8" src="//api-maps.yandex.ru/services/constructor/1.0/js/?sid='.$sid.'&width='.$width.'&height='.$height.'"></script>';
    }

    private function displayContactsShop() {
        $this->html = '';
//        $this->html .= '<div class="RegionWrapper">';
            $this->html .= $this->generationBlockShop();
//        $this->html .= '</div>';
    }
    
    private function generationBlockShop() {
        $html = "";
        $html .= '<div class="RegionBlock" id="'.$this->region.'">';
            $html .= '<div class="RegionMaps">';
                $html .= $this->generationAllShopMaps();
                $html .= $this->generationShopMaps();
            $html .= '</div>';

            $html .= '<div class="RegionElements">';
                $html .= $this->generationShopСontactBlock();
            $html .= '</div>';
        $html .= '</div>'; 
        return $html;
    }

    private function generationAllShopMaps() {
        $html = "";
        if($this->MainMap != null && $this->MainMap != "") {
            $html .= '<div class="RegionMap Main" idShop="region_main_map">';
                $html .= '<div class="ShopСontactMap">';
                    $html .= $this->сontactsMap($this->MainMap, $this->mapsHeight, $this->mapsWidth);
                $html .= '</div>';
            $html .= '</div> ';
        }
        return $html;
    }

    private function generationShopMaps() {
        $html = "";
        foreach (array_keys($this->data) as $shops) {
            $html .= '<div class="RegionMap" id="'.$shops.'_map">';
                $html .= '<div class="ShopСontactMap">';
                    $html .= $this->сontactsMap($this->data[$shops]['maps'], $this->mapsHeight, $this->mapsWidth);
                $html .= '</div>';
            $html .= '</div>';
        }
        return $html;
    }

    private function generationShopСontactBlock($shops) {
        $html = "";
        foreach (array_keys($this->data) as $shops) {
            $idBlockСontact = ID_GENERATOR::generateID();
            $html .= '<div class="RegionElement">';
                $html .= '<div class="ShopСontactBlock">';

                    $html .= '<div class="ShopСontactName" idBlockСontact="'.$idBlockСontact.'" idMap="'.$shops.'_map"><h1>'.$this->data[$shops]['name'].'</h1></div>';

                    $html .= '<div class="ShopСontactData" id="'.$idBlockСontact.'" idMap="'.$shops.'_map">';
                        $html .= '<div class="ShopСontactText" >';
                            $html .= '<div class="ShopСontactText_adress">'.$this->data[$shops]['adress'].'</div>';
                            $html .= '<div class="ShopСontactText_email"><a href="mailto:'.$this->data[$shops]['email'].'">'.$this->data[$shops]['email'].'</a></div>';
                            $html .= '<div class="ShopСontactText_phone">'.$this->data[$shops]['phoneText1'].'</div>';//<!-- обязателен только один--> 
                            if (isset($this->data[$shops]['phoneText2'])) {
                                $html .= '<div class="ShopСontactText_phone">'.$this->data[$shops]['phoneText2'].'</div>';
                            }
                        $html .= '</div>';

                        $html .= '<div class="ShopСontactTimeTable">';
                            $timeTable = new ContactsTimeTableUI($this->data[$shops]['table']);
                            $html .= $timeTable->getTimeTableHTML();
                        $html .= '</div>';

                    $html .= '</div>';
                    $html .= '<div class="clear"></div>';
                $html .= '</div>';
            $html .= '</div>';
        }
        return $html;
    }

}