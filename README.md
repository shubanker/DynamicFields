## Dynamic Fields Names ##

A small class written in PHP which can help you hide real fields name of your forms by replacing it with some random string.
It uses session to store key and time

This requires no extra configuration,you just need to include this file in your script and pass the form name you wish into a function as shown shown below in the example.

    <input type="text" name="<?php echo $dynamicFields->DynamicName("userName")?>"/>
The fields are set to their original names as soon as the object is created,or you can choose to do it manually like.

    $dynamicFields=new DynamicFields(false);
And you can populate `$_POST` with original names by.

    $dynamicFields->setOriginalElementNames();

*Please look at [Demo File](demo.php) for its working Demo.*
## License

See the [LICENSE](LICENSE.md) file for license rights and limitations (MIT).