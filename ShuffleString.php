<?php
class ShuffleString{
	
	/*
	 * public refrence to swap useful to switch algo in future.
	 */
	static function shuffledName($name,$key,$chars,$decrypt=FALSE,$salt='AnOptionalRandomString'){
		return self::swap($name, $key,$chars,$decrypt,$salt);
	}
	
	/*
	 * To shuffle the set of chars according to the key.
	 */
	static private function createChanged($passKey,$chars){
	
		$i=str_split($chars);
		$passhash =hash('sha256',$passKey);
// 		Uncomment below line if you change(increase) the default chars set.
// 		$passhash = (strlen(hash('sha256',$passKey)) < strlen($this->chars))? hash('sha512',$passKey): hash('sha256',$passKey);

		for ($n=0; $n < strlen($chars); $n++)
			$p[] =  substr($passhash, $n ,1);
	
		array_multisort($p,  SORT_DESC, $i);
		$converted = implode($i);
	
		return $converted;
	}
	
	static private function swap($input,$key,$chars,$decrypt=FALSE,$salt='AnOptionalRandomString'){
		
		$changedkey=self::createChanged($salt.$key,$chars);//Shuffle the characters according to the key
	
		$normal = $decrypt?$changedkey:$chars;
		$changed=$decrypt?$chars:$changedkey;
		
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
	static private function swap2($input,$key,$chars,$decrypt=FALSE,$salt='AnOptionalRandomString'){
	
		$changedkey=self::createChanged($salt.$key,$chars);
		$normal = $decrypt?$changedkey:$chars;
	
		$changed=$decrypt?$chars:$changedkey;
	
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
	
	/*
	 * Generates a random String of specified Size
	 * default range 2-9 characters.
	 * @String
	 */
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
}