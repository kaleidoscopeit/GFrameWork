<?php
error_reporting(E_ALL);

class _call{
    /**  As of PHP 5.3.0  */
    public static function __callStatic($name, $arguments)
    {
        // Note: value of $name is case sensitive.
        echo "Calling static method '$name' "
             . implode(', ', $arguments). "\n";
    }
    
}

$webgets['mywebget'] = (object)array('test' => 'value');



echo $webgets['mywebget']->test;

echo _w('324234')->test;

die;
_call::activities_tasks_check_modules($arguments);

_call.activities_tasks_check_modules($arguments);




_w('test.cippa')->caption(3424);

$_test_cippa->caption(3424);




_call('activities.tasks.check_modules', $arguments);




die;
phpinfo();
?>
