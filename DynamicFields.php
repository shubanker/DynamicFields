<?php
class DynamicFields{
	private $keepOriginalNames;
	private $key;
	private $keyValidity;
	private $chars;
	private $isOriginslSet;
	
	function __construct($setOriginal=TRUE,$keepOriginal=TRUE){
		
		$this->keyValidity="10 mins";//Change the value as per your needs
		
		$this->chars="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		
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
	
	/*
	 * To shuffle the set of chars according to the key.
	 */
	private function createChanged($passKey){
	
		$i=str_split($this->chars);
		$passhash =hash('sha256',$passKey);
// 		Uncomment below line if you change(increase) the default chars set.
// 		$passhash = (strlen(hash('sha256',$passKey)) < strlen($this->chars))? hash('sha512',$passKey): hash('sha256',$passKey);

		for ($n=0; $n < strlen($this->chars); $n++)
			$p[] =  substr($passhash, $n ,1);
	
		array_multisort($p,  SORT_DESC, $i);
		$converted = implode($i);
	
		return $converted;
	}
	private function Swap($input,$key,$decrypt=FALSE,$salt='AnOptionalRandomString'){
		
		$changedkey=$this->createChanged($salt.$key);//Shuffle the characters according to the key
	
		$normal = $decrypt?$changedkey:$this->chars;
		$changed=$decrypt?$this->chars:$changedkey;
		
		$output='';
		$n=str_split($input);
		for($i=0;$i<count($n);$i++){
			$c=$n[$i];
	
			for($j=0;$j<strlen($normal) && $c!==substr($normal,$j,1);$j++);//Geting position of char
	
			if ($j<strlen($normal)){
				$output.=substr($changed,$j,1);
			}
			else {
				$output.=$c;//If its not in the set of characters do not include it.
			}
		}
		return $output;
	}
	
	//With another Algo
	private function Swap2($input,$key,$decrypt=FALSE,$salt='AnOptionalRandomString'){
	
		$changedkey=$this->createChanged($salt.$key);
		$normal = $decrypt?$changedkey:$this->chars;
	
		$changed=$decrypt?$this->chars:$changedkey;
	
		$output='';
		$n=str_split($input);
		$index=array();
		
		//Creating an index associative array 
		for($i=0;$i<strlen($normal);$i++){
			$index[substr($normal,$i,1)]=substr($changed,$i,1);
		}
		//using index to get original value of the character.
		for ($i=0;$i<strlen($input);$i++){
			$output.=isset($index[substr($input,$i,1)])?$index[substr($input,$i,1)]:substr($input,$i,1);
		}
		return $output;
	}
	static function getRandomString($min=NULL,$max=NULL){

		$min=$min==NULL?rand(2,9):$min;//Default range is between 2 and 9 change this if needed.
		$max=$max==NULL?$min:$max;
		$str="";
		while (strlen($str)<$max){
			$str.=rtrim(base64_encode(hash("sha512",microtime())),"=");
		}
		#$str=str_shuffle($str);//Optional as the generated string is random of itself.
		return substr($str, 0, rand($min, $max));
	}
	function setOriginalElementNames(){
		if (empty($this->key)||empty($_POST)||$this->isOriginslSet){
			return;
		}
		// 	foreach ($_POST as $key=>$value){
		// 		$_POST[Swap($key, $this->key,true)]=$value;
		// 		unset($_POST[$key]);//Removes Backup variable.
	
		// 	}
		
		$keys=array_keys($_POST);
		foreach ($keys as $key){
			$_POST[$this->Swap($key, $this->key,true)]=&$_POST[$key];//Assigning the address of the received key to decrypted key.
			if (!$this->keepOriginalNames){
				unset($_POST[$key]);//Removes Backup variables.
			}
		}
		$this->isOriginslSet=true;//Making Shure the function is not called more than once.
	}
	function DynamicName($name){
		return $this->Swap($name, $this->key);
	}
	function resetKeys(){
		$_SESSION['key']=$this->getRandomString(5,11);
		$_SESSION['time']=strtotime("+ ".$this->keyValidity);
	}
}