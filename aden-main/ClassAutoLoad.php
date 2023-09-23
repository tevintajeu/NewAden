<?php

date_default_timezone_set("Africa/Nairobi");

require_once "config.php";
require_once "sql/dbconn.php";
require "lang/en.php";
require "processes/methods.php";


    function ClassAutoLoad($ClassName){
        $directories = array("contents","forms", "layouts", "globals", "processes", "lang","sql");
        foreach($directories AS $dir){
            $FileName = dirname(__FILE__) . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $ClassName . ".php";
            if(is_readable($FileName)){
                require $FileName;
            }
        }
    }
    spl_autoload_register('ClassAutoLoad', true, true);//this function enables the ClassAutoLoad() to require/include files into the current file 
                                                       //1st parameter specifies the function to run that will  load the files
                                                       //2nd parameter makes sure that that the function named in the 1st parameter will run first, if there are other methods with the same function
                                                       //3rd parameter enables the error to be displayed if there will  any regarding to file availability 
                                         




// instantiating classes
// creating objects.

$OBJ_Layout = NEW layouts();
$OBJ_Contents = NEW contents();
$OBJ_Forms = NEW forms();
// $OBJ_SendMail = NEW SendMail();
// $OBJ_Proc = NEW auth();
$OBJ_verify_email = NEW methods();


// call method
//$OBJ_Proc->receive_sign_up($MYSQL, $OBJ_SendMail, $conf, $lang);