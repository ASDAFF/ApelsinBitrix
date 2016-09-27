<?php

/**
 * Created by PhpStorm.
 * User: Максим
 * Date: 05.07.2016
 * Time: 11:18
 */
class AplsXmlParser {
	private $array;

	public function __construct() {
		$this->valid = FALSE;
	}

	public function xmlwebi($file_name, $WHITE=1, $encoding='UTF-8') {
		$data = file_get_contents($file_name);
		$data = str_replace ("&", "&amp;" , $data);
		$data = preg_replace ("'<\?xml.*\?>'si", "", $data);
		$data = "<webi_xml>".$data."</webi_xml>";
		$data = trim($data);
		$vals = $index = $this->array = array();
		$parser = xml_parser_create($encoding);
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, $WHITE);
		$this->valid = xml_parse_into_struct($parser, $data, $vals, $index);
		xml_parser_free($parser);
		$i = 0;
		$tagname = $vals[$i]['tag'];
		if(isset($vals[$i]['attributes'])) {
			$this->array[$tagname]['@'] = $vals[$i]['attributes'];
		} else {
			$this->array[$tagname]['@'] = array();
		}
		$this->array[$tagname]['#'] = $this->xml_depth($vals, $i);
		return $this->array['webi_xml']['#'];
	}

	private function xml_depth($vals,&$i) {
		$children = array();
		if (isset($vals[$i]['value'])) {
			array_push($children, $vals[$i]['value']);
		}
		while (++$i < count($vals)) {
			switch ($vals[$i]['type']) {
				case 'open':
					if (isset($vals[$i]['tag'])) {
						$tagname = $vals[$i]['tag'];
					}
					else {
						$tagname = '';
					}
					if (isset($children[$tagname])) {
						$size = sizeof($children[$tagname]);
					}
					else {
						$size = 0;
					}
					if ( isset ( $vals[$i]['attributes'] ) ) {
						$children[$tagname][$size]['@'] = $vals[$i]["attributes"];
					}
					$children[$tagname][$size]['#'] = $this->xml_depth($vals, $i);
					break;
				case 'cdata':
					array_push($children, nl2br($vals[$i]['value']));
					break;
				case 'complete':
					$tagname = $vals[$i]['tag'];
					if(isset($children[$tagname])) {
						$size = sizeof($children[$tagname]);
					}
					else {
						$size = 0;
					}

					if(isset($vals[$i]['value'])) {
						$children[$tagname][$size]['#'] = $vals[$i]['value'];
					}
					else {
						$children[$tagname][$size]['#'] = '';
					}

					if(isset($vals[$i]['attributes'])) {
						$children[$tagname][$size]['@'] = $vals[$i]['attributes'];
					}
					break;
				case 'close':
					return $children;
					break;
			}
		}
		return $children;
	}
}