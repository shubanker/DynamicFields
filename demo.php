<?php
session_start();
require_once 'DynamicFields.php';
$dynamicFields=new DynamicFields();
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>::Dynamic Fields Demo::</title>
</head>
<body>
	<h1>Dynamic Form Fields</h1><?php if (!empty($_POST)){?>
	<div>
		<h3>Inputs</h3>
		<pre><?php print_r($_POST);?>
			
		</pre>
	</div>
	<h3>Try Again</h3><?php }?>
	<form action="" method="post">
		First Name:<input type="text" name="<?php echo $dynamicFields->DynamicName("name[]")?>"/><br>
		Last Name:<input type="text" name="<?php echo $dynamicFields->DynamicName("name[]")?>"/><br>
		Password:<input type="password" name="<?php echo $dynamicFields->DynamicName("password")?>"/><br>
		Email:<input type="email" name="<?php echo $dynamicFields->DynamicName("email")?>"/><br>
		<input type="submit" value="Submit"/>
	</form>
</body>
</html>