<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * Description of DisplayPageContactsShop
 *
 * @author Olga Rjabchikova
 * @copyright © 2010-2016, CompuProjec
 * @created 29.06.2016 16:12:03
 */
class DisplayPageContactsShop {
    
    private $html;
    private $idBlock;                   
    private $region;
    private $mapsHeight;
    private $mapsWidth;

    public function __construct($idBlock, $region, $mapsHeight, $mapsWidth) {
        $this->idBlock = $idBlock;
        $this->region = $region;
        $this->mapsHeight = $mapsHeight;
        $this->mapsWidth = $mapsWidth;
        $this->displayPageContactsShop();
    }

    public function getDisplayPageContactsShopHtml() {
        return $this->html;
    }

    public function getDisplayPageContactsShop() {
        echo $this->html;
    }
    
    private function displayPageContactsShop() {
        $this->html = '';
        $this->html .= '<div class="RegionsContacts">';
        
            $this->html .= '<div class="RegionsButtonsWrapper">';
                $this->html .= $this->generationButtonBlock();
            $this->html .= '</div> ';

            $this->html .= '<div class="RegionsWrapper">';
                $this->html .= $this->generationRegionShops();
            $this->html .= '</div>';

            $this->html .= $this->generateJS();
            
        $this->html .= '</div>';
    }
    
    private function generationButtonBlock() {
        $html = "";
        $curClass = true;
        foreach (array_keys($this->region) as $region) {
            if($curClass) {
                $html .= '<div class="RegionsButtonBlock current" idRegion="'.$region.'">';
                $curClass = false;
            } else {
                $html .= '<div class="RegionsButtonBlock" idRegion="'.$region.'">';
            }
                $html .= $this->region[$region]['name'];
            $html .= '</div> ';
        }
        return $html;
    }

    private function generationRegionShops() {
        $html = "";
        foreach (array_keys($this->region) as $region) {
            $dataContacts = new DataContactsShop($this->idBlock, $this->region[$region]['id']);
            $data = $dataContacts->getAllDataContactsShops();
            $display = new DisplayContactsShop($data, $region, $this->mapsHeight, $this->mapsWidth);
            $html .= $display->getDisplayContactsShopHtml();
        }
        return $html;
    }

    private function generateJS() {
        $js = "
            <script type='text/javascript'>
                function unsetRegionElementAndShowMainMap() {
                    $('.RegionMaps .RegionMap ').hide();
                    $('.RegionMaps .RegionMap.Main').show();
                    $('.RegionsWrapper .RegionElement .ShopСontactData').hide();
                    $('.RegionsWrapper .ShopСontactName').removeClass('current');
                    
                }
                jQuery('.RegionsButtonsWrapper .RegionsButtonBlock').click(function(){
	                if(!$(this).hasClass('current')) {
		                $('.RegionsButtonsWrapper .RegionsButtonBlock.current').removeClass('current');
		                $(this).addClass('current');
		                var idRegion = $(this).attr('idRegion');
		                $('.RegionsWrapper .RegionBlock').hide();
		                $('#' + idRegion).show();
	                }
                    unsetRegionElementAndShowMainMap();
                });
                jQuery('.RegionsWrapper .ShopСontactName').click(function(){
                    if(!$(this).hasClass('current')) {
                        $('.RegionsWrapper .ShopСontactName').removeClass('current');
                        var idMap = $(this).addClass('current');
                        var idMap = $(this).attr('idMap');
                        $('.RegionMaps .RegionMap ').hide();
                        $('.RegionMaps #' + idMap).show();
                        $('.RegionsWrapper .RegionElement .ShopСontactData').hide();
                        $('#' + $(this).attr('idblockСontact')).show();
                    } else {
                        unsetRegionElementAndShowMainMap();
                    }
                });
            </script>";
        return $js;
    }

//    private function generateJS() {
//        $js = "
//            <script type='text/javascript'>
//                jQuery('.RegionsButtonsWrapper .RegionsButtonBlock').click(function(){
//	                if(!$(this).hasClass('current')) {
//		                $('.RegionsButtonsWrapper .RegionsButtonBlock.current').removeClass('current');
//		                $(this).addClass('current');
//		                var idRegion = $(this).attr('idRegion');
//		                $('.RegionsWrapper .RegionBlock').hide();
//		                $('#' + idRegion).show();
//	                }
//                    $('.RegionMaps .RegionMap ').hide();
//                    $('.RegionMaps .RegionMap.Main').show();
//                });
//                jQuery('.RegionsWrapper .ShopСontactName').click(function(){
//                    $('#' + $(this).attr('idblockСontact')).toggle();
//                });
//                jQuery('.RegionsWrapper .ShopСontactData').click(function(){
//                    var idmap = $(this).attr('idmap');
//                    $('.RegionMaps .RegionMap ').hide();
//                    $('.RegionMaps #' + idmap).show();
//                });
//            </script>";
//        return $js;
//    }
}
