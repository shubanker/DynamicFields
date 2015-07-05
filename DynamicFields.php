<?php
require_once 'ShuffleString.php';

class DynamicFields extends ShuffleString{
	private $charSet;
	private $key;
	private $salt;
	function __construct($key,$charSet=null,$salt=NULL){
		
		$this->key = $key;
		
		$this->charSet = $charSet==null?DEFAULT_CHAR_SET:$charSet;
		$this->salt = $salt;
	}
	function encode($str){
		return self::shuffledName($str, $this->key,$this->charSet,false);
	}
	function decode($str){
		return self::shuffledName($str, $this->key,$this->charSet,true);
	}
}