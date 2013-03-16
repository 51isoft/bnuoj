<?php
    include_once("conn.php");
    $cid = convert_str($_POST['cid']);
    $value=array();
    if ($cid=="0"||(db_contest_type($cid)!=99&&db_contest_passed($cid))) {
        foreach ($_POST as $name => $id) {
            if ($name=="cid") continue;
            $id=convert_str($id);
            $sql="select count(distinct pid) from status where contest_belong='$cid' and username='$id' and result='Accepted'";
            list($value[])=mysql_fetch_array(mysql_query($sql));
            $sql="select count(distinct status.pid) from status,contest_problem where contest_problem.pid=status.pid and contest_problem.cid='$cid' and username='$id' and result='Accepted'";
            list($value[])=mysql_fetch_array(mysql_query($sql));
        }
        echo json_encode($value);
    }
    else {
        echo "Error!";
    }
?>
