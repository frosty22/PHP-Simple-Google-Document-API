<?php

namespace GoogleDocs;

/**
 * Unknown file type of items
 *
 * @author vit-ledvinka
 */
class UnknownItem {
	
	/**
	 * Array of values
	 * @var array
	 */
	private $values = array();
	
		/**
	 * Parse element and create object
	 * @param \SimpleXMLElement $element 
	 */
	public function __construct(\SimpleXMLElement $element) {
		$this->values = $this->simplexml2array($element);
	}
	
	/**
	 * Magic method for get data form item
	 * @param string $name
	 * @return mixed 
	 */
	public function __get($name)
	{
		return isset($this->values[$name]) ? $this->values[$name] : null;
	}

	/**
	 * Recursive function for covert XML to Array
	 * @param mixed $xml
	 * @return array 
	 */
	private function simplexml2array($xml) {
		if (is_object($xml) && (get_class($xml) == 'SimpleXMLElement')) {
			$attributes = $xml->attributes();
			foreach($attributes as $k=>$v) {
				if ($v) $a[$k] = (string) $v;
			}
			$x = $xml;
			$xml = get_object_vars($xml);
		}
		if (is_array($xml)) {
			if (count($xml) == 0) return (string) $x; // for CDATA
			foreach($xml as $key => $value) {
				$r[$key] = $this->simplexml2array($value);
			}
			if (isset($a)) $r['@attributes'] = $a;    // Attributes
			return $r;
		}
		return (string) $xml;
	}	
	
}