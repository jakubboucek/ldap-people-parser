<?php
class LdapPeople implements Iterator {

	private $data;
	private $seek = 0;

	public function __construct($file) {
		$content = $this->loadRawContent($file);
		$this->data = $this->parsePeople($content);
	}

	public function getPerson($key) {
		$person = $this->data[$key];
		return $this->mergeParams($person);
	}

	private function loadRawContent($file) {
		if(!is_file($file) || !is_readable($file)) {
			throw new Exception("File $file is not readable or not regulear file", 1);
		}

		return file_get_contents($file);
	}

	private function parsePeople($raw) {
		return array_map( [$this, 'readRawPeople'], $this->separeRawPeople($raw));
	}

	private function separeRawPeople($raw) {
		return preg_split("/\n\n(?=#)/m", $raw);
	}

	private function readRawPeople($person) {
		$rawParams = $this->splitParams($person);
		$params = array_filter( array_map( [$this, 'parseParams'], $rawParams ) );
		return $params;
	}

	private function splitParams($person) {
		return preg_split("/\n(?=[a-z])/im", $person);
	}

	private function parseParams($rawParams) {
		if( strpos($rawParams, ':') === FALSE ) {
			return NULL;
		}
		if( strpos($rawParams, '::') !== FALSE ) {
			$params = explode('::', $rawParams);
			$params[1] = base64_decode( trim($params[1]) );
		}
		else {
			$params = explode(':', $rawParams);
		}

		return array_map('trim', $params);
	}

	private function mergeParams( $person ) {
		$params = [];
		foreach( $person as $param ) {
			if( !isset( $params[$param[0]] )) {
				$params[$param[0]] = $param[1];
			}
			elseif( is_array( $params[$param[0]] ) ) {
				$params[$param[0]][] = $param[1];
			}
			else {
				$params[$param[0]] = [ $param[1] ];
			}
		}
		return $params;
	}

	public function current ( ) {
		return $this->getPerson($this->seek);
	}
	public function key ( ) {
		return $this->seek;
	}
	public function next ( ) {
		return ++$this->seek;
	}
	public function rewind ( ) {
		return $this->seek = 0;
	}
	public function valid ( ) {
		return isset($this->data[$this->seek]);
	}
}
