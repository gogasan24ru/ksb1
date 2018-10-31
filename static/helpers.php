<?php
class helperSigleton
{
    private static $instance;
    private static $db;

    private function __construct($cnf)
    {
        self::$db = new SafeMySQL(
            array('user' => $cnf['username'],
                'pass' => $cnf['password'],
                'db' => $cnf['database'],
                'host'=>$cnf['host'],
                'charset' => 'utf8')
        );

    }
    public static function getInstance($cnf=null)
    {
        if(!isset($cnf))return self::$instance;
        if (!isset(self::$instance))
        {
            self::$instance = new helperSigleton($cnf);
        }
        return self::$instance;
    }

    public static function getDB()
    {
        return self::$db;
    }
}

class authManagerHelper
{
    function authResult(){
        if(isset($_SESSION['username']))
        {
            return $_SESSION['username'];
        }
        else
        {
            return "";
        }
    }
    function auth($username, $password)
    {
        session_start();
        $data=helperSigleton::getInstance()->getDB()->getOne("SELECT id,username FROM users WHERE password=?s AND username=?s",md5($password),$username);
        if($data==false)return false;
        $_SESSION['username']=$data['username'];
        return true;
    }
    function logout()
    {
        unset($_SESSION['username']);
        session_destroy();

    }

}
