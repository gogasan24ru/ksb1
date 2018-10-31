<?php
//setup
//require_once "static/functions.php";
require_once "static/config.php";
require_once "static/mysql.php";


if(isset($_GET['module']))
{
    $db = new SafeMySQL(
        array('user' => $mycnf['username'],
            'pass' => $mycnf['password'],
            'db' => $mycnf['database'],
            'host'=>$mycnf['host'],
            'charset' => 'utf8')
    );

    $module=$_GET['module'];
    include "modules/".$module.'.php';
    $worker = new $module;
    $contents=$worker->render();
    include("static/template.php");
}
else{
    $contents="Welcome";
    include("static/template.php");

}

?>