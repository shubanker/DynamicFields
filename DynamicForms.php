<?php
require_once 'ShuffleString.php';

class DynamicForms extends ShuffleString{
	
	private $keepOriginalNames;
	private $key;
	private $keyValidity;
	private $chars;
	private $isOriginslSet;//TO skip Seting Originals Multiple times.
	
	/*
	 * @setOriginal-boolean value
	 * 
	 * Automatically decodes all $_POST variable and set them to original values if set to true as soon as object is created.
	 * if set false you can manually call setOriginalElementNames() when you want.
	 * 
	 * 
	 * @keepOriginal-boolean value
	 * 
	 * removes traces of the decoded fields names after encoding it.
	 * it does not creates a copy of variable thus enabling/disabling this won't cause mutch difference in performance.
	 * 
	 * 
	 * @keyValadity-string value
	 * override default time set for the expiry of the key.
	 */
	function __construct($setOriginal=TRUE,$keepOriginal=FALSE,$keyValidity=NULL){
		
		$defaultKeyValidity="10 mins";
		$this->keyValidity=empty($keyValidity)?$defaultKeyValidity:$keyValidity;//Change the value as per your needs
		
		$this->chars=DEFAULT_CHAR_SET;
		
		$this->keepOriginalNames=$keepOriginal;
		$this->isOriginslSet=false;
		if (!isset($_SESSION['key'])||empty($_SESSION['key'])||$_SESSION['time']<strtotime("now")){
			$this->resetKeys();
		}
		$this->key=$_SESSION['key'];
		if ($setOriginal){
			$this->setOriginalElementNames();
		}
		
	}
	function setOriginalElementNames(){
		if (empty($this->key)||empty($_POST)||$this->isOriginslSet){
			return false;
		}
		// 	foreach ($_POST as $key=>$value){
		// 		$_POST[swap($key, $this->key,true)]=$value;
		// 		unset($_POST[$key]);//Removes Backup variable.
	
		// 	}
		
		$keys=array_keys($_POST);
		foreach ($keys as $key){
			$_POST[self::shuffledName($key, $this->key,$this->chars,true)]=&$_POST[$key];//Assigning the address of the received key to decrypted key.
			if (!$this->keepOriginalNames){
				unset($_POST[$key]);//Removes Backup variables.
			}
		}
		return $this->isOriginslSet=true;//Making Shure the function is not called more than once.
	}
	function DynamicName($name){
		return self::shuffledName($name, $this->key,$this->chars);
	}
	function resetKeys(){
		$_SESSION['key']=self::getRandomString(5,11);
		$_SESSION['time']=strtotime("+ ".$this->keyValidity);
	}
}