<?php


class authManager
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

}
