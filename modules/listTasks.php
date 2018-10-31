<?php
class listTasks
{
    function render(){
        $filter="";
        if(isset($_POST['filter']))
            $filter=$this->constructFilter($_POST['filter']);
        $am=new authManagerHelper();
        if($am->authResult()=="")
        {
            Header('Location: ./?module=auth');
        }

        $limit=10; $offset=0;

        $db=helperSigleton::getInstance()->getDB();
        $request=
            "SELECT COUNT(*) FROM tickets";
        $total=intval($db->getOne($request));


        if (isset($_GET['page']))//isset($_GET['limit'])&&
        {
//            $limit=$_GET['limit'];
            $page=$_GET['page'];
            if($page<1)$page=1;
            if($page>ceil($total / $limit))
            {
                $page=ceil($total / $limit);
            }
            $offset=($page-1)*$limit;
        }else {$page=1;}

        $request=
            "SELECT fio,date,id,address FROM tickets ".($filter!=""?" WHERE ".$filter:"")."LIMIT $limit OFFSET $offset";
        //var_dump($request);
        $data=$db->getAll($request);
        $ret=$this->renderFilter($_POST['filter']).'
        <h1>Tickets</h1>
        
        <table class="t"><thead><tr><td>ID</td><td>Date</td><td>FIO</td><td>address</td><td>Action group</td></tr></thead><tbody>
        ';
        foreach ($data as $datum)
        {
            $ret.='
                <tr><td>'.$datum['id'].'</td><td>'.$datum['date'].'</td><td>'.$datum['fio'].'</td><td>'.$datum['address'].'</td><td>
                <a href="?module=editTask&mode=view&id='.$datum['id'].'" class="action-button">VIEW</a>
                <a href="?module=editTask&mode=edit&id='.$datum['id'].'" class="action-button">EDIT</a>
                </td></tr>
        ';

        }

        $ret.='</tbody><tfoot>
        <tr><td colspan="5">
        <div class="links">
        <a href="?module=listTasks&page='.($page-1).'">PREV.</a> | 
        <a href="?module=listTasks&page='.($page+1).'">NEXT</a>
        </div>
        </td></tr>

        </tfoot></table>';

        return $ret;

    }

    private function constructFilter($source){
        $filters=Array();

//        array(2) { ["flags"]=> array(3) { ["date"]=> string(2) "on" ["fio"]=> string(2) "on" ["address"]=> string(2) "on" }
// ["values"]=> array(4) { ["datefrom"]=> string(10) "2018-10-31" ["dateto"]=> string(10) "2018-10-31" ["fio"]=> string(6) "asdasd"
// ["address"]=> string(6) "232133" } }
        if(isset($source['flags']['date']))
        {
            $from=DateTime::createFromFormat("Y-m-j",$source['values']['datefrom']);
            $to=DateTime::createFromFormat("Y-m-j",$source['values']['dateto']);//2011-11-10 23:59:59
            $filters[]=' ( date BETWEEN \''.$from->format("Y-m-j 00:00:00").'\' AND \''.$to->format("Y-m-j 23:59:59").'\' ) ';
        }
        if(isset($source['flags']['fio']))
        {
            $filters[]=' ( fio LIKE \'%'.$source['values']['fio'].'%\' ) ';
        }
        if(isset($source['flags']['address']))
        {
            $filters[]=' ( address LIKE \'%'.$source['values']['address'].'%\' ) ';
        }

        $ret=join(" AND ",$filters);
        return $ret;
    }
    private function renderFilter($filter=Array())
    {
        $ret='
        <form method="post">
            <table class="t">
            <tr><td><input type="checkbox" name="filter[flags][date]" id="date" '.(isset($filter['flags']['date'])?"checked":"").'><label for="date">filter by date range</label></td>
            <td>
                Range from: <input type="date" name="filter[values][datefrom]" value="'.(isset($filter['values']['fio'])?$filter['values']['datefrom']:date("Y-m-j")).'"> <br>
                Range to: <input type="date" name="filter[values][dateto]" value="'.(isset($filter['values']['fio'])?$filter['values']['dateto']:date("Y-m-j")).'">
            </td></tr>
            <tr><td><input type="checkbox" name="filter[flags][fio]" id="fio" '.(isset($filter['flags']['fio'])?"checked":"").'><label for="fio">filter by FIO</label></td>
            <td>
                <input name="filter[values][fio]" value="'.(isset($filter['values']['fio'])?$filter['values']['fio']:"").'">
            </td></tr>
            <tr><td><input type="checkbox" name="filter[flags][address]" id="address" '.(isset($filter['flags']['address'])?"checked":"").'><label for="address">filter by address</label></td>
            <td>
                <input name="filter[values][address]" value="'.(isset($filter['values']['fio'])?$filter['values']['address']:"").'">
            </td></tr>
            </table>
            <input type="submit" value="Apply">
            </form>
        ';

        return $ret;
    }
}
