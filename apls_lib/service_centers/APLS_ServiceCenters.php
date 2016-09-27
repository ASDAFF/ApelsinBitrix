<?php

/**
 * Created by PhpStorm.
 * User: Максим
 * Date: 05.07.2016
 * Time: 11:17
 */
class APLS_ServiceCenters {

	private $file;
	private $logos;
	private $xmlwebi;
	private $xmlData;
	private $massServiceCenter;
	private $massServiceCenterGroup;
	private $serviceCentersGroupIdPrefix = 'ServiceCentersGroupBlock_';
	private $serviceCentersGroupIdArchivePostfix = '_Arcive';
	private $serviceCentersGroupIdColumnPostfix = '_Column';
	private $serviceCentersColumns = 2;
	private $HTML;
	private $JS;

	public function __construct($PathToRoot = "../") {
		$this->file = $PathToRoot.'/apls_resources/service_centers/service_centers.xml';
		$this->logos = $PathToRoot.'/apls_resources/service_centers/logos/';
		$this->xmlwebi = new AplsXmlParser();
		$this->xmlData = $this->xmlwebi->xmlwebi($this->file);
		$this->GetData();
		$this->generateHTML();
		$this->generateJS();
	}

	private function GetData() {
		$this->massServiceCenter = array();
		$this->massServiceCenterGroup = array();
		$j=0;
		foreach ($this->xmlData['ServiceCenter'][0]['#']['Group'] as $group) {
			$i=0;
			$this->massServiceCenterGroup[$j] = $group['@']['name'];
			//asort($group['#']['Item']);
			foreach ($group['#']['Item'] as $item) {
				$this->massServiceCenter[$j][$i]['Code'] = $item['#']['Code'][0]['#'];
				$this->massServiceCenter[$j][$i]['Title'] = $item['#']['Title'][0]['#'];
				$Adress = str_replace("---// ","",$item['#']['Adress'][0]['#']);
				$Adress = str_replace("--- // ","",$Adress);
				$Adress = str_replace("// ","<br />",$Adress);
				$this->massServiceCenter[$j][$i++]['Adress'] = nl2br($Adress);
			}
			$j++;
		}
	}

	private function getServiceCenterGroupSelectBox($array,$id) {
		$out = '';
		$out .= '<select id="'.$id.'" onchange="showServiceCenterGroup('.count($array).');" class="ServiceCenterSelectBox">';
		$out .= '<option>Показать все</option>';
		foreach ($array as $value) {
			$out .= '<option>';
			$out .= $value;
			$out .= '</option>';
		}
		$out .= '</select><br>';
		return $out;
	}
	private function getServiceCenterSelectBox($array,$id) {
		$out = '';
		$out .= '<select id="'.$id.'" style="display: none;" onchange="showServiceCenter('.count($array).');" class="ServiceCenterSelectBox">';
		$out .= '<option>Показать все</option>';
		foreach ($array as $value) {
			$out .= '<option value="#">';
			$out .= $value['Title'];
			$out .= '</option>';
		}
		$out .= '</select>';
		return $out;
	}

	private function generateHTML() {
		$this->HTML = '';
//        $this->HTML .= '<h1>Сервисные центры</h1>';
		$this->HTML .= '<center>';
		$this->HTML .= $this->getServiceCenterGroupSelectBox($this->massServiceCenterGroup,'serviceCenterGroups');
		for ($i=0; $i<count($this->massServiceCenterGroup); $i++) {
			$this->HTML .= $this->getServiceCenterSelectBox($this->massServiceCenter[$i],'serviceCenterGroup_'.$i);
		}
		$this->HTML .= '</center><br>';

		$j=0;
		foreach ($this->massServiceCenter as $serviceCenterGroup) {
			$this->HTML .= "<div class='ServiceCentersGroup' id='".$this->getServiceCentersGroupId($j)."' style='display: ;'>";
			$this->HTML .= "<h2 class='ServiceCentersSection'>".$this->massServiceCenterGroup[$j]."</h2>";
			$i=0;
			$this->HTML .= "<div class='ServiceCentersGroup_Arcive' id='".$this->getServiceCentersGroupIdArchive($j)."' style='display: none;'>";
			foreach ($serviceCenterGroup as $serviceCenter) {


				$elementId = 'ServiceCenterBlock_'.$j.'_'.$i++;
				$this->HTML .= "<div class='ServiceCentersElement' id='$elementId' style='display: ;'>";
				$this->HTML .= "<div class='element'>".$serviceCenter['Title']."</div>";
				$this->HTML .= "<div class='inf'>";
				$this->HTML .= "<table class='ServiceCentersInformationTable'>";
				$this->HTML .= "<tr>";
				$this->HTML .= "<td class='ServiceCentersInformationTable_Adress'>";
				$this->HTML .= $serviceCenter['Adress'];
				$this->HTML .= "</td>";
				$this->HTML .= "<td class='ServiceCentersInformationTable_Logo'>";
				$this->HTML .= $this->GetUnitsIMG($serviceCenter['Code']);
//                                    $this->HTML .= $serviceCenter['Code'];
				$this->HTML .= "</td>";
				$this->HTML .= "</tr>";
				$this->HTML .= "</table>";

				$this->HTML .= "</div>";
				$this->HTML .= "</div>";
			}
			$this->HTML .= "</div>";
			for($columnNumber = 1; $columnNumber <= $this->serviceCentersColumns; $columnNumber++) {
				$this->HTML .= "<div class='ServiceCentersGroup".$this->serviceCentersGroupIdColumnPostfix.$columnNumber."' id='".$this->getServiceCentersGroupIdColumn($j,$columnNumber)."'></div>";
			}
			$this->HTML .= "</div>";
			$this->HTML .= "<div class='clear'></div>";
			$j++;
		}
	}

	public function GetUnitsIMG($code) {
		$IMG_URL = $this->logos.$code.".png";
		if(!file_exists($IMG_URL)) {
			$IMG_URL = $this->logos.$code.".jpg";
			if(!file_exists($IMG_URL)) {
				$IMG_URL = null;
			}
		}
		if($IMG_URL!=null) {
			$logo = "<img class='ServiceCentersLogo' src='".$IMG_URL."'>";
		} else {
			$logo = '';
		}
		return $logo;
	}

	private function getServiceCentersGroupId($id) {
		return $this->serviceCentersGroupIdPrefix.$id;
	}
	private function getServiceCentersGroupIdArchive($id) {
		return $this->getServiceCentersGroupId($id).$this->serviceCentersGroupIdArchivePostfix;
	}
	private function getServiceCentersGroupIdColumn($idGroup,$idColumn) {
		return $this->getServiceCentersGroupId($idGroup).$this->serviceCentersGroupIdColumnPostfix.$idColumn;
	}

	private function generateJS() {
		$this->JS .= '';
		$this->JS .= '<script type="text/javascript">';
		$this->JS .= '$(document).ready(function() {';
		$j = 0;
		foreach ($this->massServiceCenter as $serviceCenterGroup) {
			$archiveID = "";
			$columnID = "";
			$archiveID .= "'#".$this->getServiceCentersGroupIdArchive($j)."'";
			$columnID .= "[";
			for($columnNumber = 1; $columnNumber <= $this->serviceCentersColumns; $columnNumber++) {
				$columnID .= "'#".$this->getServiceCentersGroupIdColumn($j,$columnNumber)."',";
			}
			$columnID = substr($columnID, 0, strlen($columnID)-1);
			$columnID .= "]";
			$this->JS .= "ContentToColumns(".$archiveID.",".$columnID.");";
			$j++;
		}
		$this->JS .= '});';
		$this->JS .= '</script>';

		$this->JS .= "<script>
			function show_hide(idName,arr) {
				if(document.getElementById(idName).style.display=='none') {
					serviceCenterGroups.style.display = '';
				} else {
					document.getElementById(idName).style.display = 'none';
				}
				for(var i=0; i<arr.length; i++) {
					document.getElementById(arr[i]).style.display = 'none';
				}
				return true;
			}
			</script>
			
			<script>
				function showServiceCenterGroup(count) {
					var id = \"\";
					var id2 = \"\";
					var index = document.getElementById('serviceCenterGroups').selectedIndex - 1;
					var idSelect = \"\";
					if(index>=0) {
						for(var i=0; i<count; i++) {
							id = 'serviceCenterGroup_' + i;
							id2 = 'ServiceCentersGroupBlock_' + i;
							if(document.getElementById(id)) {
								document.getElementById(id).style.display = 'none';
							}
							if(document.getElementById(id2)) {
								document.getElementById(id2).style.display = 'none';
							}
						}
						id = 'serviceCenterGroup_' + index;
						id2 = 'ServiceCentersGroupBlock_' + index;
						if(document.getElementById(id)) {
							document.getElementById(id).style.display = '';
						}
						if(document.getElementById(id2)) {
							document.getElementById(id2).style.display = '';
						}
					} else {
						for(var i=0; i<count; i++) {
							idSelect = 'serviceCenterGroup_' + i;
							if(document.getElementById(idSelect)) {
								showServiceCenterWG(i,document.getElementById(idSelect).options.length);
							}
						}
						for(var i=0; i<count; i++) {
							id = 'serviceCenterGroup_' + i;
							id2 = 'ServiceCentersGroupBlock_' + i;
							if(document.getElementById(id)) {
								document.getElementById(id).style.display = 'none';
							}
							if(document.getElementById(id2)) {
								document.getElementById(id2).style.display = '';
							}
						}
					}
				}
				
				function showServiceCenter(count) {
					var id = \"\";
					if(!document.getElementById('serviceCenterGroups')) {
						return;
					}
					var group = document.getElementById('serviceCenterGroups').selectedIndex - 1;
					var idSelect = 'serviceCenterGroup_' + group;
					if(!document.getElementById(idSelect)) {
						return;
					}
					var index = document.getElementById(idSelect).selectedIndex - 1;
					if(index>=0) {
						for(var i=0; i<count; i++) {
							id = 'ServiceCenterBlock_' + group + \"_\" + i;
							if(document.getElementById(id)) {
								document.getElementById(id).style.display = 'none';
							}
						}
						id = 'ServiceCenterBlock_' + group + \"_\" + index;
						if(document.getElementById(id)) {
							document.getElementById(id).style.display = '';
						}
					} else {
						for(var i=0; i<count; i++) {
							id = 'ServiceCenterBlock_' + group + \"_\" + i;
							if(document.getElementById(id)) {
								document.getElementById(id).style.display = '';
							}
						}
					}
				}
				
				function showServiceCenterWG(group,count) {
					var id = \"\";  
					for(var i=0; i<count; i++) {
						id = 'ServiceCenterBlock_' + group + \"_\" + i;
						if(document.getElementById(id)) {
							document.getElementById(id).style.display = '';
						}
					}
				}
			</script>";
	}

	public function getHtml() {
		return $this->HTML;
	}

	public function getJS() {
		return $this->JS;
	}
}