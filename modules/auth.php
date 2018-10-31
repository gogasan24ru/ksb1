<?php

class auth
{
    function render(){
        if (isset($_GET['logout']))
        {
            $am=new authManagerHelper();
            $am->logout();
            Header("Location: ./");
        }

        if(isset($_POST['username']))
        {
            $am=new authManagerHelper();
            $authResult = $am->auth($_POST['username'],$_POST['password']);

            if($authResult===true){
                Header("Location: ./");
            }
        }
        $result="";
        $am=new authManagerHelper();
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
                <table>
                    <thead>
                    </thead>
                    <tbody>
                        <tr><td>Username</td><td><input type="text" name="username"></td></tr>
                        <tr><td>Password</td><td><input type="password" name="password"></td></tr>
                    </tbody>
                </table>
                <input type="submit" value="Login">
            </form>
            
            ';
    }
}