<?php

class auth
{
    function render(){
        $result="";
        $am=new authManager();
        if($am->authResult()=="")
        {
            $result=$this->renderAuthForm();
        }
        return $result;

    }
    function renderAuthForm()
    {
        return
            '
            <form method="POST" >
            <input type="hidden" name="module" value="auth">
            <input type="text" name="username">
            <input type="password" name="password">
            <input type="submit" value="Login">
            
            
            </form>
            
            ';
    }
}