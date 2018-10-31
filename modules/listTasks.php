<?php
class listTasks
{
    function render(){
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
            "SELECT fio,date,id FROM tickets LIMIT $limit OFFSET $offset";
        $data=$db->getAll($request);
        $ret='
        <h1>Tickets</h1>
        
        <table><thead><tr><td>ID</td><td>Date</td><td>FIO</td><td>Action group</td></tr></thead><tbody>
        ';
        foreach ($data as $datum)
        {
            $ret.='
                <tr><td>'.$datum['id'].'</td><td>'.$datum['date'].'</td><td>'.$datum['fio'].'</td><td>
                <a href="?module=editTask&mode=view&id='.$datum['id'].'" class="action-button">VIEW</a>
                <a href="?module=editTask&mode=edit&id='.$datum['id'].'" class="action-button">EDIT</a>
                </td></tr>
        ';

        }

        $ret.='</tbody><tfoot>
        <tr><td colspan="4">
        <a href="?module=listTasks&page='.($page-1).'">PREV.</a> | 
        <a href="?module=listTasks&page='.($page+1).'">NEXT</a>
        </td></tr>

        </tfoot></table>';

        return $ret;

    }
}
