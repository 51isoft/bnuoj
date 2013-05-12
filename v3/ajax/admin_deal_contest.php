<?php
include_once(dirname(__FILE__)."/../functions/contests.php");
include_once(dirname(__FILE__)."/../functions/problems.php");
include_once(dirname(__FILE__)."/../functions/users.php");
$ret=array();
$ret["code"]=1;
$cid = convert_str($_POST['cid']);
if ($current_user->is_root()&&contest_get_val($cid,"type")!=99) {
    $title = htmlspecialchars(convert_str($_POST['title']));
    /*$sql_cn = "select max(cid) from contest";
    $que_cn = mysql_query($sql_cn);
    $num = mysql_fetch_array($que_cn);
    $cid=convert_str($_POST['cid']);
    $maxcid=$num[0]+1;
    $isupd=true;
    if ($cid=="") {
        $cid=$maxcid;
        $isupd=false;
    }*/
    if ($cid=="") $isupd=false;
    else $isupd=true;

    $isprivate=convert_str($_POST['isprivate']);
    $description =htmlspecialchars(convert_str($_POST['description']));
    $lock_board_time=convert_str($_POST['lock_board_time']);
    $start_time=convert_str($_POST['start_time']);
    $end_time=convert_str($_POST['end_time']);
    $report=convert_str($_POST['report']);
    $ctype=convert_str($_POST['ctype']);
    $has_cha=convert_str($_POST['has_cha']);
    $challenge_start_time=convert_str($_POST['challenge_start_time']);
    $challenge_end_time=convert_str($_POST['challenge_end_time']);
    $hide_others=convert_str($_POST['hide_others']);
    $n = $config["limits"]["problems_on_contest_add"];
    for($i=0;$i<$n;$i++){
        $pid[$i] = convert_str($_POST['pid'.$i]);
        $lable[$i] = convert_str($_POST['lable'.$i]);
        $ptype[$i] = convert_str($_POST['ptype'.$i]);
        $base[$i] = convert_str($_POST['base'.$i]);
        $minp[$i] = convert_str($_POST['minp'.$i]);
        $paraa[$i] = convert_str($_POST['paraa'.$i]);
        $parab[$i] = convert_str($_POST['parab'.$i]);
        $parac[$i] = convert_str($_POST['parac'.$i]);
        $parad[$i] = convert_str($_POST['parad'.$i]);
        $parae[$i] = convert_str($_POST['parae'.$i]);
    }

    
    $stt=strtotime($start_time);
    $edt=strtotime($end_time);
    $lbt=strtotime($lock_board_time);
    $nt=time();
    
    //echo "$title $start_time $end_time $pid[0] $stt $edt $lbt $nt ";
    //echo $_POST['submit'];
    $legal=true;
    if ($title=="") {
        $ret["msg"].="No title.<br />";
        $legal=false;
    }
    if ($start_time==""||$stt==0) {
        $ret["msg"].="Invalid start time.<br />";
        $legal=false;
    }
    if ($end_time==""||$edt==0) {
        $ret["msg"].="Invalid end time.<br />";
        $legal=false;
    }
    if ($lbt!=0&&($lbt<$stt||$lbt>$edt)) {
        $ret["msg"].="Invalid lock board time.<br />";
        $legal=false;
    }

    if ($legal) {
        $ret["code"]=0;
        $ret["msg"]="Success!<br />";
        if ($isupd) $sql_con="update contest set 
            title='$title',
            description='$description',
            isprivate='$isprivate',
            start_time='$start_time',
            end_time='$end_time',
            lock_board_time='$lock_board_time',
            hide_others='$hide_others',
            isvirtual='0',
            report='$report',
            type='$ctype',
            has_cha='$has_cha',
            challenge_start_time='$challenge_start_time',
            challenge_end_time='$challenge_end_time'
            where cid='$cid'";
        else $sql_con = "insert into contest (title,description,isprivate,lock_board_time,start_time,end_time,hide_others,isvirtual,report,type,has_cha,challenge_start_time,challenge_end_time) values
            ('$title','$description','$isprivate','$lock_board_time','$start_time','$end_time','$hide_others',0,'$report','$ctype','$has_cha','$challenge_start_time','$challenge_end_time')";
        //$sql_con = change_in($sql_con);
        $db->query($sql_con);
        if ($cid=="") $cid=$db->insert_id;
        //echo $sql_con."\n";
        $pd=false;
        for($i=0;$i<$n;$i++){
            if($pid[$i] == "")continue;
            if (!problem_exist($pid[$i])) {
                $ret["msg"].="Failed to add Problem ".$lable[$i].", pid: ".$pid[$i]." not exists.<br />";
            }
            //else echo "Problem ".$lable[$i]." added, pid: ".$pid[$i].".<br />";
        }
        for($i=0;$i<$n;$i++){
            if($pid[$i] == "") continue;
            if ($ctype==0) $sql= "insert into contest_problem (cid ,pid,lable) values ('".$cid."','".$pid[$i]."','".$lable[$i]."')";
            else $sql = "insert into contest_problem (cid ,pid,lable,type,base,minp,para_a,para_b,para_c,para_d,para_e) values
                ('".$cid."','".$pid[$i]."','".$lable[$i]."','".$ptype[$i]."','".$base[$i]."','".$minp[$i]."','".$paraa[$i]."','".$parab[$i]."','".$parac[$i]."','".$parad[$i]."','".$parae[$i]."')";
            $db->query($sql);
        }
        $cres=$db->query("select problem.title from contest_problem,problem where cid=".$cid." and contest_problem.pid=problem.pid");
        $str=array();
        foreach ( (array) $db->get_results(null,ARRAY_N) as $crow) {
            $str[]=trim(strtolower($crow[0]));
        }
        sort($str);
        $db->query("update contest set allp='".md5(implode($str,$config["salt_problem_in_contest"]))."' where cid=".$cid);
        $names = preg_split("/[^A-Z0-9a-z_-]+/",$_POST["names"]);
        foreach ($names as $tmp) {
            if (!user_exist($tmp)) {
                $ret["msg"].="No such user $tmp.<br />";
            }
            else if (contest_has_user($cid,$tmp)) {
                $ret["msg"].="User $tmp already in contest $cid.<br />";
            }
            else {
                $que="insert into contest_user set cid=$cid, username='$tmp'";
                $db->query($que);
            }
        }
    }
    
}
else $ret["msg"]="Please login as root!";
echo json_encode($ret);
?>
