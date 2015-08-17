<?php
require_once '../DynamicFields.php';
$ob = new DynamicFields("SecretKey","abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");//cant include numbers incase it will appear as first character.

$tableName="table";
$columns=array(
		"ID",
		"Name",
		"DOB",
		"Email",
		"Password",
		"Gender",
		"Adderss",
		"Phone"
);
$data=array(
		"ID"=>1,
		"Name"=>"Example",
		"DOB"=>"23-May-1995",
		"Email"=>"Example@example.com",
		"Password"=>"PasswordHash",
		"Gender"=>"M",
		"Adderss"=>"House No x,xyz Street,xyz",
		"Phone"=>"9874563210"
);
$sql="INSERT INTO ".$ob->encode($tableName)." ( ";
$sql2="VALUES (";
foreach ($columns as $column){
	
	$encodedColumn=$ob->encode($column);
	
	$value=$data[$column];
// 	$value=escapeString($value);//Escaping String
	$sql.="`$encodedColumn`,";
	
	$sql2.="'$value',";
}
$sql=substr($sql, 0,-1);//removing last comma.
$sql.=") ";
$sql2=substr($sql2, 0,-1).")";

$sql.=$sql2;
echo $sql;
