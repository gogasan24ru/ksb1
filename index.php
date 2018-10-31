<?php
session_start();
//setup
//require_once "static/functions.php";
require_once "static/config.php";
require_once "static/mysql.php";
require_once "static/helpers.php";

helperSigleton::getInstance($mycnf);

$authed=(new authManagerHelper())->authResult();

if(isset($_GET['module']))
{

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