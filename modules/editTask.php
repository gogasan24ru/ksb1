<?php

class editTask
{
    function render(){
        $ret="";
        if(isset($_GET['mode']))
        {
            $am=new authManagerHelper();
            if($am->authResult()=="")
            {
                Header('Location: ./?module=auth');
            }
            switch ($_GET['mode'])
            {
                Case "view":
                    $ret.=$this->renderTicket($_GET['id']);
                    break;
                Case "edit":
                    if(isset($_POST['fio']))
                    {
                        try
                        {
                            $this->updateTicket($_GET['id']);//should pass $_POST values here
                            Header("Location: ".$_SERVER['HTTP_REFERER']);
                        }
                        catch (Exception $e)
                        {
                            $ret=helperSigleton::getInstance()->messageBox("Got exception while processing your request: ".$e->getMessage(),true);
                        }
                    }
                    $ret.=$this->editTicketForm($_GET['id']);
                    break;
                default:Header('Location: ./');
            }
        }else{
            if(isset($_POST['data']))
            {
                $result=false;
                $answer="";
                try
                {
                    $result=$this->newTicket($_POST['data']);
                    if($result!=false)$ret=helperSigleton::getInstance()->messageBox("Your message accepted. Ticket ID: ".$result);
                }
                catch (Exception $e)
                {
                    $ret=helperSigleton::getInstance()->messageBox("Got exception while processing your request: ".$e->getMessage(),true);
                }
            }
            $ret.=$this->newTicketForm();
        }
        return $ret;
    }
    private function newTicket($ticketData)
    {
        //die(var_dump($ticketData));
        if (!filter_var($ticketData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email address provided.");
        }
        if(!preg_match("/^(\+7[0-9]{10})|(8[0-9]{10})$/",$ticketData['phone'])) {
            throw new Exception("Invalid phone number provided.");
        }
        $db=helperSigleton::getInstance()->getDB();
        $query="INSERT INTO tickets SET ?u ;";
        $db->query($query,$ticketData);
        return $db->insertId();
    }
    private function updateTicket($id)
    {
        $db=helperSigleton::getInstance()->getDB();
        $args=Array(
            'fio'=>$_POST['fio'],
            'phone'=>$_POST['phone'],
            'email'=>$_POST['email'],
            'address'=>$_POST['address'],
            'details'=>$_POST['details'],
        );
        $query="UPDATE tickets SET ?u WHERE id = ?s";
        $db->query($query,$args,$id);
    }
    private function renderTicket($id){
        $ret="";
        $db=helperSigleton::getInstance()->getDB();
        $query="SELECT * FROM tickets WHERE id=".$id.";";
        $datum=$db->getRow($query);
        $ret.='
        <table class="t"><tbody>
            <tr>
                <td>ID</td><td>'.$datum['id'].'</td>
            </tr>
            <tr>
                <td>DATE</td><td>'.$datum['date'].'</td>
            </tr>
            <tr>
                <td>FIO</td><td>'.$datum['fio'].'</td>
            </tr>
            <tr>
                <td>PHONE</td><td>'.$datum['phone'].'</td>
            </tr>
            <tr>
                <td>EMAIL</td><td>'.$datum['email'].'</td>
            </tr>
            <tr>
                <td>ADDRESS</td><td>'.$datum['address'].'</td>
            </tr>
            <tr>
                <td>DETAILS</td><td>'.$datum['details'].'</td>
            </tr>
        </tbody></table>
        ';
        return $ret;
    }
    private function newTicketForm(){
        $ret="";
        $ret.='
        <form method="post">
        <table class="t"><tbody>
            <tr>
                <td>FIO</td><td><input required placeholder="ФИО" name="data[fio]" ></td>
            </tr>
            <tr>
                <td>PHONE</td><td><input required placeholder="Номер телефона" name="data[phone]" value=""></td>
            </tr>
            <tr>
                <td>EMAIL</td><td><input required placeholder="email" name="data[email]" value=""></td>
            </tr>
            <tr>
                <td>ADDRESS</td><td><input required placeholder="Адрес" name="data[address]" value=""></td>
            </tr>
            <tr>
                <td>DETAILS</td><td><textarea required name="data[details]"></textarea></td>
            </tr>
        </tbody></table>
        <input type="submit" value="Send">
        </form>
        ';
        return $ret;


    }
    private function editTicketForm($id=null){

        $ret="";
        $db=helperSigleton::getInstance()->getDB();
        $query="SELECT * FROM tickets WHERE id=".$id.";";
        $datum=$db->getRow($query);
        $ret.='
        <form method="post">
        <table  class="t"><tbody>
            <tr>
                <td>FIO</td><td><input name="fio" value="'.$datum['fio'].'"></td>
            </tr>
            <tr>
                <td>PHONE</td><td><input name="phone" value="'.$datum['phone'].'"></td>
            </tr>
            <tr>
                <td>EMAIL</td><td><input name="email" value="'.$datum['email'].'"></td>
            </tr>
            <tr>
                <td>ADDRESS</td><td><input name="address" value="'.$datum['address'].'"></td>
            </tr>
            <tr>
                <td>DETAILS</td><td><textarea name="details">'.$datum['details'].'</textarea></td>
            </tr>
        </tbody></table>
        <input type="submit" value="Send">
        </form>
        ';
        return $ret;

    }

}
