<?php
    include_once("conn.php");
    $uname=convert_str($_POST['username']);
    $value=array();
    if (db_user_exist($uname)) {
        foreach ($_POST as $name => $cid) {
            if ($name=="username") continue;
            if ($cid=="0"||(db_contest_type($cid)!=99&&db_contest_passed($cid))) {
                $uname=convert_str($uname);
                $sql="select count(distinct pid) from status where contest_belong='$cid' and username='$uname' and result='Accepted'";
                list($value[])=mysql_fetch_array(mysql_query($sql));
                $sql="select count(distinct status.pid) from status,contest_problem where contest_problem.pid=status.pid and contest_problem.cid='$cid' and username='$uname' and result='Accepted'";
                list($value[])=mysql_fetch_array(mysql_query($sql));
            }
        }
        echo json_encode($value);
    }
    else {
        echo "Error!";
    }
?>
