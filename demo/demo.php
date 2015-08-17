<?php
session_start();
require_once '../DynamicForms.php';

$dynamicFields=new DynamicForms(false);//false skips automatic population of $_POST.
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>::Dynamic Fields Names Demo::</title>
</head>
<body>
	<h1>Dynamic Fields Names</h1>
	<?php if (!empty($_POST)){?>
	<div>
		<h3>Inputs</h3>
		<pre><?php 
			print_r($_POST);
			$dynamicFields->setOriginalElementNames();//Manually Calling setOriginalElementNames() to populate $_POST with decoded fields names.?>
		</pre>
		<h4>Real Values</h4>
		<pre><?php print_r($_POST);?></pre>
	</div>
	<h3>Wanna Try Again</h3>
	<?php }?>
	<form action="" method="post">
		First Name:<input type="text" name="<?php echo $dynamicFields->DynamicName("name[]")?>"/><br>
		Last Name:<input type="text" name="<?php echo $dynamicFields->DynamicName("name[]")?>"/><br>
		Password:<input type="password" name="<?php echo $dynamicFields->DynamicName("password")?>"/><br>
		Email:<input type="email" name="<?php echo $dynamicFields->DynamicName("email")?>"/><br>
		<input type="submit" value="Submit"/>
	</form>
</body>
</html>